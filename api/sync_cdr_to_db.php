<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Kolkata');

/* DB */
require_once __DIR__ . '/../core/db.php';

/* Providers */
require_once __DIR__ . '/cdr_provider.php';
require_once __DIR__ . '/download_recording.php';

$recordingDir = realpath(__DIR__ . '/../recordings') . DIRECTORY_SEPARATOR;

/* Ensure recordings directory exists */
if (!is_dir($recordingDir)) {
    mkdir($recordingDir, 0777, true);
}

/* Log start */
file_put_contents(
    __DIR__ . '/sync.log',
    date('Y-m-d H:i:s') . " sync started\n",
    FILE_APPEND
);

/* Fetch CDRs */
$cdrList = getUnifiedCDR(1, 50);

if (empty($cdrList)) {
    echo json_encode([
        "status" => "ok",
        "message" => "No data from Vobiz"
    ]);
    exit;
}

/* Billing config */
$userId = 1;
$rate   = 3;
$rows   = 0;

foreach ($cdrList as $cdr) {

    $billsec = (int) ($cdr['billsec'] ?? 0);
    if ($billsec <= 0) {
        continue;
    }

    $minutes = ceil($billsec / 60);
    $amount  = $minutes * $rate;

    /* Insert / update call */
    $stmt = $pdo->prepare("
        INSERT INTO call_records
        (
            user_id, call_uuid, call_date, direction,
            from_number, to_number,
            billsec, bill_minutes, rate_per_min,
            amount, status, paid, recording_url
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)
        ON DUPLICATE KEY UPDATE
            billsec = VALUES(billsec),
            bill_minutes = VALUES(bill_minutes),
            amount = VALUES(amount),
            status = VALUES(status),
            recording_url = VALUES(recording_url)
    ");

    $stmt->execute([
        $userId,
        $cdr['uuid'],
        str_replace('T', ' ', $cdr['start_time']),
        $cdr['call_direction'],
        $cdr['caller_id_number'],
        $cdr['destination_number'],
        $billsec,
        $minutes,
        $rate,
        $amount,
        ucfirst($cdr['hangup_disposition']),
        $cdr['recording_url']
    ]);

    $rows++;

    /* Download recording if needed */
    if (!empty($cdr['recording_url'])) {

        $chk = $pdo->prepare("
            SELECT recording_downloaded
            FROM call_records
            WHERE call_uuid = ?
        ");
        $chk->execute([$cdr['uuid']]);
        $row = $chk->fetch(PDO::FETCH_ASSOC);

        if ($row && (int)$row['recording_downloaded'] === 0) {

            $fileName = $cdr['uuid'] . '.wav';
            $savePath = $recordingDir . $fileName;

            if (!file_exists($savePath)) {

                $result = downloadRecording($cdr['recording_url'], $savePath);

                if ($result['success']) {

                    $upd = $pdo->prepare("
                        UPDATE call_records
                        SET recording_path = ?, recording_downloaded = 1
                        WHERE call_uuid = ?
                    ");
                    $upd->execute([
                        'recordings/' . $fileName,
                        $cdr['uuid']
                    ]);

                    file_put_contents(
                        __DIR__ . '/sync.log',
                        "Recording downloaded: {$fileName}\n",
                        FILE_APPEND
                    );
                } else {
                    file_put_contents(
                        __DIR__ . '/sync.log',
                        "Recording FAILED {$cdr['uuid']} : {$result['error']}\n",
                        FILE_APPEND
                    );
                }
            }
        }
    }

    file_put_contents(
        __DIR__ . '/sync.log',
        "Synced UUID {$cdr['uuid']}\n",
        FILE_APPEND
    );
}

echo json_encode([
    "status" => "success",
    "rows_processed" => $rows
]);

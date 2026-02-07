<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Kolkata');

require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/download_recording.php';

$recordingDir = realpath(__DIR__ . '/../recordings') . DIRECTORY_SEPARATOR;

/* Ensure folder exists */
if (!is_dir($recordingDir)) {
    mkdir($recordingDir, 0777, true);
}

/* Fetch pending recordings */
$stmt = $pdo->query("
    SELECT call_uuid, recording_url
    FROM call_records
    WHERE recording_url IS NOT NULL
      AND recording_downloaded = 0
");

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo "No pending recordings\n";
    exit;
}

foreach ($rows as $row) {

    $uuid = $row['call_uuid'];
    $url  = $row['recording_url'];

    $fileName = $uuid . '.wav';
    $savePath = $recordingDir . $fileName;

    /* Skip if file already exists */
    if (file_exists($savePath)) {
        $pdo->prepare("
            UPDATE call_records
            SET recording_downloaded = 1,
                recording_path = ?
            WHERE call_uuid = ?
        ")->execute([
            'recordings/' . $fileName,
            $uuid
        ]);
        continue;
    }

    $result = downloadRecording($url, $savePath);

    if ($result['success']) {

        $pdo->prepare("
            UPDATE call_records
            SET recording_downloaded = 1,
                recording_path = ?
            WHERE call_uuid = ?
        ")->execute([
            'recordings/' . $fileName,
            $uuid
        ]);

        echo "Downloaded: $fileName\n";

    } else {
        echo "FAILED $uuid : {$result['error']}\n";
    }
}

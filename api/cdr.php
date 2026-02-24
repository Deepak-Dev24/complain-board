<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* Detect CLI */
$isCli = php_sapi_name() === 'cli';

/* Auth only for browser */
if (!$isCli) {
    require_once __DIR__ . '/../core/auth.php';
}

require_once __DIR__ . '/cdr_provider.php';

header('Content-Type: application/json');

/* Params */
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = min(50, max(10, (int)($_GET['limit'] ?? 50)));
$date  = $_GET['date'] ?? null;

/* Fetch unified CDR data */
$cdrList = getUnifiedCDR($page, $limit);

/* Format response */
$data = [];
foreach ($cdrList as $cdr) {

    if ($date) {
        $cdrDate = date('Y-m-d', strtotime($cdr['start_time']));
        if ($cdrDate !== $date) continue;
    }

    $data[] = [
        "uuid"          => $cdr['uuid'],
        "date"          => date('Y-m-d H:i:s', strtotime($cdr['start_time'] ?? '')),
        "direction"     => $cdr['call_direction'],
        "from"          => $cdr['caller_id_number'],
        "to"            => $cdr['destination_number'],
        "duration"      => $cdr['billsec'] ?? 0,
        "status" => ucfirst($cdr['hangup_disposition'] ?? ''),
        "recording_url" => $cdr['recording_url'] ?? null
    ];
}

echo json_encode([
    "page"  => $page,
    "limit"=> $limit,
    "data" => $data
]);

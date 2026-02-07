<?php
require '../core/auth.php';
require '../config/vobiz_config.php';

if (!isset($_GET['url'])) {
    http_response_code(400);
    exit("Missing URL");
}

$url = $_GET['url'];

/* temp file */
$tmpWav = tempnam(sys_get_temp_dir(), 'rec_') . '.wav';

/* download recording */
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-Auth-ID: " . VOBIZ_AUTH_ID,
        "X-Auth-Token: " . VOBIZ_AUTH_TOKEN
    ],
]);
$data = curl_exec($ch);
curl_close($ch);

if (!$data) {
    http_response_code(500);
    exit("Failed to download recording");
}

file_put_contents($tmpWav, $data);

/* 🔑 IMPORTANT HEADERS */
header("Content-Type: audio/wav");
header("Content-Length: " . filesize($tmpWav));
header("Accept-Ranges: bytes");

/* stream file */
readfile($tmpWav);
unlink($tmpWav);
exit;

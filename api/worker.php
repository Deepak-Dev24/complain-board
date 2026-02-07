<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "WORKER STARTED\n";

/* ✅ DEFINE BASE PATH ONCE */
define('BASE_PATH', realpath(__DIR__ . '/..'));

require_once BASE_PATH . '/core/db.php';
require_once BASE_PATH . '/config/vobiz_config.php';
require_once BASE_PATH . '/api/process_calls.php';

set_time_limit(0);

$authId    = VOBIZ_AUTH_ID;
$authToken = VOBIZ_AUTH_TOKEN;

$python = "C:\\Users\\HP\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";
$transcriber = BASE_PATH . "/python/transcribe.py";

/* ---------------- RECORDINGS INDEX ---------------- */
$recordingIndex = [];

$recUrl = "https://api.vobiz.ai/api/v1/Account/$authId/Recording/?recording_type=trunk";
$ch = curl_init($recUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-Auth-ID: $authId",
        "X-Auth-Token: $authToken"
    ],
]);
$recJson = json_decode(curl_exec($ch), true);
curl_close($ch);

foreach ($recJson['objects'] ?? [] as $rec) {
    $recordingIndex[$rec['call_uuid']] = $rec['recording_url'];
}

/* ---------------- FETCH CDR ---------------- */
$cdrUrl = "https://api.vobiz.ai/api/v1/account/$authId/cdr?page=1&per_page=50";
$ch = curl_init($cdrUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-Auth-ID: $authId",
        "X-Auth-Token: $authToken"
    ],
]);
$cdrJson = json_decode(curl_exec($ch), true);
curl_close($ch);


/* ---------------- PROCESS CALLS ---------------- */
foreach ($cdrJson['data'] ?? [] as $cdr) {

    echo "CDR UUID: ";
    var_dump($cdr['uuid'] ?? null);

    echo "Available recording keys (sample): ";
    var_dump(array_slice(array_keys($recordingIndex), 0, 3));

    // 🔥 THIS WAS THE ROOT BUG (FIXED)
    $uuid = $cdr['call_uuid'] ?? $cdr['uuid'];

    if (!isset($recordingIndex[$uuid])) {
        continue;
    }

    $wav = BASE_PATH . "/recordings/$uuid.wav";
    $txt = BASE_PATH . "/transcripts/$uuid.txt";

    /* ⬇️ DOWNLOAD */
    if (!file_exists($wav) || filesize($wav) < 20000) {
        echo "Downloading: $uuid\n";

        $audio = file_get_contents($recordingIndex[$uuid]);
        if ($audio === false) continue;

        file_put_contents($wav, $audio);
    }

    /* ⬇️ TRANSCRIBE */
    if (!file_exists($txt) || filesize($txt) < 100) {
        exec(
            "\"$python\" \"$transcriber\" \"$wav\" \"$txt\"",
            $out,
            $code
        );

        if ($code !== 0 || !file_exists($txt)) continue;
    }

    /* ⬇️ AI + DB */
    processCall($uuid, $pdo);
}

echo "WORKER DONE\n";

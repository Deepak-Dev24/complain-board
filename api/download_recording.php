<?php
require_once __DIR__ . '/../config/vobiz_config.php';

/* CA bundle for Windows CLI */
$CAFILE = 'C:/wamp64/bin/php/php8.3.28/cacert.pem';

/**
 * Download authenticated Vobiz recording
 */
function downloadRecording(string $url, string $savePath): array
{
    global $CAFILE;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Auth-ID: " . VOBIZ_AUTH_ID,
            "X-Auth-Token: " . VOBIZ_AUTH_TOKEN
        ],
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CAINFO => $CAFILE,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $data = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($data === false || $http !== 200) {
        return [
            'success' => false,
            'error'   => $err ?: "HTTP $http"
        ];
    }

    if (file_put_contents($savePath, $data) === false) {
        return [
            'success' => false,
            'error'   => 'File write failed'
        ];
    }

    return ['success' => true];
}

<?php
require_once __DIR__ . '/../config/vobiz_config.php';

$isCli = php_sapi_name() === 'cli';
$CAFILE = 'C:/wamp64/bin/php/php8.3.28/cacert.pem';


function curlJson($url)
{
    global $isCli, $CAFILE;

    $authId    = VOBIZ_AUTH_ID;
    $authToken = VOBIZ_AUTH_TOKEN;

    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Auth-ID: $authId",
            "X-Auth-Token: $authToken"
        ],
        CURLOPT_TIMEOUT => 20,
    ];

    if ($isCli) {
        $opts[CURLOPT_CAINFO] = $CAFILE;
        $opts[CURLOPT_SSL_VERIFYPEER] = true;
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, $opts);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err      = curl_error($ch);

    curl_close($ch);

    /* 🔴 HARD FAIL FOR CLI */
    if ($isCli && ($response === false || $response === '' || $httpCode !== 200)) {
        file_put_contents(
            __DIR__ . '/sync.log',
            "CURL FAIL [$httpCode]: $err\nURL: $url\n",
            FILE_APPEND
        );
        return [];
    }

    return json_decode($response, true) ?: [];
}

function getUnifiedCDR($page = 1, $limit = 50)
{
    $authId = VOBIZ_AUTH_ID;

    /* Fetch recordings */
    $recordingIndex = [];
    $recUrl = "https://api.vobiz.ai/api/v1/Account/$authId/Recording/?recording_type=trunk";
    $recJson = curlJson($recUrl);

    foreach ($recJson['objects'] ?? [] as $rec) {
        $recordingIndex[$rec['call_uuid']] = $rec['recording_url'] ?? null;
    }

    /* Fetch CDR */
    $cdrUrl = "https://api.vobiz.ai/api/v1/account/$authId/cdr?page=$page&per_page=$limit";
    $cdrJson = curlJson($cdrUrl);

    $list = $cdrJson['data'] ?? [];

    foreach ($list as &$cdr) {
        $uuid = $cdr['uuid'] ?? null;
        $cdr['recording_url'] = $recordingIndex[$uuid] ?? null;
    }

    return $list;
}

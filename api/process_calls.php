<?php
// api/process_calls.php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

function processCall($uuid, $pdo)
{
    $transcriptFile = BASE_PATH . "/transcripts/$uuid.txt";

    // 1️⃣ Transcript must exist & have content
    if (!file_exists($transcriptFile) || filesize($transcriptFile) < 50) {
        return;
    }

    // 2️⃣ Prevent duplicate DB insert
    $check = $pdo->prepare("SELECT 1 FROM call_analysis WHERE call_uuid = ?");
    $check->execute([$uuid]);
    if ($check->fetch()) {
        return;
    }

    // 3️⃣ Python path
    $python = "C:\\Users\\HP\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";
    $analyzer = BASE_PATH . "/python/analyze.py";

    // 4️⃣ Run AI
    $cmd = "\"$python\" \"$analyzer\" " . escapeshellarg($transcriptFile) . " 2>&1";
    $json = shell_exec($cmd);

    if (!$json) return;

    $data = json_decode($json, true);
    if (!is_array($data)) return;

    // 5️⃣ Insert into DB
    $stmt = $pdo->prepare("
        INSERT INTO call_analysis
        (call_uuid, name, problem, village, city, date_requested, summary)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $uuid,
        $data['name'] ?? null,
        $data['problem'] ?? null,
        $data['village'] ?? null,
        $data['city'] ?? null,
        $data['date_requested'] ?? null,
        $data['summary'] ?? null
    ]);
}

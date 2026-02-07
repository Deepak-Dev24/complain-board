<?php
require_once __DIR__ . '/core/session_secure.php';
$config = require __DIR__ . '/config/config.php';

/* Only POST allowed */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$_SESSION['attempts'] = $_SESSION['attempts'] ?? 0;

if ($_SESSION['attempts'] >= 5) {
    die("Too many attempts. Try again later.");
}

/* LOGIN CHECK */
if (
    hash_equals($config['admin_username'], $username) &&
    password_verify($password, $config['admin_password_hash'])
) {
    session_regenerate_id(true);

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    $_SESSION['attempts'] = 0;

    // ✅ SUCCESS → index.php
    header("Location: index.php");
    exit;
}

// ❌ FAIL → login.php
$_SESSION['attempts']++;
header("Location: login.php?error=1");
exit;

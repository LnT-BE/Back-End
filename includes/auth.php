<?php
session_start();
require_once '../config/db.php'; // Koneksi database

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isEmailDuplicate($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

function rememberMe($pdo) {
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);

        // Cek token di database
        $stmt = $pdo->prepare("SELECT id FROM users WHERE remember_token = ?");
        $stmt->execute([$hashedToken]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id']; // Auto login
        }
    }
}

rememberMe($pdo);

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>

<?php
session_start();

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

function rememberMe() {
    if (isset($_COOKIE['remember_me'])) {
        $_SESSION['user_id'] = $_COOKIE['remember_me'];
    }
}
?>
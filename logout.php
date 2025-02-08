<?php
session_start();
require_once '../config/db.php';

session_destroy();

setcookie('remember_token', '', time() - 3600, "/", "", true, true);

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

header("Location: login.php");
exit();
?>
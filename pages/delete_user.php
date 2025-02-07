<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

// Cek apakah admin sudah login
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Ambil ID user dari query string
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit();
}

// Hapus user dari database
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
try {
    $stmt->execute([$id]);
    header("Location: dashboard.php?success=User berhasil dihapus!");
    exit();
} catch (PDOException $e) {
    header("Location: dashboard.php?error=Gagal menghapus user: " . $e->getMessage());
    exit();
}
?>

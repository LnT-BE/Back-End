<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

// Cek apakah pengguna sudah login
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Ambil informasi admin dari sesi atau database
// Misalnya, dari sesi:
$admin_id = $_SESSION['user_id'];

// Query database untuk mengambil data admin
$sql = "SELECT first_name, last_name, email, bio FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data admin tidak ditemukan
if (!$admin) {
    echo "Data admin tidak ditemukan.";
    exit();
}

include '../includes/header.php';
?>

<h2>Profile</h2>
<div>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($admin['first_name']); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($admin['last_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
    <p><strong>Bio:</strong> <?php echo htmlspecialchars($admin['bio']); ?></p>
</div>

<!-- Tombol logout -->
<form action="../logout.php" method="POST">
    <button type="submit">Log Out</button>
</form>

<?php include '../includes/footer.php'; ?>

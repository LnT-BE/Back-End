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

// Ambil data user berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: dashboard.php");
    exit();
}

include '../includes/header.php';
?>

<h2>Detail User</h2>
<div class="card">
    <div class="card-body">
        <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio']); ?></p>
    </div>
</div>
<a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
<a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Delete</a>
<a href="dashboard.php" class="btn btn-secondary">Kembali</a>

<?php include '../includes/footer.php'; ?>

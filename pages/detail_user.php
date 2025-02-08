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
        <!-- Foto Profil -->
        <div class="mb-3">
            <?php if (!empty($user['photo'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Foto Profil" class="profile-pic">
            <?php else: ?>
                <img src="https://via.placeholder.com/150" alt="Foto Profil" class="profile-pic">
            <?php endif; ?>
        </div>

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

<!-- Tambahkan gaya untuk gambar -->
<style>
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 10%; /* Membuat gambar berbentuk persegi */
        object-fit: cover; /* Menjaga proporsi gambar */
        border: 2px solid #ddd;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<?php include '../includes/footer.php'; ?>

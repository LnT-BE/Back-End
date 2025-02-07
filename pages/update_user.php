<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Validasi data
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = "First name, last name, dan email tidak boleh kosong!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid!";
    } elseif ($email != $user['email'] && isEmailDuplicate($pdo, $email)) {
        $error = "Email sudah terdaftar!";
    } else {
        // Update data user
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, bio = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $email, $bio, $id]);

        // Redirect ke dashboard dengan pesan sukses
        header("Location: dashboard.php?success=User berhasil diperbarui!");
        exit();
    }
}

include '../includes/header.php';
?>

<h2>Update User</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST">
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="bio">Bio</label>
        <textarea class="form-control" id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/auth.php'; // Fungsi autentikasi
require_once '../config/db.php'; // Koneksi database

// Cek apakah user sudah login
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Ambil data user yang sedang login
$admin_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

// Ambil semua user kecuali admin yang sedang login
$search = $_GET['search'] ?? ''; // Ambil parameter search (jika ada)
$query = "SELECT * FROM users WHERE id != ?";
$params = [$admin_id];

// Jika ada pencarian
if (!empty($search)) {
    $query .= " AND (CONCAT(first_name, ' ', last_name) LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Dashboard</h2>
        <p>Selamat datang, <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?>!</p>

        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari user berdasarkan nama atau email..." value="<?php echo htmlspecialchars($search); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <!-- Tabel List User -->
        <h3>Daftar User</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data user.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                             <?php 
                                $profilePic = !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'uploads/default.png';
                                 ?>
                             <img src="http://localhost/Back-End/uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="50">
                            </td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="detail_user.php?id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm">View</a>
                                <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol Add New User -->
        <a href="create_user.php" class="btn btn-success mb-4">Add New User</a>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-4">
        <p>&copy; 2023 Your Company. All rights reserved.</p>
        <p>
            <a href="#" class="text-dark">Facebook</a> | 
            <a href="#" class="text-dark">Twitter</a> | 
            <a href="#" class="text-dark">Instagram</a>
        </p>
    </footer>
</body>
</html>
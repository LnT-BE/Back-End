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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .card-custom {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
        }

        .footer-links a {
            margin: 0 10px;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="login.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Welcome Card -->
        <div class="card card-custom mb-4 p-4">
            <h3 class="mb-2">Selamat Datang, <span class="text-primary"><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></span>!</h3>
            <p>Ini adalah dashboard utama Anda. Gunakan menu di bawah untuk mengelola data pengguna.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari user berdasarkan nama atau email..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary rounded-pill ms-2">Cari</button>
            </div>
        </form>

        <!-- Tabel List User -->
        <h3>Daftar User</h3>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead class="table-dark">
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
                                <?php if (!empty($user['photo'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Picture" class="profile-pic">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="Profile Picture" class="profile-pic">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="detail_user.php?id=<?php echo $user['id']; ?>" class="btn btn-info">View</a>
                                <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol Add New User -->
        <a href="create_user.php" class="btn btn-success ">Add New User</a>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 mt-5 shadow-sm">
        <p>&copy; 2023 Your Company. All rights reserved.</p>
        <div class="footer-links">
            <a href="#" class="text-dark">Facebook</a>
            <a href="#" class="text-dark">Twitter</a>
            <a href="#" class="text-dark">Instagram</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

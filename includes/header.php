<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #f8f9fa; /* Warna latar navbar */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
        }
        .navbar-brand {
            font-weight: bold; /* Buat teks lebih tebal */
            font-size: 1.5rem; /* Ukuran lebih besar */
        }
        .navbar .nav-item .btn {
            border-radius: 25px; /* Buat tombol lebih bulat */
            font-weight: bold; /* Buat teks tombol lebih tebal */
        }
        .btn-logout:hover {
            background-color: #c82333; /* Warna merah lebih gelap saat hover */
            border-color: #bd2130;
        }
        .btn-login {
            background-color: #007bff; /* Warna biru tombol login */
            border-color: #007bff;
        }
        .btn-login:hover {
            background-color: #0056b3; /* Warna biru lebih gelap saat hover */
            border-color: #004085;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand text-primary" href="dashboard.php">Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isLoggedIn()): ?>
                    <li class="">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-login text-white px-4" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">

<?php
session_start(); // Mulai session
require_once '../config/db.php'; // Koneksi database

$error = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitasi input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validasi Input
    if (empty($email) || empty($password)) {
        $error = "Email dan password tidak boleh kosong!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Cek domain email
        $allowed_domains = ['gmail.com', 'binus.ac.id']; // Daftar domain yang diizinkan
        $email_domain = explode('@', $email)[1]; // Ambil domain dari email
        if (!in_array($email_domain, $allowed_domains)) {
            $error = "Domain email tidak diizinkan!";
        } else {
            // Cek email dan password di database
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Verifikasi password menggunakan MD5
            if ($user && md5($password) === $user['password']) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id']; // Simpan user ID di session
                header("Location: dashboard.php"); // Redirect ke dashboard
                exit();
            } else {
                $error = "Email atau password salah!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Login</h2>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small class="form-text text-muted">
                            Hanya email dengan domain @gmail.com atau @binus.ac.id yang diizinkan.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

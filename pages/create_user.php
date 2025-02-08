<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk mendapatkan ID terakhir
function getLastUserId($pdo) {
    $stmt = $pdo->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
    $lastId = $stmt->fetchColumn();
    return $lastId;
}

// Fungsi untuk menghasilkan ID baru
function generateNewId($lastId) {
    // Jika tidak ada ID sebelumnya, mulai dari A001
    if ($lastId === false) {
        return 'A001';
    }
    
    // Ambil angka dari ID terakhir, misalnya A001 -> 001
    $lastNumber = (int)substr($lastId, 1);
    
    // Tambah angka tersebut dan buat ID baru
    $newNumber = $lastNumber + 1;
    
    // Format dengan awalan 'A' dan padding 3 digit
    return 'A' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $photo = $_FILES['photo'];

    // Validasi input
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = "First name, last name, dan email tidak boleh kosong!";
    } elseif (strlen($firstName) > 255 || strlen($lastName) > 255) {
        $error = "Panjang first name atau last name tidak boleh lebih dari 255 karakter!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid!";
    } elseif (isEmailDuplicateInCreateUser($pdo, $email)) {
        $error = "Email sudah terdaftar!";
    } elseif (empty($photo['name'])) {
        $error = "Foto tidak boleh kosong!";
    } elseif (isPhotoDuplicate($pdo, $photo['name'])) {
        $error = "Nama gambar sudah ada, silakan pilih gambar lain!";
    } else {
        // Validasi format email
        $allowedEmails = ['@gmail.com', '@binus.ac.id'];  // Daftar domain yang diizinkan
        $emailDomain = substr($email, strpos($email, '@'));
        if (!in_array($emailDomain, $allowedEmails)) {
            $error = "Email harus menggunakan domain yang valid!";
        } else {
            // Generate ID baru
            $lastId = getLastUserId($pdo);  // Ambil ID terakhir
            $newId = generateNewId($lastId);  // Generate ID baru
            
            // Simpan foto ke folder uploads
            $uploadDir = '../uploads/';
            $photoName = uniqid() . '-' . basename($photo['name']);
            $uploadFilePath = $uploadDir . $photoName;
            move_uploaded_file($photo['tmp_name'], $uploadFilePath);

            // Buat password acak dan hash MD5
            $randomPassword = bin2hex(random_bytes(8));  // Membuat password acak 16 karakter
            $hashedPassword = md5($randomPassword);

            // Siapkan query untuk memasukkan data user
            $stmt = $pdo->prepare("INSERT INTO users (id, first_name, last_name, email, password, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$newId, $firstName, $lastName, $email, $hashedPassword, $bio, $photoName]);

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();
        }
    }
}

// Fungsi untuk cek email duplikat
function isEmailDuplicateInCreateUser($pdo, $email) {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}
// Fungsi untuk cek nama gambar duplikat
function isPhotoDuplicate($pdo, $photoName) {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE photo = ?");
    $stmt->execute([$photoName]);
    return $stmt->fetchColumn() > 0;
}
?>

<?php include '../includes/header.php'; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .btn-primary {
            border-radius: 30px;
        }

        .alert {
            border-radius: 10px;
        }

        .form-group label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" maxlength="255" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" maxlength="255" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Create User</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


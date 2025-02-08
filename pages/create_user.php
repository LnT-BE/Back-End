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
<h2>Create User</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="photo">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" required>
    </div>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="bio">Bio</label>
        <textarea class="form-control" id="bio" name="bio"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>

<?php include '../includes/footer.php'; ?>

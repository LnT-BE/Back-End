<?php
require_once '../config/db.php';

// Data Admin
$admin_data = [
    'id' => 'A001',
    'first_name' => 'admin',
    'last_name' => 'BNCC',
    'email' => 'adminBNCC@gmail.com',
    'password' => 'e64b78fc3bc91bcbc7dc232ba8ec59e0', // Hash MD5 dari 'Admin123'
    'bio' => 'Hi my name is Admin, and I like backend development.',
    'photo' => 'admin_profile.jpg' // Nama file foto
];

// Verifikasi hash password
$password = 'Admin123';
$hashed_password = md5($password); // Hash dengan MD5

if ($admin_data['password'] === $hashed_password) {
    echo "Password sudah di-hash dengan MD5.\n";
} else {
    echo "Password belum di-hash atau menggunakan algoritma lain.\n";
}

echo md5($password); // Hasilnya: 0192023a7bbd73250516f069df18b500

// Query untuk menambahkan data admin
$sql = "INSERT INTO users (id, first_name, last_name, email, password, bio, photo) VALUES (:id, :first_name, :last_name, :email, :password, :bio, :photo)";
$stmt = $pdo->prepare($sql);

// Eksekusi query
try {
    $stmt->execute($admin_data);
    echo "Data admin berhasil ditambahkan!";
} catch (PDOException $e) {
    echo "Gagal menambahkan data admin: " . $e->getMessage();
}
?>

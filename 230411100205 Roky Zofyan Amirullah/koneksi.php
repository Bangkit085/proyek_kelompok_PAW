<?php
// Pastikan username & password ini SAMA dengan punya teman Anda (Sigit)
$host = "localhost";
$user = "tugp4813_admin1"; // Cek punya teman Anda
$pass = "Sigit12345";      // Cek punya teman Anda
$db   = "tugp4813_perpustakaan";

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
?>
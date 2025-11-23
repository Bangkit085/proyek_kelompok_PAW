<?php
$host = "localhost";
$user = "root";
$pass = "Password123!";
$db   = "penjualan_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
} else {
    // echo "Koneksi berhasil"; 
}

<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_pengguna'])) {
    die("Akses ditolak");
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_buku = $_POST['id_buku'];
$rating = $_POST['nilai_rating'];
$ulasan = $_POST['ulasan'];

mysqli_query($koneksi, "INSERT INTO rating (id_pengguna, id_buku, nilai_rating, ulasan, dibuat_pada)
VALUES ('$id_pengguna','$id_buku','$rating','$ulasan',NOW())");

header("Location: ../nunnn/riwayat_peminjaman.php?pesan=ulasan_baru");
exit;

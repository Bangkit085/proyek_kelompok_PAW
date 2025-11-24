<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    echo "<script>alert('Login dulu bos!'); window.location='login.php';</script>";
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_buku = $_POST['id_buku'];
$tgl_pinjam = date('Y-m-d');
$tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days')); 

$update_stok = "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'";
$hasil_update = mysqli_query($koneksi, $update_stok);

if ($hasil_update) {
    $insert_pinjam = "INSERT INTO peminjaman (id_pengguna, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status) 
                      VALUES ('$id_pengguna', '$id_buku', '$tgl_pinjam', '$tgl_jatuh_tempo', 'dipinjam')";
    
    if (mysqli_query($koneksi, $insert_pinjam)) {
        echo "<script>alert('Berhasil Pinjam!'); window.location='riwayat.php';</script>";
    } else {
        echo "Gagal simpan data pinjam: " . mysqli_error($koneksi);
    }
} else {
    echo "Gagal update stok: " . mysqli_error($koneksi);
}
?>
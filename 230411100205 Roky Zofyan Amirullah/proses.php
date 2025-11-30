<?php
session_start();
// Gunakan koneksi yang ada di folder roky
include 'koneksi.php';

$aksi = $_GET['aksi'];

// --- 1. LOGIKA HAPUS RATING ---
if($aksi == 'hapus_rating'){
    $id = $_GET['id'];
    $conn->prepare("DELETE FROM rating WHERE id_rating = ?")->execute([$id]);
    header("Location: halaman_rating.php?pesan=dihapus");

// --- 2. LOGIKA VERIFIKASI: TERIMA ---
} elseif($aksi == 'verifikasi_terima'){
    $id = $_GET['id'];
    // Update Denda Lunas
    $conn->prepare("UPDATE denda SET status_pembayaran='sudah' WHERE id_denda=?")->execute([$id]);
    // Catat Tanggal Verifikasi
    $conn->prepare("UPDATE pembayaran SET tgl_verifikasi=NOW() WHERE id_denda=?")->execute([$id]);
    
    header("Location: halaman_verifikasi.php?pesan=Pembayaran Diterima");

// --- 3. LOGIKA VERIFIKASI: TOLAK ---
} elseif($aksi == 'verifikasi_tolak'){
    $id = $_GET['id'];
    // Reset Status Denda
    $conn->prepare("UPDATE denda SET status_pembayaran='belum' WHERE id_denda=?")->execute([$id]);
    // Hapus Bukti Pembayaran (Agar mahasiswa upload ulang)
    $conn->prepare("DELETE FROM pembayaran WHERE id_denda=?")->execute([$id]);
    
    header("Location: halaman_verifikasi.php?pesan=Pembayaran Ditolak");
}
?>
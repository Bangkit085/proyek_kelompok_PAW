<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['nim'])) {
    echo "<script>alert('Anda harus login!'); window.location='login.php';</script>";
    exit;
}

$nim_session = $_SESSION['nim'];
$id_buku = intval($_POST['id_buku']);
$tgl_pinjam = date('Y-m-d');
$tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days'));

try {
    $stmtUser = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE nim = :nim");
    $stmtUser->execute([':nim' => $nim_session]);
    $user = $stmtUser->fetch();

    if (!$user) {
        throw new Exception("Data pengguna tidak ditemukan. Pastikan sudah login dengan benar.");
    }
    $id_pengguna = $user['id_pengguna'];

    $stmtBuku = $conn->prepare("SELECT stok FROM buku WHERE id_buku = :id");
    $stmtBuku->execute([':id' => $id_buku]);
    $data_buku = $stmtBuku->fetch();

    if ($data_buku && $data_buku['stok'] > 0) {
        
        $conn->beginTransaction();

        $updateStok = $conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = :id");
        $updateStok->execute([':id' => $id_buku]);

        $sqlPinjam = "INSERT INTO peminjaman (id_pengguna, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status) 
                      VALUES (:uid, :bid, :tgl, :tempo, 'menunggu_validasi')";
        
        $stmtPinjam = $conn->prepare($sqlPinjam);
        $stmtPinjam->execute([
            ':uid' => $id_pengguna,
            ':bid' => $id_buku,
            ':tgl' => $tgl_pinjam,
            ':tempo' => $tgl_jatuh_tempo
        ]);

        $conn->commit();

        echo "<script>
                alert('Berhasil! Permintaan peminjaman dikirim. Silakan tunggu validasi Admin.'); 
                window.location='katalog.php'; 
              </script>";

    } else {
        echo "<script>alert('Gagal: Stok buku habis.'); window.location='deteksi_buku.php?id=$id_buku';</script>";
    }

} catch (Exception $e) {
    if($conn->inTransaction()) $conn->rollBack();
    echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location='deteksi_buku.php?id=$id_buku';</script>";
}
?>
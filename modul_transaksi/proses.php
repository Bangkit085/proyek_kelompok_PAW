<?php
include '../koneksi.php';

$aksi = $_GET['aksi'];

// --- 1. ADMIN MENYETUJUI PEMINJAMAN ---
if($aksi == "setujui"){
    $id = $_GET['id'];
    $id_buku = $_GET['id_buku'];

    // Cek stok dulu
    $stmt = $conn->prepare("SELECT stok FROM buku WHERE id_buku = ?");
    $stmt->execute([$id_buku]);
    $data_stok = $stmt->fetch();

    if($data_stok['stok'] > 0){
        // Kurangi stok buku
        $stmt = $conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?");
        $stmt->execute([$id_buku]);
        
        // Ubah status peminjaman jadi 'dipinjam'
        $stmt = $conn->prepare("UPDATE peminjaman SET status = 'dipinjam' WHERE id_peminjaman = ?");
        $stmt->execute([$id]);
        
        header("location:list_pinjam.php?pesan=sukses_validasi");
    } else {
        echo "<script>alert('Stok buku habis! Tidak bisa menyetujui.'); window.location='list_pinjam.php';</script>";
    }

// --- 2. ADMIN MENOLAK PEMINJAMAN ---
} elseif($aksi == "tolak"){
    $id = $_GET['id'];
    
    // Ubah status jadi ditolak
    $stmt = $conn->prepare("UPDATE peminjaman SET status = 'ditolak' WHERE id_peminjaman = ?");
    $stmt->execute([$id]);
    
    header("location:list_pinjam.php?pesan=sukses_tolak");

// --- 3. PROSES PENGEMBALIAN BUKU ---
} elseif($aksi == "kembali"){
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_buku       = $_POST['id_buku'];
    $id_pengguna   = $_POST['id_pengguna'];
    $denda         = $_POST['denda'];
    $tgl_kembali   = date('Y-m-d'); 

    // a. Update status peminjaman jadi 'dikembalikan'
    $stmt = $conn->prepare("UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=? WHERE id_peminjaman=?");
    $stmt->execute([$tgl_kembali, $id_peminjaman]);

    // b. Kembalikan stok buku (Stok + 1)
    $stmt = $conn->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku=?");
    $stmt->execute([$id_buku]);

    // c. Jika ada DENDA, masukkan ke tabel denda
    if($denda > 0){
        $sql_denda = "INSERT INTO denda (id_peminjaman, id_pengguna, jumlah_denda, status_pembayaran) VALUES (?, ?, ?, 'belum')";
        $stmt = $conn->prepare($sql_denda);
        $stmt->execute([$id_peminjaman, $id_pengguna, $denda]);
    }

    header("location:list_pinjam.php?pesan=sukses_kembali");
}
?>
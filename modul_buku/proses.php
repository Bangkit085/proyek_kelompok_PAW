<?php
include '../koneksi.php';

$aksi = $_GET['aksi'];

// --- LOGIKA TAMBAH BUKU ---
if($aksi == "tambah"){
    $judul       = $_POST['judul'];
    $penulis     = $_POST['penulis'];
    $penerbit    = $_POST['penerbit']; 
    $tahun       = $_POST['tahun_terbit'];
    $isbn        = $_POST['isbn'];
    $stok        = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];
    $id_rak      = $_POST['id_rak'];

    // Upload Cover
    $cover = "";
    if($_FILES['cover']['name'] != ""){
        $rand = rand();
        $filename = $_FILES['cover']['name'];
        $ukuran = $_FILES['cover']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if($ukuran < 2044070){ // Limit 2MB
            $cover = $rand.'_'.$filename;
            move_uploaded_file($_FILES['cover']['tmp_name'], '../../uploads/cover_buku/'.$cover);
        }
    }

    // GANTI KE PDO PREPARED STATEMENT
    try {
        $sql = "INSERT INTO buku (judul, penulis, id_kategori, id_rak, stok, isbn, tahun_terbit, cover, penerbit) 
                VALUES (:judul, :penulis, :id_kategori, :id_rak, :stok, :isbn, :tahun, :cover, :penerbit)";
        
        $stmt = $conn->prepare($sql);
        $params = [
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':id_kategori' => $id_kategori,
            ':id_rak' => $id_rak,
            ':stok' => $stok,
            ':isbn' => $isbn,
            ':tahun' => $tahun,
            ':cover' => $cover,
            ':penerbit' => $penerbit
        ];

        $stmt->execute($params);
        header("location:list_buku.php?pesan=sukses_tambah");

    } catch (PDOException $e) {
        echo "Gagal: " . $e->getMessage();
    }

// --- LOGIKA EDIT BUKU ---
} elseif($aksi == "edit"){
    $id          = $_POST['id_buku'];
    $judul       = $_POST['judul'];
    $penulis     = $_POST['penulis'];
    $penerbit    = $_POST['penerbit'];
    $tahun       = $_POST['tahun_terbit'];
    $isbn        = $_POST['isbn'];
    $stok        = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];
    $id_rak      = $_POST['id_rak'];
    $cover_lama  = $_POST['cover_lama'];

    // Siapkan array parameter dasar
    $params = [
        ':judul' => $judul,
        ':penulis' => $penulis,
        ':penerbit' => $penerbit,
        ':tahun' => $tahun,
        ':isbn' => $isbn,
        ':stok' => $stok,
        ':id_kategori' => $id_kategori,
        ':id_rak' => $id_rak,
        ':id' => $id
    ];

    // Cek ganti gambar atau tidak
    if($_FILES['cover']['name'] != ""){
        // 1. Hapus gambar lama
        if($cover_lama != "" && file_exists('../../uploads/cover_buku/'.$cover_lama)){
            unlink('../../uploads/cover_buku/'.$cover_lama);
        }

        // 2. Upload gambar baru
        $rand = rand();
        $filename = $_FILES['cover']['name'];
        $cover_baru = $rand.'_'.$filename;
        move_uploaded_file($_FILES['cover']['tmp_name'], '../../uploads/cover_buku/'.$cover_baru);

        // SQL Update DENGAN Cover
        $sql = "UPDATE buku SET judul=:judul, penulis=:penulis, penerbit=:penerbit, tahun_terbit=:tahun, isbn=:isbn, stok=:stok, id_kategori=:id_kategori, id_rak=:id_rak, cover=:cover WHERE id_buku=:id";
        
        // Tambahkan param cover
        $params[':cover'] = $cover_baru;

    } else {
        // SQL Update TANPA Cover
        $sql = "UPDATE buku SET judul=:judul, penulis=:penulis, penerbit=:penerbit, tahun_terbit=:tahun, isbn=:isbn, stok=:stok, id_kategori=:id_kategori, id_rak=:id_rak WHERE id_buku=:id";
    }

    // EKSEKUSI QUERY UPDATE (PDO)
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        header("location:list_buku.php?pesan=sukses_edit");
    } catch (PDOException $e) {
        echo "Gagal Update: " . $e->getMessage();
    }

// --- LOGIKA HAPUS BUKU ---
} elseif($aksi == "hapus"){
    $id = $_GET['id'];
    $cover = $_GET['cover'];

    // Hapus file fisik cover
    if($cover != "" && file_exists('../../uploads/cover_buku/'.$cover)){
        unlink('../../uploads/cover_buku/'.$cover);
    }

    // Hapus data DB (PDO)
    try {
        $stmt = $conn->prepare("DELETE FROM buku WHERE id_buku = :id");
        $stmt->execute([':id' => $id]);
        header("location:list_buku.php?pesan=sukses_hapus");
    } catch (PDOException $e) {
        echo "Gagal Hapus: " . $e->getMessage();
    }
}
?>
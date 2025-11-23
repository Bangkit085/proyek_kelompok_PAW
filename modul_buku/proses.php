<?php
include "../koneksi.php";

$aksi = $_GET['aksi'];

// ===============================
// TAMBAH BUKU
// ===============================
if ($aksi == "tambah") {

    $query = "
        INSERT INTO buku (judul, penulis, id_kategori, id_rak, stok, isbn, tahun_terbit)
        VALUES (
            '$_POST[judul]',
            '$_POST[penulis]',
            '$_POST[id_kategori]',
            '$_POST[id_rak]',
            '$_POST[stok]',
            '$_POST[isbn]',
            '$_POST[tahun_terbit]'
        )
    ";

    mysqli_query($koneksi, $query);
    header("Location: list_buku.php");
}



// ===============================
// EDIT BUKU
// ===============================
if ($aksi == "edit") {

    $query = "
        UPDATE buku SET 
            judul='$_POST[judul]',
            penulis='$_POST[penulis]',
            id_kategori='$_POST[id_kategori]',
            id_rak='$_POST[id_rak]',
            stok='$_POST[stok]',
            isbn='$_POST[isbn]',
            tahun_terbit='$_POST[tahun_terbit]'
        WHERE id_buku='$_POST[id_buku]'
    ";

    mysqli_query($koneksi, $query);
    header("Location: list_buku.php");
}



// ===============================
// HAPUS BUKU
// ===============================
if ($aksi == "hapus") {

    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$_GET[id]'");
    header("Location: list_buku.php");
}

<?php
include "../koneksi.php";

// ============ TAMBAH BUKU ============
if (isset($_POST['tambah'])) {

    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $id_kategori = $_POST['id_kategori'];
    $id_rak = $_POST['id_rak'];
    $stok = $_POST['stok'];
    $isbn = $_POST['isbn'];
    $tahun = $_POST['tahun_terbit'];

    mysqli_query($conn, "INSERT INTO buku VALUES(
        '',
        '$judul',
        '$penulis',
        '$id_kategori',
        '$id_rak',
        '$stok',
        '$isbn',
        '$tahun'
    )");

    header("Location: list_buku.php");
    exit;
}

// ============ EDIT BUKU ============
if (isset($_POST['edit'])) {

    $id = $_POST['id_buku'];

    mysqli_query($conn, 
        "UPDATE buku SET
            judul='$_POST[judul]',
            penulis='$_POST[penulis]',
            id_kategori='$_POST[id_kategori]',
            id_rak='$_POST[id_rak]',
            stok='$_POST[stok]',
            isbn='$_POST[isbn]',
            tahun_terbit='$_POST[tahun_terbit]'
         WHERE id_buku='$id'"
    );

    header("Location: list_buku.php");
    exit;
}

// ============ HAPUS BUKU ============
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");

    header("Location: list_buku.php");
    exit;
}

?>

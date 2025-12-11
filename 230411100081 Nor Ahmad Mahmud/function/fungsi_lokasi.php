<?php
include '../koneksi.php';

function getLokasiById($id_buku) {
    global $koneksi;
    $query = "SELECT buku.id_buku, buku.judul, buku.penulis, rak.kode_rak, rak.lantai
              FROM buku
              INNER JOIN rak ON buku.id_rak = rak.id_rak
              WHERE buku.id_buku = '$id_buku'";
    $result = mysqli_query($koneksi, $query);

    return mysqli_fetch_assoc($result);
}

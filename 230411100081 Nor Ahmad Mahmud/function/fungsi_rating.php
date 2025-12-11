<?php
function totalRating($koneksi, $id_buku) {
    $q1 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM rating WHERE id_buku='$id_buku'");
    $d1 = mysqli_fetch_assoc($q1);
    return $d1['total'];
}

function rataRating($koneksi, $id_buku) {
    $q2 = mysqli_query($koneksi, "SELECT AVG(nilai_rating) as avg FROM rating WHERE id_buku='$id_buku'");
    $d2 = mysqli_fetch_assoc($q2);
    return number_format($d2['avg'], 1);
}
?>

<?php
include '../../koneksi.php'; 

// Query ambil data
$query = mysqli_query($koneksi, "SELECT * FROM buku");
?>
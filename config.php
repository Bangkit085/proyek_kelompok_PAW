<?php

$usernamae = "root";
$db = "proyek_akhir_paw";
$host = "localhost";
$pass = "Password1234!";

$conn = mysqli_connect($host, $usernamae, $pass, $db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
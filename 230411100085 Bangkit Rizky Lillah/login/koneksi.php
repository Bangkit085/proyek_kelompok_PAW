<?php

$host = 'localhost';
$username = "root";
$pw = "";
$db = "project_paw";

$conn = mysqli_connect($host, $username, $pw, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>

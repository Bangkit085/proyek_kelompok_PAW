<?php
session_start();
require "koneksi.php";

if(!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM pengguna");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
     <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>

</body>
</html>
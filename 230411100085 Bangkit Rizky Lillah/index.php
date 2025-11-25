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
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
<style>
body {
  margin: 0;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 130px;
  background-color: #f1f1f1;
  position: fixed;
  height: 100%;
  overflow: auto;
}

li a {
  display: block;
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}

li a.active {
  background-color: #04AA6D;
  color: white;
}

li a:hover:not(.active) {
  background-color: #555555;
  color: white;
}
</style>
</head>
<body>

<ul>
    <b>Perpustakaan Digital</b>
    <li><a class="active" href="#home">Home</a></li>
    <li><a href="#news">News</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#about">About</a></li>
</ul>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="logo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill"> 
    </a>
  </div>
</nav>

<div style="margin-left:130px;padding:1px 16px;height:1000px;">
  <h2>Full-height Vertical Navbar</h2>
  <h3>Try to scroll this area, and see how the sidenav sticks to the page</h3>
  <p>Notice that we have set overflow:auto to sidenav. This will add a scrollbar when the sidenav is too long (for example if it has over 50 links inside of it).</p>
  <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</div>

</body>
</html>
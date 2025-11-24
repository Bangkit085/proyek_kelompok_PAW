<?php
require "koneksi.php";

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM buku WHERE 
          judul LIKE '%$search%' OR 
          penulis LIKE '%$search%'";
        //   OR 
        //   kategori LIKE '%$search%'

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Katalog Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <a href="#">Navigasi 1</a>
    <a href="#">Navigasi 2</a>
    <a href="#">Navigasi 3</a>
</div>

<div class="top-menu">
    <div class="fitur">
        <a href="#">Fitur 1</a>
        <a href="#">Fitur 2</a>
        <a href="#">Fitur 3</a>
        <a href="#">Fitur 4</a>
    </div>

    <a href="login.php" class="login-btn">Login / Registrasi</a>
</div>

<!-- ————— KONTEN UTAMA ————— -->
<div class="main-content">
    <h2>Katalog Buku</h2>

    <!-- FORM PENCARIAN -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Cari buku..." 
               value="<?= $search ?>" style="padding:6px;width:300px;">
        <button type="submit" style="padding:7px;">Cari</button>
    </form>

    <!-- TABEL HASIL -->
    <table>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Stok</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['judul'] ?></td>
            <td><?= $row['penulis'] ?></td>
            <td><?= $row['kategori'] ?></td>
            <td><?= $row['stok'] > 0 ? "Tersedia" : "Habis" ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

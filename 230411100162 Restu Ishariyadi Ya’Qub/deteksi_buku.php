<?php
include 'koneksi.php';
session_start();

$id_buku = $_GET['id'];


$query = "SELECT b.*, r.lantai, r.kode_rak, k.nama_kategori 
          FROM buku b
          LEFT JOIN rak r ON b.id_rak = r.id_rak
          LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
          WHERE b.id_buku = '$id_buku'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

$query_ebook = "SELECT * FROM ebook WHERE id_buku = '$id_buku'";
$result_ebook = mysqli_query($koneksi, $query_ebook);
$ada_ebook = mysqli_num_rows($result_ebook) > 0;
$data_ebook = mysqli_fetch_assoc($result_ebook);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Buku</title>
    </head>
<body>

    <h1><?php echo $data['judul']; ?></h1>
    <img src="<?php echo $data['cover']; ?>" width="200">
    
    <h3>Informasi Buku</h3>
    <p>Penulis: <?php echo $data['penulis']; ?></p>
    <p>Kategori: <?php echo $data['nama_kategori']; ?></p>
    <p>Stok Fisik: <?php echo $data['stok']; ?></p>

    <div style="border: 1px solid #000; padding: 10px; margin: 10px 0;">
        <h3>Lokasi Penyimpanan</h3>
        <p>Lantai: <?php echo $data['lantai']; ?></p>
        <p>Kode Rak: <strong><?php echo $data['kode_rak']; ?></strong></p>
    </div>

    <?php if ($data['stok'] > 0) { ?>
        <form action="proses_pinjam.php" method="POST">
            <input type="hidden" name="id_buku" value="<?php echo $data['id_buku']; ?>">
            <button type="submit">Pinjam Buku Fisik</button>
        </form>
    <?php } else { ?>
        <button disabled style="background: grey;">Stok Habis</button>
    <?php } ?>

    <?php if ($ada_ebook) { ?>
        <br>
        <a href="<?php echo $data_ebook['file_path']; ?>" target="_blank">
            <button style="background: lightblue;">Baca / Download E-Book</button>
        </a>
    <?php } ?>

</body>
</html>
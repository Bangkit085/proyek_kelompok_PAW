<!-- index.php -->
<?php
include "sidebar.php"; 
include "koneksi.php"; // file koneksi database

// Hitung total buku
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku"))['total'];

// Hitung peminjaman aktif
$peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'dipinjam'"
))['total'];

// Hitung peminjam terlambat
$telat = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'terlambat'"
))['total'];

// Hitung total ebook
$total_ebook = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) AS total FROM ebook"
))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card {
            border-radius: 10px;
            border: none;
        }

        .card-body {
            background: white;
            border-left: 7px solid #2f3542;
        }

        h3 {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="content">
    <h3 class="mb-4">Dashboard Admin</h3>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Buku</h5>
                    <h2><?= $total_buku ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Peminjaman Aktif</h5>
                    <h2><?= $peminjaman_aktif ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Peminjaman Terlambat</h5>
                    <h2><?= $telat ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total E-Book</h5>
                    <h2><?= $total_ebook ?></h2>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>

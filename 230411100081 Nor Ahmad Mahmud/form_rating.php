<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

$id_buku = $_GET['id_buku'];
$id_pengguna = $_SESSION['id_pengguna'];

$buku = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT judul FROM buku WHERE id_buku='$id_buku'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Rating Buku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        background-color: #e3f2fd; /* Biru Pastel */
        color: #0d6efd; /* Biru Teks yang lebih gelap */
        font-weight: 600;
    }

    .content-wrapper {
        margin-left: 280px;
        padding: 30px;
        padding-top: 90px;
    }

    .card-custom {
        border: none;
        border-radius: 15px;
        background-color: #ffffff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 25px;
    }

    .table thead {
        background-color: #e3f2fd;
        color: #0d6efd;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    .btn-success {
        border-radius: 8px;
    }
</style>
</head>
<body>

<div class="container" style="padding-top:100px;">
    <h3 class="fw-bold text-center mb-4">📝 Beri Ulasan Buku</h3>

    <div class="card shadow p-4" style="max-width:600px; margin:auto;">
        <h5 class="text-center mb-3"><?= $buku['judul']; ?></h5>

        <form action="proses_rating.php" method="POST">
            <input type="hidden" name="id_buku" value="<?= $id_buku ?>">

            <label class="form-label fw-bold">Rating (1 - 5)</label>
            <select class="form-select mb-3" name="nilai_rating" required>
                <option value="">-- Pilih Rating --</option>
                <option>1</option><option>2</option><option>3</option>
                <option>4</option><option>5</option>
            </select>

            <label class="form-label fw-bold">Ulasan</label>
            <textarea name="ulasan" class="form-control mb-3" rows="4"></textarea>
            <div class="row mt-4">
                <div class="text-center mt-4">
                    <button type="submit" name="submit" class="btn btn-primary px-4">Rating</button>
                    <a href="../nunnn/riwayat_peminjaman.php" class="btn btn-danger px-4">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

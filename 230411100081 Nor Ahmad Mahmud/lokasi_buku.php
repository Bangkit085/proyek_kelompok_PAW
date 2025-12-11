<?php
session_start();
include '../koneksi.php';
include 'function/fungsi_lokasi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

$data = null;
$pesan = "";

if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];
    $data = getLokasiById($id_buku);

    if (!$data) {
        $pesan = "Lokasi buku tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lokasi Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

        
<div class="col-auto px-0">
    <?php include '../bangkit/navbar.php'; ?>
    </div>
<div class="container" style="padding-top: 100px;">  <!-- supaya tidak ketutup navbar -->
    <h2 class="fw-bold mb-4 text-secondary text-center">📍 Informasi Lokasi Buku</h2>

    <?php if ($data): ?>
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 520px; border-radius: 15px;">
            <h4 class="mb-3 text-center"><?= htmlspecialchars($data['judul']); ?></h4>

            <p><strong>Penulis :</strong> <?= htmlspecialchars($data['penulis']); ?></p>
            <hr>

            <p><strong>Lantai :</strong> <?= $data['lantai']; ?></p>
            <p><strong>Kode Rak :</strong> <?= $data['kode_rak']; ?></p>

            <div class="mt-4 text-center">
                <a href="../bangkit/katalog.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger text-center">
            <?= $pesan; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

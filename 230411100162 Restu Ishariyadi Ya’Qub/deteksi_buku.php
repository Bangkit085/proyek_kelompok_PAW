<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Error: Pilih buku dari katalog dulu.</div>";
    exit;
}
$id_buku = intval($_GET['id']);

try {
    $sql = "SELECT b.*, k.nama_kategori, r.kode_rak, r.lantai, r.gedung, e.file_path, e.file_format
            FROM buku b
            LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
            LEFT JOIN rak r ON b.id_rak = r.id_rak
            LEFT JOIN ebook e ON b.id_buku = e.id_buku
            WHERE b.id_buku = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_buku]);
    $buku = $stmt->fetch();

    if (!$buku) {
        echo "<div class='alert alert-warning'>Buku tidak ditemukan.</div>";
        exit;
    }
} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Buku - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-book-half me-2"></i>LibrarySys</a>
        <span class="text-white">User: <?= $_SESSION['nim'] ?></span>
    </div>
</nav>

<div class="container">
    <a href="katalog.php" class="btn btn-outline-secondary mb-4 rounded-pill px-4">← Kembali ke Katalog</a>
    
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-5">
            <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="rounded-4 overflow-hidden shadow-sm position-relative" style="background:#eee;">
                        <?php 
                        $img_path = "uploads/cover_buku/" . $buku['cover'];
                        if(!empty($buku['cover']) && file_exists($img_path)): ?>
                            <img src="<?= $img_path ?>" class="img-fluid w-100" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="py-5 text-muted">No Cover Available</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-8 ps-md-5">
                    <h1 class="fw-bold text-dark mb-2"><?= htmlspecialchars($buku['judul']) ?></h1>
                    <p class="text-muted fs-5 mb-3">Penulis: <span class="text-dark fw-semibold"><?= htmlspecialchars($buku['penulis']) ?></span></p>
                    
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-4"><?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?></span>
                    
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <h6 class="fw-bold text-secondary mb-2">Lokasi Penyimpanan</h6>
                        <p class="mb-0">
                            Gedung <?= $buku['gedung'] ?? '-' ?>, Lantai <?= $buku['lantai'] ?? '-' ?>, 
                            <strong>Rak <?= $buku['kode_rak'] ?? '-' ?></strong>
                        </p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted">Penerbit</small>
                            <div class="fw-semibold"><?= htmlspecialchars($buku['penerbit'] ?? '-') ?></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tahun Terbit</small>
                            <div class="fw-semibold"><?= htmlspecialchars($buku['tahun_terbit']) ?></div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <?php if ($buku['stok'] > 0): ?>
                            <form action="proses_pinjam.php" method="POST" class="flex-grow-1">
                                <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="return confirm('Ajukan peminjaman buku ini?')">
                                    Pinjam Buku Fisik
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary flex-grow-1 py-3 rounded-pill fw-bold" disabled>Stok Habis</button>
                        <?php endif; ?>

                        <?php if (!empty($buku['file_path'])): ?>
                            <a href="uploads/file_ebook/<?= $buku['file_path'] ?>" target="_blank" class="btn btn-outline-success py-3 px-4 rounded-pill fw-bold border-2">
                                Baca E-Book
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($buku['stok'] > 0): ?>
                        <small class="text-muted d-block mt-2 text-center">*Sisa stok: <?= $buku['stok'] ?></small>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
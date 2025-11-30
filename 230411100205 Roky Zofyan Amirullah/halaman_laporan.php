<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("Location: ../bangkit/login.php");
    exit;
}
include 'koneksi.php';

// DATA LAPORAN
$laporan_denda = $conn->query("SELECT 
    SUM(jumlah_denda) as total_denda,
    SUM(CASE WHEN status_pembayaran = 'sudah' THEN jumlah_denda ELSE 0 END) as denda_terbayar,
    SUM(CASE WHEN status_pembayaran != 'sudah' THEN jumlah_denda ELSE 0 END) as denda_tertunggak
 FROM denda")->fetch(PDO::FETCH_ASSOC);

$stmt_populer = $conn->query("SELECT b.judul, COUNT(p.id_buku) as total FROM peminjaman p JOIN buku b ON p.id_buku=b.id_buku GROUP BY p.id_buku ORDER BY total DESC LIMIT 5");
$stmt_pinjam = $conn->query("SELECT p.*, u.nama, b.judul FROM peminjaman p JOIN pengguna u ON p.id_pengguna=u.id_pengguna JOIN buku b ON p.id_buku=b.id_buku ORDER BY p.tanggal_pinjam DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>body { background: #f8f9fa; }</style>
</head>
<body>
    <?php $path = "../sigit/"; include '../sigit/sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        <h4 class="fw-bold text-secondary mb-4">Laporan Statistik</h4>
        
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm">
                    <h6 class="text-muted">Denda Masuk</h6>
                    <h3 class="text-success fw-bold">Rp <?= number_format($laporan_denda['denda_terbayar'] ?? 0); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm">
                    <h6 class="text-muted">Tertunggak</h6>
                    <h3 class="text-danger fw-bold">Rp <?= number_format($laporan_denda['denda_tertunggak'] ?? 0); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm">
                    <h6 class="text-muted">Total Estimasi</h6>
                    <h3 class="text-primary fw-bold">Rp <?= number_format($laporan_denda['total_denda'] ?? 0); ?></h3>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Riwayat Transaksi</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Peminjam</th><th>Buku</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt_pinjam->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $row['tanggal_pinjam']; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['judul']; ?></td>
                            <td><span class="badge bg-secondary"><?= $row['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
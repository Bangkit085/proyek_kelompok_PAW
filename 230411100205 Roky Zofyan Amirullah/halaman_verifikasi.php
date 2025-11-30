<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("Location: ../bangkit/login.php");
    exit;
}
include 'koneksi.php';

$stmt = $conn->query("SELECT d.id_denda, p.nama, d.jumlah_denda, pay.bukti_pembayaran, pay.metode_bayar 
    FROM denda d
    JOIN pengguna p ON d.id_pengguna = p.id_pengguna
    JOIN pembayaran pay ON d.id_denda = pay.id_denda
    WHERE d.status_pembayaran = 'menunggu_verifikasi'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Verifikasi - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>body { background: #f8f9fa; }</style>
</head>
<body>
    <?php $path = "../sigit/"; include '../sigit/sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        <h4 class="fw-bold text-secondary mb-4">Verifikasi Denda</h4>
        
        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-info"><?= $_GET['pesan']; ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>Nama</th><th>Tagihan</th><th>Bukti</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold"><?= $row['nama']; ?></td>
                            <td class="text-danger">Rp <?= number_format($row['jumlah_denda']); ?></td>
                            <td>
                                <?php if($row['metode_bayar']=='QRIS'): ?>
                                    <a href="../uploads/bukti/<?= $row['bukti_pembayaran']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Foto</a>
                                <?php else: ?> Cash <?php endif; ?>
                            </td>
                            <td>
                                <a href="proses.php?aksi=verifikasi_terima&id=<?= $row['id_denda']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Terima?')">Terima</a>
                                <a href="proses.php?aksi=verifikasi_tolak&id=<?= $row['id_denda']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak?')">Tolak</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if($stmt->rowCount() == 0) echo "<p class='text-center py-3 text-muted'>Tidak ada data verifikasi.</p>"; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("Location: ../bangkit/login.php");
    exit;
}
include 'koneksi.php';

$stmt = $conn->query("SELECT r.*, u.nama, b.judul FROM rating r JOIN pengguna u ON r.id_pengguna=u.id_pengguna JOIN buku b ON r.id_buku=b.id_buku ORDER BY r.dibuat_pada DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Rating - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>body { background: #f8f9fa; }</style>
</head>
<body>
    <?php $path = "../sigit/"; include '../sigit/sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        <h4 class="fw-bold text-secondary mb-4">Rating & Ulasan</h4>
        
        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-success">Ulasan berhasil dihapus.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>Mahasiswa</th><th>Buku</th><th>Rating</th><th>Ulasan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold"><?= $row['nama']; ?></td>
                            <td><?= $row['judul']; ?></td>
                            <td class="text-warning"><?= str_repeat("★", $row['nilai_rating']); ?></td>
                            <td class="text-muted small">"<?= $row['ulasan']; ?>"</td>
                            <td>
                                <a href="proses.php?aksi=hapus_rating&id=<?= $row['id_rating']; ?>" class="btn btn-light text-danger btn-sm" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
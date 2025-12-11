<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

$query = "SELECT peminjaman.*, buku.judul 
          FROM peminjaman
          INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
          WHERE peminjaman.id_pengguna = '$id_pengguna'
          ORDER BY peminjaman.id_peminjaman DESC";

$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/bangkit/favicon.png">
    <title>Pemijaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
body {
    background-color: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar layout */
.main-content {
    margin-left: 280px;
    padding: 30px;
    padding-top: 100px;
    min-height: 100vh;
}

/* Statistik Card (jika digunakan bersama dashboard lain) */
.stat-card {
    border: none;
    border-radius: 12px;
    background: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s;
    display: flex;
    align-items: center;
}

.stat-card:hover { transform: translateY(-5px); }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 15px;
}

.bg-icon-blue { background-color: #e3f2fd; color: #0d6efd; }
.bg-icon-red { background-color: #f8d7da; color: #dc3545; }
.bg-icon-green { background-color: #d1e7dd; color: #198754; }

/* Card umum */
.card-custom {
    border: none;
    border-radius: 15px;
    background-color: #ffffff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

/* Header card */
.card-header-custom {
    background-color: white;
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 20px;
    font-weight: 700;
    color: #555;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* Tabel */
.table thead {
    background-color: #e3f2fd;
    color: #0d6efd;
}

/* Button tambahan */
.btn-success {
    border-radius: 8px;
}

</style>
</head>
<body>

<?php include '../bangkit/navbar.php'; ?>
<div class="main-content">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-secondary"><i class="bi bi-book me-2"></i>Riwayat Peminjaman Buku</h2>
    </div>
</div>
<table class="table table-bordered table-striped text-center mt-3">
    <thead class="table-secondary">
        <tr>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th> <!-- Kolom tambahan -->
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['judul']); ?></td>
            <td><?= $row['tanggal_pinjam']; ?></td>
            <td><?= $row['tanggal_kembali'] ?? '-'; ?></td>
            <td>
                <?php if ($row['status'] == 'dipinjam'): ?>
                    <span class="badge bg-warning text-dark">Dipinjam</span>
                <?php elseif ($row['status'] == 'dikembalikan'): ?>
                    <span class="badge bg-success">Dikembalikan</span>
                <?php else: ?>
                    <span class="badge bg-danger">Terlambat</span>
                <?php endif; ?>
            </td>

            <td>
                <?php if ($row['status'] == 'dikembalikan'): ?>
                    <a href="form_rating.php?id_buku=<?= $row['id_buku']; ?>&id_pinjam=<?= $row['id_peminjaman']; ?>" 
                       class="btn btn-primary btn-sm">Beri Ulasan</a>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled>Belum Bisa</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

</div>
</body>
</html>

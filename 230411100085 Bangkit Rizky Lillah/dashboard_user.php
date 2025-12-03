<?php
require "../koneksi.php";
session_start();

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_pengguna'] ?? $_SESSION['id'] ?? 0; 
$nama_user = $_SESSION['nama'] ?? 'Mahasiswa';
$nim_user = $_SESSION['nim'] ?? '-';


$q_pinjam = "SELECT p.*, b.judul, b.cover 
             FROM peminjaman p 
             JOIN buku b ON p.id_buku = b.id_buku 
             WHERE p.id_pengguna = '$id_user' AND p.status = 'dipinjam'
             ORDER BY p.tanggal_pinjam DESC";
$pinjaman_aktif = mysqli_query($koneksi, $q_pinjam);


$total_denda = 0;
$cek_tabel_denda = mysqli_query($koneksi, "SHOW TABLES LIKE 'denda'");
if ($cek_tabel_denda && mysqli_num_rows($cek_tabel_denda) > 0) {
    $q_denda = "SELECT SUM(jumlah_denda) as total FROM denda 
                WHERE id_anggota = '$id_user' AND status_denda = 'belum_lunas'";
    $res_denda = mysqli_query($koneksi, $q_denda);
    $row_denda = mysqli_fetch_assoc($res_denda);
    $total_denda = $row_denda['total'] ?? 0;
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
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Dashboard - Perpustakaan</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #e3f2fd; /* Biru Pastel */
            color: #0d6efd; /* Biru Teks yang lebih gelap */
            font-weight: 600;
        }
        
        /* Layout Sidebar Consistent */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            padding-top: 100px; /* Space for Navbar */
            min-height: 100vh;
        }

        /* Card Statistik Minimalis */
        .stat-card {
            border: none;
            border-radius: 12px;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        /* Warna Pastel Custom */
        .bg-icon-blue { background-color: #e3f2fd; color: #0d6efd; }
        .bg-icon-red { background-color: #f8d7da; color: #dc3545; }
        .bg-icon-green { background-color: #d1e7dd; color: #198754; }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }
        .card-header-custom {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 700;
            color: #555;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
    </style>
</head>
<body>

<!-- Include Navbar (Pastikan file navbar.php ada di folder yang sama) -->
<?php include 'navbar.php'; ?>

<div class="main-content">
    
    <!-- Header Welcome -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-secondary"><i class="bi bi-bookshelf me-2"></i>Dashboard</h2>
            <p class="text-muted">Selamat datang kembali, <?= htmlspecialchars($nama_user) ?>!</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y') ?>
            </span>
        </div>
    </div>

    <!-- 1. BARIS STATISTIK (RINGKASAN) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Pinjaman Aktif -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Sedang Dipinjam</h6>
                    <h3 class="fw-bold mb-0"><?= mysqli_num_rows($pinjaman_aktif) ?> <small class="fs-6 text-muted">Buku</small></h3>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Total Denda -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-icon-red">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Tagihan Denda</h6>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($total_denda, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <!-- Card 3: Status Akun -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-icon-green">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Status Keanggotaan</h6>
                    <h5 class="fw-bold mb-0 text-success">Aktif</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- KOLOM KIRI (UTAMA) -->
        <div class="col-lg-8">

            <!-- TABEL PEMINJAMAN AKTIF -->
            <div class="card card-custom mb-4">
                <div class="px-3"> <!-- Padding kanan & kiri -->
        
                    <!-- Header Judul -->
                    <div class="text-justify py-2 mb-3">
                        <h4 class="fw-bold text-secondary mb-0">
                            <i class="bi bi-book me-2"></i>Riwayat Peminjaman Buku
                        </h4>
                    </div>
        
                    <!-- TABEL -->
                    <table class="table table-bordered table-striped text-center mt-3">
                        <thead class="table-secondary">
                            <tr>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
                                        <a href="../nunnn/form_rating.php?id_buku=<?= $row['id_buku']; ?>&id_pinjam=<?= $row['id_peminjaman']; ?>" 
                                           class="btn btn-primary btn-sm">Beri Ulasan</a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>Belum Bisa</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div> <!-- End padding -->
            </div>
        </div>

        <!-- KOLOM KANAN (SIDEBAR KANAN) -->
        <div class="col-lg-4">
            
            <!-- 4. KARTU PROFIL SINGKAT -->
            <div class="card card-custom">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold mb-0"><?= $nama_user ?></h5>
                    <p class="text-muted small"><?= $nim_user ?></p>
                    <hr>
                    <div class="d-grid">
                        <a href="edit_profil.php" class="btn btn-outline-secondary btn-sm">Edit Profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
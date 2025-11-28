<?php
// session_start();

// // 2. CEK APAKAH SUDAH LOGIN & APAKAH DIA ADMIN?
// if($_SESSION['status'] != "login"){
//     header("location:../login.php?pesan=belum_login");
//     exit();
// }

// // Opsional: Cek apakah dia benar-benar admin? (Jaga-jaga mahasiswa iseng masuk link admin)
// if($_SESSION['role'] != "admin"){
//     die("Anda bukan Admin! Akses ditolak.");
// }

// 1. Panggil Koneksi (Mundur 1 folder karena index.php ada di dalam folder SIGIT)
include 'koneksi.php';
$path = "";

// 2. Query untuk Data Dashboard (Versi PDO Singkat)
// Hitung Total Buku
$data_buku = $conn->query("SELECT COUNT(*) as total FROM buku")->fetch();

// Hitung Peminjaman Aktif (Status 'dipinjam')
$data_pinjam = $conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'")->fetch();

// Hitung Total Ebook
$data_ebook = $conn->query("SELECT COUNT(*) as total FROM ebook")->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* Gaya Kustom Minimalis & Pastel */
        body {
            background-color: #f8f9fa; /* Abu-abu sangat muda (hampir putih) */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Modifikasi Sidebar Link saat Aktif */
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #e3f2fd; /* Biru Pastel */
            color: #0d6efd; /* Biru Teks yang lebih gelap */
            font-weight: 600;
        }

        /* Card Statistik yang Elegan */
        .card-stat {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #ffffff;
        }

        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .bg-pastel-blue { background-color: #e3f2fd; color: #0d6efd; }
        .bg-pastel-green { background-color: #d1e7dd; color: #0f5132; }
        .bg-pastel-purple { background-color: #e2d9f3; color: #59359a; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-auto px-0">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col" style="margin-left: 280px; padding: 30px;">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fw-bold text-secondary">Dashboard Overview</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card card-stat p-3 h-100 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Koleksi Buku</h6>
                                    <h2 class="fw-bold mb-0"><?php echo $data_buku['total']; ?></h2>
                                </div>
                                <div class="icon-box bg-pastel-blue">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="modul_buku/list_buku.php" class="text-decoration-none text-muted small">
                                    Lihat Detail <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-stat p-3 h-100 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Sedang Dipinjam</h6>
                                    <h2 class="fw-bold mb-0"><?php echo $data_pinjam['total']; ?></h2>
                                </div>
                                <div class="icon-box bg-pastel-green">
                                    <i class="bi bi-person-check fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="modul_transaksi/list_pinjam.php" class="text-decoration-none text-muted small">
                                    Validasi Pinjaman <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-stat p-3 h-100 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Koleksi E-Book</h6>
                                    <h2 class="fw-bold mb-0"><?php echo $data_ebook['total']; ?></h2>
                                </div>
                                <div class="icon-box bg-pastel-purple">
                                    <i class="bi bi-tablet-landscape fs-4"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="modul_ebook/list_ebook.php" class="text-decoration-none text-muted small">
                                    Kelola File <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-4">
                            <h5 class="fw-bold text-secondary mb-3">Aktivitas Terbaru</h5>
                            <p class="text-muted">Belum ada aktivitas yang direkam.</p>
                        </div>
                    </div>
                </div>

            </div> </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
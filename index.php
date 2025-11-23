<?php
// index.php - Dashboard Admin
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-2 p-0">
            <?php include 'sidebar.php'; ?>
        </div>

        <!-- Content -->
        <div class="col-10 p-4">
            <h2>Dashboard Admin</h2>
            <hr>

            <!-- Card Statistik -->
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h3 class="text-primary">120</h3>
                            <p>Total Buku</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h3 class="text-success">35</h3>
                            <p>Buku Dipinjam</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h3 class="text-warning">4</h3>
                            <p>Buku Terlambat</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h3 class="text-danger">2</h3>
                            <p>Denda Belum Dibayar</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-4">

            <!-- Tabel Riwayat Terbaru -->
            <h4>Riwayat Peminjaman Terbaru</h4>
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Rizki</td>
                                <td>Pemrograman Web</td>
                                <td>2025-02-10</td>
                                <td><span class="badge bg-success">Dipinjam</span></td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>Basis Data</td>
                                <td>2025-02-08</td>
                                <td><span class="badge bg-warning">Terlambat</span></td>
                            </tr>
                            <tr>
                                <td>Siti Aisyah</td>
                                <td>Data Mining</td>
                                <td>2025-02-05</td>
                                <td><span class="badge bg-secondary">Dikembalikan</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
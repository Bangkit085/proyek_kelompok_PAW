<?php
// sidebar.php - Sidebar navigasi Admin Perpustakaan
?>

<!-- Sidebar Container -->
<div class="d-flex flex-column p-3 bg-dark text-white" style="width: 250px; min-height: 100vh; position: fixed;">
    <h4 class="text-center mb-4">Admin Panel</h4>

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Dashboard -->
        <li class="nav-item mb-2">
            <a href="/ariii/index.php" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <!-- Modul Buku -->
        <li class="nav-item mb-2">
            <a href="/ariii/modul_buku/list_buku.php" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> Manajemen Buku
            </a>
        </li>

        <!-- Modul Transaksi -->
        <li class="nav-item mb-2">
            <a href="/ariii/modul_transaksi/list_pinjam.php" class="nav-link text-white">
                <i class="bi bi-arrow-left-right me-2"></i> Transaksi Peminjaman
            </a>
        </li>

        <!-- Modul E-Book -->
        <li class="nav-item mb-2">
            <a href="/ariii/modul_ebook/list_ebook.php" class="nav-link text-white">
                <i class="bi bi-file-earmark-pdf me-2"></i> Manajemen E-Book
            </a>
        </li>
    </ul>

    <hr class="text-secondary">

    <!-- LOGOUT -->
    <div>
        <a href="/logout.php" class="nav-link text-white">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>
</div>

<!-- Tambahkan Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
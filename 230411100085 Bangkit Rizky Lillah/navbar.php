<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Tentukan path relatif yang dibutuhkan (berapa kali '../' yang diperlukan)
$path_root = '';
// Hitung level direktori (jumlah '/' di PHP_SELF, dikurangi 1 untuk file itu sendiri)
$level = substr_count($_SERVER['PHP_SELF'], '/');

// Kita hanya perlu mundur 1 level karena semua file Anda berada di folder tingkat pertama
if ($level > 1) {
    // Jika ada lebih dari satu slash (berarti di dalam sub-folder)
    $path_root = '../';
} else {
    // Jika di root, biarkan kosong
    $path_root = '';
}

// Nama file pemanggil (untuk logika 'active')
$current_file = basename($_SERVER['PHP_SELF']);
?>

<!-- SIDEBAR -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-white shadow-sm"
     style="width: 280px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">

    <a href="/bangkit/dashboard_user.php"
       class="d-flex align-items-center mb-3 link-dark text-decoration-none">
        <i class="bi bi-book-half fs-4 me-2 text-primary"></i>
        <span class="fs-4 fw-bold">LibrarySys</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href="/bangkit/dashboard_user.php"
               class="nav-link <?= ($current_file=='dashboard_user.php')?'active':'link-dark'; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="/bangkit/katalog.php"
               class="nav-link <?= ($current_file=='katalog.php')?'active':'link-dark'; ?>">
                <i class="bi bi-journal-text me-2"></i> Katalog Buku
            </a>
        </li>

        <li class="nav-item">
            <a href="/nunnn/riwayat_peminjaman.php"
               class="nav-link <?= ($current_file=='riwayat_peminjaman.php')?'active':'link-dark'; ?>">
                <i class="bi bi-arrow-left-right me-2"></i> Riwayat Peminjaman
            </a>
        </li>

        <li class="nav-item">
            <a href="/restu/ebook.php"
               class="nav-link <?= ($current_file=='ebook.php')?'active':'link-dark'; ?>">
                <i class="bi bi-tablet me-2"></i> E-Book
            </a>
        </li>

        <li class="nav-item">
            <a href="/nunnn/pembayaran_denda.php"
               class="nav-link <?= ($current_file=='pembayaran_denda.php')?'active':'link-dark'; ?>">
                <i class="bi bi-cash-coin me-2"></i> Denda
            </a>
        </li>

    </ul>

    <hr>

    <footer class="footer" style="padding:10px; background:#f4f4f4; text-align:center; border-top:1px solid #ddd;">
        <p style="margin:0; color:#555;">© 2025 Sistem Perpustakaan Digital</p>
    </footer>
</div>

<!-- TOPBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm"
     style="position: fixed; top: 0; right: 0; left: 280px; z-index: 2000; height: 60px;">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <!-- (Search dihapus) -->
        <div></div>

        <!-- USER DROPDOWN -->
        <div class="dropdown me-3">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">

                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2"
                     style="width: 36px; height: 36px; font-weight: 600;">
                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                </div>

                <span class="fw-semibold"><?= $_SESSION['nama']; ?></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <a class="dropdown-item disabled">
                        <span class="fw-semibold"><?= $_SESSION['nama']; ?></span><br>
                        <span class="fw-semibold"><?= $_SESSION['nim']; ?></span><br>
                        <span class="text-muted" style="font-size:12px;"><?= $_SESSION['email']; ?></span>
                    </a>
                </li>

                <?php if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") : ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/bangkit/login.php">Login</a></li>

                <?php else : ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/bangkit/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
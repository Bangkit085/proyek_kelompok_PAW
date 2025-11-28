<?php
// Cek apakah variabel $path sudah didefinisikan di halaman yang memanggil sidebar ini?
// Jika belum (misal di halaman index.php), set jadi kosong "".
// Jika sudah (misal di dalam modul), biarkan isinya (misal "../").
$path = isset($path) ? $path : "";
?>

<div class="d-flex flex-column flex-shrink-0 p-3 bg-white shadow-sm" style="width: 280px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
    <a href="<?php echo $path; ?>index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <i class="bi bi-book-half fs-4 me-2 text-primary"></i>
        <span class="fs-4 fw-bold" style="color: #5a7c93;">LibrarySys</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?php echo $path; ?>index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : 'link-dark'; ?>">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo $path; ?>modul_buku/list_buku.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'list_buku.php' || basename($_SERVER['PHP_SELF']) == 'tambah.php' || basename($_SERVER['PHP_SELF']) == 'edit.php') ? 'active' : 'link-dark'; ?>">
                <i class="bi bi-journal-text me-2"></i>
                Data Buku
            </a>
        </li>
        <li>
            <a href="<?php echo $path; ?>modul_transaksi/list_pinjam.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'list_pinjam.php') ? 'active' : 'link-dark'; ?>">
                <i class="bi bi-arrow-left-right me-2"></i>
                Peminjaman
            </a>
        </li>
        <li>
            <a href="<?php echo $path; ?>modul_ebook/list_ebook.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'list_ebook.php') ? 'active' : 'link-dark'; ?>">
                <i class="bi bi-tablet me-2"></i>
                E-Book
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Admin+Sigit&background=random" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Admin Sigit</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
            <li><a class="dropdown-item text-danger" href="<?php echo $path; ?>../logout.php">Sign out</a></li>
        </ul>
    </div>
</div>
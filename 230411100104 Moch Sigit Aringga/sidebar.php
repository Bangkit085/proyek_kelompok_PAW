<!-- sidebar.php -->
<?php
// opsional: session admin
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit;
// }
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f4f4f4;
    }

    .sidebar {
        width: 230px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background-color: #2f3542; /* abu2 tua */
        color: white;
        padding-top: 20px;
    }

    .sidebar a {
        color: #dfe4ea;
        padding: 12px 20px;
        display: block;
        text-decoration: none;
        font-size: 15px;
    }

    .sidebar a:hover {
        background-color: #57606f;
        color: white;
    }

    .sidebar .logo {
        font-size: 22px;
        font-weight: bold;
        padding: 0 20px 20px 20px;
        text-align: left;
        border-bottom: 1px solid #57606f;
        margin-bottom: 10px;
    }

    .content {
        margin-left: 240px;
        padding: 20px;
    }
</style>

<div class="sidebar">
    <div class="logo">📚 Perpustakaan</div>

    <a href="http://localhost/proyek_kelompok_PAW/230411100104%20Moch%20Sigit%20Aringga">🏠 Dashboard</a>
    <a href="http://localhost/proyek_kelompok_PAW/230411100104%20Moch%20Sigit%20Aringga/modul_buku/list_buku.php?">📘 Manajemen Buku</a>
    <a href="http://localhost/proyek_kelompok_PAW/230411100104%20Moch%20Sigit%20Aringga/modul_transaksi/list_pinjam.php">🔄 Peminjaman</a>
    <a href="modul_ebook/list_ebook.php">📂 E-Book</a>
    <a href="logout.php">🚪 Logout</a>
</div>

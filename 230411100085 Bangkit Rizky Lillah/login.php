<?php 
session_start();
require 'function.php';  

$error = null;  

if (!isset($_SESSION['login'])) {     
    $error = checkLogin($_POST);  
} 
?>  

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Login LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">

<div class="card p-4 shadow-sm text-center" style="width: 380px;">

    <!-- LOGO + JUDUL DI TENGAH -->
    <i class="bi bi-book-half fs-1 text-primary"></i>
    <div class="mt-2 mb-4">
        <span class="fs-3 fw-bold" style="color: #5a7c93;">Login LibrarySys</span>
    </div>

    <form method="POST">

        <!-- NIM -->
        <div class="mb-3 text-start">
            <label class="form-label">NIM</label>
            <input type="text" name="nim" class="form-control" required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3 text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <!-- ERROR -->
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- BUTTON LOGIN -->
        <button type="submit" name="login" class="btn btn-primary w-100 mb-3">
            Login
        </button>

        <!-- LINK REGISTER -->
        <div class="text-center text-secondary">
            Belum punya akun? 
            <a href="register.php">Daftar di sini</a>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

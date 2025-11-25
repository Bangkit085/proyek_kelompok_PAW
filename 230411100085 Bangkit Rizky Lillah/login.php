<?php
session_start();
require "koneksi.php";

if(isset($_SESSION['nim'])) {
    header("Location: index.php");
    exit;
}

if(isset($_POST['login'])) {
    $nim = $_POST['nim'];
    $password = md5($_POST['password']);

    $q = mysqli_query($conn, "SELECT * FROM pengguna WHERE nim='$nim' AND password='$password'");

    if(mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $_SESSION['nim'] = $data['nim'];
        $_SESSION['peran'] = $data['peran'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Nim atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">


<div class="container mt-5" style="max-width: 400px;">
    <div class="card p-4 shadow-sm"><br>
        <img src="logo.png" alt="Logo PD" style="width:200px;" class="mx-auto d-block"> 
        <h1 class="text-center">Perpustakaan <br>Digital</h1><br>
        
        <form method="POST">
            <div class="mb-3" >
                <label>Nim</label>
                <input type="text" name="nim" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </div>
            <div class="text-center text-secondary" >
                Belum punya akun? <a href="register.php" id="">Daftar di sini</a>
            </div>
        </form>

    </div>
</div>

</body>
</html>

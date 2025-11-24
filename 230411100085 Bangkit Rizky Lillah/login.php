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
        $error = "nim atau Password salah!";
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
    <div class="card p-4 shadow-sm">

        <h3 class="text-center">Login</h3>

        <?php if(isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nim</label>
                <input type="text" name="nim" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" name="login" class="btn btn-primary w-50">Login</button>
                <a href="register.php" class="btn btn-success w-50">Register</a>
            </div>

        </form>

    </div>
</div>

</body>
</html>

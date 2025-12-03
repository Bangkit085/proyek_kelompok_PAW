<?php
require "../koneksi.php";
session_start();

// Cek login — pakai session id_pengguna
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_pengguna'];

// Ambil data user dari database
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna = '$id_user'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "Data pengguna tidak ditemukan.";
    exit;
}

// Proses update data
if (isset($_POST['simpan'])) {

    $nim     = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email   = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password']; // nanti di-hash jika diisi

    if ($password == "") {
        // Tidak mengganti password
        $update = mysqli_query($koneksi, "
            UPDATE pengguna SET
                nim='$nim',
                nama='$nama',
                email='$email'
            WHERE id_pengguna='$id_user'
        ");
    } else {
        // Mengganti password
        $hash = md5($password); // mengikuti sistem awal
        $update = mysqli_query($koneksi, "
            UPDATE pengguna SET
                nim='$nim',
                nama='$nama',
                email='$email',
                password='$hash'
            WHERE id_pengguna='$id_user'
        ");
    }

    // Jika berhasil → redirect ke dashboard
    if ($update) {
        $_SESSION['nim'] = $nim;
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;

        header("Location: dashboard_user.php");
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Gagal mengupdate profil.</div>";
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .form-label { font-weight: 600; color: #444; }
</style>

</head>

<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">

<h3>Edit Profil</h3>
<hr class="mb-4">

<?= isset($message) ? $message : '' ?>

<form method="POST">

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">NIM</label>
        <div class="col-sm-9">
            <input type="number" name="nim" class="form-control"
                   value="<?= $user['nim'] ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Nama User</label>
        <div class="col-sm-9">
            <input type="text" name="nama" class="form-control"
                   value="<?= $user['nama'] ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <input type="email" name="email" class="form-control"
                   value="<?= $user['email'] ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Password Baru</label>
        <div class="col-sm-9">
            <input type="password" name="password" class="form-control"
                   placeholder="Kosongkan jika tidak ubah password">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-9 offset-sm-3">
            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            <a href="dashboard_user.php" class="btn btn-danger">Batal</a>
        </div>
    </div>

</form>

</div>

</body>
</html>

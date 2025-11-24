<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nim = $_POST['nim'];
    $password = md5($_POST['password']);
    $nama     = $_POST['nama'];
    $email   = $_POST['email'];
    $peran    = $_POST['peran'];

    $query = "INSERT INTO pengguna (nim, password, nama, email, peran) 
              VALUES ('$nim', '$password', '$nama', '$email', '$peran')";
    
    mysqli_query($conn, $query);
    header("Location: index.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-label {
            font-weight: 600;
            color: #444;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">

    <h3>REGISTER</h3>
    <hr class="mb-4">

    <form method="POST">

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Nim</label>
            <div class="col-sm-9">
                <input type="number" name="nim" class="form-control" placeholder="nim" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Nama User</label>
            <div class="col-sm-9">
                <input type="text" name="nama" class="form-control" placeholder="Nama User" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
                <input type="email" name="email" class="form-control" rows="3" placeholder="email">
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Peran</label>
            <div class="col-sm-9">
                <select name="peran" class="form-select">
                    <option value="">--- Pilih Jenis Peran ---</option>
                    <option value="Dosen">Dosen</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                </select>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                <a href="index.php" class="btn btn-danger">Batal</a>
            </div>
        </div>

    </form>

</div>

</body>
</html>

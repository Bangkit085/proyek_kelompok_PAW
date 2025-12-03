<?php
session_start();

// Panggil file koneksi temanmu
require '../koneksi.php'; 

function checkLogin($data)
{
    global $koneksi; 
    
    $input_user = $data['nim'] ?? null;
    $password   = $data['password'] ?? null;

    // Jika form belum diisi → jangan tampilkan error
    if (!$input_user || !$password) {
        return null;
    }

    // Enkripsi password
    $password = md5($password);

    // Query login
    $query_str = "SELECT * FROM pengguna 
                  WHERE (nim='$input_user' OR email='$input_user') 
                  AND password='$password'";
                  
    $result = mysqli_query($koneksi, $query_str);

    if (!$result) {
        return "Error Database: " . mysqli_error($koneksi);
    }

    // Cek apakah user ditemukan
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // SESSION
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        $_SESSION['nim']         = $user['nim'];
        $_SESSION['nama']        = $user['nama'];
        $_SESSION['email']       = $user['email'];
        $_SESSION['peran']       = $user['peran'];
        $_SESSION['status']      = "login";
        $_SESSION['role']        = $user['peran'];

        // Redirect
        if ($user['peran'] == "admin") {
            header("Location: ../sigit/index.php");
            exit;
        } elseif ($user['peran'] == "mahasiswa") {
            header("Location: dashboard_user.php");
            exit;
        } else {
            return "Peran akun tidak valid!";
        }

    } 
    else {
        return "NIM/Email atau Password salah!";
    }
}

<?php

// 1. Mendapatkan daftar denda belum lunas berdasarkan user
function getDendaBelumLunas($koneksi, $id_pengguna) {
    $sql = "SELECT d.id_denda, d.jumlah_denda, d.status_pembayaran,
            b.judul, p.tanggal_kembali
            FROM denda d
            JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_pengguna = ? AND d.status_pembayaran != 'Lunas'";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    return $stmt->get_result();
}

// 2. Proses pembayaran denda
function prosesPembayaranDenda($koneksi, $id_denda, $jumlah_bayar, $metode_bayar, $bukti_qris = null) {

    if ($metode_bayar == "Cash") {

        $sql = "INSERT INTO pembayaran (id_denda, tanggal_bayar, jumlah_bayar, metode_bayar, status_verifikasi)
                VALUES (?, NOW(), ?, ?, 'Diterima')";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ids", $id_denda, $jumlah_bayar, $metode_bayar);
        $stmt->execute();

        $update = $koneksi->prepare("UPDATE denda SET status_pembayaran='Lunas' WHERE id_denda=?");
        $update->bind_param("i", $id_denda);
        return $update->execute();

    } else if ($metode_bayar == "QRIS") {

        $namaFile = time() . "_" . $_FILES['bukti']['name'];
        $lokasi = "../uploads_qris/" . $namaFile;

        if (move_uploaded_file($_FILES['bukti']['tmp_name'], $lokasi)) {

            $sql = "INSERT INTO pembayaran (id_denda, tanggal_bayar, jumlah_bayar, metode_bayar, bukti_qris, status_verifikasi)
                    VALUES (?, NOW(), ?, ?, ?, 'Menunggu Verifikasi')";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("idss", $id_denda, $jumlah_bayar, $metode_bayar, $namaFile);
            return $stmt->execute();
        }
    }

    return false;
}

function getRiwayatDenda($koneksi, $id_pengguna) {
    $query = "SELECT denda.*, buku.judul 
              FROM denda 
              JOIN peminjaman ON denda.id_peminjaman = peminjaman.id_peminjaman
              JOIN buku ON peminjaman.id_buku = buku.id_buku
              WHERE denda.id_pengguna = '$id_pengguna' 
              AND denda.status_pembayaran != 'Belum Lunas'
              ORDER BY denda.id_denda DESC";
    return mysqli_query($koneksi, $query);
}


<?php
// fungsi_peminjaman.php

/**
 * Mendapatkan riwayat peminjaman untuk pengguna tertentu.
 */
function getRiwayatPeminjaman($koneksi, $id_pengguna) {
    $sql = "SELECT p.tanggal_pinjam, p.tanggal_kembali, b.judul
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_pengguna = ?
            ORDER BY p.tanggal_pinjam DESC";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $riwayat = [];
    while ($row = $result->fetch_assoc()) {
        $riwayat[] = $row;
    }
    return $riwayat;
}

?>
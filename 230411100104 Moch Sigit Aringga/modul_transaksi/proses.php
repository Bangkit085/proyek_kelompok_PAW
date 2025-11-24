<?php
include "../koneksi.php";

if (isset($_GET['aksi']) && $_GET['aksi'] == "setujui") {
    $id = $_GET['id'];

    // ambil id buku
    $data = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT id_buku FROM peminjaman WHERE id_peminjaman = $id"
    ));
    $id_buku = $data['id_buku'];

    mysqli_query($conn, "UPDATE peminjaman SET status = 'dipinjam' WHERE id_peminjaman = $id");

    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = $id_buku");

    header("Location: list_pinjam.php");
    exit;
}


if (isset($_POST['aksi']) && $_POST['aksi'] == "kembalikan") {

    $id_peminjaman = $_POST['id_peminjaman'];
    $denda = $_POST['denda'];

    $data = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM peminjaman WHERE id_peminjaman = $id_peminjaman
    "));

    $id_buku = $data['id_buku'];
    $id_pengguna = $data['id_pengguna'];

    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status = 'dikembalikan', tanggal_kembali = CURDATE()
        WHERE id_peminjaman = $id_peminjaman
    ");

    mysqli_query($conn, "
        UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku
    ");

    // Jika ada denda → masukkan ke tabel denda
    if ($denda > 0) {
        mysqli_query($conn, "
            INSERT INTO denda (id_peminjaman, id_pengguna, jumlah_denda, status_pembayaran)
            VALUES ($id_peminjaman, $id_pengguna, $denda, 'belum')
        ");

        // Update status jadi terlambat
        mysqli_query($conn, "
            UPDATE peminjaman SET status = 'terlambat'
            WHERE id_peminjaman = $id_peminjaman
        ");
    }

    header("Location: list_pinjam.php");
    exit;
}

?>

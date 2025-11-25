<?php
include 'koneksi.php';

$denda_awal = 5000;
$denda_tambahan_per_hari = 1000;

$id_peminjaman_yang_dikembalikan = $_POST['id_peminjaman'];
$tanggal_kembali_sekarang = date('Y-m-d');

$stmt = $pdo->prepare("SELECT id_pengguna, tanggal_jatuh_tempo FROM peminjaman WHERE id_peminjaman = ?");
$stmt->execute([$id_peminjaman_yang_dikembalikan]);
$pinjam = $stmt->fetch();

if ($pinjam) {
    $stmt_update = $pdo->prepare("UPDATE peminjaman SET tanggal_kembali = ?, status = 'dikembalikan' WHERE id_peminjaman = ?");
    $stmt_update->execute([$tanggal_kembali_sekarang, $id_peminjaman_yang_dikembalikan]);

    $tgl_jatuh_tempo = new DateTime($pinjam['tanggal_jatuh_tempo']);
    $tgl_kembali = new DateTime($tanggal_kembali_sekarang);

    if ($tgl_kembali > $tgl_jatuh_tempo) {
        $selisih_hari = $tgl_kembali->diff($tgl_jatuh_tempo)->days;

        if ($selisih_hari == 1) {
            $jumlah_denda = $denda_awal;
        } else {
            $jumlah_denda = $denda_awal + (($selisih_hari - 1) * $denda_tambahan_per_hari);
        }

        $stmt_denda = $pdo->prepare(
            "INSERT INTO denda (id_peminjaman, id_pengguna, jumlah_denda, status_pembayaran)
             VALUES (?, ?, ?, 'belum')"
        );
        $stmt_denda->execute([
            $id_peminjaman_yang_dikembalikan,
            $pinjam['id_pengguna'],
            $jumlah_denda
        ]);
    }
} else {
    echo "Error: Data peminjaman tidak ditemukan.";
    exit;
}

header("Location: dashboard_admin.php?page=pengembalian");
exit;
?>
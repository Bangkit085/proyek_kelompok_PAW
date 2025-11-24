<?php
include "../koneksi.php";
include "../sidebar.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT peminjaman.*, buku.judul 
    FROM peminjaman
    JOIN buku ON peminjaman.id_buku = buku.id_buku
    WHERE id_peminjaman = $id
"));
?>

<div class="content">
    <h3>Proses Pengembalian Buku</h3>
    <div class="card mt-3 p-4">

        <form action="proses.php" method="POST">
            <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman'] ?>">

            <div class="mb-3">
                <label>Judul Buku</label>
                <input type="text" class="form-control" value="<?= $data['judul'] ?>" disabled>
            </div>

            <div class="mb-3">
                <label>Tanggal Pinjam</label>
                <input type="text" class="form-control" value="<?= $data['tanggal_pinjam'] ?>" disabled>
            </div>

            <div class="mb-3">
                <label>Jatuh Tempo</label>
                <input type="text" class="form-control" value="<?= $data['tanggal_jatuh_tempo'] ?>" disabled>
            </div>

            <?php
            $tglJatuh = strtotime($data['tanggal_jatuh_tempo']);
            $tglHariIni = strtotime(date('Y-m-d'));

            $telat = 0;
            if ($tglHariIni > $tglJatuh) {
                $telat = ($tglHariIni - $tglJatuh) / 86400;
            }

            $denda = $telat * 1000; // 1 hari = 1000
            ?>

            <div class="mb-3">
                <label>Terlambat (hari)</label>
                <input type="text" class="form-control" value="<?= $telat ?>" disabled>
            </div>

            <div class="mb-3">
                <label>Denda</label>
                <input type="text" class="form-control" value="Rp <?= number_format($denda) ?>" disabled>
            </div>

            <input type="hidden" name="denda" value="<?= $denda ?>">

            <button type="submit" name="aksi" value="kembalikan" class="btn btn-primary">
                Konfirmasi Pengembalian
            </button>
        </form>

    </div>
</div>

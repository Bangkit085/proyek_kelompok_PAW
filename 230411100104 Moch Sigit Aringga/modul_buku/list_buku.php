<?php
include "../sidebar.php";
include "../koneksi.php";

// Ambil daftar kategori untuk dropdown filter
$kategori = mysqli_query($conn, "SELECT * FROM kategori");

// Siapkan kondisi WHERE (optional)
$where = "";

if (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] != "") {
    $idkat = $_GET['filter_kategori'];
    $where = " WHERE buku.id_kategori = '$idkat' ";
}

// Query utama ambil data buku + kategori + rak
$sql = "SELECT buku.*, kategori.nama_kategori, rak.kode_rak 
        FROM buku
        JOIN kategori ON buku.id_kategori = kategori.id_kategori
        JOIN rak ON buku.id_rak = rak.id_rak
        $where
        ORDER BY buku.id_buku DESC";

// JALANKAN QUERY DENGAN VARIABEL YANG BENAR
$result = mysqli_query($conn, $sql);
?>


<div class="content">
    <h3 class="mb-3">📘 Daftar Buku</h3>

    <a href="tambah.php" class="btn btn-dark mb-3">+ Tambah Buku</a>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="filter_kategori" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            <?php while ($kat = mysqli_fetch_assoc($kategori)) { ?>
                                <option value="<?= $kat['id_kategori'] ?>"
                                    <?= (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == $kat['id_kategori']) ? 'selected' : '' ?>>
                                    <?= $kat['nama_kategori'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-dark w-80">Filter</button>
                    </div>
                </div>
            </form>


            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['penulis'] ?></td>
                            <td><?= $row['nama_kategori'] ?></td>
                            <td><?= $row['kode_rak'] ?></td>
                            <td><?= $row['stok'] ?></td>

                            <td>
                                <a href="edit.php?id=<?= $row['id_buku'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="proses.php?hapus=<?= $row['id_buku'] ?>"
                                    onclick="return confirm('Hapus buku ini?')"
                                    class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
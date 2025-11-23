<?php include "../sidebar.php"; ?>
<?php include "../koneksi.php"; ?>

<div class="container mt-4">
    <h3>Manajemen Buku</h3>
    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Buku</a>

    <table class="table table-bordered table-striped">
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

<?php
$query = "
    SELECT buku.*, kategori.nama_kategori, rak.kode_rak
    FROM buku
    LEFT JOIN kategori ON kategori.id_kategori = buku.id_kategori
    LEFT JOIN rak ON rak.id_rak = buku.id_rak
";
$sql = mysqli_query($koneksi, $query);

while ($data = mysqli_fetch_assoc($sql)) {
?>
        <tr>
            <td><?= $data['id_buku'] ?></td>
            <td><?= $data['judul'] ?></td>
            <td><?= $data['penulis'] ?></td>
            <td><?= $data['nama_kategori'] ?></td>
            <td><?= $data['kode_rak'] ?></td>
            <td><?= $data['stok'] ?></td>
            <td>
                <a href="edit.php?id=<?= $data['id_buku'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="proses.php?aksi=hapus&id=<?= $data['id_buku'] ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
            </td>
        </tr>
<?php } ?>

        </tbody>
    </table>
</div>

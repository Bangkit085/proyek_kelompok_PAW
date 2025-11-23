<?php include "../sidebar.php"; ?>
<?php include "../koneksi.php"; ?>

<?php
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'"));
?>

<div class="container mt-4">
    <h3>Edit Buku</h3>

    <form action="proses.php?aksi=edit" method="POST">
        <input type="hidden" name="id_buku" value="<?= $data['id_buku'] ?>">

        <div class="mb-3">
            <label>Judul Buku</label>
            <input type="text" name="judul" class="form-control" value="<?= $data['judul'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Penulis</label>
            <input type="text" name="penulis" class="form-control" value="<?= $data['penulis'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="id_kategori" class="form-select">
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM kategori");
                while ($k = mysqli_fetch_assoc($q)) {
                    $sel = ($k['id_kategori'] == $data['id_kategori']) ? "selected" : "";
                    echo "<option value='$k[id_kategori]' $sel>$k[nama_kategori]</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Lokasi Rak</label>
            <select name="id_rak" class="form-select">
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM rak");
                while ($r = mysqli_fetch_assoc($q)) {
                    $sel = ($r['id_rak'] == $data['id_rak']) ? "selected" : "";
                    echo "<option value='$r[id_rak]' $sel>$r[kode_rak] - Lantai $r[lantai]</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control" value="<?= $data['isbn'] ?>">
        </div>

        <div class="mb-3">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" class="form-control" value="<?= $data['tahun_terbit'] ?>">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="list_buku.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

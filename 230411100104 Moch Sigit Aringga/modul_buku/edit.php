<?php
include "../sidebar.php";
include "../koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id'"));

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
$rak      = mysqli_query($conn, "SELECT * FROM rak");
?>

<div class="content">
    <h3 class="mb-3">✏ Edit Buku</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="proses.php" method="post">
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
                    <select name="id_kategori" class="form-control" required>
                        <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                            <option value="<?= $k['id_kategori'] ?>" 
                                <?= ($data['id_kategori'] == $k['id_kategori']) ? 'selected' : '' ?>>
                                <?= $k['nama_kategori'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Rak</label>
                    <select name="id_rak" class="form-control" required>
                        <?php while ($r = mysqli_fetch_assoc($rak)) { ?>
                            <option value="<?= $r['id_rak'] ?>" 
                                <?= ($data['id_rak'] == $r['id_rak']) ? 'selected' : '' ?>>
                                <?= $r['kode_rak'] ?> (Lantai <?= $r['lantai'] ?>)
                            </option>
                        <?php } ?>
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

                <button type="submit" name="edit" class="btn btn-warning">Update</button>
                <a href="list_buku.php" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>
</div>

<?php
include "../sidebar.php";
include "../koneksi.php";

// ambil kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
// ambil rak
$rak = mysqli_query($conn, "SELECT * FROM rak");
?>

<div class="content">
    <h3 class="mb-3">➕ Tambah Buku</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="proses.php" method="post">

                <div class="mb-3">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Penulis</label>
                    <input type="text" name="penulis" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                            <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Lokasi Rak</label>
                    <select name="id_rak" class="form-control" required>
                        <option value="">-- Pilih Rak --</option>
                        <?php while ($r = mysqli_fetch_assoc($rak)) { ?>
                            <option value="<?= $r['id_rak'] ?>"><?= $r['kode_rak'] ?> (Lantai <?= $r['lantai'] ?>)</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Stok Buku</label>
                    <input type="number" name="stok" min="1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-control" maxlength="4">
                </div>

                <button type="submit" name="tambah" class="btn btn-dark">Simpan</button>
                <a href="list_buku.php" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>
</div>

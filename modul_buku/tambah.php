<?php include "../sidebar.php"; ?>
<?php include "../koneksi.php"; ?>

<div class="container mt-4">
    <h3>Tambah Buku</h3>

    <form action="proses.php?aksi=tambah" method="POST">

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
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM kategori");
                while ($k = mysqli_fetch_assoc($q)) {
                    echo "<option value='$k[id_kategori]'>$k[nama_kategori]</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Lokasi Rak</label>
            <select name="id_rak" class="form-select" required>
                <option value="">-- Pilih Rak --</option>
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM rak");
                while ($r = mysqli_fetch_assoc($q)) {
                    echo "<option value='$r[id_rak]'>$r[kode_rak] - Lantai $r[lantai]</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="list_buku.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

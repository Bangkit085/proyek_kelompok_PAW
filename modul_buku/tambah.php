<?php
// session_start();
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }

include '../koneksi.php';

// Ambil data Kategori & Rak untuk Dropdown (Versi PDO)
$data_kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$data_rak      = $conn->query("SELECT * FROM rak ORDER BY kode_rak ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .card-form { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; color: #555; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 15px; border: 1px solid #e0e0e0; }
        .form-control:focus, .form-select:focus { border-color: #e3f2fd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); }
        .btn-simpan { background-color: #0d6efd; border: none; padding: 10px 30px; border-radius: 50px; font-weight: 600; }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Tambah Buku Baru</h4>
            <a href="list_buku.php" class="btn btn-light text-secondary border shadow-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card card-form p-4 bg-white">
            <form action="proses.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Pemrograman Web Lanjut" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" name="penulis" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" placeholder="2024" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" name="isbn" class="form-control" placeholder="xxx-xxx-xxx" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Buku</label>
                            <input type="number" name="stok" class="form-control" value="1" min="0" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="">- Pilih Kategori -</option>
                                <?php 
                                // Ganti mysqli_fetch_array ke PDO fetch()
                                while($k = $data_kategori->fetch()){ ?>
                                    <option value="<?php echo $k['id_kategori']; ?>"><?php echo $k['nama_kategori']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Rak</label>
                            <select name="id_rak" class="form-select" required>
                                <option value="">- Pilih Rak -</option>
                                <?php 
                                // Ganti mysqli_fetch_array ke PDO fetch()
                                while($r = $data_rak->fetch()){ ?>
                                    <option value="<?php echo $r['id_rak']; ?>">
                                        <?php echo $r['kode_rak'] . " (Lantai " . $r['lantai'] . ")"; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Cover</label>
                            <input type="file" name="cover" class="form-control" accept=".jpg, .jpeg, .png">
                            <div class="form-text text-muted small">Format: JPG/PNG. Maks 2MB.</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-simpan shadow-sm">
                        <i class="bi bi-save me-2"></i> Simpan Data
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
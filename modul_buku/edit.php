<?php
// session_start();
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }
include '../koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// 1. Ambil Data Buku (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM buku WHERE id_buku = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Ambil data dropdown (Query biasa lewat PDO)
$data_kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$data_rak      = $conn->query("SELECT * FROM rak ORDER BY kode_rak ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .card-form { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Edit Data Buku</h4>
            <a href="list_buku.php" class="btn btn-light border shadow-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card card-form p-4 bg-white">
            <form action="proses.php?aksi=edit" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_buku" value="<?php echo $data['id_buku']; ?>">
                <input type="hidden" name="cover_lama" value="<?php echo $data['cover']; ?>">

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" value="<?php echo $data['judul']; ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" name="penulis" class="form-control" value="<?php echo $data['penulis']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" value="<?php echo isset($data['penerbit']) ? $data['penerbit'] : ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" value="<?php echo $data['tahun_terbit']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" name="isbn" class="form-control" value="<?php echo $data['isbn']; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Buku</label>
                            <input type="number" name="stok" class="form-control" value="<?php echo $data['stok']; ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <?php 
                                // PERUBAHAN PDO: Ganti mysqli_fetch_array jadi $stmt->fetch()
                                while($k = $data_kategori->fetch()){ ?>
                                    <option value="<?php echo $k['id_kategori']; ?>" <?php if($data['id_kategori'] == $k['id_kategori']) echo 'selected'; ?>>
                                        <?php echo $k['nama_kategori']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Rak</label>
                            <select name="id_rak" class="form-select" required>
                                <?php 
                                // PERUBAHAN PDO: Ganti mysqli_fetch_array jadi $stmt->fetch()
                                while($r = $data_rak->fetch()){ ?>
                                    <option value="<?php echo $r['id_rak']; ?>" <?php if($data['id_rak'] == $r['id_rak']) echo 'selected'; ?>>
                                        <?php echo $r['kode_rak'] . " (Lantai " . $r['lantai'] . ")"; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cover Saat Ini</label><br>
                            <?php if($data['cover'] != ""){ ?>
                                <img src="../../uploads/cover_buku/<?php echo $data['cover']; ?>" width="100" class="rounded mb-2">
                            <?php } else { echo "<span class='text-muted small'>Tidak ada cover</span><br>"; } ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ganti Cover (Opsional)</label>
                            <input type="file" name="cover" class="form-control" accept=".jpg, .jpeg, .png">
                            <div class="form-text text-muted small">Biarkan kosong jika tidak ingin mengganti.</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Update Data</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// session_start();
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }

include '../koneksi.php';

// Ambil data Buku untuk Dropdown (Versi PDO)
$query_buku = $conn->query("SELECT id_buku, judul, penulis FROM buku ORDER BY judul ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload E-Book - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .card-form { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; color: #555; }
        .upload-area { border: 2px dashed #e0e0e0; border-radius: 10px; padding: 30px; text-align: center; background: #fafafa; }
        .upload-area:hover { background: #f0f8ff; border-color: #b3e5fc; }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Upload File Digital</h4>
            <a href="list_ebook.php" class="btn btn-light border shadow-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-form p-4 bg-white">
                    
                    <form action="proses.php?aksi=upload" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4">
                            <label class="form-label">Pilih Buku Induk</label>
                            <select name="id_buku" class="form-select p-3" required>
                                <option value="">-- Cari Judul Buku --</option>
                                <?php 
                                // Ganti mysqli_fetch_array ke PDO fetch()
                                while($b = $query_buku->fetch()){ ?>
                                    <option value="<?php echo $b['id_buku']; ?>">
                                        <?php echo $b['judul'] . " - " . $b['penulis']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="form-text text-muted">File e-book akan terhubung dengan data buku ini.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">File E-Book (PDF)</label>
                            <div class="upload-area">
                                <i class="bi bi-file-earmark-pdf display-4 text-danger mb-3"></i>
                                <br>
                                <input type="file" name="file_ebook" class="form-control" accept=".pdf" required>
                                <small class="text-muted mt-2 d-block">Hanya format PDF. Maksimal 10MB.</small>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold">
                                <i class="bi bi-cloud-upload me-2"></i> Mulai Upload
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
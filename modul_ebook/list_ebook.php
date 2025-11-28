<?php
// session_start();

// // Cek Login
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }

include '../koneksi.php';

// --- LOGIKA PAGINATION & PENCARIAN ---
$batas = 5; 
$halaman = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

// --- PERSIAPAN FILTER (PDO Prepared Statement) ---
$where = "";
$url_cari = "";
$params = [];

if(isset($_GET['cari'])){
    $cari = $_GET['cari'];
    // Gunakan 2 placeholder berbeda (:cari1 dan :cari2) agar aman dari error PDO
    $where = "WHERE buku.judul LIKE :cari1 OR kategori.nama_kategori LIKE :cari2";
    $url_cari = "&cari=".$cari;
    
    // Simpan nilai parameter
    $params[':cari1'] = "%" . $cari . "%";
    $params[':cari2'] = "%" . $cari . "%";
}

// 1. Hitung Total Data (PDO)
$query_jumlah = "SELECT COUNT(*) FROM ebook 
                 JOIN buku ON ebook.id_buku = buku.id_buku 
                 JOIN kategori ON buku.id_kategori = kategori.id_kategori
                 $where";
$stmt_jumlah = $conn->prepare($query_jumlah);
$stmt_jumlah->execute($params);
$jumlah_data = $stmt_jumlah->fetchColumn();
$total_halaman = ceil($jumlah_data / $batas);

// 2. Query Data Utama (PDO)
$query = "SELECT ebook.*, buku.judul, buku.cover, buku.penulis, kategori.nama_kategori 
          FROM ebook 
          JOIN buku ON ebook.id_buku = buku.id_buku 
          JOIN kategori ON buku.id_kategori = kategori.id_kategori
          $where 
          ORDER BY ebook.id_ebook DESC 
          LIMIT :offset, :limit";

$stmt = $conn->prepare($query);

// Bind Parameter Pencarian
foreach($params as $key => $val){
    $stmt->bindValue($key, $val);
}
// Bind Limit (Wajib Integer)
$stmt->bindValue(':offset', (int)$halaman_awal, PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$batas, PDO::PARAM_INT);

$stmt->execute();
$nomor = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi E-Book - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        /* Table Style Consistent */
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            border-collapse: separate; 
            border-spacing: 0;
        }
        .table-custom thead { background-color: #e3f2fd; color: #4a6fa5; }
        .table-custom th { padding: 15px; font-weight: 600; border: none; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; color: #555; }
        .table-custom tr:last-child td { border-bottom: none; }
        .table-custom tr:hover { background-color: #f8fbff; }

        /* Images */
        .img-cover-mini {
            width: 45px;
            height: 65px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Badge File Type */
        .badge-pdf { 
            background-color: #ffebee; 
            color: #c62828; 
            border: 1px solid #ffcdd2;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-soft { border: none; font-weight: 500; transition: 0.2s; }
        .btn-soft:hover { transform: translateY(-2px); }
        
        /* Pagination */
        .page-link { border: none; color: #6c757d; margin: 0 3px; border-radius: 5px !important; }
        .page-item.active .page-link { background-color: #e3f2fd; color: #0d6efd; font-weight: bold; }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px; transition: all 0.3s;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-secondary mb-0">Manajemen E-Book</h2>
                <p class="text-muted small">Kelola file digital buku (PDF/EPUB)</p>
            </div>
            <a href="upload.php" class="btn btn-primary px-4 rounded-pill shadow-sm">
                <i class="bi bi-cloud-arrow-up me-2"></i> Upload E-Book
            </a>
        </div>

        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "sukses_upload"){
                echo '<div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>File E-Book berhasil diunggah!</div>';
            } elseif($_GET['pesan'] == "sukses_hapus"){
                echo '<div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-trash me-2"></i>File E-Book berhasil dihapus.</div>';
            } elseif($_GET['pesan'] == "gagal_ext"){
                echo '<div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-exclamation-circle me-2"></i>Format file tidak diizinkan! Harus PDF.</div>';
            }
        }
        ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="text" name="cari" class="form-control border-0 bg-light" placeholder="Cari judul buku atau kategori..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                    <button type="submit" class="btn btn-primary btn-soft px-4" style="background-color: #e3f2fd; color: #0d6efd;"><i class="bi bi-search"></i> Cari</button>
                    <?php if(isset($_GET['cari'])): ?>
                        <a href="list_ebook.php" class="btn btn-light text-danger"><i class="bi bi-x-lg"></i> Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Cover</th>
                        <th width="35%">Info Buku</th>
                        <th width="15%">Format</th>
                        <th width="20%">File</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Ganti mysqli_num_rows ke rowCount()
                    if($stmt->rowCount() > 0){
                        // Ganti mysqli_fetch_assoc ke fetch()
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                            // Cek gambar cover buku induk
                            $gambar = "../../uploads/cover_buku/" . $row['cover'];
                            if(!file_exists($gambar) || empty($row['cover'])){
                                $gambar = "https://via.placeholder.com/150x200?text=No+Cover";
                            }
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $nomor++; ?></td>
                        <td>
                            <img src="<?php echo $gambar; ?>" class="img-cover-mini" alt="Cover">
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo $row['judul']; ?></div>
                            <small class="text-muted d-block">Kategori: <?php echo $row['nama_kategori']; ?></small>
                        </td>
                        <td>
                            <span class="badge-pdf">
                                <i class="bi bi-file-earmark-pdf me-1"></i> <?php echo strtoupper($row['file_format']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../../uploads/file_ebook/<?php echo $row['file_path']; ?>" target="_blank" class="btn btn-sm btn-light text-primary border shadow-sm">
                                <i class="bi bi-eye me-1"></i> Lihat File
                            </a>
                        </td>
                        <td>
                            <a href="proses.php?aksi=hapus&id=<?php echo $row['id_ebook']; ?>&file=<?php echo $row['file_path']; ?>" class="btn btn-sm btn-light text-danger shadow-sm" onclick="return confirm('Hapus e-book ini? File akan hilang permanen.')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data E-Book yang diupload.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item <?php if($halaman <= 1) echo 'disabled'; ?>">
                    <a class="page-link border-0" <?php if($halaman > 1) echo "href='?hal=$previous$url_cari'"; ?>>Previous</a>
                </li>
                <?php for($x=1; $x<=$total_halaman; $x++){ ?> 
                <li class="page-item <?php if($halaman == $x) echo 'active'; ?>">
                    <a class="page-link border-0 rounded" href="?hal=<?php echo $x . $url_cari; ?>"><?php echo $x; ?></a>
                </li>
                <?php } ?>
                <li class="page-item <?php if($halaman >= $total_halaman) echo 'disabled'; ?>">
                    <a class="page-link border-0" <?php if($halaman < $total_halaman) echo "href='?hal=$next$url_cari'"; ?>>Next</a>
                </li>
            </ul>
        </nav>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
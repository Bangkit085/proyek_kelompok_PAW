<?php
// session_start();

// // Cek Login
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }

include '../koneksi.php';

// --- LOGIKA PAGINATION & PENCARIAN ---
$batas = 10; 
$halaman = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

// --- PERSIAPAN FILTER (PDO Prepared Statement) ---
$where = "";
$url_cari = "";
$params = [];

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    // Gunakan placeholder berbeda untuk keamanan maksimal PDO
    $where = "WHERE pengguna.nama LIKE :cari1 OR buku.judul LIKE :cari2";
    $url_cari = "&cari=" . $cari;
    
    // Simpan parameter
    $like_str = "%" . $cari . "%";
    $params[':cari1'] = $like_str;
    $params[':cari2'] = $like_str;
}

// 1. Hitung Total Data (PDO)
$query_jumlah = "SELECT COUNT(*) FROM peminjaman 
                 JOIN pengguna ON peminjaman.id_pengguna = pengguna.id_pengguna
                 JOIN buku ON peminjaman.id_buku = buku.id_buku
                 $where";
                 
$stmt_jumlah = $conn->prepare($query_jumlah);
$stmt_jumlah->execute($params);
$jumlah_data = $stmt_jumlah->fetchColumn();
$total_halaman = ceil($jumlah_data / $batas);

// 2. Query Data Utama (PDO)
$query = "SELECT peminjaman.*, pengguna.nama, buku.judul, buku.cover 
          FROM peminjaman 
          JOIN pengguna ON peminjaman.id_pengguna = pengguna.id_pengguna
          JOIN buku ON peminjaman.id_buku = buku.id_buku
          $where 
          ORDER BY peminjaman.id_peminjaman DESC 
          LIMIT :offset, :limit";

$stmt = $conn->prepare($query);

// Bind Parameter Pencarian
foreach($params as $key => $val){
    $stmt->bindValue($key, $val);
}

// Bind Parameter Limit (Wajib Integer)
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
    <title>Transaksi Peminjaman - LibrarySys</title>
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

        /* Badge Status Custom */
        .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; }
        .status-wait { background-color: #fff3cd; color: #856404; } /* Kuning: Menunggu */
        .status-active { background-color: #cff4fc; color: #055160; } /* Biru: Dipinjam */
        .status-done { background-color: #d1e7dd; color: #0f5132; } /* Hijau: Kembali */
        .status-reject { background-color: #f8d7da; color: #842029; } /* Merah: Ditolak */

        .btn-soft { border: none; font-weight: 500; transition: 0.2s; }
        .btn-soft:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px; transition: all 0.3s;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-secondary mb-0">Transaksi Peminjaman</h2>
                <p class="text-muted small">Validasi peminjaman dan proses pengembalian buku</p>
            </div>
            <a href="list_pinjam.php" class="btn btn-light shadow-sm rounded-circle">
                <i class="bi bi-arrow-clockwise text-primary"></i>
            </a>
        </div>

        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "sukses_validasi"){
                echo '<div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>Peminjaman disetujui! Stok buku berkurang.</div>';
            } elseif($_GET['pesan'] == "sukses_tolak"){
                echo '<div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-x-circle me-2"></i>Peminjaman ditolak. Stok dikembalikan.</div>';
            } elseif($_GET['pesan'] == "sukses_kembali"){
                echo '<div class="alert alert-primary border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-box-seam me-2"></i>Buku berhasil dikembalikan. Transaksi selesai.</div>';
            }
        }
        ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="text" name="cari" class="form-control border-0 bg-light" placeholder="Cari nama peminjam atau judul buku..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                    <button type="submit" class="btn btn-primary btn-soft px-4" style="background-color: #e3f2fd; color: #0d6efd;"><i class="bi bi-search"></i> Cari</button>
                    <?php if(isset($_GET['cari'])): ?>
                        <a href="list_pinjam.php" class="btn btn-light text-danger"><i class="bi bi-x-lg"></i> Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Peminjam</th>
                        <th width="25%">Buku</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Ganti mysqli_num_rows ke rowCount()
                    if($stmt->rowCount() > 0){
                        // Ganti mysqli_fetch_assoc ke fetch()
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $nomor++; ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo $row['nama']; ?></div>
                            <small class="text-muted" style="font-size: 0.75rem;">ID User: <?php echo $row['id_pengguna']; ?></small>
                        </td>
                        <td>
                            <div class="fw-bold text-secondary"><?php echo $row['judul']; ?></div>
                        </td>
                        <td>
                            <div class="d-flex flex-column small">
                                <span>Pinjam: <span class="fw-bold"><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></span></span>
                                <span class="text-danger">Tempo: <?php echo date('d/m/Y', strtotime($row['tanggal_jatuh_tempo'])); ?></span>
                            </div>
                        </td>
                        <td>
                            <?php 
                            if($row['status'] == 'menunggu_validasi'){
                                echo '<span class="status-badge status-wait"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>';
                            } elseif($row['status'] == 'dipinjam'){
                                echo '<span class="status-badge status-active"><i class="bi bi-check-circle me-1"></i> Dipinjam</span>';
                            } elseif($row['status'] == 'dikembalikan'){
                                echo '<span class="status-badge status-done"><i class="bi bi-check-all me-1"></i> Selesai</span>';
                            } elseif($row['status'] == 'ditolak'){
                                echo '<span class="status-badge status-reject"><i class="bi bi-x-circle me-1"></i> Ditolak</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 'menunggu_validasi'): ?>
                                <div class="btn-group shadow-sm">
                                    <a href="proses.php?aksi=setujui&id=<?php echo $row['id_peminjaman']; ?>&id_buku=<?php echo $row['id_buku']; ?>" class="btn btn-sm btn-success btn-soft" title="Setujui" onclick="return confirm('Setujui peminjaman ini?')">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    <a href="proses.php?aksi=tolak&id=<?php echo $row['id_peminjaman']; ?>" class="btn btn-sm btn-danger btn-soft" title="Tolak" onclick="return confirm('Tolak peminjaman ini?')">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                </div>
                            
                            <?php elseif($row['status'] == 'dipinjam'): ?>
                                <a href="kembalikan.php?id=<?php echo $row['id_peminjaman']; ?>" class="btn btn-sm btn-primary btn-soft shadow-sm px-3">
                                    <i class="bi bi-box-arrow-in-left me-2"></i> Kembalikan
                                </a>

                            <?php else: ?>
                                <span class="text-muted small fst-italic">Tidak ada aksi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data transaksi.</td></tr>';
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
<?php
// session_start();
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
$params = []; // Array untuk menyimpan data binding

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];

    // PERBAIKAN DISINI: Gunakan nama parameter beda (:cari1, :cari2, :cari3)
    // Karena di PDO native, kita tidak bisa pakai 1 nama parameter berulang kali
    $where = "WHERE buku.judul LIKE :cari1 OR buku.penulis LIKE :cari2 OR kategori.nama_kategori LIKE :cari3";
    $url_cari = "&cari=" . $cari;

    // Isi array parameter untuk 3 placeholder tadi
    $like_str = "%" . $cari . "%";
    $params[':cari1'] = $like_str;
    $params[':cari2'] = $like_str;
    $params[':cari3'] = $like_str;
}

// 1. Hitung Total Data (PDO)
$query_jumlah = "SELECT COUNT(*) FROM buku 
                 LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori 
                 $where";
$stmt_jumlah = $conn->prepare($query_jumlah);
$stmt_jumlah->execute($params);
$jumlah_data = $stmt_jumlah->fetchColumn(); // Ambil angka total
$total_halaman = ceil($jumlah_data / $batas);

// 2. Query Data Utama (PDO)
$sql = "SELECT buku.*, kategori.nama_kategori, rak.kode_rak, rak.lantai 
          FROM buku 
          LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori 
          LEFT JOIN rak ON buku.id_rak = rak.id_rak 
          $where 
          ORDER BY buku.id_buku DESC 
          LIMIT :offset, :limit";

$stmt = $conn->prepare($sql);

// Bind Parameter Pencarian (Looping params yang tadi dibuat)
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}

// Bind Parameter LIMIT (Wajib Integer)
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
    <title>Data Buku - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead {
            background-color: #e3f2fd;
            color: #4a6fa5;
        }

        .table-custom th {
            padding: 15px;
            font-weight: 600;
            border: none;
        }

        .table-custom td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom tr:hover {
            background-color: #f8fbff;
        }

        .img-cover-mini {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .badge-kategori {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .btn-primary-soft {
            background-color: #e3f2fd;
            color: #0d6efd;
            border: none;
            font-weight: 600;
        }

        .btn-primary-soft:hover {
            background-color: #bbdefb;
            color: #0b5ed7;
        }

        .page-link {
            border: none;
            color: #6c757d;
            margin: 0 3px;
            border-radius: 5px !important;
        }

        .page-item.active .page-link {
            background-color: #e3f2fd;
            color: #0d6efd;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php $path = "../";
    include '../sidebar.php'; ?>

    <div class="p-4" style="margin-left: 280px; transition: all 0.3s;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-secondary mb-0">Kelola Buku</h2>
                <p class="text-muted small">Manajemen data koleksi buku fisik perpustakaan</p>
            </div>
            <a href="tambah.php" class="btn btn-primary px-4 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Buku
            </a>
        </div>

        <?php
        if (isset($_GET['pesan'])) {
            if ($_GET['pesan'] == "sukses_tambah") {
                echo '<div id="alertPesan" class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-check-circle me-2"></i>Data buku berhasil ditambahkan!</div>';
            } elseif ($_GET['pesan'] == "sukses_hapus") {
                echo '<div id="alertPesan" class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-trash me-2"></i>Data buku berhasil dihapus.</div>';
            } elseif ($_GET['pesan'] == "sukses_edit") {
                echo '<div id="alertPesan" class="alert alert-primary border-0 shadow-sm rounded-3 mb-4"><i class="bi bi-pencil me-2"></i>Data buku berhasil diperbarui.</div>';
            }
        }
        ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="text" name="cari" class="form-control border-0 bg-light" placeholder="Cari judul, penulis, atau kategori..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                    <button type="submit" class="btn btn-primary-soft px-4"><i class="bi bi-search"></i> Cari</button>
                    <?php if (isset($_GET['cari'])): ?>
                        <a href="list_buku.php" class="btn btn-light text-danger"><i class="bi bi-x-lg"></i> Reset</a>
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
                        <th width="30%">Info Buku</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Lokasi Rak</th>
                        <th width="10%">Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Cek RowCount PDO
                    if ($stmt->rowCount() > 0) {
                        // Looping Data Fetch PDO
                        while ($row = $stmt->fetch()) {

                            $path_cover = "../../uploads/cover_buku/" . $row['cover'];

                            if (!empty($row['cover']) && file_exists($path_cover)) {
                                $gambar_final = $path_cover;
                            } else {
                                $gambar_final = "https://via.placeholder.com/150x200?text=No+Cover";
                            }
                    ?>
                            <tr>
                                <td class="text-center text-muted"><?php echo $nomor++; ?></td>

                                <td>
                                    <img src="<?php echo $gambar_final; ?>" class="img-cover-mini" alt="Cover">
                                </td>

                                <td>
                                    <div class="fw-bold text-dark"><?php echo $row['judul']; ?></div>
                                    <small class="text-muted d-block">Penulis: <?php echo $row['penulis']; ?></small>
                                </td>
                                <td>
                                    <span class="badge-kategori">
                                        <?php echo $row['nama_kategori'] ? $row['nama_kategori'] : '-'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-bookshelf me-2 text-secondary fs-5"></i>
                                        <span class="fw-bold text-dark"><?php echo $row['kode_rak']; ?></span>
                                    </div>
                                    <small class="text-muted ps-4" style="font-size: 0.75rem;">Lantai <?php echo $row['lantai']; ?></small>
                                </td>
                                <td>
                                    <?php if ($row['stok'] > 0): ?>
                                        <span class="fw-bold text-success"><?php echo $row['stok']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-2">Habis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $row['id_buku']; ?>" class="btn btn-sm btn-light text-primary shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="proses.php?aksi=hapus&id=<?php echo $row['id_buku']; ?>&cover=<?php echo $row['cover']; ?>" class="btn btn-sm btn-light text-danger shadow-sm" onclick="return confirm('Hapus buku ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data buku.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item <?php if ($halaman <= 1) echo 'disabled'; ?>">
                    <a class="page-link" <?php if ($halaman > 1) echo "href='?hal=$previous$url_cari'"; ?>>Previous</a>
                </li>

                <?php
                for ($x = 1; $x <= $total_halaman; $x++) {
                ?>
                    <li class="page-item <?php if ($halaman == $x) echo 'active'; ?>">
                        <a class="page-link" href="?hal=<?php echo $x . $url_cari; ?>"><?php echo $x; ?></a>
                    </li>
                <?php
                }
                ?>

                <li class="page-item <?php if ($halaman >= $total_halaman) echo 'disabled'; ?>">
                    <a class="page-link" <?php if ($halaman < $total_halaman) echo "href='?hal=$next$url_cari'"; ?>>Next</a>
                </li>
            </ul>
        </nav>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alert setelah 2 detik
        setTimeout(() => {
            const alert = document.getElementById("alertPesan");
            if (alert) {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>
</body>

</html>
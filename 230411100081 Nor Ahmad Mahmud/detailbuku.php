<?php
require "../koneksi.php";
session_start();

// 1. Cek Login
if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

// 2. Cek Parameter ID
if (!isset($_GET['id'])) {
    echo "<script>alert('ID Buku tidak ditemukan!');window.location='../bangkit/katalog.php';</script>";
    exit;
}

$id_buku = $_GET['id'];

// 3. Query Data Buku
$query = "SELECT * FROM buku WHERE id_buku = '$id_buku'";
$result = mysqli_query($koneksi, $query);
$buku = mysqli_fetch_assoc($result);

if (!$buku) {
    echo "<script>alert('Data buku tidak ditemukan!');window.location='../bangkit/katalog.php';</script>";
    exit;
}

// --- LOGIKA NAMA KATEGORI & RAK ---
$nama_kategori = $buku['id_kategori']; 
$nama_rak      = $buku['id_rak']; // Default tampilkan ID dulu
$lantai_rak    = "";             // Variabel untuk menampung info lantai

// Cek Kategori
$cek_kat = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = '".$buku['id_kategori']."'");
if ($cek_kat && mysqli_num_rows($cek_kat) > 0) {
    $d_kat = mysqli_fetch_assoc($cek_kat);
    if(isset($d_kat['nama_kategori'])) $nama_kategori = $d_kat['nama_kategori'];
}

// --- PERBAIKAN LOGIKA RAK ---
// Menggunakan kolom 'kode_rak' dan 'lantai' sesuai tabel user
$cek_rak = mysqli_query($koneksi, "SELECT * FROM rak WHERE id_rak = '".$buku['id_rak']."'");
if ($cek_rak && mysqli_num_rows($cek_rak) > 0) {
    $d_rak = mysqli_fetch_assoc($cek_rak);
    
    // Ambil Kode Rak
    if(isset($d_rak['kode_rak'])) {
        $nama_rak = $d_rak['kode_rak'];
    }
    
    // Ambil Lantai
    if(isset($d_rak['lantai'])) {
        $lantai_rak = $d_rak['lantai'];
    }
}

$q_rating = mysqli_query($koneksi, "
    SELECT AVG(nilai_rating) AS avgRating, COUNT(*) AS totalRating
    FROM rating
    WHERE id_buku = '$id_buku'
");
$ratingData = mysqli_fetch_assoc($q_rating);
$avgRating = round($ratingData['avgRating'], 1); 
$totalRating = $ratingData['totalRating'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?= $buku['judul'] ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-content {
            margin-left: 280px; /* Lebar sidebar */
            padding: 40px 20px;
            padding-top: 100px;
            min-height: 100vh; /* Agar tinggi minimal setinggi layar */
        }
        .cover-detail {
            width: 100%;
            max-width: 280px; /* Dikecilkan sedikit agar proporsional */
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }
        .cover-detail:hover {
            transform: scale(1.03);
        }
        .detail-table th {
            width: 30%;
            color: #495057;
            font-weight: 600;
            vertical-align: middle;
        }
        .detail-table td {
            vertical-align: middle;
        }
        
        /* Responsive Sidebar fix */
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }
        .star { color: #f5c518; } /* gold */
        .star.empty { color: #ccc; } /* abu abu */

    </style>
</head>

<body>
<?php include 'navbar.php'; ?>

<div class="main-content">
    
    <!-- Tambahkan Container agar Center -->
    <div class="container">
        
        <!-- Baris untuk tombol kembali agar sejajar dengan card -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <a href="../bangkit/katalog.php" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog
                </a>
            </div>
        </div>

        <!-- Baris Card Utama -->
        <div class="row justify-content-center">
            <div class="col-lg-10"> <!-- Membatasi lebar card maksimal 10 kolom -->
                
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="row">
                            
                            <!-- KOLOM KIRI: GAMBAR -->
                            <div class="col-lg-4 text-center mb-4 mb-lg-0 border-end-lg">
                                <?php 
                                    $gambarName = $buku['cover'];
                                    $path_folder = "uploads/";
                                    
                                    // Cek folder alternatif (misalnya ada di folder cover_buku)
                                    if (!file_exists($path_folder . $gambarName) && file_exists("../uploads/cover_buku/" . $gambarName)) {
                                        $path_folder = "../uploads/cover_buku/";
                                    } elseif (!file_exists($path_folder . $gambarName) && file_exists("../uploads/" . $gambarName)) {
                                        $path_folder = "../uploads/";
                                    }

                                    $imgSrc = !empty($gambarName) ? $path_folder . $gambarName : "https://via.placeholder.com/300x450?text=No+Cover";
                                ?>
                                <img src="<?= $imgSrc ?>" alt="<?= $buku['judul'] ?>" class="cover-detail mb-4">
                                <!-- ⭐⭐ Tampilkan rating bintang ⭐⭐ -->
                                    <div class="mt-3">
                                        <?php
                                            $fullStars = floor($avgRating);
                                            $halfStar = ($avgRating - $fullStars >= 0.5);
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        
                                            for ($i = 0; $i < $fullStars; $i++) echo "<span class='star'>&#9733;</span>";
                                            if ($halfStar) echo "<span class='star'>&#10032;</span>";
                                            for ($i = 0; $i < $emptyStars; $i++) echo "<span class='star empty'>&#9733;</span>";
                                        ?>
                                        

                        
                                    </div>
                                
                                <div class="d-flex justify-content-center px-3">
                                    <?php if ($buku['stok'] > 0): ?>
                                        <div class="alert alert-success w-100 py-2 border-success shadow-sm">
                                            <h6 class="mb-1 fw-bold"><i class="bi bi-check-circle-fill"></i> Tersedia</h6>
                                            <small>Sisa Stok: <?= $buku['stok'] ?></small>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-danger w-100 py-2 border-danger shadow-sm">
                                            <h6 class="mb-1 fw-bold"><i class="bi bi-x-circle-fill"></i> Habis</h6>
                                            <small>Dipinjam semua</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- KOLOM KANAN: INFORMASI -->
                            <div class="col-lg-8 ps-lg-5 d-flex flex-column justify-content-center">
                                
                                <h2 class="fw-bold text-dark mb-2"><?= $buku['judul'] ?></h2>
                                <p class="text-muted fs-5 mb-4">
                                    <i class="bi bi-pen-fill me-2 text-primary"></i><?= $buku['penulis'] ?>
                                </p>
                                
                                <div class="card bg-light border-0 mb-4 rounded-3">
                                    <div class="card-body p-3">
                                        <table class="table table-borderless table-sm mb-0 detail-table">
                                            <tr>
                                                <th>Kategori</th>
                                                <td>: <?= $nama_kategori ?></span></td>
                                            </tr>
                                            <tr>
                                                <th>Penerbit</th>
                                                <td>: <?= $buku['penerbit'] ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tahun</th>
                                                <td>: <?= $buku['tahun_terbit'] ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <th>ISBN</th>
                                                <td>: <?= $buku['isbn'] ?? '-' ?></td>
                                            </tr>
                                            
                                            <!-- BAGIAN LOKASI RAK (KODE_RAK & LANTAI) -->
                                            <tr>
                                                <th>Lokasi</th>
                                                <td>
    
                                                        
                                                            <span class="d-block fw-bold text-dark">
                                                                 : <?= $nama_rak ?>
                                                            </span>
                                                            <?php if(!empty($lantai_rak)): ?>
                                                                : Lantai <?= $lantai_rak ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="d-block text-muted small">
                                                                    <i class="bi bi-info-circle me-1"></i> -
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>

                                <hr class="my-3 text-muted opacity-25">

                                <!-- TOMBOL AKSI -->
                                <h6 class="fw-bold text-secondary mb-3">Pilihan Peminjaman:</h6>
                                <div class="d-grid gap-2 d-md-flex">
                                    
                                    <!-- Tombol Pinjam -->
                                    <?php if ($buku['stok'] > 0): ?>
                                        <a href="../restu/peminjaman.php?id=<?= $buku['id_buku'] ?>" class="btn btn-primary btn-lg px-4 flex-grow-1 shadow-sm">
                                            Pinjam Buku
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-lg px-4 flex-grow-1" disabled>
                                            Stok Habis
                                        </button>
                                    <?php endif; ?>

                                    <!-- Tombol Ebook -->
                                    <?php 
                                        $ada_ebook = !empty($buku['file_ebook']) || !empty($buku['link_ebook']); 
                                        if ($ada_ebook): 
                                            $link = !empty($buku['file_ebook']) ? "../ebooks/".$buku['file_ebook'] : $buku['link_ebook']; 
                                    ?>
                                        <a href="<?= $link ?>" target="_blank" class="btn btn-danger btn-lg px-4 flex-grow-1 shadow-sm">
                                            <i class="bi bi-file-pdf"></i> Baca E-Book
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-lg px-4 flex-grow-1" disabled>
                                            E-Book N/A
                                        </button>
                                    <?php endif; ?>
                                    
                                </div>
                                <hr class="my-4">

                                <!-- List Ulasan -->
                                <h4>Review Pengguna</h4>
                                <?php
                                $query_ulasan = "SELECT rating.*, pengguna.nama 
                                                 FROM rating 
                                                 INNER JOIN pengguna ON rating.id_pengguna = pengguna.id_pengguna 
                                                 WHERE rating.id_buku = $id_buku 
                                                 ORDER BY rating.id_rating DESC";
                                $result_ulasan = mysqli_query($koneksi, $query_ulasan);
                            
                                while ($ulasan = mysqli_fetch_assoc($result_ulasan)) {
                                ?>
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <strong><?php echo $ulasan['nama']; ?></strong><br>
                                            <small class="text-muted"><?php echo $ulasan['dibuat_pada']; ?></small>
                                            <p class="mt-2"><?php echo $ulasan['ulasan']; ?></p>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div> <!-- End Card -->

            </div>
        </div>
    </div> <!-- End Container -->

</div>

</body>
</html>
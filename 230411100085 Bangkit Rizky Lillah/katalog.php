<?php
require "../koneksi.php";
session_start();
include 'function.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: login.php");
    exit;
}

$search = $_GET['search'] ?? '';

// Query Pencarian
$query = "SELECT buku.* FROM buku 
          WHERE judul LIKE '%$search%' OR penulis LIKE '%$search%'";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Katalog Buku User</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Segoe UI', 
            sans-serif; 
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #e3f2fd; /* Biru Pastel */
            color: #0d6efd; /* Biru Teks yang lebih gelap */
            font-weight: 600;
        }
        .main-content { 
            margin-left: 280px; 
            padding: 30px; 
            padding-top: 100px; 
        }
        .card { 
            transition: transform 0.2s; 
            border: none; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }
        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            
        }
        .card-img-wrapper {
            height: 280px;
            overflow: hidden;
            background: #e9ecef;
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 10px;            /* Tambahkan jarak */
            border-radius: 10px;      /* Cantik sedikit */
            position: relative;
        }
        .card-img-wrapper img { 
            width: auto; 
            height: 100%; 
            object-fit: contain;      /* agar pas di dalam area */
        }
        .card-title {
            font-size: 1.1rem; 
            font-weight: bold;
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            height: 3.2em;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary"><i class="bi bi-book-half me-2"></i>Katalog Buku</h2>
        <form action="" method="GET" class="d-flex" style="width: 300px;">
            <input class="form-control me-2" type="search" name="search" placeholder="Cari judul..."value="<?= htmlspecialchars($search) ?>"
                <?php if (isset($_GET['autosearch'])) echo 'autofocus'; ?>>
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col">
            <div class="card h-100">
                <div class="card-img-wrapper">
                    <?php 
                        // Cek gambar
                        $imgSrc = !empty($row['cover']) ? "../uploads/cover_buku/".$row['cover'] : "https://via.placeholder.com/150x220?text=No+Cover";
                    ?>
                    <img src="<?= $imgSrc ?>" alt="<?= $row['judul'] ?>">
                    
                    
                </div>

                <div class="card-body">
                    <h5 class="card-title text-dark"><?= $row['judul'] ?></h5>
                    <!-- Stok Badge -->
                    <?php if ($row['stok'] > 0): ?>
                        <span class="position-absolute top-0 end-0 badge bg-success m-2">Stok: <?= $row['stok'] ?></span>
                    <?php else: ?>
                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">Habis</span>
                    <?php endif; ?>
                    <p class="text-muted small mb-0"><i class="bi bi-person"></i> <?= $row['penulis'] ?></p>
                </div>

                <div class="card-footer bg-white border-0 pb-3">
                    <!-- PERBAIKAN UTAMA: Menggunakan id_buku -->
                    <a href="../nunnn/detailbuku.php?id=<?= $row['id_buku'] ?>" class="btn btn-outline-primary w-100 fw-bold">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
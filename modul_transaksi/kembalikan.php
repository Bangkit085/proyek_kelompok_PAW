<?php
// session_start();
// if($_SESSION['status'] != "login"){
//     header("location:../../login.php?pesan=belum_login");
//     exit();
// }

include '../koneksi.php';

$id = $_GET['id'];

// Ambil data peminjaman, buku, dan pengguna (Versi PDO Prepared Statement)
$sql = "SELECT peminjaman.*, buku.judul, buku.cover, pengguna.nama 
        FROM peminjaman 
        JOIN buku ON peminjaman.id_buku = buku.id_buku 
        JOIN pengguna ON peminjaman.id_pengguna = pengguna.id_pengguna 
        WHERE id_peminjaman = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// --- LOGIKA HITUNG DENDA OTOMATIS ---
$tgl_tempo = new DateTime($data['tanggal_jatuh_tempo']);
$tgl_skrg  = new DateTime(); // Waktu saat ini (Hari pengembalian)
$selisih   = $tgl_skrg->diff($tgl_tempo);

$telat_hari = 0;
$total_denda = 0;
$denda_per_hari = 1000; // Setting Nominal Denda Di Sini

// Cek apakah Tanggal Sekarang LEBIH BESAR dari Tanggal Tempo?
if($tgl_skrg > $tgl_tempo){
    $telat_hari = $selisih->days;
    $total_denda = $telat_hari * $denda_per_hari;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengembalian - LibrarySys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .img-buku { width: 120px; height: 170px; object-fit: cover; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .bg-denda { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .bg-aman { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    </style>
</head>
<body>

    <?php $path = "../"; include '../sidebar.php'; ?> 

    <div class="p-4" style="margin-left: 280px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-secondary">Proses Pengembalian Buku</h4>
            <a href="list_pinjam.php" class="btn btn-light border shadow-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Batal
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom p-4 text-center bg-white h-100">
                    <h6 class="text-muted mb-3">Buku yang dikembalikan</h6>
                    <?php 
                        $gambar = "../../uploads/cover_buku/" . $data['cover'];
                        if(!file_exists($gambar) || empty($data['cover'])) $gambar = "https://via.placeholder.com/150x200?text=No+Cover";
                    ?>
                    <img src="<?php echo $gambar; ?>" class="img-buku mx-auto mb-3">
                    <h5 class="fw-bold"><?php echo $data['judul']; ?></h5>
                    <p class="text-muted small">Peminjam: <strong class="text-dark"><?php echo $data['nama']; ?></strong></p>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom p-4 bg-white h-100">
                    <form action="proses.php?aksi=kembali" method="POST">
                        <input type="hidden" name="id_peminjaman" value="<?php echo $data['id_peminjaman']; ?>">
                        <input type="hidden" name="id_buku" value="<?php echo $data['id_buku']; ?>">
                        <input type="hidden" name="id_pengguna" value="<?php echo $data['id_pengguna']; ?>">

                        <h5 class="fw-bold mb-4 text-secondary">Rincian Waktu</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Pinjam</label>
                                <input type="text" class="form-control" value="<?php echo date('d F Y', strtotime($data['tanggal_pinjam'])); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Jatuh Tempo</label>
                                <input type="text" class="form-control" value="<?php echo date('d F Y', strtotime($data['tanggal_jatuh_tempo'])); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small text-muted">Tanggal Dikembalikan (Hari Ini)</label>
                            <input type="text" class="form-control fw-bold" value="<?php echo date('d F Y'); ?>" readonly>
                        </div>

                        <hr>

                        <?php if($telat_hari > 0): ?>
                            <div class="p-3 rounded mb-3 bg-denda text-center">
                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>TERLAMBAT <?php echo $telat_hari; ?> HARI</h6>
                                <div class="display-6 fw-bold">Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></div>
                                <small>Denda Rp 1.000 / hari</small>
                            </div>
                            <input type="hidden" name="denda" value="<?php echo $total_denda; ?>">
                        <?php else: ?>
                            <div class="p-3 rounded mb-3 bg-aman text-center">
                                <h6 class="fw-bold mb-0"><i class="bi bi-check-circle-fill me-2"></i>TEPAT WAKTU (Bebas Denda)</h6>
                            </div>
                            <input type="hidden" name="denda" value="0">
                        <?php endif; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm" onclick="return confirm('Konfirmasi pengembalian buku ini? Stok akan bertambah kembali.')">
                                <i class="bi bi-save me-2"></i> KONFIRMASI PENGEMBALIAN
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
<?php
session_start();

// 1. AKTIFKAN ERROR REPORTING (Biar ketahuan kalau ada salah query)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. INCLUDE KONEKSI
include '../koneksi.php';

// Validasi Koneksi
if (!isset($koneksi) || !$koneksi) {
    if (isset($conn)) $koneksi = $conn;
    else die("Error: Variabel koneksi tidak ditemukan.");
}

// --- 1. KONFIGURASI MIDTRANS ---
$serverKey = 'SB-Mid-server-42AftZn9Pkq73u7Sl_2UGwvY'; 
$clientKey = 'SB-Mid-client-xMnCo8VolYxMujkq';         
$authHeader = base64_encode($serverKey . ':');

// --- 2. CEK LOGIN ---
if (!isset($_SESSION['peran']) || $_SESSION['peran'] != "mahasiswa") {
    header("Location: ../bangkit/login.php");
    exit;
}

$id_pengguna_login = $_SESSION['id_pengguna'] ?? 0;
if ($id_pengguna_login == 0) {
    die("Silakan login terlebih dahulu.");
}

// --- 3. HANDLE CALLBACK SUKSES MIDTRANS ---
if (isset($_GET['midtrans_status']) && $_GET['midtrans_status'] == 'success') {
    echo "<script>alert('Pembayaran via Midtrans Sukses! Langkah Terakhir: Silakan Screenshot dan UPLOAD BUKTI tersebut di form bawah ini.'); window.location='pembayaran_denda.php';</script>";
}

// --- 4. PROSES PEMBAYARAN MANUAL (CASH & QRIS UPLOAD) ---
if (isset($_POST['bayar_manual'])) {
    $id_denda = $_POST['id_denda'];
    $metode   = $_POST['metode'];
    
    $berhasil = false;

    // A. LOGIKA CASH (Bayar di Tempat)
    if ($metode == "Cash") {
        // Insert hanya ke kolom yang ada: id_denda, metode_bayar, tgl_verifikasi
        // tgl_verifikasi diisi NOW() karena Cash dianggap langsung lunas
        $sql = "INSERT INTO pembayaran (id_denda, metode_bayar, tgl_verifikasi) VALUES (?, 'Cash', NOW())";
        $stmt = $koneksi->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id_denda);
            if($stmt->execute()){
                // Update status denda jadi Lunas
                $koneksi->query("UPDATE denda SET status_pembayaran='Lunas' WHERE id_denda='$id_denda'");
                $berhasil = true;
            } else {
                echo "Gagal Eksekusi Cash: " . $stmt->error;
            }
        } else {
            echo "Gagal Prepare Cash: " . $koneksi->error;
        }
    }
    // B. LOGIKA QRIS (Upload Bukti)
    else if ($metode == "QRIS") {
        $namaFile = $_FILES['bukti']['name'];
        $tmpName  = $_FILES['bukti']['tmp_name'];
        
        if (!empty($namaFile)) {
            $ext = pathinfo($namaFile, PATHINFO_EXTENSION);
            $namaBaru = time() . "_" . $id_denda . "." . $ext;
            $target = "../uploads/bukti/" . $namaBaru; 

            // Buat folder jika belum ada
            if (!file_exists("../uploads/bukti/")) { 
                mkdir("../uploads/bukti/", 0777, true); 
            }

            if (move_uploaded_file($tmpName, $target)) {
                // Insert ke kolom: id_denda, metode_bayar, bukti_pembayaran
                // tgl_verifikasi dibiarkan NULL (karena menunggu admin)
                $sql = "INSERT INTO pembayaran (id_denda, metode_bayar, bukti_pembayaran) VALUES (?, 'QRIS', ?)";
                $stmt = $koneksi->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("is", $id_denda, $namaBaru);
                    
                    if($stmt->execute()){
                        // Update status denda jadi Menunggu Verifikasi
                        $koneksi->query("UPDATE denda SET status_pembayaran='Menunggu Verifikasi' WHERE id_denda='$id_denda'");
                        $berhasil = true;
                    } else {
                        echo "Gagal Eksekusi QRIS: " . $stmt->error;
                    }
                } else {
                    echo "Gagal Prepare QRIS: " . $koneksi->error;
                }
            } else {
                echo "<script>alert('Gagal mengupload file gambar. Cek permission folder.');</script>";
            }
        } else {
            echo "<script>alert('Harap pilih file bukti pembayaran!');</script>";
        }
    }

    if ($berhasil) {
        echo "<script>alert('Pembayaran berhasil dikirim! Status sekarang Menunggu Verifikasi Admin.'); window.location='pembayaran_denda.php';</script>";
    }
}

// --- 5. FUNGSI TOKEN MIDTRANS ---
function getSnapToken($id_denda, $amount, $user_info, $authHeader) {
    if ($amount <= 0) return ['error' => true, 'message' => "Nominal 0"];

    $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    $order_id = "DENDA-" . $id_denda . "-" . time();

    $payload = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => (int)$amount
        ],
        'customer_details' => [
            'first_name' => !empty($user_info['nama']) ? $user_info['nama'] : "Mahasiswa",
            'email' => !empty($user_info['email']) ? $user_info['email'] : "mahasiswa@library.com"
        ],
        'item_details' => [[
            'id' => $id_denda,
            'price' => (int)$amount,
            'quantity' => 1,
            'name' => "Denda Keterlambatan"
        ]]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . $authHeader
    ]);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);
    
    if ($curlErr) return ['error' => true, 'message' => "Koneksi Gagal: " . $curlErr];
    if ($httpCode != 201 && $httpCode != 200) return ['error' => true, 'message' => "API Error ($httpCode)"];

    $data = json_decode($result, true);
    return ['error' => false, 'token' => $data['token'] ?? ''];
}

// --- AMBIL DATA ---
// Menggunakan d.id_pengguna sesuai kolom denda kamu
$q_denda = "SELECT d.id_denda, d.jumlah_denda, d.status_pembayaran, b.judul, p.tanggal_kembali
            FROM denda d
            JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE d.id_pengguna = '$id_pengguna_login' AND d.status_pembayaran != 'Lunas'";
$denda_list = mysqli_query($koneksi, $q_denda);

$q_riwayat = "SELECT d.id_denda, b.judul, p.tanggal_kembali, d.jumlah_denda, pay.metode_bayar, 
              d.status_pembayaran, pay.bukti_pembayaran, pay.tgl_verifikasi
              FROM denda d
              JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman
              JOIN buku b ON p.id_buku = b.id_buku
              LEFT JOIN pembayaran pay ON d.id_denda = pay.id_denda
              WHERE d.id_pengguna = '$id_pengguna_login' 
              AND (d.status_pembayaran = 'Lunas' OR d.status_pembayaran = 'Menunggu Verifikasi')
              ORDER BY d.id_denda DESC";
$riwayat_denda = mysqli_query($koneksi, $q_riwayat);

$q_user = mysqli_query($koneksi, "SELECT nama, email FROM pengguna WHERE id_pengguna='$id_pengguna_login'");
$user_info = mysqli_fetch_assoc($q_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Denda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo $clientKey; ?>"></script>

<style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .content-wrapper { padding-left: 300px; padding-right: 30px; padding-top: 100px; }
    .card-custom { border: none; border-radius: 15px; background-color: #ffffff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 25px; }
    .table thead { background-color: #e3f2fd; color: #0d6efd; }
    .btn-primary { background-color: #0d6efd; border: none; }
</style>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-auto px-0">
            <?php include '../bangkit/navbar.php'; ?>
        </div>
        
        <div class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-secondary"><i class="bi bi-wallet2"></i> Pembayaran Denda</h2>
                <div class="alert alert-warning border-0 shadow-sm py-2 px-3 mb-0 text-dark">
                    <i class="bi bi-info-circle-fill me-2"></i> <strong>Aturan:</strong> Rp 5.000 (Hari ke-1) + Rp 1.000 (Hari berikutnya)
                </div>
            </div>

            <!-- TABEL TAGIHAN -->
            <div class="card-custom">
                <?php if ($denda_list && $denda_list->num_rows > 0) { ?>
                <div class="table-responsive">  
                    <table class="table table-bordered align-middle text-center">
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Tanggal Kembali</th>
                                <th>Telat</th>
                                <th>Jumlah Denda</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $denda_list->fetch_assoc()) {
                            $jumlah_denda = $row['jumlah_denda'];
                            $hari_telat = ($jumlah_denda > 0) ? ceil($jumlah_denda / 5000) : 0; 

                            if ($jumlah_denda > 0) {
                                $tokenResult = getSnapToken($row['id_denda'], $jumlah_denda, $user_info, $authHeader);
                                $snapToken = $tokenResult['token'] ?? '';
                                $errorMsg = $tokenResult['error'] ? $tokenResult['message'] : '';
                            } else {
                                $snapToken = ''; $errorMsg = 'Nominal 0';
                            }
                        ?>
                            <tr>
                                <td><?= $row['judul'] ?></td>
                                <td><?= $row['tanggal_kembali'] ?></td>
                                <td><?= $hari_telat ?> hari</td>
                                <td><b>Rp <?= number_format($jumlah_denda) ?></b></td>
                                <td>
                                    <?php if($row['status_pembayaran'] == 'Menunggu Verifikasi'): ?>
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($jumlah_denda > 0 && $row['status_pembayaran'] != 'Menunggu Verifikasi'): ?>
                                    <button class="btn btn-sm btn-primary" onclick="document.getElementById('bayar<?= $row['id_denda'] ?>').style.display='table-row'">
                                        <i class="bi bi-cash-coin"></i> Bayar
                                    </button>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">Diproses / Lunas</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
    
                            <!-- FORM BAYAR (HIDDEN) -->
                            <tr id="bayar<?= $row['id_denda'] ?>" style="display:none;">
                                <td colspan="6" class="text-start bg-light p-4">
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id_denda" value="<?= $row['id_denda'] ?>">
                                        <!-- Nominal hanya untuk display logic, tidak diinsert -->
                                        <input type="hidden" name="jumlah" value="<?= $jumlah_denda ?>">
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="fw-bold mb-2">Pilih Metode Pembayaran:</label>
                                                <select name="metode" class="form-select" onchange="toggleMetode(this.value, <?= $row['id_denda'] ?>)" required>
                                                    <option value="Cash">Cash (Bayar di Tempat)</option>
                                                    <option value="QRIS">QRIS (Scan & Upload Bukti)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-8">
                                                <!-- AREA QRIS (MIDTRANS + UPLOAD) -->
                                                <div id="qris-area-<?= $row['id_denda'] ?>" style="display:none;">
                                                    <div class="alert alert-info py-2 mb-3 small border-primary">
                                                        <strong>Langkah Pembayaran QRIS:</strong><br>
                                                        1. Klik tombol biru <b>"Bayar via Midtrans"</b> di bawah.<br>
                                                        2. Lakukan pembayaran via Gopay/QRIS.<br>
                                                        3. <b>Screenshot</b> bukti pembayaran sukses.<br>
                                                        4. Upload bukti screenshot tersebut pada form di bawah ini.
                                                    </div>

                                                    <!-- 1. Tombol Midtrans -->
                                                    <?php if(!empty($snapToken)): ?>
                                                        <button type="button" id="pay-btn-<?= $row['id_denda'] ?>" class="btn btn-sm btn-primary fw-bold mb-3">
                                                            <i class="bi bi-qr-code-scan"></i> 1. Bayar via Midtrans (QRIS)
                                                        </button>
                                                        
                                                        <script>
                                                            document.getElementById('pay-btn-<?= $row['id_denda'] ?>').onclick = function(){
                                                                snap.pay('<?= $snapToken ?>', {
                                                                    onSuccess: function(r){ 
                                                                        alert("Pembayaran di Midtrans BERHASIL! \n\nLangkah Terakhir: Silakan Screenshot dan UPLOAD bukti pembayaran di form bawah ini agar Admin bisa memverifikasi."); 
                                                                    },
                                                                    onPending: function(r){ alert("Menunggu pembayaran..."); },
                                                                    onError: function(r){ alert("Pembayaran gagal!"); }
                                                                });
                                                            };
                                                        </script>
                                                    <?php else: ?>
                                                        <span class="text-danger small">Gagal memuat Midtrans: <?= htmlspecialchars($errorMsg) ?></span>
                                                    <?php endif; ?>

                                                    <!-- 2. Input Upload -->
                                                    <div class="card p-3 bg-white border">
                                                        <label class="small fw-bold mb-1">2. Upload Bukti Screenshot:</label>
                                                        <input type="file" name="bukti" id="bukti-input-<?= $row['id_denda'] ?>" class="form-control">
                                                        <div class="form-text text-danger small">* Wajib diisi jika memilih QRIS</div>
                                                    </div>
                                                </div>
                                                
                                                <!-- TOMBOL SUBMIT FORM -->
                                                <button type="submit" name="bayar_manual" class="btn btn-success mt-3 w-100 fw-bold">
                                                    <i class="bi bi-send-check"></i> KIRIM PROSES / BUKTI
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                    <p class="text-center text-muted fs-5">Tidak ada denda 🎉</p>
                <?php } ?>
            </div>

            <!-- RIWAYAT -->
            <div class="card-custom mt-5">
                <h4 class="fw-bold text-secondary mb-3"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</h4>
                <?php if ($riwayat_denda && $riwayat_denda->num_rows > 0) { ?>
                <div class="table-responsive">  
                    <table class="table table-bordered align-middle text-center">
                        <thead style="background:#e2d9f3; color:#59359a;">
                            <tr>
                                <th>Judul</th>
                                <th>Tgl Kembali</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $riwayat_denda->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['judul'] ?></td>
                                <td><?= $row['tanggal_kembali'] ?></td>
                                <td><b>Rp <?= number_format($row['jumlah_denda'] ?? 0) ?></b></td> 
                                <td><?= $row['metode_bayar'] ?></td>
                                <td>
                                    <?php if ($row['status_pembayaran'] == "Lunas") { ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['bukti_pembayaran'])) { ?>
                                        <a href="../uploads/bukti/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-image"></i> Cek</a>
                                    <?php } else { echo "-"; } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                    <p class="text-center text-muted">Belum ada riwayat.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMetode(val, id) {
    var qrisArea = document.getElementById("qris-area-" + id);
    var buktiInput = document.getElementById("bukti-input-" + id);

    if (val === "QRIS") {
        qrisArea.style.display = "block";
        buktiInput.required = true;
    } else {
        qrisArea.style.display = "none";
        buktiInput.required = false;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
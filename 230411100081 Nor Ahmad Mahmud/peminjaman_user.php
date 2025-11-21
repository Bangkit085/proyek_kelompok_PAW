<?php
// peminjaman_user.php
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter user_id diperlukan']);
    exit;
}

$user_id = intval($_GET['user_id']);

// Fungsi helper untuk menghitung denda sederhana:
// contoh: denda per hari keterlambatan = 2000 (ubah sesuai kebijakan)
function hitung_denda($tanggal_jatuh_tempo, $tanggal_kembali = null) {
    $denda_per_hari = 2000;
    $today = new DateTimeImmutable('now');
    $due = new DateTimeImmutable($tanggal_jatuh_tempo);
    $end = $tanggal_kembali ? new DateTimeImmutable($tanggal_kembali) : $today;
    $diff = $end->diff($due);
    $days = intval($diff->format('%r%a')); // negatif jika belum lewat
    if ($end <= $due) return 0.00;
    // days = selisih hari >0
    return $days * $denda_per_hari;
}

// Ambil peminjaman aktif
$sql_act = "SELECT p.*, b.judul, b.penulis
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_user = ? AND p.status = 'dipinjam'";

$stmt = $conn->prepare($sql_act);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res_act = $stmt->get_result();
$aktif = [];
while ($r = $res_act->fetch_assoc()) {
    $r['denda_terhitung'] = number_format((float) hitung_denda($r['tanggal_jatuh_tempo']),2, '.', '');
    $aktif[] = $r;
}
$stmt->close();

// Ambil histori (dikembalikan)
$sql_hist = "SELECT p.*, b.judul, b.penulis
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_user = ? AND p.status = 'dikembalikan'
            ORDER BY p.tanggal_kembali DESC
            LIMIT 200";
$stmt2 = $conn->prepare($sql_hist);
$stmt2->bind_param('i', $user_id);
$stmt2->execute();
$res_hist = $stmt2->get_result();
$history = [];
while ($r = $res_hist->fetch_assoc()) {
    $r['denda_terhitung'] = number_format((float) hitung_denda($r['tanggal_jatuh_tempo'], $r['tanggal_kembali']),2, '.', '');
    $history[] = $r;
}
$stmt2->close();

echo json_encode([
    'success' => true,
    'aktif' => $aktif,
    'history' => $history
]);

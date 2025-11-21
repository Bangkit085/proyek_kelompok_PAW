<?php
// kembalikan_buku.php
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($_POST['id_pinjam'])) {
    http_response_code(400);
    echo json_encode(['error' => 'id_pinjam diperlukan']);
    exit;
}
$id_pinjam = intval($_POST['id_pinjam']);

$conn->begin_transaction();
try {
    // update status peminjaman
    $upd = $conn->prepare("UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=NOW() WHERE id_pinjam = ? AND status='dipinjam'");
    $upd->bind_param('i', $id_pinjam);
    $upd->execute();
    if ($upd->affected_rows === 0) {
        throw new Exception('Tidak ada peminjaman aktif dengan id tersebut');
    }
    $upd->close();

    // (opsional) cek keterlambatan dan isi kolom denda_decimal jika ada
    $q = $conn->prepare("SELECT tanggal_jatuh_tempo FROM peminjaman WHERE id_pinjam = ?");
    $q->bind_param('i', $id_pinjam);
    $q->execute();
    $res = $q->get_result()->fetch_assoc();
    $q->close();
    if ($res) {
        $due = new DateTime($res['tanggal_jatuh_tempo']);
        $now = new DateTime();
        if ($now > $due) {
            $diff = $now->diff($due);
            $days = intval($diff->format('%a'));
            $denda_per_hari = 2000;
            $denda = $days * $denda_per_hari;
            $u = $conn->prepare("UPDATE peminjaman SET denda_decimal = ? WHERE id_pinjam = ?");
            $u->bind_param('di', $denda, $id_pinjam);
            $u->execute();
            $u->close();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

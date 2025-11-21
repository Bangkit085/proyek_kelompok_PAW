<?php
// get_rak.php
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

if (!isset($_GET['book_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter book_id diperlukan']);
    exit;
}

$book_id = intval($_GET['book_id']);

$sql = "SELECT b.id_buku, b.judul, r.id_rak, r.kode_rak, r.gedung, r.lantai, r.keterangan
        FROM buku b
        LEFT JOIN rak r ON b.id_rak = r.id_rak
        WHERE b.id_buku = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Buku tidak ditemukan']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Query gagal dipersiapkan']);
}

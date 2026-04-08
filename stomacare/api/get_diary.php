<?php
// ============================================================
//  api/get_diary.php
//  Ambil daftar catatan food diary user yang sedang login
//  Query param: ?tanggal=YYYY-MM-DD (opsional, default hari ini)
// ============================================================

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
requireLogin();

header('Content-Type: application/json');

$userId  = $_SESSION['user_id'];
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

// Validasi format tanggal
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    echo json_encode(['status' => 'error', 'message' => 'Format tanggal tidak valid']);
    exit;
}

$stmt = $conn->prepare(
    'SELECT id, tanggal, waktu_makan, makanan, minuman, porsi, gejala, nyeri, kondisi, catatan, created_at
     FROM food_diary
     WHERE user_id = ? AND tanggal = ?
     ORDER BY FIELD(waktu_makan,"Pagi","Siang","Malam","Camilan"), created_at ASC'
);
$stmt->bind_param('is', $userId, $tanggal);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    // Decode gejala JSON
    $row['gejala'] = json_decode($row['gejala'], true) ?? ['Belum dicatat'];
    $data[] = $row;
}
$stmt->close();

echo json_encode(['status' => 'success', 'data' => $data, 'tanggal' => $tanggal]);

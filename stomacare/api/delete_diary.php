<?php
// ============================================================
//  api/delete_diary.php
//  Hapus catatan food diary (hanya milik user sendiri)
// ============================================================

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak valid']);
    exit;
}

$userId  = $_SESSION['user_id'];
$entryId = (int)($_POST['id'] ?? 0);

if (!$entryId) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

// Pastikan entry milik user sendiri (security check)
$stmt = $conn->prepare('DELETE FROM food_diary WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $entryId, $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Catatan dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan atau bukan milikmu']);
}
$stmt->close();

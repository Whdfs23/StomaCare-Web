<?php
// ============================================================
//  api/save_diary.php
//  Simpan catatan food diary ke MySQL
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
$tanggal = $_POST['tanggal']     ?? '';
$waktu   = $_POST['waktu']       ?? '';
$makanan = trim($_POST['makanan'] ?? '');
$minuman = trim($_POST['minuman'] ?? '');
$porsi   = trim($_POST['porsi']   ?? '');
$gejala  = $_POST['gejala']      ?? [];   // array dari checkbox
$nyeri   = (int)($_POST['nyeri'] ?? 0);
$kondisi = trim($_POST['kondisi'] ?? '');
$catatan = trim($_POST['catatan'] ?? '');

// Validasi wajib
$validWaktu = ['Pagi', 'Siang', 'Malam', 'Camilan'];
if (!$tanggal || !in_array($waktu, $validWaktu) || !$makanan) {
    echo json_encode(['status' => 'error', 'message' => 'Field wajib tidak lengkap']);
    exit;
}

// Gejala: simpan sebagai JSON string
$gejalaSimpan = is_array($gejala) && count($gejala) > 0
    ? json_encode($gejala, JSON_UNESCAPED_UNICODE)
    : json_encode(['Belum dicatat']);

$stmt = $conn->prepare(
    'INSERT INTO food_diary
        (user_id, tanggal, waktu_makan, makanan, minuman, porsi, gejala, nyeri, kondisi, catatan)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param(
    'isssssssss',
    $userId, $tanggal, $waktu, $makanan,
    $minuman, $porsi, $gejalaSimpan,
    $nyeri, $kondisi, $catatan
);

if ($stmt->execute()) {
    $newId = $stmt->insert_id;
    $stmt->close();
    echo json_encode([
        'status'  => 'success',
        'message' => 'Catatan berhasil disimpan',
        'id'      => $newId
    ]);
} else {
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
}

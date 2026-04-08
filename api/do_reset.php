<?php
// ============================================================
//  api/do_reset.php — Eksekusi perubahan password dari token
// ============================================================
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('auth.php'));
    exit;
}

$token    = $_POST['token'] ?? '';
$new_pass = $_POST['new_password'] ?? '';

if (!$token || strlen($new_pass) < 6) {
    die("Data tidak valid atau password kurang dari 6 karakter.");
}

// Cek token lagi untuk keamanan ganda
$stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() LIMIT 1');
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Token tidak valid atau sudah kadaluarsa.");
}

// Enkripsi password baru
$hashed_password = password_hash($new_pass, PASSWORD_BCRYPT);

// Update password dan kosongkan token agar tidak bisa dipakai lagi
$update = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
$update->bind_param('si', $hashed_password, $user['id']);

if ($update->execute()) {
    header('Location: ' . url('auth.php') . '?success=Password+berhasil+diubah!+Silakan+login');
} else {
    echo "Gagal mengubah password.";
}
$update->close();
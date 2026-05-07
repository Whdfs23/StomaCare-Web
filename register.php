<?php
// ============================================================
//  api/register.php
//  Handle POST register form
// ============================================================

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('auth.php'));
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

// Validasi
if (!$username || !$email || !$password) {
    header('Location: ' . url('auth.php') . '?mode=register&error=Semua+field+wajib+diisi');
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . url('auth.php') . '?mode=register&error=Format+email+tidak+valid');
    exit;
}
if (strlen($password) < 6) {
    header('Location: ' . url('auth.php') . '?mode=register&error=Password+minimal+6+karakter');
    exit;
}

// Cek duplikat username/email
$stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    header('Location: ' . url('auth.php') . '?mode=register&error=Username+atau+email+sudah+terdaftar');
    exit;
}
$stmt->close();

// Hash password & simpan
$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt   = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $username, $email, $hashed);

if ($stmt->execute()) {
    $newUserId = $stmt->insert_id;
    $stmt->close();

    $_SESSION['user_id']  = $newUserId;
    $_SESSION['username'] = $username;
    $_SESSION['email']    = $email;

    header('Location: ' . url('index.php')); 
    exit;
}

$stmt->close();
header('Location: ' . url('auth.php') . '?mode=register&error=Registrasi+gagal,+coba+lagi');
exit;

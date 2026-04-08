<?php
// ============================================================
//  api/login.php
//  Handle POST login form
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
$password = $_POST['password'] ?? '';

// Validasi input kosong
if (!$username || !$password) {
    header('Location: ' . url('auth.php') . '?error=Username+dan+password+wajib+diisi');
    exit;
}

// Cari user
$stmt = $conn->prepare('SELECT id, username, email, password FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

// Verifikasi password
if (!$user || !password_verify($password, $user['password'])) {
    header('Location: ' . url('auth.php') . '?error=Username+atau+password+salah');
    exit;
}

// Set session
$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email']    = $user['email'];

header('Location: ' . url('index.php'));
exit;

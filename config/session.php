<?php
// ============================================================
//  config/session.php
//  Helper: mulai session & cek login
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirect ke login jika belum login.
 * Panggil di halaman yang butuh autentikasi.
 */
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /auth.php');
        exit;
    }
}

/**
 * Cek apakah user sudah login (return bool).
 */
function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Ambil data user dari session.
 */
function currentUser(): array {
    return [
        'id'       => $_SESSION['user_id']       ?? null,
        'username' => $_SESSION['username']       ?? '',
        'email'    => $_SESSION['email']          ?? '',
    ];
}

<?php
// ============================================================
//  config/db.php
//  Koneksi ke MySQL via MySQLi
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Kosongkan jika XAMPP default
define('DB_NAME', 'stomacare_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode([
        'status'  => 'error',
        'message' => 'Koneksi database gagal: ' . $conn->connect_error
    ]));
}

$conn->set_charset('utf8mb4');

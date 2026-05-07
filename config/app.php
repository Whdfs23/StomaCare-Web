<?php
// ============================================================
//  config/app.php
//  Base URL — sesuaikan jika subfolder
// ============================================================

// Deteksi otomatis base path
// Contoh: http://localhost/stomacare/ → BASE_URL = '/stomacare'
// Contoh: http://localhost/           → BASE_URL = ''

$scriptDir  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptDir  = rtrim($scriptDir, '/');

// Cari root project (folder yang berisi index.php)
// Naik satu level dari subfolder kalau perlu
define('BASE_URL', $scriptDir === '' ? '' : '/' . trim(explode('/', ltrim($scriptDir, '/'))[0], '/'));

/**
 * Helper: buat URL dengan base path
 * Usage: url('/assets/css/style.css')
 */
function url(string $path): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

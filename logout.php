<?php
// ============================================================
//  api/logout.php
// ============================================================

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';
session_destroy();
header('Location: ' . url('auth.php') . '?success=Kamu+berhasil+logout');
exit;

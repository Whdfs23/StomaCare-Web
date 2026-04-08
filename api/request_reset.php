<?php
// ============================================================
//  api/request_reset.php — Membuat token & Kirim Email PHPMailer
// ============================================================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

// Panggil file PHPMailer secara manual
require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';// Panggil PHPMailer

// Catatan: Jika temanmu tidak pakai composer, sesuaikan require-nya:
// require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
// require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

$email = trim($_POST['email'] ?? '');

if (!$email) {
    header('Location: ' . url('forgot-password.php') . '?error=Email+wajib+diisi');
    exit;
}

// Cek apakah email ada di database
$stmt = $conn->prepare('SELECT id, username FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    // Demi keamanan, tetap arahkan dengan pesan sama meski email tidak ada (mencegah enumerasi email)
    header('Location: ' . url('forgot-password.php') . '?success=Jika+email+terdaftar,+link+reset+telah+dikirim');
    exit;
}

// Generate Token
$token = bin2hex(random_bytes(32));

// Simpan token ke database (Gunakan DATE_ADD bawaan MySQL agar sinkron)
$update = $conn->prepare('UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?');
$update->bind_param('si', $token, $user['id']);
$update->execute();
$update->close();

// Kirim Email dengan PHPMailer
$mail = new PHPMailer(true);
try {
    // --- KONFIGURASI SMTP KAMU DI SINI ---
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Ganti jika tidak pakai Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'zulfanashihin@gmail.com';   // Masukkan email pengirim
    $mail->Password   = 'zrqz jimw pdje cepd';       // Masukkan App Password (bukan password email biasa)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('zulfanashihin@gmail.com', 'StomaCare System');
    $mail->addAddress($email, $user['username']);

    // Konten Email
    $resetLink = 'http://localhost/stomacare/reset-password.php?token=' . $token;
    
    $mail->isHTML(true);
    $mail->Subject = 'Reset Password Akun StomaCare';
    $mail->Body    = "Halo <b>{$user['username']}</b>,<br><br>
                      Kamu baru saja meminta reset password untuk akun StomaCare.<br>
                      Silakan klik link di bawah ini untuk membuat password baru (berlaku 1 jam):<br><br>
                      <a href='{$resetLink}'>{$resetLink}</a><br><br>
                      Jika kamu tidak memintanya, abaikan saja email ini.";

    $mail->send();
    header('Location: ' . url('forgot-password.php') . '?success=Link+reset+password+telah+dikirim+ke+emailmu');
} catch (Exception $e) {
    header('Location: ' . url('forgot-password.php') . '?error=Gagal+mengirim+email.+Error:+' . urlencode($mail->ErrorInfo));
}
<?php
// ============================================================
//  forgot-password.php — Antarmuka Input Email Reset
// ============================================================
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/app.php';

if (isLoggedIn()) {
    header('Location: ' . url('index.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password – StomaCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { 'stoma-dark': '#1a3c30', 'stoma-green': '#458b68', 'stoma-light': '#a8d5be', 'stoma-pale': '#e8f5ee' }, fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-gradient-to-br from-stoma-pale to-[#c8e6d4] min-h-screen flex items-center justify-center p-6 font-sans">

    <?php if (!empty($_GET['error'])): ?>
    <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-100 text-red-800 border border-red-200 px-5 py-3 rounded-xl font-semibold text-sm shadow-lg alert-box">
        <i class="fas fa-exclamation-circle mr-2"></i> <?= htmlspecialchars($_GET['error']) ?>
    </div>
    <?php elseif (!empty($_GET['success'])): ?>
    <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-100 text-green-800 border border-green-200 px-5 py-3 rounded-xl font-semibold text-sm shadow-lg alert-box">
        <i class="fas fa-check-circle mr-2"></i> <?= htmlspecialchars($_GET['success']) ?>
    </div>
    <?php endif; ?>

    <div class="bg-white w-full max-w-md rounded-[28px] shadow-[0_20px_60px_rgba(26,60,48,.15)] p-10 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-stoma-pale rounded-full -translate-y-1/2 translate-x-1/2"></div>
        
        <a href="<?= url('auth.php') ?>" class="text-gray-400 hover:text-stoma-green text-sm font-bold mb-6 inline-block transition-colors relative z-10">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
        </a>

        <h1 class="text-3xl font-extrabold text-stoma-dark mb-2 relative z-10">Lupa Password?</h1>
        <p class="text-gray-400 text-sm mb-8 relative z-10 leading-relaxed">
            Jangan khawatir! Masukkan email akunmu di bawah ini, dan kami akan mengirimkan instruksi untuk mengatur ulang passwordmu.
        </p>

        <form action="<?= url('api/request_reset.php') ?>" method="POST" class="relative z-10">
            
            <div class="relative mb-6">
                <input class="w-full bg-[#f4f8f6] border-2 border-[#e0ede7] rounded-xl px-4 py-3.5 text-sm text-stoma-dark outline-none focus:border-stoma-green transition-colors" 
                       type="email" name="email" placeholder="Masukkan Email Terdaftar" required>
                <i class="fas fa-envelope absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <button type="submit" class="w-full bg-stoma-green hover:bg-stoma-dark text-white rounded-xl py-3.5 font-bold transition-colors shadow-lg flex justify-center items-center">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset
            </button>
        </form>

    </div>

    <script>
        const alerts = document.querySelectorAll('.alert-box');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.style.display = 'none', 500);
            }, 4000);
        });
    </script>
</body>
</html>
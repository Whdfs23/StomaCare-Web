<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

$token = $_GET['token'] ?? '';
$valid = false;

if ($token) {
    // Cek apakah token ada dan belum kadaluarsa
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW() LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $valid = true;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Password Baru – StomaCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { 'stoma-dark': '#1a3c30', 'stoma-green': '#458b68' } } } }
    </script>
</head>
<body class="bg-[#e8f5ee] min-h-screen flex items-center justify-center p-4" style="font-family: sans-serif;">

    <div class="bg-white max-w-md w-full rounded-[28px] shadow-xl p-8">
        <?php if (!$valid): ?>
            <div class="text-center">
                <div class="text-red-500 text-5xl mb-4">⚠️</div>
                <h1 class="text-2xl font-bold text-stoma-dark mb-2">Link Tidak Valid</h1>
                <p class="text-gray-500 text-sm mb-6">Link reset password tidak valid atau sudah kadaluarsa.</p>
                <a href="<?= url('forgot-password.php') ?>" class="bg-stoma-dark text-white px-6 py-2 rounded-xl font-bold inline-block">Minta Link Baru</a>
            </div>
        <?php else: ?>
            <h1 class="text-2xl font-bold text-stoma-dark mb-2">Buat Password Baru</h1>
            <p class="text-gray-500 text-sm mb-6">Silakan ketikkan password baru untuk akunmu.</p>

            <form action="<?= url('api/do_reset.php') ?>" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="mb-5">
                    <input type="password" name="new_password" placeholder="Password Baru (min. 6 karakter)" required minlength="6"
                           class="w-full bg-[#f4f8f6] border-2 border-[#e0ede7] rounded-xl px-4 py-3 text-sm text-stoma-dark outline-none focus:border-stoma-green transition-colors">
                </div>
                <button type="submit" class="w-full bg-stoma-green hover:bg-stoma-dark text-white font-bold py-3.5 rounded-xl transition-colors">
                    Simpan Password Baru
                </button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
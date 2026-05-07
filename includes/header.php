<?php
// ============================================================
//  includes/header.php
//  Navbar StomaCare — Tailwind
//  Variabel yang bisa di-set sebelum include:
//    $pageTitle  (string) — judul tab browser
//    $activePage (string) — 'home' | 'about' | 'food-diary'
// ============================================================

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/app.php';

$pageTitle  = $pageTitle  ?? 'StomaCare';
$activePage = $activePage ?? '';
$user       = currentUser();

function navClass(string $page, string $active): string {
    $base   = 'nav-link';
    $active_cls = 'nav-link-active';
    return $page === $active ? "$base $active_cls" : $base;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> – StomaCare</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CDN + custom color config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'stoma-dark':  '#1a3c30',
                        'stoma-green': '#458b68',
                        'stoma-light': '#a8d5be',
                        'stoma-pale':  '#e8f5ee',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- CSS Global (custom classes non-Tailwind) -->
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body class="overflow-x-hidden bg-white">

<div class="fixed top-0 left-0 w-full flex justify-center p-4 md:p-6 z-50">
    <nav class="bg-stoma-dark w-full max-w-6xl rounded-full px-6 md:px-10 py-3 flex items-center shadow-2xl border border-white/10">
        <div class="flex items-center gap-3">
            <img src="<?= url('assets/img/logo.png') ?>" alt="Logo StomaCare" class="w-8 h-8 object-contain"
                 onerror="this.src='https://api.dicebear.com/7.x/bottts/svg?seed=stoma'">
            <span class="text-xl font-bold text-white tracking-tight">StomaCare</span>
        </div>

        <div class="ml-auto flex items-center gap-2 md:gap-4">
            <div class="hidden md:flex items-center gap-1">
               <a href="<?= url('index.php') ?>"      class="<?= navClass('home',       $activePage) ?>">Home</a>
                <?php if (isLoggedIn()): ?>
                <a href="<?= url('dashboard.php') ?>"  class="<?= navClass('dashboard',  $activePage) ?>">Dashboard</a>
                <?php endif; ?>
                <a href="<?= url('about.php') ?>"      class="<?= navClass('about',      $activePage) ?>">About Us</a>
               <?php if (isLoggedIn()): ?>
                <a href="<?= url('riwayat.php') ?>" class="<?= navClass('riwayat', $activePage) ?>">Riwayat</a>
                <?php endif; ?>
            </div>

            <div class="hidden md:block ml-2">
                <?php if (isLoggedIn()): ?>
                    <div class="flex items-center gap-3">
                        <span class="text-white/70 text-sm font-semibold">
                            Hi, <?= htmlspecialchars($user['username']) ?>!
                        </span>
                        <a href="<?= url('api/logout.php') ?>"
                           class="bg-red-500 text-white px-5 py-2 rounded-full font-bold text-xs btn-hover inline-block shadow-lg">
                            Logout
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= url('auth.php') ?>"
                       class="bg-white text-stoma-dark px-7 py-2.5 rounded-full font-bold text-xs btn-hover inline-block shadow-lg">
                        Login / Register
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile hamburger -->
        <div class="md:hidden ml-auto text-xl text-white cursor-pointer" id="menuToggle">
            <i class="fas fa-bars"></i>
        </div>
    </nav>
</div>

<!-- Mobile menu -->
<div id="mobileMenu" class="hidden fixed top-20 left-4 right-4 bg-stoma-dark rounded-2xl p-4 z-40 shadow-2xl">
    <a href="<?= url('index.php') ?>"      class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">Home</a>
    <a href="<?= url('about.php') ?>"      class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">About Us</a>
    <?php if (isLoggedIn()): ?> 
    <a href="<?= url('riwayat.php') ?>" class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">Riwayat</a>
    <a href="<?= url('api/logout.php') ?>" class="block text-red-400 py-2 px-4 rounded-xl hover:bg-white/10">Logout</a>
    <?php else: ?>
    <a href="<?= url('auth.php') ?>"       class="block text-white py-2 px-4 rounded-xl bg-white/10 mt-1">Login / Register</a>
    <?php endif; ?>
</div>

<a href="<?= url('index.php') ?>"      class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">Home</a>
    <?php if (isLoggedIn()): ?>
    <a href="<?= url('dashboard.php') ?>"  class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">Dashboard</a>
    <?php endif; ?>
    <a href="<?= url('about.php') ?>"      class="block text-white/70 py-2 px-4 rounded-xl hover:bg-white/10">About Us</a>
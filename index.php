<?php
// ============================================================
//  index.php — Landing Page StomaCare
// ============================================================

$pageTitle  = 'Beranda';
$activePage = 'home';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- HERO SECTION -->
    <header class="relative min-h-screen flex items-center pt-20 overflow-visible">
        <div class="illustration-corner-wrapper">
            <!-- Blob SVG fallback kalau gambar belum di-copy -->
            <img src="<?= url('assets/img/blob.png') ?>" alt="Blob Background" class="corner-blob"
                 onerror="this.onerror=null;this.src='data:image/svg+xml,<svg viewBox='0 0 500 500' xmlns='http://www.w3.org/2000/svg'><path d='M420,300Q380,400,280,430Q180,460,120,370Q60,280,80,180Q100,80,200,60Q300,40,380,110Q460,180,420,300Z' fill='%23a8d5be'/></svg>'">
            <img src="<?= url('assets/img/stomach.png') ?>" alt="Stomach Illustration" class="corner-stomach"
                 onerror="this.style.display='none'">
        </div>
        <div class="container mx-auto px-6 md:px-20 z-10">
            <div class="md:w-1/2 text-center md:text-left">
                <h1 class="text-4xl md:text-7xl font-bold leading-tight mb-6">
                    Sayangi Lambungmu,<br>
                    <span class="text-stoma-light brightness-90">Hidup Lebih Nyaman</span>
                </h1>
                <p class="text-gray-500 text-lg md:text-xl mb-10 max-w-xl leading-relaxed">
                    Pelajari cara menjaga kesehatan lambung, kenali gejala awalnya, dan temukan pola hidup sehat bersama StomaCare.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <button onclick="document.getElementById('features').scrollIntoView({behavior:'smooth'})"
                            class="bg-stoma-green text-white px-8 py-4 rounded-2xl font-bold text-lg btn-hover shadow-lg">
                        Pelajari Sekarang
                    </button>
                    <?php if (!isLoggedIn()): ?>
                    <a href="<?= url('auth.php') ?>"
                       class="border-2 border-stoma-green text-stoma-green px-8 py-4 rounded-2xl font-bold text-lg btn-hover text-center">
                        Daftar Gratis
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- FEATURES SECTION -->
    <section id="features" class="px-6 md:px-20 py-32 bg-gray-50 rounded-t-[50px]">
        <div class="max-w-7xl mx-auto">
            <div class="mb-20">
                <h2 class="text-4xl font-bold mb-2">Kenali Masalah Lambungmu</h2>
                <h2 class="text-4xl font-bold text-stoma-light brightness-90">Lebih Dalam</h2>
            </div>
            <div class="features-grid">
                <!-- Card Gastritis -->
                <div class="relative group">
                    <div class="card-bg-layer-top-right"></div>
                    <div class="layered-card card-shadow border border-gray-100">
                        <div class="card-icon"><i class="fas fa-plus"></i></div>
                        <div class="card-content">
                            <h3 class="font-bold text-2xl mb-4 text-[#1a3c30]">Gastritis (Maag)</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-5">
                                Gastritis adalah peradangan pada dinding lambung. Bisa terjadi secara tiba-tiba (akut) atau perlahan seiring waktu (kronis).
                            </p>
                            <ul class="card-symptoms space-y-2 text-xs text-gray-400">
                                <li>• Nyeri menusuk atau panas di ulu hati.</li>
                                <li>• Perut terasa penuh setelah makan.</li>
                                <li>• Mual dan muntah.</li>
                            </ul>
                            <div class="card-action">
                                <?php if (isLoggedIn()): ?>
                                <a href="<?= url('food-diary.php') ?>"
                                   class="bg-[#458b68] text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-2 btn-hover text-sm w-fit">
                                    Catat di Food Diary <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                                <?php else: ?>
                                <a href="<?= url('auth.php') ?>"
                                   class="bg-[#458b68] text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-2 btn-hover text-sm w-fit">
                                    Coba Fitur Pengecekan <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card GERD -->
                <div class="relative group card-offset-down">
                    <div class="card-bg-layer-bottom-left"></div>
                    <div class="layered-card card-shadow border border-gray-100">
                        <div class="card-icon"><i class="fas fa-wave-square"></i></div>
                        <div class="card-content">
                            <h3 class="font-bold text-2xl mb-4 text-[#1a3c30]">GERD</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-5">
                                GERD terjadi ketika asam lambung berulang kali naik ke kerongkongan karena katup perut melemah.
                            </p>
                            <ul class="card-symptoms space-y-2 text-xs text-gray-400">
                                <li>• Sensasi terbakar di dada (heartburn).</li>
                                <li>• Rasa asam atau pahit di belakang mulut.</li>
                                <li>• Batuk kronis atau kesulitan menelan.</li>
                            </ul>
                            <div class="card-action">
                                <?php if (isLoggedIn()): ?>
                                <a href="<?= url('food-diary.php') ?>"
                                   class="bg-[#458b68] text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-2 btn-hover text-sm w-fit">
                                    Catat di Food Diary <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                                <?php else: ?>
                                <a href="<?= url('auth.php') ?>"
                                   class="bg-[#458b68] text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-2 btn-hover text-sm w-fit">
                                    Coba Fitur Pengecekan <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER BANNER CTA -->
    <section class="px-6 md:px-20 py-20">
        <div class="max-w-6xl mx-auto bg-stoma-light rounded-[50px] p-12 md:p-20 text-center relative overflow-hidden shadow-xl">
            <div class="relative z-10">
                <h2 class="text-3xl md:text-5xl font-bold text-stoma-dark mb-6">Mulai Pantau Kondisi Lambungmu Hari Ini!</h2>
                <p class="text-stoma-dark/60 text-lg mb-12 max-w-2xl mx-auto">
                    Akses fitur eksklusif: Pengecekan Gejala, Food Diary Harian, dan Ensiklopedia Makanan aman untuk lambung.
                </p>
                <?php if (!isLoggedIn()): ?>
                <a href="<?= url('auth.php?mode=register') ?>"
                   class="bg-white text-stoma-dark px-12 py-4 rounded-full font-bold shadow-md btn-hover inline-block">
                    Daftar Gratis Sekarang
                </a>
                <?php else: ?>
                <a href="<?= url('food-diary.php') ?>"
                   class="bg-white text-stoma-dark px-12 py-4 rounded-full font-bold shadow-md btn-hover inline-block">
                    Buka Food Diary
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

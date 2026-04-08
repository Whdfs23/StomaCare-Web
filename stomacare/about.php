<?php
// ============================================================
//  about.php — Halaman About Us StomaCare
// ============================================================

$pageTitle  = 'About Us';
$activePage = 'about';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- HERO ABOUT -->
    <section class="relative pt-36 pb-24 px-6 md:px-20 overflow-hidden">
        <!-- Dekorasi blob -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-stoma-light/20 rounded-full blur-3xl -z-10 translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-stoma-light/10 rounded-full blur-2xl -z-10"></div>

        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block bg-stoma-pale text-stoma-green text-xs font-bold px-4 py-2 rounded-full mb-6 tracking-wide uppercase">
                Tentang Kami
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-stoma-dark leading-tight mb-6">
                Kami Peduli pada<br>
                <span class="text-stoma-green">Kesehatan Lambungmu</span>
            </h1>
            <p class="text-gray-500 text-lg leading-relaxed max-w-2xl mx-auto">
                StomaCare adalah platform digital kesehatan pencernaan yang membantu kamu memahami, memantau, dan menjaga kondisi lambung setiap harinya.
            </p>
        </div>
    </section>

    <!-- VISI MISI -->
    <section class="px-6 md:px-20 py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10">

            <!-- Visi -->
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 fade-in">
                <div class="w-14 h-14 bg-stoma-pale rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-stoma-green text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-stoma-dark mb-4">Visi</h2>
                <p class="text-gray-500 leading-relaxed">
                    Menjadi platform kesehatan lambung digital terpercaya di Indonesia yang membantu masyarakat menjalani hidup lebih sehat melalui edukasi dan pemantauan mandiri.
                </p>
            </div>

            <!-- Misi -->
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 fade-in">
                <div class="w-14 h-14 bg-stoma-pale rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-bullseye text-stoma-green text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-stoma-dark mb-4">Misi</h2>
                <ul class="text-gray-500 leading-relaxed space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-stoma-green mt-1 flex-shrink-0"></i>
                        Menyediakan informasi medis yang akurat dan mudah dipahami.
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-stoma-green mt-1 flex-shrink-0"></i>
                        Membantu pengguna mencatat pola makan dan gejala harian.
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-stoma-green mt-1 flex-shrink-0"></i>
                        Mendorong gaya hidup sehat untuk penderita gangguan lambung.
                    </li>
                </ul>
            </div>

        </div>
    </section>

    <!-- FITUR UTAMA -->
    <section class="px-6 md:px-20 py-20">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold text-stoma-dark mb-3">Fitur Utama</h2>
                <p class="text-gray-400 text-base">Semua yang kamu butuhkan untuk menjaga kesehatan lambung</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">

                <?php
                $fitur = [
                    ['icon' => 'fa-book-open',     'judul' => 'Food Diary',        'desc' => 'Catat setiap makanan dan gejala yang kamu rasakan setelah makan. Data tersimpan permanen di database.'],
                    ['icon' => 'fa-stethoscope',   'judul' => 'Cek Gejala',        'desc' => 'Kenali gejala gastritis dan GERD sejak dini dengan panduan berbasis referensi medis terpercaya.'],
                    ['icon' => 'fa-book-medical',  'judul' => 'Edukasi Kesehatan', 'desc' => 'Akses artikel dan informasi tentang pola makan sehat, makanan yang aman, serta tips menjaga lambung.'],
                ];
                foreach ($fitur as $f): ?>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform fade-in">
                    <div class="w-12 h-12 bg-stoma-pale rounded-xl flex items-center justify-center mb-5">
                        <i class="fas <?= $f['icon'] ?> text-stoma-green"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stoma-dark mb-3"><?= $f['judul'] ?></h3>
                    <p class="text-gray-400 text-sm leading-relaxed"><?= $f['desc'] ?></p>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- TIM -->
    <section class="px-6 md:px-20 py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold text-stoma-dark mb-3">Tim Pengembang</h2>
                <p class="text-gray-400 text-base">Kelompok 4 — Aplikasi Sistem Web dan Seluler</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                <?php
                $tim = [
                    ['nama' => 'Widyadana Hussin F.',  'peran' => 'Backend Developer'],
                    ['nama' => 'Zulfa Nashihin',   'peran' => 'Frontend Developer'],
                    ['nama' => 'Yofi Widiyanto',   'peran' => 'UI/UX Designer'],
                    ['nama' => 'Navista Andara P.', 'peran' => 'Database & Docs'],
                ];
                foreach ($tim as $t):
                    // Generate avatar warna dari nama
                    $seed = urlencode($t['nama']);
                ?>
                <div class="bg-white rounded-3xl p-6 text-center shadow-sm border border-gray-100 fade-in">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed=<?= $seed ?>&backgroundColor=458b68&textColor=ffffff"
                         alt="<?= htmlspecialchars($t['nama']) ?>"
                         class="w-16 h-16 rounded-full mx-auto mb-4 object-cover">
                    <h4 class="font-bold text-stoma-dark text-sm"><?= htmlspecialchars($t['nama']) ?></h4>
                    <p class="text-gray-400 text-xs mt-1"><?= htmlspecialchars($t['peran']) ?></p>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="px-6 md:px-20 py-20">
        <div class="max-w-4xl mx-auto bg-stoma-dark rounded-[40px] p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold text-white mb-4">Mulai Perjalanan Sehatmu</h2>
                <p class="text-white/60 mb-8 text-sm leading-relaxed max-w-lg mx-auto">
                    Bergabung dengan StomaCare dan mulai pantau kesehatan lambungmu secara rutin.
                </p>
                <?php if (!isLoggedIn()): ?>
                <a href="<?= url('auth.php?mode=register') ?>"
                   class="bg-stoma-light text-stoma-dark px-10 py-3.5 rounded-full font-bold btn-hover inline-block shadow-lg">
                    Daftar Gratis Sekarang
                </a>
                <?php else: ?>
                <a href="<?= url('food-diary.php') ?>"
                   class="bg-stoma-light text-stoma-dark px-10 py-3.5 rounded-full font-bold btn-hover inline-block shadow-lg">
                    Buka Food Diary
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

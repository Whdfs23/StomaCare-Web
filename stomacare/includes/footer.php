<?php require_once __DIR__ . '/../config/app.php'; ?>
<?php
// ============================================================
//  includes/footer.php
// ============================================================
?>
    <!-- FOOTER -->
    <footer class="bg-stoma-green py-16 px-6 text-white text-center">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <span class="text-3xl font-bold tracking-widest uppercase">StomaCare</span>
                <p class="text-sm text-white/70 mt-3">© <?= date('Y') ?> StomaCare. Solusi Pintar Kesehatan Lambung Anda.</p>
            </div>
            <p class="text-[11px] text-white/50 max-w-3xl mx-auto leading-relaxed italic">
                Disclaimer: Informasi ini untuk edukasi, bukan pengganti diagnosis atau saran medis dari dokter profesional.
            </p>
        </div>
    </footer>

    <!-- JS Global -->
    <script src="<?= url('assets/js/script.js') ?>"></script>
</body>
</html>

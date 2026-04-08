<?php
// ============================================================
//  food-diary.php — Halaman Input Food Diary (Tanpa Riwayat)
// ============================================================

$pageTitle  = 'Tambah Catatan';
$activePage = 'food-diary';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
requireLogin();

$user = currentUser();
?>

<link rel="stylesheet" href="<?= url('assets/css/food-diary.css') ?>">

<div class="hero-strip" style="margin-top:80px;">
    <div class="hero-inner">
        <div class="hero-date">
            <i class="fas fa-calendar-alt"></i>
            <span id="heroDate">—</span>
        </div>
        <h1 class="hero-title">Catat Makanan Baru</h1>
        <p class="hero-sub">
            Halo, <strong><?= htmlspecialchars($user['username']) ?></strong>!
            Catat detail makanan &amp; gejala yang kamu rasakan hari ini.
        </p>
    </div>
</div>

<div class="main-area">

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-ico"><i class="fas fa-pen"></i></div>
            <div>
                <h2>Form Catatan</h2>
                <p>Isi detail makanan yang kamu konsumsi</p>
            </div>
        </div>
        <div class="form-card-body">

            <div class="field-block">
                <div class="waktu-label">Waktu Makan <span class="req">*</span></div>
                <div class="pills-row">
                    <button type="button" class="waktu-pill" data-val="Pagi"    onclick="pilihWaktu(this)">🌅 Pagi</button>
                    <button type="button" class="waktu-pill" data-val="Siang"   onclick="pilihWaktu(this)">☀️ Siang</button>
                    <button type="button" class="waktu-pill" data-val="Malam"   onclick="pilihWaktu(this)">🌙 Malam</button>
                    <button type="button" class="waktu-pill" data-val="Camilan" onclick="pilihWaktu(this)">🍎 Camilan</button>
                </div>
                <div id="errWaktu" class="err-msg">Pilih waktu makan terlebih dahulu.</div>
            </div>

            <div class="field-row">
                <div>
                    <label class="lbl" for="fTanggal">Tanggal <span class="req">*</span></label>
                    <input type="date" id="fTanggal" class="inp">
                    <div id="errTanggal" class="err-msg">Tanggal wajib diisi.</div>
                </div>
                <div>
                    <label class="lbl" for="fPorsi">Porsi</label>
                    <select id="fPorsi" class="inp">
                        <option value="">— Pilih —</option>
                        <option>Sedikit</option><option>Normal</option><option>Banyak</option>
                    </select>
                </div>
            </div>

            <div class="field-block">
                <label class="lbl" for="fMakanan">Makanan <span class="req">*</span></label>
                <input type="text" id="fMakanan" class="inp" placeholder="cth: Nasi goreng, tempe bacem...">
                <div id="errMakanan" class="err-msg">Nama makanan wajib diisi.</div>
            </div>
            <div class="field-block">
                <label class="lbl" for="fMinuman">Minuman</label>
                <input type="text" id="fMinuman" class="inp" placeholder="cth: Air putih, teh hangat...">
            </div>

            <div class="field-block">
                <div class="waktu-label">Gejala Setelah Makan</div>
                <div class="gejala-grid" id="gejalaPicker">
                    <?php
                    $gejalaDaftar = ['Mual','Kembung','Nyeri Ulu Hati','Heartburn','Sendawa','Diare','Sembelit','Lemas','Pusing','Tidak Ada'];
                    foreach ($gejalaDaftar as $g): ?>
                    <label class="gejala-item" onclick="toggleGejala(this, event)">
                        <input type="checkbox" value="<?= htmlspecialchars($g) ?>">
                        <?= htmlspecialchars($g) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="field-block">
                <div class="waktu-label" style="display:flex;align-items:center;gap:10px;">
                    Tingkat Nyeri
                    <span class="nyeri-badge" id="nyeriBadge" style="background:#dcfce7;color:#166534;">0</span>
                    <span style="font-size:12px;color:#6b7280;font-weight:600;" id="nyeriLabel">Tidak ada nyeri</span>
                </div>
                <input type="range" id="fNyeri" class="nyeri-slider" min="0" max="10" value="0"
                       oninput="updateNyeri(this.value)">
            </div>

            <div class="field-row">
                <div>
                    <label class="lbl" for="fKondisi">Kondisi Lambung</label>
                    <select id="fKondisi" class="inp">
                        <option value="">— Pilih —</option>
                        <option>Baik</option><option>Cukup</option>
                        <option>Kurang Baik</option><option>Buruk</option>
                    </select>
                </div>
                <div>
                    <label class="lbl" for="fCatatan">Catatan Tambahan</label>
                    <input type="text" id="fCatatan" class="inp" placeholder="Opsional...">
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="button" class="btn-save" id="btnSimpan" onclick="simpanCatatan()">
                    <i class="fas fa-save" style="margin-right:8px;"></i>Simpan Catatan
                </button>
                <button type="button" class="btn-reset" onclick="resetForm()">Reset</button>
                
                <a href="<?= url('riwayat.php') ?>" class="btn-reset" style="display:flex;align-items:center;text-decoration:none;">
                    Batal
                </a>
            </div>
        </div>
    </div>

    <div class="hasil-card" id="hasilCard">
        <div class="hasil-header">
            <i class="fas fa-check-circle" style="color:#6dcea0;font-size:18px;"></i>
            <div>
                <h2 id="hasilJudul">Catatan Berhasil Disimpan!</h2>
                <p  id="hasilSub">—</p>
            </div>
        </div>
        <div class="hasil-grid" id="hasilGrid"></div>
        <div style="padding: 16px 24px; background: #fff; border-top: 1px solid #f0f9f4; text-align: right;">
            <a href="<?= url('riwayat.php') ?>" class="text-sm font-bold text-stoma-green hover:underline">
                Lihat di Riwayat &rarr;
            </a>
        </div>
    </div>

</div><script src="<?= url('assets/js/food-diary.js') ?>"></script>

<script>
// ── Override simpanCatatan: kirim ke PHP via fetch (Tanpa Render Riwayat) ──
async function simpanCatatan() {
    const dataForm = {
        tanggal : document.getElementById('fTanggal').value,
        waktu   : waktuDipilih,
        makanan : document.getElementById('fMakanan').value.trim(),
        minuman : document.getElementById('fMinuman').value.trim(),
        porsi   : document.getElementById('fPorsi').value,
        gejala  : ambilGejala(),
        nyeri   : document.getElementById('fNyeri').value,
        kondisi : document.getElementById('fKondisi').value,
        catatan : document.getElementById('fCatatan').value.trim()
    };

    if (!validasiForm(dataForm)) return;

    const body = new FormData();
    Object.entries(dataForm).forEach(([k, v]) => {
        if (k === 'gejala') v.forEach(g => body.append('gejala[]', g));
        else body.append(k, v);
    });

    const btn = document.getElementById('btnSimpan');
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i>Menyimpan...';

    try {
        const res  = await fetch('<?= url("api/save_diary.php") ?>', { method: 'POST', body });
        const json = await res.json();

        if (json.status !== 'success') {
            alert('Gagal menyimpan: ' + json.message);
            return;
        }

        // Tampilkan hasil sukses
        renderHasil(dataForm);
        const hc = document.getElementById('hasilCard');
        document.getElementById('hasilJudul').textContent = 'Catatan ' + dataForm.waktu + ' Berhasil Disimpan!';
        document.getElementById('hasilSub').textContent   = formatTgl(dataForm.tanggal) + ' · Gejala: ' + dataForm.gejala.join(', ');
        hc.style.display = 'block';
        hc.style.animation = 'none'; hc.offsetHeight; hc.style.animation = '';

        hc.scrollIntoView({ behavior: 'smooth', block: 'center' });
        resetForm();

    } catch (e) {
        alert('Koneksi error. Pastikan XAMPP berjalan.');
        console.error(e);
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save" style="margin-right:8px;"></i>Simpan Catatan';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
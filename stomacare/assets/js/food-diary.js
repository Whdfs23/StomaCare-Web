// ============================================================
//  food-diary.js
//  StomaCare – Jurnal Makan Harian
//  Tugas 2: Variabel dan Fungsi JavaScript
//  Aplikasi Sistem Web dan Seluler (Mobile) — Rombel 2
// ============================================================

// ── VARIABEL GLOBAL ──
var waktuDipilih = "";
var logHarian = [];
var idCounter = 0;

var nyeriKet = [
    "Tidak ada nyeri",
    "Sangat ringan",
    "Ringan",
    "Ringan–Sedang",
    "Sedang",
    "Sedang–Berat",
    "Berat",
    "Berat sekali",
    "Sangat Berat",
    "Hampir Tak Tertahankan",
    "Tak Tertahankan"
];

// ── INISIALISASI ──
window.addEventListener('load', function () {
    var today = new Date();

    var tgl = document.getElementById('fTanggal');
    if (tgl) tgl.value = today.toISOString().split('T')[0];

    var hd = document.getElementById('heroDate');
    if (hd) hd.textContent = today.toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

    updateNyeri(0);
});

// ── FUNGSI: Pilih waktu makan ──
function pilihWaktu(el) {
    document.querySelectorAll('.waktu-pill').forEach(function (p) {
        p.classList.remove('active');
    });
    el.classList.add('active');
    waktuDipilih = el.getAttribute('data-val');
    var err = document.getElementById('errWaktu');
    if (err) err.style.display = 'none';
}

// ── FUNGSI: Toggle checkbox gejala ──
// PENTING: pakai parameter e eksplisit, bukan global 'event'
function toggleGejala(el, e) {
    if (e) e.preventDefault();
    var cb = el.querySelector('input[type="checkbox"]');
    if (!cb) return;
    cb.checked = !cb.checked;
    if (cb.checked) {
        el.classList.add('checked');
    } else {
        el.classList.remove('checked');
    }
}

// ── FUNGSI: Ambil semua gejala yang dicentang ──
function ambilGejala() {
    var boxes = document.querySelectorAll('#gejalaPicker input[type="checkbox"]');
    var hasil = [];
    for (var i = 0; i < boxes.length; i++) {
        if (boxes[i].checked) hasil.push(boxes[i].value);
    }
    return hasil.length > 0 ? hasil : ["Belum dicatat"];
}

// ── FUNGSI: Update tampilan slider nyeri ──
function updateNyeri(val) {
    val = parseInt(val);
    var badge = document.getElementById('nyeriBadge');
    var label = document.getElementById('nyeriLabel');
    if (!badge || !label) return;
    badge.textContent = val;
    label.textContent = nyeriKet[val] || '';
    if (val <= 2) {
        badge.style.background = '#dcfce7'; badge.style.color = '#166534';
    } else if (val <= 5) {
        badge.style.background = '#fef9c3'; badge.style.color = '#854d0e';
    } else if (val <= 7) {
        badge.style.background = '#fed7aa'; badge.style.color = '#9a3412';
    } else {
        badge.style.background = '#fee2e2'; badge.style.color = '#991b1b';
    }
}

// ── FUNGSI: Set/clear error per field ──
function setError(inputId, errId, show) {
    var inp = document.getElementById(inputId);
    var err = document.getElementById(errId);
    if (!inp || !err) return;
    if (show) {
        inp.classList.add('err');
        err.style.display = 'block';
    } else {
        inp.classList.remove('err');
        err.style.display = 'none';
    }
}

// ── FUNGSI: Validasi form ──
function validasiForm(data) {
    var valid = true;

    if (!waktuDipilih) {
        var errW = document.getElementById('errWaktu');
        if (errW) errW.style.display = 'block';
        valid = false;
    }
    if (!data.tanggal) {
        setError('fTanggal', 'errTanggal', true); valid = false;
    } else {
        setError('fTanggal', 'errTanggal', false);
    }
    if (!data.makanan) {
        setError('fMakanan', 'errMakanan', true); valid = false;
    } else {
        setError('fMakanan', 'errMakanan', false);
    }
    return valid;
}

// ── FUNGSI: Format tanggal ke Bahasa Indonesia ──
function formatTgl(str) {
    if (!str) return '—';
    return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
}

// ── FUNGSI: Warna badge nyeri ──
function nyeriStyle(v) {
    v = parseInt(v);
    if (v <= 2) return { bg: '#dcfce7', color: '#166534' };
    if (v <= 5) return { bg: '#fef9c3', color: '#854d0e' };
    if (v <= 7) return { bg: '#fed7aa', color: '#9a3412' };
    return { bg: '#fee2e2', color: '#991b1b' };
}

// ── FUNGSI: Render hasil di halaman yang sama ──
function renderHasil(d) {
    var gTags = d.gejala.map(function (g) {
        return '<span class="gtag">' + g + '</span>';
    }).join('');
    var ns = nyeriStyle(d.nyeri);

    var grid = document.getElementById('hasilGrid');
    if (!grid) return;

    grid.innerHTML =
        '<div class="hasil-cell"><div class="hc-lbl">Waktu Makan</div>'
        + '<div class="hc-val" style="font-weight:800;">' + d.waktu + ' — ' + formatTgl(d.tanggal) + '</div></div>'

        + '<div class="hasil-cell"><div class="hc-lbl">Porsi</div>'
        + '<div class="hc-val">' + (d.porsi || '—') + '</div></div>'

        + '<div class="hasil-cell full"><div class="hc-lbl">Makanan</div>'
        + '<div class="hc-val">' + d.makanan + '</div></div>'

        + '<div class="hasil-cell full"><div class="hc-lbl">Minuman</div>'
        + '<div class="hc-val">' + (d.minuman || '—') + '</div></div>'

        + '<div class="hasil-cell full"><div class="hc-lbl">Gejala Setelah Makan</div>'
        + '<div class="hc-val" style="margin-top:4px;">' + gTags + '</div></div>'

        + '<div class="hasil-cell"><div class="hc-lbl">Tingkat Nyeri</div>'
        + '<div class="hc-val"><span style="display:inline-flex;align-items:center;gap:5px;padding:3px 12px;'
        + 'border-radius:50px;font-weight:800;font-size:12px;background:' + ns.bg + ';color:' + ns.color + ';">'
        + d.nyeri + '/10 — ' + nyeriKet[parseInt(d.nyeri)] + '</span></div></div>'

        + '<div class="hasil-cell"><div class="hc-lbl">Kondisi Lambung</div>'
        + '<div class="hc-val">' + (d.kondisi || '—') + '</div></div>'

        + (d.catatan
            ? '<div class="hasil-cell full"><div class="hc-lbl">Catatan</div>'
            + '<div class="hc-val">' + d.catatan + '</div></div>'
            : '');
}

// ── FUNGSI: Render riwayat log ──
function renderRiwayat() {
    var countEl = document.getElementById('riwayatCount');
    var listEl = document.getElementById('riwayatList');
    if (!countEl || !listEl) return;

    countEl.textContent = logHarian.length;

    if (logHarian.length === 0) {
        listEl.innerHTML =
            '<div style="text-align:center;padding:32px;color:#9ca3af;font-size:13px;">'
            + '<i class="fas fa-book-open" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db;"></i>'
            + 'Belum ada catatan hari ini.</div>';
        return;
    }

    var wkCls = { Pagi: 'wk-pagi', Siang: 'wk-siang', Malam: 'wk-malam', Camilan: 'wk-camilan' };
    var html = '';
    for (var i = 0; i < logHarian.length; i++) {
        var d = logHarian[i];
        var ns = nyeriStyle(d.nyeri);
        html +=
            '<div class="log-item">'
            + '<span class="wk-pill ' + (wkCls[d.waktu] || '') + '">' + d.waktu + '</span>'
            + '<div>'
            + '<div class="log-food">' + d.makanan + '</div>'
            + '<div class="log-sub">'
            + (d.minuman ? d.minuman + ' · ' : '')
            + 'Gejala: ' + d.gejala.join(', ')
            + '</div>'
            + '</div>'
            + '<span class="log-nyeri-tag" style="background:' + ns.bg + ';color:' + ns.color + ';">'
            + 'Nyeri ' + d.nyeri + '/10'
            + '</span>'
            + '<button class="btn-del" onclick="hapusCatatan(' + d.id + ')">'
            + '<i class="fas fa-trash-alt"></i>'
            + '</button>'
            + '</div>';
    }
    listEl.innerHTML = html;
}

// ── FUNGSI UTAMA: Simpan catatan ──
function simpanCatatan() {
    var dataForm = {
        tanggal: document.getElementById('fTanggal').value,
        waktu: waktuDipilih,
        makanan: document.getElementById('fMakanan').value.trim(),
        minuman: document.getElementById('fMinuman').value.trim(),
        porsi: document.getElementById('fPorsi').value,
        gejala: ambilGejala(),
        nyeri: document.getElementById('fNyeri').value,
        kondisi: document.getElementById('fKondisi').value,
        catatan: document.getElementById('fCatatan').value.trim()
    };

    if (!validasiForm(dataForm)) return;

    dataForm.id = ++idCounter;
    logHarian.push(dataForm);

    renderHasil(dataForm);

    var hc = document.getElementById('hasilCard');
    var hj = document.getElementById('hasilJudul');
    var hs = document.getElementById('hasilSub');
    if (!hc) return;

    if (hj) hj.textContent = 'Catatan ' + dataForm.waktu + ' Berhasil Disimpan!';
    if (hs) hs.textContent = formatTgl(dataForm.tanggal) + ' · Gejala: ' + dataForm.gejala.join(', ');

    hc.style.display = 'block';
    hc.style.animation = 'none';
    hc.offsetHeight;
    hc.style.animation = '';

    var rc = document.getElementById('riwayatCard');
    if (rc) rc.style.display = 'block';
    renderRiwayat();

    hc.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── FUNGSI: Hapus catatan dari riwayat ──
function hapusCatatan(id) {
    logHarian = logHarian.filter(function (d) { return d.id !== id; });
    renderRiwayat();
    if (logHarian.length === 0) {
        var hc = document.getElementById('hasilCard');
        var rc = document.getElementById('riwayatCard');
        if (hc) hc.style.display = 'none';
        if (rc) rc.style.display = 'none';
    }
}

// ── FUNGSI: Reset form ──
function resetForm() {
    var ids = ['fMakanan', 'fMinuman', 'fCatatan'];
    ids.forEach(function (id) {
        var el = document.getElementById(id);
        if (el) { el.value = ''; el.classList.remove('err'); }
    });

    var tgl = document.getElementById('fTanggal');
    if (tgl) tgl.value = new Date().toISOString().split('T')[0];

    var porsi = document.getElementById('fPorsi');
    if (porsi) porsi.value = '';

    var kondisi = document.getElementById('fKondisi');
    if (kondisi) kondisi.value = '';

    var nyeri = document.getElementById('fNyeri');
    if (nyeri) nyeri.value = 0;
    updateNyeri(0);

    waktuDipilih = '';
    document.querySelectorAll('.waktu-pill').forEach(function (p) {
        p.classList.remove('active');
    });

    document.querySelectorAll('#gejalaPicker .gejala-item').forEach(function (el) {
        el.classList.remove('checked');
        var cb = el.querySelector('input');
        if (cb) cb.checked = false;
    });

    ['errWaktu', 'errTanggal', 'errMakanan'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    var hc = document.getElementById('hasilCard');
    if (hc) hc.style.display = 'none';
}

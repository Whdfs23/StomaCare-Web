<?php
// ============================================================
//  setup.php — StomaCare Image Setup
//  Akses: http://localhost/stomacare/setup.php
//  HAPUS file ini setelah selesai!
// ============================================================

$targetDir  = __DIR__ . '/assets/img/';
$messages   = [];
$imgDir     = '';

// ── Buat folder assets/img kalau belum ada ──
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}
// ── Auto-copy dari folder img/ lama jika ditemukan ──
$knownMappings = [
    // Nama file persis dari proyek lama (sesuai screenshot)
    'Gambar 1(logo_stomacare).png'    => 'logo.png',
    'Gambar 1 (logo_stomacare).png'   => 'logo.png',
    'Gambar_1_logo_stomacare_.png'    => 'logo.png',
    'Gambar_1(logo_stomacare).png'    => 'logo.png',
    'Gambar 2 Background.png'         => 'blob.png',
    'Gambar  2 Background.png'        => 'blob.png',   // 2 spasi
    'Gambar_2_Background.png'         => 'blob.png',
    'Gambar__2_Background.png'        => 'blob.png',   // 2 underscore
    'Gambar 3 Pelapis Background.png' => 'stomach.png',
    'Gambar_3_Pelapis_Background.png' => 'stomach.png',
    // Fallback nama lama
    'image 1 (logo_stomacare).png'    => 'logo.png',
    'Vector (1).png'                   => 'stomach.png',
    'fluent_shape-organic-16-filled (1).png' => 'blob.png',
];

// Cari di folder img/ satu level di atas (proyek lama)
$searchDirs = [
    dirname(__DIR__) . '/img',
    __DIR__ . '/img',
    dirname(__DIR__) . '/Assets',
    __DIR__ . '/Assets',
    dirname(__DIR__) . '/assets/img',
];

foreach ($searchDirs as $dir) {
    if (!is_dir($dir)) continue;
    $files = @scandir($dir);
    if (!$files) continue;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        if (isset($knownMappings[$file])) {
            $dest = $targetDir . $knownMappings[$file];
            if (!file_exists($dest)) {
                @copy($dir . '/' . $file, $dest);
            }
        }
    }
}



// ── Cari folder Assets secara rekursif di htdocs ──
function scanForAssets(string $startDir, int $depth = 0): array {
    $found = [];
    if ($depth > 4) return $found;
    try {
        $items = @scandir($startDir);
        if (!$items) return $found;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $fullPath = $startDir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($fullPath)) {
                $lower = strtolower($item);
                if (in_array($lower, ['assets', 'asset', 'images', 'img', 'image'])) {
                    $found[] = $fullPath;
                }
                $found = array_merge($found, scanForAssets($fullPath, $depth + 1));
            }
        }
    } catch (Exception $e) {}
    return $found;
}

// ── Handle upload manual ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gambar'])) {
    $targetNames = [
        'logo'    => 'logo.png',
        'blob'    => 'blob.png',
        'stomach' => 'stomach.png',
    ];
    $jenis = $_POST['jenis'] ?? '';
    $file  = $_FILES['gambar'];

    if ($file['error'] === UPLOAD_ERR_OK && isset($targetNames[$jenis])) {
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
        if (in_array($ext, $allowed)) {
            $dest = $targetDir . $targetNames[$jenis];
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $messages[] = ['ok', "✅ Berhasil upload <strong>{$targetNames[$jenis]}</strong>"];
            } else {
                $messages[] = ['err', "❌ Gagal menyimpan file. Cek permission folder assets/img/"];
            }
        } else {
            $messages[] = ['err', "❌ Format file tidak didukung. Gunakan PNG/JPG/SVG/WEBP"];
        }
    }
}

// ── Auto-scan folder Assets di htdocs ──
$htdocs       = dirname(__DIR__);
$foundFolders = scanForAssets($htdocs);

// Status file yang sudah ada
$statusFiles = [
    'logo.png'    => file_exists($targetDir . 'logo.png'),
    'blob.png'    => file_exists($targetDir . 'blob.png'),
    'stomach.png' => file_exists($targetDir . 'stomach.png'),
];
$allDone = array_sum($statusFiles) === 3;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StomaCare Setup</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f9f4; min-height: 100vh; padding: 32px 16px; }
        .wrap { max-width: 680px; margin: 0 auto; }
        h1 { color: #1a3c30; font-size: 24px; margin-bottom: 4px; }
        .sub { color: #6b7280; font-size: 13px; margin-bottom: 24px; }
        .card { background: #fff; border-radius: 16px; padding: 24px; margin-bottom: 20px;
                box-shadow: 0 2px 16px rgba(26,60,48,.08); border: 1px solid #e0f0e8; }
        .card h2 { font-size: 15px; color: #1a3c30; margin-bottom: 16px; display:flex; align-items:center; gap:8px; }
        .status-row { display: flex; align-items: center; gap: 10px; padding: 10px 0;
                      border-bottom: 1px solid #f0f9f4; }
        .status-row:last-child { border-bottom: none; }
        .dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .dot.ok  { background: #22c55e; }
        .dot.err { background: #ef4444; }
        .fname { font-size: 13px; font-weight: 600; color: #1a3c30; flex: 1; }
        .badge-ok  { font-size: 11px; background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 50px; }
        .badge-err { font-size: 11px; background: #fee2e2; color: #991b1b; padding: 2px 10px; border-radius: 50px; }
        .upload-form { margin-top: 12px; }
        .field { margin-bottom: 14px; }
        label { display: block; font-size: 11px; font-weight: 700; color: #374151;
                text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
        select, input[type=file] { width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db;
                                   border-radius: 10px; font-size: 13px; outline: none;
                                   background: #f9fafb; }
        select:focus { border-color: #458b68; }
        .btn { background: #458b68; color: #fff; border: none; border-radius: 10px;
               padding: 11px 24px; font-size: 14px; font-weight: 700; cursor: pointer;
               transition: background .2s; }
        .btn:hover { background: #1a3c30; }
        .btn-done { background: #22c55e; }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
        .alert.ok  { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.err { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .tip { background: #fef9c3; border: 1px solid #fde68a; border-radius: 10px;
               padding: 12px 16px; font-size: 12px; color: #92400e; line-height: 1.6; }
        .tip strong { display: block; margin-bottom: 4px; }
        .folder-list { font-size: 12px; color: #6b7280; margin-top: 8px; }
        .folder-list li { padding: 3px 0; }
        code { background: #f3f4f6; padding: 1px 6px; border-radius: 4px; font-size: 12px; }
        .success-banner { background: linear-gradient(135deg, #1a3c30, #458b68);
                          color: #fff; border-radius: 16px; padding: 24px; text-align: center;
                          margin-bottom: 20px; }
        .success-banner h2 { font-size: 20px; margin-bottom: 8px; }
        .success-banner p  { opacity: .8; font-size: 13px; }
        a.btn { display: inline-block; text-decoration: none; margin-top: 12px; }
    </style>
</head>
<body>
<div class="wrap">

    <h1>🛠 StomaCare Setup</h1>
    <p class="sub">Upload gambar yang dibutuhkan untuk tampilan website</p>

    <?php foreach ($messages as [$type, $msg]): ?>
    <div class="alert <?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>

    <?php if ($allDone): ?>
    <!-- Semua gambar sudah ada -->
    <div class="success-banner">
        <h2>✅ Semua Gambar Sudah Terpasang!</h2>
        <p>Website StomaCare siap digunakan sepenuhnya.</p>
        <a href="index.php" class="btn" style="background:rgba(255,255,255,.2);margin-top:16px;">
            Buka StomaCare →
        </a>
    </div>
    <div class="card">
        <p style="font-size:13px;color:#6b7280;">
            ⚠️ <strong>Jangan lupa hapus file <code>setup.php</code> ini</strong> setelah selesai, karena bisa jadi celah keamanan.
        </p>
    </div>
    <?php else: ?>

    <!-- Status gambar -->
    <div class="card">
        <h2>📋 Status Gambar</h2>
        <?php foreach ($statusFiles as $fname => $exists): ?>
        <div class="status-row">
            <div class="dot <?= $exists ? 'ok' : 'err' ?>"></div>
            <span class="fname"><?= $fname ?></span>
            <?php if ($exists): ?>
            <span class="badge-ok">✓ Sudah ada</span>
            <?php else: ?>
            <span class="badge-err">Belum diupload</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Form upload -->
    <div class="card">
        <h2>📤 Upload Gambar Manual</h2>
        <div class="tip">
            <strong>Butuh 3 file gambar:</strong>
            <b>Logo</b> → file logo StomaCare (lingkaran hijau karakter lambung)<br>
            <b>Blob</b> → background bentuk organik hijau di hero<br>
            <b>Stomach</b> → ilustrasi lambung di pojok kanan hero
        </div>

        <form class="upload-form" method="POST" enctype="multipart/form-data">
            <div class="field" style="margin-top:16px;">
                <label>Jenis Gambar</label>
                <select name="jenis" required>
                    <option value="">— Pilih —</option>
                    <option value="logo"    <?= !$statusFiles['logo.png']    ? '' : 'disabled' ?>>
                        Logo StomaCare <?= $statusFiles['logo.png'] ? '(✓ sudah ada)' : '' ?>
                    </option>
                    <option value="blob"    <?= !$statusFiles['blob.png']    ? '' : 'disabled' ?>>
                        Blob Background <?= $statusFiles['blob.png'] ? '(✓ sudah ada)' : '' ?>
                    </option>
                    <option value="stomach" <?= !$statusFiles['stomach.png'] ? '' : 'disabled' ?>>
                        Stomach Illustration <?= $statusFiles['stomach.png'] ? '(✓ sudah ada)' : '' ?>
                    </option>
                </select>
            </div>
            <div class="field">
                <label>Pilih File (PNG/JPG/SVG/WEBP)</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Upload Gambar</button>
        </form>
    </div>

    <!-- Cara copy manual -->
    <div class="card">
        <h2>📁 Atau Copy Manual</h2>
        <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">
            Kalau tahu lokasi file gambarnya, copy langsung ke folder ini:
        </p>
        <code style="display:block;padding:10px;background:#f3f4f6;border-radius:8px;font-size:12px;word-break:break-all;">
            <?= htmlspecialchars($targetDir) ?>
        </code>
        <p style="font-size:13px;color:#6b7280;margin-top:10px;margin-bottom:6px;">
            Dengan nama file persis:
        </p>
        <ul style="font-size:13px;color:#374151;padding-left:20px;line-height:1.8;">
            <li><code>logo.png</code> — logo StomaCare</li>
            <li><code>blob.png</code> — background organik hijau</li>
            <li><code>stomach.png</code> — ilustrasi lambung</li>
        </ul>

        <?php if (!empty($foundFolders)): ?>
        <p style="font-size:13px;color:#6b7280;margin-top:14px;margin-bottom:4px;">
            📂 Folder gambar yang ditemukan otomatis di htdocs:
        </p>
        <ul class="folder-list" style="padding-left:18px;">
            <?php foreach (array_slice($foundFolders, 0, 8) as $folder): ?>
            <li><code><?= htmlspecialchars($folder) ?></code></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <!-- Bisa lanjut tanpa gambar -->
    <div class="card" style="text-align:center;">
        <p style="font-size:13px;color:#6b7280;margin-bottom:12px;">
            Mau lanjut dulu tanpa gambar? Website tetap bisa jalan, gambar bisa diupload nanti.
        </p>
        <a href="index.php" class="btn" style="display:inline-block;text-decoration:none;">
            Lanjut ke StomaCare →
        </a>
    </div>

    <?php endif; ?>
</div>
</body>
</html>

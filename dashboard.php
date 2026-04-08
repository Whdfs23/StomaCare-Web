<?php
// ============================================================
//  dashboard.php — Halaman Dashboard & Risk Score
// ============================================================

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
requireLogin();

require_once __DIR__ . '/config/db.php';
$user = currentUser();
$userId = $user['id'];

// ============================================================
//  1. DATA GRAFIK & RISK SCORE (7 Hari Terakhir)
// ============================================================
$labels = [];
$riwayatMingguan = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($date));
    $riwayatMingguan[$date] = ['total_score' => 0, 'count' => 0];
}

$stmt = $conn->prepare(
    "SELECT id, tanggal, waktu_makan, makanan, gejala, nyeri 
     FROM food_diary 
     WHERE user_id = ? 
     ORDER BY tanggal DESC, id DESC"
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$skorHariIni = 0;
$catatanHariIni = 0;
$dataSemua = [];

// Array untuk analitik tambahan
$frekuensiGejala = [];
$makananPemicu = [];

while ($row = $result->fetch_assoc()) {
    $dataSemua[] = $row;
    $tgl = $row['tanggal'];
    $gejalaArr = json_decode($row['gejala'], true) ?? [];
    $nyeri = (int)$row['nyeri'];

    // Hitung Skor (hanya untuk 7 hari terakhir ke dalam grafik)
    if (isset($riwayatMingguan[$tgl])) {
        $score = $nyeri * 5;
        foreach ($gejalaArr as $g) {
            if ($g !== 'Tidak Ada' && $g !== 'Belum dicatat') {
                $score += 10;
                // Hitung frekuensi gejala
                if (!isset($frekuensiGejala[$g])) $frekuensiGejala[$g] = 0;
                $frekuensiGejala[$g]++;
            }
        }
        if ($score > 100) $score = 100;

        $riwayatMingguan[$tgl]['total_score'] += $score;
        $riwayatMingguan[$tgl]['count'] += 1;

        if ($tgl === date('Y-m-d')) {
            $skorHariIni += $score;
            $catatanHariIni++;
        }
    }

    // Deteksi Makanan Pemicu (Nyeri >= 5)
    if ($nyeri >= 5) {
        $mkn = trim(strtolower($row['makanan']));
        if (!in_array($row['makanan'], $makananPemicu) && $mkn !== '') {
            $makananPemicu[] = $row['makanan'];
        }
    }
}
$stmt->close();

// Proses Data Grafik
$dataGrafik = [];
foreach ($riwayatMingguan as $tgl => $data) {
    $dataGrafik[] = $data['count'] > 0 ? round($data['total_score'] / $data['count']) : 0;
}

// Proses Status Hari Ini
$rataSkorHariIni = $catatanHariIni > 0 ? round($skorHariIni / $catatanHariIni) : 0;
$statusTeks = "Aman";
$statusWarna = "text-green-600";
$statusBg = "bg-green-100";
$pesanStatus = "Kondisi lambungmu terpantau baik hari ini. Pertahankan pola makanmu!";

if ($rataSkorHariIni >= 70) {
    $statusTeks = "Bahaya";
    $statusWarna = "text-red-600";
    $statusBg = "bg-red-100";
    $pesanStatus = "Gejala dan nyerimu cukup parah. Segera periksakan ke dokter!";
} elseif ($rataSkorHariIni >= 40) {
    $statusTeks = "Waspada";
    $statusWarna = "text-yellow-600";
    $statusBg = "bg-yellow-100";
    $pesanStatus = "Ada indikasi asam lambung naik. Hindari makanan pedas dan asam.";
}

// Urutkan gejala terbanyak
arsort($frekuensiGejala);
$topGejala = array_slice($frekuensiGejala, 0, 3, true); // Ambil top 3
$recentLogs = array_slice($dataSemua, 0, 4); // Ambil 4 catatan terakhir
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="pt-32 pb-20 px-6 md:px-20 max-w-6xl mx-auto min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 fade-in">
        <div>
            <h1 class="text-3xl font-bold text-stoma-dark">Dashboard & Monitoring</h1>
            <p class="text-gray-500 mt-2">Ringkasan aktivitas dan <span class="font-semibold text-stoma-green">Risk Score</span> lambungmu.</p>
        </div>
        <a href="<?= url('food-diary.php') ?>" class="bg-stoma-green hover:bg-stoma-dark text-white px-6 py-3.5 rounded-2xl font-bold transition-colors flex items-center gap-2 shadow-lg w-fit btn-hover">
            <i class="fas fa-plus"></i> Tambah Catatan Baru
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-8">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 fade-in flex flex-col justify-center items-center text-center">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Risk Score Hari Ini</h2>
            <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 <?= str_replace('bg-', 'border-', $statusBg) ?> mb-4">
                <span class="text-4xl font-extrabold text-stoma-dark"><?= $rataSkorHariIni ?></span>
            </div>
            <span class="px-4 py-1.5 rounded-full text-sm font-bold <?= $statusBg ?> <?= $statusWarna ?> mb-3">
                Status: <?= $statusTeks ?>
            </span>
            <p class="text-xs text-gray-500 leading-relaxed"><?= $pesanStatus ?></p>
            <a href="<?= url('food-diary.php') ?>" class="mt-6 text-sm text-stoma-green font-bold hover:underline">
                + Tambah Catatan
            </a>
        </div>

        <div class="md:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 fade-in">
            <h2 class="text-lg font-bold text-stoma-dark mb-6 flex items-center gap-2">
                <i class="fas fa-chart-line text-stoma-green"></i> Grafik Mingguan
            </h2>
            <div class="relative h-64 w-full">
                <canvas id="riskChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        
        <div class="md:col-span-2 grid sm:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 fade-in">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-head-side-cough text-yellow-500"></i> Sering Dirasakan
                </h3>
                <?php if (empty($topGejala)): ?>
                    <p class="text-sm text-gray-400 italic">Belum ada data gejala yang dicatat.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($topGejala as $nama => $jumlah): ?>
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="font-semibold text-sm text-stoma-dark"><?= htmlspecialchars($nama) ?></span>
                            <span class="text-xs font-bold bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full"><?= $jumlah ?> kali</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 fade-in">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-500"></i> Suspect Pemicu Nyeri
                </h3>
                <p class="text-xs text-gray-400 mb-4">Makanan yang dimakan saat nyeri tinggi (&#8805; 5).</p>
                <?php if (empty($makananPemicu)): ?>
                    <p class="text-sm text-green-600 font-medium bg-green-50 p-3 rounded-xl border border-green-100">
                        <i class="fas fa-check-circle mr-1"></i> Belum ada indikasi pemicu.
                    </p>
                <?php else: ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (array_slice($makananPemicu, 0, 6) as $mkn): ?>
                            <span class="bg-red-50 text-red-600 border border-red-100 text-xs font-bold px-3 py-1.5 rounded-full">
                                <?= htmlspecialchars($mkn) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 fade-in">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-history text-stoma-green"></i> Catatan Terakhir
            </h3>
            <?php if (empty($recentLogs)): ?>
                <p class="text-sm text-gray-400 italic text-center py-4">Belum ada riwayat makan.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recentLogs as $log): 
                        $waktuWarna = match($log['waktu_makan']) {
                            'Pagi' => 'bg-yellow-100 text-yellow-700',
                            'Siang' => 'bg-blue-100 text-blue-700',
                            'Malam' => 'bg-indigo-100 text-indigo-700',
                            default => 'bg-pink-100 text-pink-700'
                        };
                    ?>
                    <div class="flex items-start gap-3 border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                        <div class="mt-1 <?= $waktuWarna ?> text-[10px] font-bold px-2 py-1 rounded-md min-w-[50px] text-center">
                            <?= $log['waktu_makan'] ?>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-stoma-dark leading-tight"><?= htmlspecialchars($log['makanan']) ?></p>
                            <p class="text-[11px] text-gray-400 mt-0.5"><?= date('d M Y', strtotime($log['tanggal'])) ?> · Nyeri: <?= $log['nyeri'] ?>/10</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <a href="<?= url('riwayat.php') ?>" class="block text-center text-xs font-bold text-stoma-green bg-stoma-pale rounded-xl py-2 mt-4 hover:bg-stoma-light transition-colors">
                Lihat Semua Riwayat
            </a>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('riskChart').getContext('2d');
    
    const labels = <?= json_encode($labels) ?>;
    const dataSkor = <?= json_encode($dataGrafik) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Risk Score',
                data: dataSkor,
                borderColor: '#458b68',
                backgroundColor: 'rgba(69, 139, 104, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#458b68',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: '#f0f9f4' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a3c30',
                    padding: 12,
                    callbacks: {
                        label: function(context) { return 'Skor Risiko: ' + context.parsed.y; }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
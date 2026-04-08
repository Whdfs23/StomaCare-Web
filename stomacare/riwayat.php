<?php
// ============================================================
//  riwayat.php — Halaman Riwayat Keseluruhan
// ============================================================

$pageTitle  = 'Riwayat';
$activePage = 'riwayat';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
requireLogin();

require_once __DIR__ . '/config/db.php';
$user = currentUser();
$userId = $user['id'];

// Ambil semua data riwayat diurutkan dari yang terbaru
$stmt = $conn->prepare(
    'SELECT id, tanggal, waktu_makan, makanan, minuman, porsi, gejala, nyeri, kondisi, catatan
     FROM food_diary
     WHERE user_id = ?
     ORDER BY tanggal DESC, FIELD(waktu_makan,"Pagi","Siang","Malam","Camilan") ASC'
);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$riwayatGrouped = [];
while ($row = $result->fetch_assoc()) {
    $row['gejala'] = json_decode($row['gejala'], true) ?? ['Belum dicatat'];
    $riwayatGrouped[$row['tanggal']][] = $row;
}
$stmt->close();

function getWarnaNyeri($nyeri) {
    if ($nyeri <= 2) return 'bg-green-100 text-green-800';
    if ($nyeri <= 5) return 'bg-yellow-100 text-yellow-800';
    if ($nyeri <= 7) return 'bg-orange-100 text-orange-800';
    return 'bg-red-100 text-red-800';
}
?>

<div class="pt-32 pb-20 px-6 md:px-20 max-w-5xl mx-auto min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 fade-in">
        <div>
            <h1 class="text-3xl font-bold text-stoma-dark">Riwayat Makanan</h1>
            <p class="text-gray-500 mt-2">Pantau semua catatan makanan dan gejala yang pernah kamu masukkan.</p>
        </div>
        <a href="<?= url('food-diary.php') ?>" class="bg-stoma-green hover:bg-stoma-dark text-white px-6 py-3.5 rounded-2xl font-bold transition-colors flex items-center gap-2 shadow-lg w-fit btn-hover">
            <i class="fas fa-plus"></i> Tambah Catatan Baru
        </a>
    </div>

    <?php if (empty($riwayatGrouped)): ?>
        <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-gray-100 fade-in">
            <div class="w-20 h-20 bg-stoma-pale rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-book-open text-3xl text-stoma-green"></i>
            </div>
            <h3 class="text-xl font-bold text-stoma-dark mb-2">Belum ada riwayat</h3>
            <p class="text-gray-500 text-sm max-w-md mx-auto">Mulai pantau kesehatan lambungmu dengan mencatat makanan pertamamu hari ini.</p>
        </div>
    <?php else: ?>
        <div class="space-y-8">
            <?php foreach ($riwayatGrouped as $tanggal => $items): ?>
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 fade-in">
                    <h2 class="text-lg font-bold text-stoma-dark mb-6 pb-4 border-b border-gray-100 flex items-center gap-3">
                        <i class="fas fa-calendar-day text-stoma-green"></i>
                        <?= date('d F Y', strtotime($tanggal)) ?>
                    </h2>
                    
                    <div class="space-y-4">
                        <?php foreach ($items as $item): 
                            $waktuWarna = match($item['waktu_makan']) {
                                'Pagi' => 'bg-yellow-100 text-yellow-700',
                                'Siang' => 'bg-blue-100 text-blue-700',
                                'Malam' => 'bg-indigo-100 text-indigo-700',
                                default => 'bg-pink-100 text-pink-700'
                            };
                        ?>
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-2xl hover:bg-stoma-pale/50 transition-colors border border-transparent hover:border-stoma-light/30">
                                <div class="flex items-start gap-4">
                                    <div class="<?= $waktuWarna ?> text-xs font-bold px-3 py-1.5 rounded-lg min-w-[70px] text-center mt-1">
                                        <?= $item['waktu_makan'] ?>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-stoma-dark text-base"><?= htmlspecialchars($item['makanan']) ?></h3>
                                        <?php if (!empty($item['minuman'])): ?>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-glass-water text-stoma-green/60 mr-1"></i> <?= htmlspecialchars($item['minuman']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="flex flex-wrap gap-2 mt-2.5">
                                            <?php foreach ($item['gejala'] as $g): ?>
                                                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                                    <?= htmlspecialchars($g) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center md:flex-col md:items-end gap-3 mt-2 md:mt-0">
                                    <span class="<?= getWarnaNyeri($item['nyeri']) ?> text-xs font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap">
                                        Nyeri: <?= $item['nyeri'] ?>/10
                                    </span>
                                    <?php if (!empty($item['kondisi'])): ?>
                                        <span class="text-[11px] text-gray-400 font-semibold border border-gray-200 px-2 py-0.5 rounded-md">
                                            Kondisi: <?= htmlspecialchars($item['kondisi']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<!-- Laporan Kinerja Kasir per Shift -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Kinerja Kasir per Shift</h1>
            <p class="text-gray-500 mt-1">Analisis performa kasir berdasarkan transaksi per shift</p>
        </div>
    </div>

    <!-- Filter Date Range -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <?php 
    $totalShifts = 0;
    $totalTransactions = 0;
    $totalSales = 0;
    foreach ($userPerformance as $up) {
        $totalShifts += $up['total_shifts'];
        $totalTransactions += $up['total_transactions'];
        $totalSales += $up['total_sales'];
    }
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Shift</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= number_format($totalShifts) ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= number_format($totalTransactions) ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <p class="text-3xl font-bold text-[#398263] mt-1">Rp <?= number_format($totalSales, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Rata-rata/Shift</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp <?= $totalShifts > 0 ? number_format($totalSales / $totalShifts, 0, ',', '.') : '0' ?></p>
        </div>
    </div>

    <!-- Kasir Performance Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Kinerja per Kasir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Shift</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Penjualan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tunai</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Non-Tunai</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Rata/Transaksi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Selisih Kas Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($userPerformance)): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">Tidak ada data shift pada periode ini</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($userPerformance as $up): 
                            $avgPerTrx = $up['total_transactions'] > 0 ? $up['total_sales'] / $up['total_transactions'] : 0;
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($up['user_name']) ?></td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600"><?= $up['total_shifts'] ?></td>
                            <td class="px-4 py-3 text-center text-sm font-mono tabular-nums text-gray-600"><?= number_format($up['total_transactions']) ?></td>
                            <td class="px-4 py-3 text-right font-bold tabular-nums text-gray-900">Rp <?= number_format($up['total_sales'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-right tabular-nums text-gray-700">Rp <?= number_format($up['total_cash'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-right tabular-nums text-gray-700">Rp <?= number_format($up['total_non_cash'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-right tabular-nums text-gray-700">Rp <?= number_format($avgPerTrx, 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($up['total_difference'] !== 0): ?>
                                    <span class="font-mono tabular-nums <?= $up['total_difference'] > 0 ? 'text-blue-600' : 'text-red-600' ?>">
                                        Rp <?= number_format($up['total_difference'], 0, ',', '.') ?>
                                        <?= $up['total_difference'] > 0 ? ' (Lebih)' : ' (Kurang)' ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-green-600 font-medium">Rp 0 ✓</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Shifts -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Detail Semua Shift</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Modal Awal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Penjualan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($shifts)): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">Tidak ada data shift</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($shifts as $shift): 
                            $summary = $shift['summary'] ?? [];
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($shift['user_name']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y', strtotime($shift['start_time'])) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <?= date('H:i', strtotime($shift['start_time'])) ?> - 
                                <?= $shift['end_time'] ? date('H:i', strtotime($shift['end_time'])) : 'Buka' ?>
                            </td>
                            <td class="px-4 py-3 text-right font-mono tabular-nums text-gray-900">Rp <?= number_format($shift['starting_cash'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center tabular-nums text-gray-600"><?= $summary['total_transactions'] ?? 0 ?></td>
                            <td class="px-4 py-3 text-right font-bold tabular-nums text-gray-900">Rp <?= number_format($summary['total_amount'] ?? 0, 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($shift['status'] === 'open'): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Terbuka</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Tertutup</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($shift['difference'] !== null && $shift['difference'] !== 0): ?>
                                    <span class="font-mono tabular-nums <?= $shift['difference'] > 0 ? 'text-blue-600' : 'text-red-600' ?>">
                                        Rp <?= number_format($shift['difference'], 0, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-green-600 font-medium">Rp 0</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
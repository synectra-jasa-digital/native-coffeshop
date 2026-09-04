<!-- Laporan Penjualan Harian -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan Harian</h1>
            <p class="text-gray-500 mt-1">Ringkasan transaksi dan pendapatan per hari</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASE_URL ?>/laporan/penjualan-harian?date=<?= date('Y-m-d', strtotime('-1 day')) ?>" class="px-3 py-1.5 border border-gray-300 text-gray-600 rounded hover:bg-gray-50 text-sm">← Kemarin</a>
            <a href="<?= BASE_URL ?>/laporan/penjualan-harian?date=<?= date('Y-m-d') ?>" class="px-3 py-1.5 bg-gray-800 text-white rounded hover:bg-gray-700 text-sm">Hari Ini</a>
            <a href="<?= BASE_URL ?>/laporan/penjualan-harian?date=<?= date('Y-m-d', strtotime('+1 day')) ?>" class="px-3 py-1.5 border border-gray-300 text-gray-600 rounded hover:bg-gray-50 text-sm">Besok →</a>
        </div>
    </div>

    <!-- Date Picker -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-end gap-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end" x-data="{ reportType: '<?= isset($_GET['type']) ? $_GET['type'] : 'daily' ?>' }">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Laporan</label>
                <select name="type" x-model="reportType" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    <option value="daily" <?= (isset($_GET['type']) && $_GET['type'] == 'daily' || !isset($_GET['type'])) ? 'selected' : '' ?>>Harian</option>
                    <option value="monthly" <?= (isset($_GET['type']) && $_GET['type'] == 'monthly') ? 'selected' : '' ?>>Bulanan</option>
                    <option value="yearly" <?= (isset($_GET['type']) && $_GET['type'] == 'yearly') ? 'selected' : '' ?>>Tahunan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                <!-- Input Tanggal Harian -->
                <input x-show="reportType === 'daily'" type="date" name="date_daily" value="<?= htmlspecialchars($_GET['date_daily'] ?? date('Y-m-d')) ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                
                <!-- Input Tanggal Bulanan -->
                <input x-show="reportType === 'monthly'" type="month" name="date_monthly" value="<?= htmlspecialchars($_GET['date_monthly'] ?? date('Y-m')) ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                
                <!-- Input Tanggal Tahunan (Pakai number input tahun) -->
                <input x-show="reportType === 'yearly'" type="number" min="2020" max="2100" name="date_yearly" value="<?= htmlspecialchars($_GET['date_yearly'] ?? date('Y')) ?>" placeholder="YYYY" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary w-28">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Tampilkan
                </button>
                <button type="submit" name="export" value="pdf" class="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ekspor PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= number_format($summary['total_transactions'] ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-3xl font-bold text-[#398263] mt-1">Rp <?= number_format($summary['total_revenue'] ?? 0, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Tunai</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp <?= number_format($summary['cash_total'] ?? 0, 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Non-Tunai</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp <?= number_format($summary['non_cash_total'] ?? 0, 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Rincian Metode Pembayaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($paymentMethods as $method): ?>
                        <?php if ($method['count'] > 0): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= $method['name'] ?></td>
                            <td class="px-4 py-3 text-sm text-right tabular-nums text-gray-600"><?= number_format($method['count']) ?></td>
                            <td class="px-4 py-3 text-sm text-right tabular-nums text-gray-900 font-medium">Rp <?= number_format($method['amount'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="bg-gray-50 font-bold">
                        <td class="px-4 py-3 text-sm text-gray-900">TOTAL</td>
                        <td class="px-4 py-3 text-sm text-right tabular-nums text-gray-900"><?= number_format(array_sum(array_column($paymentMethods, 'count'))) ?></td>
                        <td class="px-4 py-3 text-sm text-right tabular-nums text-gray-900">Rp <?= number_format(array_sum(array_column($paymentMethods, 'amount')), 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Daftar Transaksi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">Tidak ada transaksi pada tanggal ini</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trx): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono font-medium text-gray-600">#<?= $trx['id'] ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= date('H:i:s', strtotime($trx['created_at'])) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?= $trx['order_type'] === 'dine_in' ? 'Dine In' : 'Take Away' ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?php $methods = ['cash'=>'Tunai','qris_static'=>'QRIS','ewallet'=>'E-Wallet','card'=>'Kartu']; echo $methods[$trx['payment_method']] ?? $trx['payment_method']; ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-right tabular-nums text-gray-900">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3">
                                <?php if ($trx['payment_status'] === 'success'): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Lunas</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full"><?= ucfirst($trx['payment_status']) ?></span>
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
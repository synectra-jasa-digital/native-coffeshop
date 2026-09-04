<!-- Riwayat Transaksi -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-500 mt-1">Daftar semua transaksi penjualan</p>
        </div>
        <a href="<?= BASE_URL ?>/pos" class="px-4 py-2 bg-[#398263] text-white rounded-lg font-medium hover:bg-[#2C6B4F] transition text-center">
            Ke POS
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Metode Bayar</label>
                <select name="payment_method" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    <option value="cash" <?= ($_GET['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai</option>
                    <option value="qris_static" <?= ($_GET['payment_method'] ?? '') === 'qris_static' ? 'selected' : '' ?>>QRIS</option>
                    <option value="ewallet" <?= ($_GET['payment_method'] ?? '') === 'ewallet' ? 'selected' : '' ?>>E-Wallet</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition">Filter</button>
            <a href="<?= BASE_URL ?>/transactions" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-md text-sm hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode Bayar</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">Belum ada data transaksi</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trx): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono font-medium text-gray-600">#<?= $trx['id'] ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($trx['created_at'])) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?= $trx['order_type'] === 'dine_in' ? 'Dine In' : 'Take Away' ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <?php
                                $methods = ['cash' => 'Tunai', 'qris_static' => 'QRIS', 'ewallet' => 'E-Wallet', 'card' => 'Kartu'];
                                echo $methods[$trx['payment_method']] ?? $trx['payment_method'];
                                ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-gray-900 text-right tabular-nums">
                                Rp <?= number_format($trx['total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($trx['payment_status'] === 'success'): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Lunas</span>
                                <?php elseif ($trx['payment_status'] === 'refunded'): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Void</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= BASE_URL ?>/transactions/<?= $trx['id'] ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
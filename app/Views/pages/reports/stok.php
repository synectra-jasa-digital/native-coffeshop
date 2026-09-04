<!-- Laporan Stok & Nilai Stok -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Stok & Nilai Stok</h1>
            <p class="text-gray-500 mt-1">Nilai stok bahan baku berdasarkan harga beli terakhir</p>
        </div>
        <a href="<?= BASE_URL ?>/inventory/ingredients" class="px-4 py-2 border border-gray-300 text-gray-600 rounded hover:bg-gray-50 text-sm">Kelola Bahan Baku</a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Bahan Baku</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= count($ingredients) ?></p>
        </div>
        <div class="bg-white rounded-lg border border-red-200 p-5">
            <p class="text-sm text-gray-500">Stok Menipis (≤ Min)</p>
            <p class="text-3xl font-bold text-red-600 mt-1">
                <?php 
                $lowCount = 0;
                foreach ($ingredients as $ing) {
                    if ($ing['min_stock'] > 0 && $ing['current_stock'] <= $ing['min_stock']) $lowCount++;
                }
                echo $lowCount;
                ?>
            </p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Total Nilai Stok</p>
            <p class="text-3xl font-bold text-[#398263] mt-1">Rp <?= number_format(array_sum(array_column($stockValues, 'total_value')), 0, ',', '.') ?></p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Stok Habis (0)</p>
            <p class="text-3xl font-bold text-red-800 mt-1">
                <?php 
                $emptyCount = 0;
                foreach ($ingredients as $ing) {
                    if ($ing['current_stock'] <= 0) $emptyCount++;
                }
                echo $emptyCount;
                ?>
            </p>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Daftar Bahan Baku & Nilai Stok</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Min Stock</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga/Unit</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Stok</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($ingredients)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">Belum ada data bahan baku</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ingredients as $ing): ?>
                            <?php 
                            $val = $stockValues[$ing['id']] ?? ['unit_price' => 0, 'total_value' => 0];
                            $status = '';
                            $statusClass = '';
                            if ($ing['min_stock'] > 0 && $ing['current_stock'] <= 0) {
                                $status = 'HABIS';
                                $statusClass = 'bg-red-100 text-red-800';
                            } elseif ($ing['min_stock'] > 0 && $ing['current_stock'] <= $ing['min_stock']) {
                                $status = 'MENIPIS';
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $status = 'AMAN';
                                $statusClass = 'bg-green-100 text-green-800';
                            }
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($ing['name']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600"><?= htmlspecialchars($ing['unit']) ?></td>
                                <td class="px-4 py-3 text-right font-mono tabular-nums text-gray-900"><?= number_format($ing['current_stock'], 2) ?></td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600"><?= number_format($ing['min_stock'], 2) ?></td>
                                <td class="px-4 py-3 text-right font-mono tabular-nums text-gray-900">Rp <?= number_format($val['unit_price'], 2) ?></td>
                                <td class="px-4 py-3 text-right font-bold tabular-nums text-gray-900">Rp <?= number_format($val['total_value'], 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $statusClass ?>"><?= $status ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pergerakan Stok Terakhir -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Pergerakan Stok Terakhir (100 Transaksi)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($movements)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada pergerakan stok</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movements as $mv): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m H:i', strtotime($mv['created_at'])) ?></td>
                            <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($mv['ingredient_name']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php 
                                $types = ['in' => 'bg-green-100 text-green-800', 'out' => 'bg-red-100 text-red-800', 'adjustment' => 'bg-blue-100 text-blue-800'];
                                $labels = ['in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Adjust'];
                                $type = $mv['type'];
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $types[$type] ?? 'bg-gray-100 text-gray-800' ?>">
                                    <?= $labels[$type] ?? $type ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono tabular-nums text-gray-900"><?= number_format($mv['quantity'], 2) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($mv['user_name']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($mv['notes'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Riwayat Shift -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Shift</h1>
            <p class="text-gray-500 mt-1">Daftar shift kasir</p>
        </div>
        <a href="<?= BASE_URL ?>/shift/open" class="px-4 py-2 bg-[#398263] text-white rounded-lg font-medium hover:bg-[#2C6B4F] transition text-center">
            Buka Shift Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mulai</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Modal Awal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($shifts)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada riwayat shift</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($shifts as $shift): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($shift['user_name']) ?></div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($shift['start_time'])) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= $shift['end_time'] ? date('d/m/Y H:i', strtotime($shift['end_time'])) : '-' ?>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Rp <?= number_format($shift['starting_cash'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <!-- Could add summary here -->
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($shift['status'] === 'open'): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Terbuka</span>
                            <?php else: ?>
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Tertutup</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
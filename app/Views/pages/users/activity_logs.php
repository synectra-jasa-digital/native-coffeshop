<!-- Log Aktivitas -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas (Audit Trail)</h1>
            <p class="text-gray-500 mt-1">Catatan audit aktivitas pengguna dalam sistem</p>
        </div>
        <a href="<?= BASE_URL ?>/users" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 font-medium transition text-center">
            Kembali ke Pengguna
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Pengguna</label>
                <select name="user_id" class="border border-gray-300 rounded-md px-3 py-2 text-sm bg-white">
                    <option value="">Semua User</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($_GET['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?> (@<?= htmlspecialchars($u['username']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Aksi</label>
                <input type="text" name="action" value="<?= htmlspecialchars($_GET['action'] ?? '') ?>" placeholder="Misal: CREATE_USER" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700 transition">Filter</button>
            <a href="<?= BASE_URL ?>/activity-logs" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-md text-sm hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi / Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500">Belum ada catatan log aktivitas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                <?= htmlspecialchars($log['user_name']) ?> <span class="text-xs text-gray-500 font-normal">(@<?= htmlspecialchars($log['username']) ?>)</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-0.5 text-xs font-mono font-medium bg-gray-100 text-gray-800 rounded border border-gray-200">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <?= htmlspecialchars($log['description']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
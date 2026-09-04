<!-- Manajemen Pengguna -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-gray-500 mt-1">Kelola akun dan role pengguna sistem</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASE_URL ?>/activity-logs" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 font-medium transition text-center">
                Log Aktivitas
            </a>
            <a href="<?= BASE_URL ?>/users/create" class="px-4 py-2 bg-[#398263] text-white rounded-lg font-medium hover:bg-[#2C6B4F] transition text-center flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar pb-2">
        <a href="<?= BASE_URL ?>/users" class="px-4 py-2 rounded-full border text-sm font-medium whitespace-nowrap transition-colors <?= !$selectedRole ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' ?>">
            Semua Role
        </a>
        <?php foreach ($roles as $role): ?>
            <a href="<?= BASE_URL ?>/users?role=<?= $role['id'] ?>" class="px-4 py-2 rounded-full border text-sm font-medium whitespace-nowrap transition-colors <?= $selectedRole == $role['id'] ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' ?>">
                <?= htmlspecialchars($role['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada data pengguna</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($u['name']) ?></div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">@<?= htmlspecialchars($u['username']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg border border-gray-200">
                                    <?= htmlspecialchars($u['role_name']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="<?= BASE_URL ?>/users/edit/<?= $u['id'] ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php if ($u['id'] != \App\Core\Session::get('user_id')): ?>
                                        <form method="POST" action="<?= BASE_URL ?>/users/delete/<?= $u['id'] ?>" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/menonaktifkan pengguna ini?');">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
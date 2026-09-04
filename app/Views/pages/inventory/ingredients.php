<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data bahan baku dan minimum stok.</p>
        </div>
        <a href="<?= BASE_URL ?>/inventory/ingredients/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Bahan Baku
        </a>
    </div>

    <!-- Alert -->
    <?php if (\App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('success')): ?>
        <!-- Handled by Layout Dialog -->
    <?php endif; ?>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan Baku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Stok</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($ingredients)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada data bahan baku.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($ingredients as $ing): 
                            $isLowStock = floatval($ing['current_stock']) <= floatval($ing['min_stock']);
                        ?>
                        <tr class="hover:bg-gray-50" id="row-<?= $ing['id'] ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($ing['name']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($ing['unit']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $isLowStock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= floatval($ing['current_stock']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= floatval($ing['min_stock']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/inventory/ingredients/edit/<?= $ing['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                                <button onclick="confirmDelete(<?= $ing['id'] ?>, '<?= htmlspecialchars(addslashes($ing['name'])) ?>')" class="text-red-600 hover:text-red-900">Hapus</button>
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

<script>
    function confirmDelete(id, name) {
        showDialog('warning', 'Hapus Bahan Baku', `Apakah Anda yakin ingin menghapus bahan baku "${name}"?`, true, async () => {
            try {
                const response = await fetch(`<?= BASE_URL ?>/inventory/ingredients/delete/${id}`, {
                    method: 'POST'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById(`row-${id}`).remove();
                    showDialog('success', 'Berhasil', result.message);
                } else {
                    showDialog('error', 'Gagal', result.message || 'Gagal menghapus bahan baku.');
                }
            } catch (error) {
                showDialog('error', 'Gagal', 'Gagal terhubung ke server.');
            }
        });
    }
</script>


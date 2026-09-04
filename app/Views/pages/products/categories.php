<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/products" class="text-textSecondary hover:text-primary transition-colors bg-surface p-1.5 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="text-xl font-semibold text-textPrimary"><?= htmlspecialchars($title) ?></h2>
    </div>
    <a href="<?= BASE_URL ?>/categories/create" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-primary text-white hover:bg-primary-hover shadow-sm transition-colors duration-200 cursor-pointer">
        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Kategori
    </a>
</div>

<!-- Alpine.js Component for Category Management -->
<div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-border bg-surface shadow-sm">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-background">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider w-16 text-center">Urutan</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Nama Kategori</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider">Jumlah Produk</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-textSecondary uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-textSecondary">
                            Belum ada kategori yang ditambahkan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-background transition-colors duration-200">
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-textSecondary tabular-nums">
                                <?= $cat['sort_order'] ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-textPrimary">
                                <?= htmlspecialchars($cat['name']) ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-textSecondary tabular-nums">
                                <?= $cat['total_products'] ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <?php if ($cat['is_active']): ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="<?= BASE_URL ?>/categories/edit/<?= $cat['id'] ?>" class="text-primary hover:text-primary-hover p-1.5 rounded hover:bg-primary/10 transition-colors duration-200 cursor-pointer" title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete(<?= $cat['id'] ?>)" class="text-danger hover:text-red-800 p-1.5 rounded hover:bg-red-50 transition-colors duration-200 cursor-pointer" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(id) {
        showDialog('warning', 'Hapus Kategori?', 'Yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak dapat dihapus.', true, () => {
            fetch(BASE_URL + '/categories/delete/' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    showDialog('error', 'Gagal', data.message);
                }
            })
            .catch(error => {
                showDialog('error', 'Error', 'Terjadi kesalahan sistem.');
            });
        });
    }
</script>
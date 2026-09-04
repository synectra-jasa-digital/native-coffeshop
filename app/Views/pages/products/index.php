<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-textPrimary">Produk & Menu</h2>
        <p class="text-sm text-textSecondary mt-1">Kelola daftar menu dan produk yang dijual.</p>
    </div>
    <div class="flex items-center gap-3">
        <?= $this->component('button', [
            'text' => 'Kelola Kategori',
            'variant' => 'secondary',
            'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
            'attributes' => ['onclick' => "window.location.href='" . BASE_URL . "/categories'"]
        ]) ?>
        <?= $this->component('button', [
            'text' => 'Tambah Produk',
            'variant' => 'primary',
            'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>',
            'attributes' => ['onclick' => "window.location.href='" . BASE_URL . "/products/create'"]
        ]) ?>
    </div>
</div>

<?php if (\App\Core\Session::hasFlash('success') || \App\Core\Session::hasFlash('error')): ?>
    <!-- Handled by layout.php Global Dialogs -->
<?php endif; ?>

<!-- Filters -->
<div class="bg-surface p-4 rounded-t-lg border border-border border-b-0 flex items-center justify-between">
    <div class="flex items-center gap-3 w-full max-w-sm">
        <label for="categoryFilter" class="text-sm font-semibold text-textSecondary whitespace-nowrap">Kategori:</label>
        <select id="categoryFilter" 
                class="block w-full rounded-md border border-border py-2 pl-3 pr-10 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors cursor-pointer"
                onchange="window.location.href='<?= BASE_URL ?>/products?category=' + this.value">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Search -->
    <div class="relative max-w-xs w-full hidden sm:block">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" id="searchInput" placeholder="Cari produk..." class="block w-full rounded-md border border-border py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
    </div>
</div>

<!-- Product Table -->
<div class="overflow-x-auto rounded-b-lg border border-border bg-surface shadow-sm">
    <table class="min-w-full divide-y divide-border" id="productsTable">
        <thead class="bg-background">
            <tr>
                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-textSecondary uppercase tracking-wider">Produk</th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-textSecondary uppercase tracking-wider">Kategori</th>
                <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-textSecondary uppercase tracking-wider">Harga Dasar</th>
                <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-textSecondary uppercase tracking-wider">Status</th>
                <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-textSecondary uppercase tracking-wider w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-border" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="mt-4 text-sm font-medium text-textSecondary">Belum ada produk yang ditambahkan.</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 product-row transition-colors duration-200">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <?php if ($product['image_url']): ?>
                                    <div class="h-12 w-12 flex-shrink-0 mr-4">
                                        <img class="h-12 w-12 rounded-lg object-cover border border-border" src="<?= htmlspecialchars($product['image_url']) ?>" alt="">
                                    </div>
                                <?php else: ?>
                                    <div class="h-12 w-12 flex-shrink-0 mr-4 bg-background border border-border rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="text-sm font-bold text-textPrimary product-name"><?= htmlspecialchars($product['name']) ?></div>
                                    <?php if ($product['description']): ?>
                                        <div class="text-xs text-textSecondary truncate max-w-[200px] mt-0.5"><?= htmlspecialchars($product['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-textSecondary font-medium">
                            <?= htmlspecialchars($product['category_name']) ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-textPrimary text-right font-bold tabular-nums">
                            Rp <?= number_format($product['base_price'], 0, ',', '.') ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <?php if ($product['is_out_of_stock']): ?>
                                <?= $this->component('badge', ['text' => 'Habis', 'variant' => 'warning']) ?>
                            <?php elseif (!$product['is_active']): ?>
                                <?= $this->component('badge', ['text' => 'Nonaktif', 'variant' => 'neutral']) ?>
                            <?php else: ?>
                                <?= $this->component('badge', ['text' => 'Aktif', 'variant' => 'success']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="<?= BASE_URL ?>/products/edit/<?= $product['id'] ?>" class="text-primary hover:text-primary-hover p-1.5 rounded hover:bg-primary/10 transition-colors cursor-pointer" title="Edit">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="<?= BASE_URL ?>/products/delete/<?= $product['id'] ?>" method="POST" class="inline" onsubmit="return false;" id="delete-form-<?= $product['id'] ?>">
                                    <button type="button" onclick="confirmDelete(<?= $product['id'] ?>)" class="text-danger hover:text-red-800 p-1.5 rounded hover:bg-red-50 transition-colors cursor-pointer" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Client side search
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.product-row');
        
        rows.forEach(row => {
            let name = row.querySelector('.product-name').textContent.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Delete confirmation
    function confirmDelete(id) {
        showDialog(
            'warning', 
            'Hapus Produk', 
            'Apakah Anda yakin ingin menghapus produk ini? Produk yang memiliki riwayat transaksi mungkin tidak dapat dihapus.',
            true, 
            () => {
                document.getElementById('delete-form-' + id).submit();
            }
        );
    }
</script>
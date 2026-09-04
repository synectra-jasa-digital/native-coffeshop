<div class="w-full mb-6">
    <!-- Page Header -->
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/products" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-0.5">Kelola informasi produk dan varian yang dijual.</p>
        </div>
    </div>
</div>

<!-- Alerts -->
<?php if (\App\Core\Session::hasFlash('error')): ?>
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-danger rounded-md flex items-start" x-data="{ show: true }" x-show="show">
        <svg class="h-5 w-5 text-danger mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="ml-3 text-sm text-red-800 font-medium flex-1"><?= htmlspecialchars(\App\Core\Session::getFlash('error')) ?></p>
        <button @click="show = false" class="text-red-800 hover:text-red-900 cursor-pointer">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ isOut: <?= ($product && $product['is_out_of_stock']) ? 'true' : 'false' ?> }">
    
    <!-- Left Column: Main Form -->
    <div class="lg:col-span-2 space-y-6">
        <form action="<?= BASE_URL ?>/products/save<?= $product ? '/' . $product['id'] : '' ?>" method="POST" id="productForm" class="bg-surface rounded-lg border border-border shadow-sm p-6 space-y-6 w-full">
            
            <h3 class="text-base font-bold text-textPrimary border-b border-border pb-2">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <?= $this->component('form-input', [
                        'name' => 'name',
                        'label' => 'Nama Produk *',
                        'placeholder' => 'Contoh: Ice Caffe Latte',
                        'value' => $product['name'] ?? '',
                        'attributes' => ['required' => true]
                    ]) ?>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-textSecondary mb-1">Kategori *</label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($product && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Base Price -->
                <div>
                    <label for="base_price" class="block text-sm font-medium text-textSecondary mb-1">Harga Dasar (Rp) *</label>
                    <input type="text" id="base_price" name="base_price" required
                           value="<?= $product ? rtrim(rtrim(number_format($product['base_price'], 2, '.', ''), '0'), '.') : '' ?>"
                           oninput="formatCurrency(this)"
                           placeholder="0"
                           class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 text-right tabular-nums">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-textSecondary mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>
            </div>

            <h3 class="text-base font-bold text-textPrimary border-b border-border pb-2 mt-6">Status Produk</h3>
            
            <div class="space-y-4">
                <!-- Is Active -->
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_active" value="1" <?= (!$product || $product['is_active']) ? 'checked' : '' ?>
                               class="h-4 w-4 rounded border-border text-primary focus:ring-primary cursor-pointer transition-colors duration-200">
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-textPrimary group-hover:text-primary transition-colors duration-200">Produk Aktif</span>
                        <span class="block text-xs text-textSecondary">Tampilkan produk ini di menu Kasir dan menu QR Pelanggan.</span>
                    </div>
                </label>

                <!-- Out of Stock -->
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_out_of_stock" value="1" x-model="isOut"
                               class="h-4 w-4 rounded border-border text-danger focus:ring-danger cursor-pointer transition-colors duration-200">
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-danger group-hover:text-red-700 transition-colors duration-200">Habis Sementara (Out of Stock)</span>
                        <span class="block text-xs text-textSecondary">Tandai produk ini habis. Pelanggan tidak akan bisa memesan, namun produk tidak dihapus.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-border flex justify-end gap-3">
                <a href="<?= BASE_URL ?>/products" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <?= $this->component('button', [
                    'type' => 'submit',
                    'text' => 'Simpan Produk',
                    'icon' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
                ]) ?>
            </div>
        </form>
    </div>

    <!-- Right Column: Image and Variants (Only show variants if editing existing product) -->
    <div class="space-y-6">
        <!-- Image Panel (Placeholder for now) -->
        <div class="bg-surface rounded-lg border border-border shadow-sm p-6">
            <h3 class="text-base font-bold text-textPrimary mb-4">Gambar Produk</h3>
            <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-border rounded-md bg-background hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                <svg class="h-10 w-10 text-textSecondary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm text-textSecondary font-medium">Pilih Gambar</span>
                <span class="text-xs text-textSecondary mt-1">JPG, PNG (Max 2MB)</span>
            </div>
            <p class="text-xs text-textSecondary mt-3 text-center">Fitur upload gambar akan tersedia di pembaruan selanjutnya.</p>
        </div>

        <?php if ($product): ?>
            <!-- Variants Panel -->
            <div class="bg-surface rounded-lg border border-border shadow-sm p-0 overflow-hidden">
                <div class="p-4 border-b border-border flex justify-between items-center bg-background">
                    <h3 class="text-base font-bold text-textPrimary">Varian Produk</h3>
                </div>
                
                <div class="p-4">
                    <p class="text-xs text-textSecondary mb-4">Tambahkan opsi ukuran, level gula, dll.</p>
                    
                    <?php if (empty($variants)): ?>
                        <div class="text-center py-4 bg-gray-50 rounded border border-border text-sm text-textSecondary">
                            Belum ada varian untuk produk ini.
                        </div>
                    <?php else: ?>
                        <ul class="space-y-2 mb-4">
                            <?php foreach ($variants as $v): ?>
                                <li class="flex items-center justify-between p-2 bg-gray-50 rounded border border-border">
                                    <div>
                                        <span class="block text-sm font-medium text-textPrimary"><?= htmlspecialchars($v['name']) ?></span>
                                        <span class="block text-xs text-textSecondary tabular-nums">+ Rp <?= number_format($v['additional_price'], 0, ',', '.') ?></span>
                                    </div>
                                    <button class="text-danger hover:bg-red-100 p-1.5 rounded transition-colors duration-200 cursor-pointer" title="Hapus Varian">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <!-- Quick Add Variant Form -->
                    <div class="mt-4 pt-4 border-t border-border">
                        <div class="text-sm font-medium mb-2">Tambah Varian Cepat</div>
                        <div class="space-y-3">
                            <input type="text" placeholder="Nama Varian (Cth: Large)" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                            <input type="text" placeholder="Harga Tambahan (Cth: 5000)" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 tabular-nums">
                            <?= $this->component('button', [
                                'text' => 'Tambah',
                                'variant' => 'secondary',
                                'class' => 'w-full text-xs py-1.5'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4 flex items-start">
                <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-800 font-medium">
                    Simpan informasi dasar produk terlebih dahulu untuk dapat menambahkan varian (ukuran, level gula, dll).
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Format currency input
    function formatCurrency(input) {
        // Remove non-numeric characters
        let val = input.value.replace(/[^\d]/g, '');
        if (val) {
            input.value = val;
        }
    }
</script>
<div class="flex flex-col h-full w-full">
    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/inventory/movements" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-1">Catat secara manual stok bahan baku yang masuk, keluar, atau rusak.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6 flex-1">
        <div x-data="{ type: 'in' }">
            <form action="<?= BASE_URL ?>/inventory/movements/record" method="POST" class="space-y-6 w-full">
                
                <div>
                    <label for="ingredient_id" class="block text-sm font-medium text-textSecondary mb-1">Pilih Bahan Baku <span class="text-danger">*</span></label>
                    <select name="ingredient_id" id="ingredient_id" required class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                        <option value="" disabled selected>-- Pilih Bahan --</option>
                        <?php foreach($ingredients as $ing): ?>
                            <option value="<?= $ing['id'] ?>"><?= htmlspecialchars($ing['name']) ?> (Sisa: <?= floatval($ing['current_stock']) ?> <?= htmlspecialchars($ing['unit']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-textSecondary mb-1">Jenis Pergerakan <span class="text-danger">*</span></label>
                        <select name="type" id="type" x-model="type" required class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                            <option value="in">Stok Masuk (+)</option>
                            <option value="out">Stok Keluar (-)</option>
                            <option value="adjustment">Penyesuaian (+/-)</option>
                        </select>
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-textSecondary mb-1">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantity" id="quantity" required
                               class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                        <p x-show="type === 'adjustment'" class="mt-1 text-xs text-textSecondary">Gunakan minus (-) untuk mengurangi</p>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-textSecondary mb-1">Keterangan / Catatan</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"
                              placeholder="Misal: Pembelian dari supplier A, atau bahan tumpah"></textarea>
                </div>

                <div class="pt-6 border-t border-border flex justify-end gap-3">
                    <a href="<?= BASE_URL ?>/inventory/movements" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                        Simpan Pergerakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
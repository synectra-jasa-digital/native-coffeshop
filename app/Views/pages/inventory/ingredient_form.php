<div class="flex flex-col h-full w-full">
    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/inventory/ingredients" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-1"><?= $ingredient ? 'Ubah informasi bahan baku yang sudah ada.' : 'Tambahkan bahan baku baru ke dalam sistem.' ?></p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6 flex-1">
        <form action="<?= BASE_URL ?>/inventory/ingredients/save<?= $ingredient ? '/' . $ingredient['id'] : '' ?>" method="POST" class="space-y-6 w-full">

            <div>
                <label for="name" class="block text-sm font-medium text-textSecondary mb-1">Nama Bahan Baku <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" required value="<?= $ingredient ? htmlspecialchars($ingredient['name']) : '' ?>"
                       class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"
                       placeholder="Misal: Biji Kopi Arabica">
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-textSecondary mb-1">Satuan <span class="text-danger">*</span></label>
                <input type="text" name="unit" id="unit" required value="<?= $ingredient ? htmlspecialchars($ingredient['unit']) : '' ?>"
                       class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"
                       placeholder="Misal: gram, ml, pcs">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-textSecondary mb-1">Minimum Stok</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" step="0.01" name="min_stock" id="min_stock" value="<?= $ingredient ? floatval($ingredient['min_stock']) : '0' ?>"
                               class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                    </div>
                    <p class="mt-1 text-xs text-textSecondary">Sistem akan memberi peringatan jika stok di bawah batas ini.</p>
                </div>

                <div>
                    <label for="current_stock" class="block text-sm font-medium text-textSecondary mb-1">Stok Awal</label>
                    <input type="number" step="0.01" name="current_stock" id="current_stock" <?= $ingredient ? 'disabled' : '' ?> value="<?= $ingredient ? floatval($ingredient['current_stock']) : '0' ?>"
                           class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 <?= $ingredient ? 'bg-gray-100 cursor-not-allowed' : '' ?>">
                    <?php if ($ingredient): ?>
                        <p class="mt-1 text-xs text-warning">Gunakan menu Pergerakan Stok untuk mengubah stok.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-6 border-t border-border flex justify-end gap-3">
                <a href="<?= BASE_URL ?>/inventory/ingredients" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                    Simpan Bahan Baku
                </button>
            </div>
        </form>
    </div>
</div>

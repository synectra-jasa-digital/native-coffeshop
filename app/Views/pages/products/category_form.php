<div class="flex flex-col h-full w-full">
    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/categories" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-1">Kategori digunakan untuk mengelompokkan menu di halaman POS.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6 flex-1">
        <form action="<?= BASE_URL ?>/categories/save<?= $category ? '/' . $category['id'] : '' ?>" method="POST" class="space-y-6 w-full">
            
            <div>
                <label for="name" class="block text-sm font-medium text-textSecondary mb-1">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" required autofocus value="<?= $category ? htmlspecialchars($category['name']) : '' ?>"
                       class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"
                       placeholder="Misal: Coffee, Makanan, dll">
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-textSecondary mb-1">Urutan Tampil (Angka)</label>
                <input type="number" name="sort_order" id="sort_order" min="0" value="<?= $category ? htmlspecialchars($category['sort_order']) : '0' ?>"
                       class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 tabular-nums">
            </div>

            <label class="flex items-start gap-3 cursor-pointer mt-4">
                <div class="flex items-center h-5">
                    <input type="checkbox" name="is_active" <?= (!$category || $category['is_active']) ? 'checked' : '' ?>
                           class="h-4 w-4 rounded border-border text-primary focus:ring-primary cursor-pointer transition-colors">
                </div>
                <div>
                    <span class="block text-sm font-medium text-textPrimary">Kategori Aktif</span>
                    <span class="block text-xs text-textSecondary">Tampilkan kategori ini pada halaman Kasir.</span>
                </div>
            </label>

            <div class="pt-6 border-t border-border flex justify-end gap-3">
                <a href="<?= BASE_URL ?>/categories" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
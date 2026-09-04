<div class="flex flex-col h-full">
    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/tables" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-1"><?= $table ? 'Ubah informasi meja.' : 'Tambahkan meja baru ke dalam sistem untuk pesanan via QR.' ?></p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6 flex-1">
        <form action="<?= BASE_URL ?>/tables/save<?= $table ? '/' . $table['id'] : '' ?>" method="POST" class="space-y-6 w-full">
            
            <div class="max-w-xl">
                <div>
                    <label for="table_number" class="block text-sm font-medium text-textSecondary mb-1">Nomor/Nama Meja <span class="text-danger">*</span></label>
                    <input type="text" name="table_number" id="table_number" required value="<?= $table ? htmlspecialchars($table['table_number']) : '' ?>"
                           class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2"
                           placeholder="Misal: 01, Meja 02, VIP-1">
                    <p class="mt-1 text-xs text-textSecondary">Harus unik dan tidak boleh sama dengan meja yang sudah ada.</p>
                </div>

                <?php if ($table): ?>
                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-textSecondary mb-1">Status Meja</label>
                    <select name="status" id="status" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer bg-white">
                        <option value="empty" <?= $table['status'] === 'empty' ? 'selected' : '' ?>>Kosong (Empty)</option>
                        <option value="occupied" <?= $table['status'] === 'occupied' ? 'selected' : '' ?>>Terisi (Occupied)</option>
                    </select>
                    <p class="mt-1 text-xs text-textSecondary">Anda bisa mereset meja secara manual jika terjadi kesalahan.</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="pt-6 border-t border-border flex justify-end gap-3 max-w-xl">
                <a href="<?= BASE_URL ?>/tables" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                    <?= $table ? 'Simpan Perubahan' : 'Simpan Meja' ?>
                </button>
            </div>
        </form>
    </div>
</div>
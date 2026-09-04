<!-- Buka Shift Form -->
<div class="w-full space-y-6">
    <!-- Page Header -->
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/pos" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-0.5">Masukkan modal kas awal untuk memulai shift kasir.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6">
        <form method="POST" action="<?= BASE_URL ?>/shift/open" class="space-y-5 w-full">
            <!-- Modal Kas Awal -->
            <div>
                <label for="starting_cash" class="block text-sm font-medium text-textSecondary mb-1">Modal Kas Awal (Rp) <span class="text-danger">*</span></label>
                <input type="number" 
                       id="starting_cash" 
                       name="starting_cash" 
                       min="0" 
                       step="1"
                       value="500000"
                       required
                       class="block w-full rounded-md shadow-sm text-lg font-bold transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-4 py-3">
                <p class="text-xs text-textSecondary mt-1">Masukkan jumlah uang kas yang tersedia di laci kasir saat ini.</p>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-blue-800">
                    <strong>Catatan:</strong> Pastikan jumlah kas fisik dihitung dengan teliti sebelum membuka shift. Selisih akhir kas akan dihitung secara otomatis saat Anda menutup shift.
                </p>
            </div>

            <!-- Buttons -->
            <div class="border-t border-border pt-4 flex items-center justify-end gap-3">
                <a href="<?= BASE_URL ?>/pos" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                    Buka Shift
                </button>
            </div>
        </form>
    </div>
</div>
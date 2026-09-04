<!-- Tutup Shift Form -->
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
            <p class="text-sm text-textSecondary mt-0.5">Hitung kas fisik akhir untuk rekonsiliasi penutupan shift.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6">
        <!-- Ringkasan Shift -->
        <div class="bg-background rounded-md p-4 mb-5 space-y-2 border border-border">
            <div class="flex justify-between text-sm">
                <span class="text-textSecondary">Modal Awal</span>
                <span class="font-medium text-textPrimary">Rp <?= number_format($shift['starting_cash'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-textSecondary">Total Penjualan (Cash)</span>
                <span class="font-medium text-textPrimary">Rp <?= number_format($summary['cash_amount'] ?? 0, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-textSecondary">Total Transaksi</span>
                <span class="font-medium text-textPrimary"><?= $summary['total_transactions'] ?? 0 ?> trx</span>
            </div>
            <div class="border-t border-border my-2"></div>
            <div class="flex justify-between text-sm font-bold">
                <span class="text-textPrimary">Kas Seharusnya (Expected)</span>
                <span class="text-primary">Rp <?= number_format(($shift['starting_cash'] + ($summary['cash_amount'] ?? 0)), 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="<?= BASE_URL ?>/shift/close" class="space-y-5 w-full">
            <!-- Kas Fisik Akhir -->
            <div>
                <label for="ending_cash" class="block text-sm font-medium text-textSecondary mb-1">Kas Fisik Akhir (Rp) <span class="text-danger">*</span></label>
                <input type="number" 
                       id="ending_cash" 
                       name="ending_cash" 
                       min="0" 
                       step="1"
                       value="<?= ($shift['starting_cash'] + ($summary['cash_amount'] ?? 0)) ?>"
                       required
                       oninput="calculateDifference(this.value)"
                       class="block w-full rounded-md shadow-sm text-lg font-bold transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-4 py-3">
                <p class="text-xs text-textSecondary mt-1">Masukkan total uang tunai hasil penghitungan di kasir saat ini.</p>
            </div>

            <!-- Selisih (auto-calculated) -->
            <div id="difference-box" class="bg-background rounded-md p-4 border border-border">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-textSecondary">Selisih Kas</span>
                    <span id="difference-value" class="text-lg font-bold tabular-nums text-textPrimary">Rp 0</span>
                </div>
                <p id="difference-status" class="text-xs mt-1 text-textSecondary">-</p>
            </div>

            <!-- Buttons -->
            <div class="border-t border-border pt-4 flex items-center justify-end gap-3">
                <a href="<?= BASE_URL ?>/pos" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-warning px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-warning transition-all duration-200">
                    Tutup Shift
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateDifference(endingCash) {
    const expectedCash = <?= ($shift['starting_cash'] + ($summary['cash_amount'] ?? 0)) ?>;
    const difference = parseFloat(endingCash || 0) - expectedCash;
    const diffBox = document.getElementById('difference-box');
    const diffValue = document.getElementById('difference-value');
    const diffStatus = document.getElementById('difference-status');
    
    diffValue.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(difference);
    
    if (difference === 0) {
        diffBox.className = 'bg-green-50 rounded-md p-4 border border-green-200';
        diffValue.className = 'text-lg font-bold text-green-700 tabular-nums';
        diffStatus.textContent = 'Kas sesuai ✓';
        diffStatus.className = 'text-xs mt-1 text-green-700 font-medium';
    } else if (difference > 0) {
        diffBox.className = 'bg-blue-50 rounded-md p-4 border border-blue-200';
        diffValue.className = 'text-lg font-bold text-blue-700 tabular-nums';
        diffStatus.textContent = 'Kelebihan kas (+ Rp ' + new Intl.NumberFormat('id-ID').format(difference) + ')';
        diffStatus.className = 'text-xs mt-1 text-blue-700 font-medium';
    } else {
        diffBox.className = 'bg-red-50 rounded-md p-4 border border-red-200';
        diffValue.className = 'text-lg font-bold text-red-700 tabular-nums';
        diffStatus.textContent = 'Kekurangan kas (Rp ' + new Intl.NumberFormat('id-ID').format(difference) + ')';
        diffStatus.className = 'text-xs mt-1 text-red-700 font-medium';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('ending_cash');
    if (input) calculateDifference(input.value);
});
</script>
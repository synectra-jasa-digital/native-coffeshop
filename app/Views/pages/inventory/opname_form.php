<div class="mb-6 flex items-center gap-3">
    <a href="<?= BASE_URL ?>/inventory/opname" class="text-textSecondary hover:text-primary transition-colors bg-surface p-1.5 rounded-md border border-border shadow-sm">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>
    <div>
        <h2 class="text-xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h2>
        <p class="text-sm text-textSecondary mt-1">Lakukan penghitungan fisik bahan baku yang ada di gudang/toko.</p>
    </div>
</div>

<div class="bg-surface border border-border rounded-lg shadow-sm max-w-2xl" x-data="opnameForm()">
    <form action="<?= BASE_URL ?>/inventory/opname/submit" method="POST" class="p-6 space-y-6">
        
        <div>
            <label for="ingredient_id" class="block text-sm font-medium text-textSecondary mb-1">Pilih Bahan Baku <span class="text-danger">*</span></label>
            <select name="ingredient_id" id="ingredient_id" required x-model="selectedId" @change="updateData()" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                <option value="" disabled selected>-- Pilih Bahan --</option>
                <?php foreach($ingredients as $ing): ?>
                    <option value="<?= $ing['id'] ?>" data-stock="<?= floatval($ing['current_stock']) ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>">
                        <?= htmlspecialchars($ing['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-textSecondary mb-1">Stok di Sistem</label>
                <div class="block w-full bg-background border border-border rounded-md shadow-sm py-2 px-3 sm:text-sm text-textSecondary">
                    <span x-text="expectedStockText"></span>
                </div>
            </div>

            <div>
                <label for="actual_qty" class="block text-sm font-medium text-textSecondary mb-1">Stok Fisik Aktual <span class="text-danger">*</span></label>
                <div class="relative rounded-md shadow-sm">
                    <input type="number" step="0.01" min="0" name="actual_qty" id="actual_qty" x-model="actualQty" required
                           class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-textSecondary sm:text-sm" x-text="unit"></span>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="selectedId !== '' && actualQty !== ''" class="rounded-md p-4 flex items-center justify-between border" :class="diffValue < 0 ? 'bg-red-50 border-red-200' : (diffValue > 0 ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200')">
            <div>
                <p class="text-sm font-medium text-textPrimary">Selisih Stok</p>
                <p class="text-xs mt-0.5" :class="diffValue < 0 ? 'text-red-600' : (diffValue > 0 ? 'text-blue-600' : 'text-gray-500')" x-text="diffMessage"></p>
            </div>
            <div class="text-xl font-bold tabular-nums" :class="diffValue < 0 ? 'text-red-700' : (diffValue > 0 ? 'text-blue-700' : 'text-gray-700')">
                <span x-text="diffText"></span>
            </div>
        </div>

        <div class="pt-4 border-t border-border flex justify-end gap-3">
            <a href="<?= BASE_URL ?>/inventory/opname" class="inline-flex justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-textPrimary shadow-sm ring-1 ring-inset ring-border hover:bg-gray-50 transition-colors duration-200">
                Batal
            </a>
            <button type="submit" class="inline-flex justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors duration-200">
                Submit Laporan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('opnameForm', () => ({
        selectedId: '',
        actualQty: '',
        expectedStock: 0,
        unit: '',

        updateData() {
            let select = document.getElementById('ingredient_id');
            let option = select.options[select.selectedIndex];
            if (option && option.value) {
                this.expectedStock = parseFloat(option.getAttribute('data-stock'));
                this.unit = option.getAttribute('data-unit');
            }
        },

        get expectedStockText() {
            if (this.selectedId === '') return '-';
            return this.expectedStock + ' ' + this.unit;
        },

        get diffValue() {
            if (this.selectedId === '' || this.actualQty === '') return 0;
            return parseFloat(this.actualQty) - this.expectedStock;
        },

        get diffText() {
            let val = this.diffValue;
            let sign = val > 0 ? '+' : '';
            return sign + val + ' ' + this.unit;
        },

        get diffMessage() {
            let val = this.diffValue;
            if (val < 0) return 'Stok fisik lebih sedikit dari sistem (Penyusutan)';
            if (val > 0) return 'Stok fisik lebih banyak dari sistem (Kelebihan)';
            return 'Stok fisik sesuai dengan sistem';
        }
    }));
});
</script>
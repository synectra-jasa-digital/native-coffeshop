<div class="space-y-6" x-data="recipeManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="<?= BASE_URL ?>/inventory/recipes" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; Kembali ke Daftar Produk</a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Daftar bahan baku yang akan dikurangi saat produk ini terjual.</p>
        </div>
        <button @click="openModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Bahan
        </button>
    </div>

    <!-- Alert -->
    <?php if (\App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('success')): ?>
        <!-- Handled by Layout Dialog -->
    <?php endif; ?>

    <!-- Form for Recipe Item (Hidden by default, shown via Alpine) -->
    <div x-show="isFormOpen" x-collapse class="bg-surface border border-border rounded-lg shadow-sm mb-6" style="display: none;">
        <form @submit.prevent="saveData" class="p-6">
            <input type="hidden" x-model="formData.product_id">
            <input type="hidden" x-model="formData.variant_id">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-textPrimary">Atur Bahan Baku</h3>
                <button type="button" @click="closeModal()" class="text-textSecondary hover:text-danger">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="ingredient_id" class="block text-sm font-medium text-textSecondary mb-1">Pilih Bahan <span class="text-danger">*</span></label>
                    <select x-model="formData.ingredient_id" id="ingredient_id" required class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                        <option value="" disabled>-- Pilih Bahan --</option>
                        <?php foreach($ingredients as $ing): ?>
                            <option value="<?= $ing['id'] ?>"><?= htmlspecialchars($ing['name']) ?> (<?= htmlspecialchars($ing['unit']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-textSecondary mb-1">Takaran <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" x-model="formData.quantity" id="quantity" required class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                    <p class="mt-1 text-xs text-textSecondary">Sesuaikan dengan satuan bahan yang dipilih.</p>
                </div>
            </div>

            <div class="pt-4 mt-6 border-t border-border flex justify-end gap-3">
                <button type="button" @click="closeModal()" class="inline-flex justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-textPrimary shadow-sm ring-1 ring-inset ring-border hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" :disabled="isSubmitting" class="inline-flex justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors duration-200 disabled:opacity-50">
                    <span x-show="!isSubmitting">Simpan</span>
                    <span x-show="isSubmitting">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Takaran (Quantity)</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($recipeItems)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                            Resep belum diatur. Silakan tambah bahan.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($recipeItems as $item): ?>
                        <tr class="hover:bg-gray-50" id="row-<?= $item['id'] ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['ingredient_name']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                <?= floatval($item['quantity']) ?> <span class="text-gray-500 font-normal"><?= htmlspecialchars($item['unit']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button @click="openModal({ingredient_id: <?= $item['ingredient_id'] ?>, quantity: <?= $item['quantity'] ?>})" class="text-primary hover:text-primary-hover p-1.5 rounded hover:bg-primary/10 transition-colors duration-200 cursor-pointer" title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                                    <button @click="confirmDelete(<?= $item['id'] ?>)" class="text-danger hover:text-red-800 p-1.5 rounded hover:bg-red-50 transition-colors duration-200 cursor-pointer" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function recipeManager() {
    return {
        isFormOpen: false,
        isSubmitting: false,
        formData: {
            product_id: <?= $product['id'] ?>,
            variant_id: <?= $variant ? $variant['id'] : 'null' ?>,
            ingredient_id: '',
            quantity: ''
        },
        
        openModal(data = null) {
            if (data) {
                this.formData.ingredient_id = data.ingredient_id;
                this.formData.quantity = data.quantity;
            } else {
                this.formData.ingredient_id = '';
                this.formData.quantity = '';
            }
            this.isFormOpen = true;
            // Scroll to form slightly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        closeModal() {
            this.isFormOpen = false;
        },
        
        async saveData() {
            this.isSubmitting = true;
            
            const formBody = new URLSearchParams();
            for (const key in this.formData) {
                if (this.formData[key] !== null && this.formData[key] !== '') {
                    formBody.append(key, this.formData[key]);
                }
            }
            
            try {
                const response = await fetch('<?= BASE_URL ?>/inventory/recipes/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formBody
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.closeModal();
                    showDialog('success', 'Berhasil', result.message, false, () => {
                        window.location.reload();
                    });
                } else {
                    showDialog('error', 'Gagal', result.message || 'Terjadi kesalahan.');
                }
            } catch (error) {
                showDialog('error', 'Gagal', 'Gagal terhubung ke server.');
            } finally {
                this.isSubmitting = false;
            }
        },

        async confirmDelete(id) {
            showDialog('warning', 'Hapus Bahan', 'Apakah Anda yakin ingin menghapus bahan ini dari resep?', true, async () => {
                try {
                    const response = await fetch(`<?= BASE_URL ?>/inventory/recipes/delete/${id}`, {
                        method: 'POST'
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        document.getElementById(`row-${id}`).remove();
                        showDialog('success', 'Berhasil', result.message);
                    } else {
                        showDialog('error', 'Gagal', result.message || 'Gagal menghapus.');
                    }
                } catch (error) {
                    showDialog('error', 'Gagal', 'Gagal terhubung ke server.');
                }
            });
        }
    }
}
</script>
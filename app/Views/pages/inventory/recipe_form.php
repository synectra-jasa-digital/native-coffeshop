<div x-data="recipeManager()" class="space-y-6">
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

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Takaran (Quantity)</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
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
                                <button @click="openModal({ingredient_id: <?= $item['ingredient_id'] ?>, quantity: <?= $item['quantity'] ?>})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                                <button @click="confirmDelete(<?= $item['id'] ?>)" class="text-red-600 hover:text-red-900">Hapus</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (For Recipe only since it's simple) -->
    <div x-show="isModalOpen" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isModalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form @submit.prevent="saveData">
                    <input type="hidden" x-model="formData.product_id">
                    <input type="hidden" x-model="formData.variant_id">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Atur Bahan Baku</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="ingredient_id" class="block text-sm font-medium text-gray-700">Pilih Bahan <span class="text-red-500">*</span></label>
                                <select x-model="formData.ingredient_id" id="ingredient_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="" disabled>-- Pilih Bahan --</option>
                                    <?php foreach($ingredients as $ing): ?>
                                        <option value="<?= $ing['id'] ?>"><?= htmlspecialchars($ing['name']) ?> (<?= htmlspecialchars($ing['unit']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Takaran <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" x-model="formData.quantity" id="quantity" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Sesuaikan dengan satuan bahan yang dipilih.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span x-show="!isSubmitting">Simpan</span>
                            <span x-show="isSubmitting">Menyimpan...</span>
                        </button>
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function recipeManager() {
    return {
        isModalOpen: false,
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
            this.isModalOpen = true;
        },
        
        closeModal() {
            this.isModalOpen = false;
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
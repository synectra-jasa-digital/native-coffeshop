<div class="space-y-6" x-data="ingredientTable()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data bahan baku dan minimum stok.</p>
        </div>
        <a href="<?= BASE_URL ?>/inventory/ingredients/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Bahan Baku
        </a>
    </div>

    <!-- Alert -->
    <?php if (\App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('success')): ?>
        <!-- Handled by Layout Dialog -->
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="bg-surface p-4 rounded-t-lg border border-border border-b-0 flex items-center justify-between">
        <div></div> <!-- Spacer -->
        <div class="relative w-full max-w-xs">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari bahan baku..." class="block w-full rounded-md border border-border py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-b-lg border border-border border-t-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan Baku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Stok</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="paginatedData.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                <span x-text="searchQuery ? 'Tidak ada bahan baku yang cocok.' : 'Belum ada data bahan baku.'"></span>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(ing, index) in paginatedData" :key="ing.id">
                        <tr class="hover:bg-gray-50" :id="'row-' + ing.id">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 tabular-nums" x-text="(currentPage - 1) * itemsPerPage + index + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="ing.name"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="ing.unit"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                      :class="parseFloat(ing.current_stock) <= parseFloat(ing.min_stock) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                      x-text="parseFloat(ing.current_stock)">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="parseFloat(ing.min_stock)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a :href="'<?= BASE_URL ?>/inventory/ingredients/edit/' + ing.id" class="text-primary hover:text-primary-hover p-1.5 rounded hover:bg-primary/10 transition-colors duration-200 cursor-pointer" title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                                    <button @click="confirmDel(ing.id, ing.name)" class="text-danger hover:text-red-800 p-1.5 rounded hover:bg-red-50 transition-colors duration-200 cursor-pointer" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 flex items-center justify-between border-t border-border bg-white sm:px-6" x-show="totalPages > 1" style="display: none;">
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-textSecondary">
                        Menampilkan <span class="font-medium text-textPrimary" x-text="(currentPage - 1) * itemsPerPage + 1"></span> sampai <span class="font-medium text-textPrimary" x-text="Math.min(currentPage * itemsPerPage, filteredData.length)"></span> dari <span class="font-medium text-textPrimary" x-text="filteredData.length"></span> entri
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-textSecondary ring-1 ring-inset ring-border hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)" :class="currentPage === page ? 'relative z-10 inline-flex items-center bg-primary px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary' : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-textPrimary ring-1 ring-inset ring-border hover:bg-gray-50 focus:z-20 focus:outline-offset-0'" x-text="page"></button>
                        </template>
                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-textSecondary ring-1 ring-inset ring-border hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ingredientTable', () => ({
        ingredients: <?= json_encode($ingredients) ?>,
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: 10,
        
        get filteredData() {
            if (this.searchQuery === '') return this.ingredients;
            const q = this.searchQuery.toLowerCase();
            return this.ingredients.filter(i => i.name.toLowerCase().includes(q));
        },
        
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredData.length / this.itemsPerPage));
        },
        
        get paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredData.slice(start, start + this.itemsPerPage);
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        confirmDel(id, name) {
            showDialog('warning', 'Hapus Bahan Baku', `Apakah Anda yakin ingin menghapus bahan baku "${name}"?`, true, async () => {
                try {
                    const response = await fetch(`<?= BASE_URL ?>/inventory/ingredients/delete/${id}`, {
                        method: 'POST'
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Remove from data array
                        this.ingredients = this.ingredients.filter(i => i.id !== id);
                        showDialog('success', 'Berhasil', result.message);
                    } else {
                        showDialog('error', 'Gagal', result.message || 'Gagal menghapus bahan baku.');
                    }
                } catch (error) {
                    showDialog('error', 'Gagal', 'Gagal terhubung ke server.');
                }
            });
        }
    }));
});
</script>


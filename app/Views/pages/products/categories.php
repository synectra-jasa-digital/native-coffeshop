<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/products" class="text-textSecondary hover:text-primary transition-colors bg-surface p-1.5 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="text-xl font-semibold text-textPrimary"><?= htmlspecialchars($title) ?></h2>
    </div>
    <a href="<?= BASE_URL ?>/categories/create" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-primary text-white hover:bg-primary-hover shadow-sm transition-colors duration-200 cursor-pointer">
        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Kategori
    </a>
</div>

<!-- Alpine.js Component for Category Management -->
<div x-data="categoryManager()">

    <!-- Filter & Search -->
    <div class="bg-surface p-4 rounded-t-lg border border-border border-b-0 flex items-center justify-between">
        <div></div> <!-- Left space -->
        <div class="relative max-w-xs w-full">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari kategori..." class="block w-full rounded-md border border-border py-2 pl-10 pr-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-b-lg border border-border bg-surface shadow-sm">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-background">
                <tr>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider w-12">No</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider w-16">Urutan</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Nama Kategori</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider">Jumlah Produk</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-textSecondary uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-textSecondary uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <template x-if="paginatedData.length === 0">
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-textSecondary">
                            <span x-text="searchQuery ? 'Tidak ada kategori yang cocok.' : 'Belum ada kategori yang ditambahkan.'"></span>
                        </td>
                    </tr>
                </template>
                <template x-for="(cat, index) in paginatedData" :key="cat.id">
                    <tr class="hover:bg-background transition-colors duration-200">
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-textSecondary tabular-nums" x-text="(currentPage - 1) * itemsPerPage + index + 1"></td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-textSecondary tabular-nums" x-text="cat.sort_order"></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-textPrimary" x-text="cat.name"></td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-textSecondary tabular-nums" x-text="cat.total_products"></td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span x-show="cat.is_active == 1" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            <span x-show="cat.is_active != 1" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">Nonaktif</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a :href="'<?= BASE_URL ?>/categories/edit/' + cat.id" class="text-primary hover:text-primary-hover p-1.5 rounded hover:bg-primary/10 transition-colors duration-200 cursor-pointer" title="Edit">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button @click="confirmDelete(cat.id)" class="text-danger hover:text-red-800 p-1.5 rounded hover:bg-red-50 transition-colors duration-200 cursor-pointer" title="Hapus">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        
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
    Alpine.data('categoryManager', () => ({
        categories: <?= json_encode($categories) ?>,
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: 10,
        
        get filteredData() {
            if (this.searchQuery === '') return this.categories;
            const q = this.searchQuery.toLowerCase();
            return this.categories.filter(i => i.name.toLowerCase().includes(q));
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
        
        confirmDelete(id) {
            showDialog('warning', 'Hapus Kategori?', 'Yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak dapat dihapus.', true, () => {
                fetch(BASE_URL + '/categories/delete/' + id, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        showDialog('error', 'Gagal', data.message);
                    }
                })
                .catch(error => {
                    showDialog('error', 'Error', 'Terjadi kesalahan sistem.');
                });
            });
        }
    }));
});
</script>
<!-- POS UI using Alpine.js for Cart State Management -->
<div class="h-screen w-full flex flex-col md:flex-row overflow-hidden bg-background relative" x-data="posApp()" :class="{ 'md:flex-row': !showCartOnMobile, 'flex-col': true }">
    
    <!-- TOP NAVIGATION BAR (ONLY FOR POS) -->
    <div class="absolute top-0 left-0 right-0 h-16 bg-surface border-b border-border shadow-sm z-30 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/" class="text-textSecondary hover:text-primary transition-colors bg-background p-2 rounded-md border border-border">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-lg font-bold text-textPrimary">Point of Sale</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden sm:block text-sm font-medium text-textSecondary bg-gray-50 px-3 py-1.5 rounded-full border border-border">
                <?= htmlspecialchars(\App\Core\Session::get('user_name')) ?>
            </div>
            <!-- Mobile Cart Toggle Button -->
            <button @click="showCartOnMobile = !showCartOnMobile" class="md:hidden relative p-2 text-textSecondary hover:text-primary bg-background rounded-md border border-border transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span x-show="totalItems > 0" class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-danger text-[10px] font-bold text-white tabular-nums" x-text="totalItems"></span>
            </button>
        </div>
    </div>

    <!-- LEFT SIDE: Product Grid -->
    <div class="flex-1 flex flex-col h-full bg-surface border-r border-border pt-16 transition-transform duration-300" :class="{ 'hidden md:flex': showCartOnMobile }">
        
        <!-- Category Filter Header -->
        <div class="p-4 border-b border-border bg-white shadow-sm z-10 flex-shrink-0 flex gap-2 overflow-x-auto no-scrollbar">
            <button @click="selectedCategory = 'all'" 
                    :class="selectedCategory === 'all' ? 'bg-primary text-white border-primary' : 'bg-white text-textSecondary border-border hover:bg-gray-50'"
                    class="px-4 py-2 rounded-full border text-sm font-medium whitespace-nowrap transition-colors">
                Semua Kategori
            </button>
            <?php foreach($categories as $cat): ?>
                <button @click="selectedCategory = <?= $cat['id'] ?>" 
                        :class="selectedCategory === <?= $cat['id'] ?> ? 'bg-primary text-white border-primary' : 'bg-white text-textSecondary border-border hover:bg-gray-50'"
                        class="px-4 py-2 rounded-full border text-sm font-medium whitespace-nowrap transition-colors">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Search Bar -->
        <div class="p-4 border-b border-border bg-gray-50 flex-shrink-0">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari menu..." class="block w-full pl-10 pr-3 py-2 border border-border rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-4 bg-background">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div @click="product.is_out_of_stock ? null : selectProduct(product)" 
                         :class="product.is_out_of_stock ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all duration-200'"
                         class="bg-white rounded-xl border border-border overflow-hidden flex flex-col h-full relative group">
                        
                        <!-- Image -->
                        <div class="aspect-w-1 aspect-h-1 w-full bg-gray-100 border-b border-border relative">
                            <template x-if="product.image_url">
                                <img :src="product.image_url" class="w-full h-32 object-cover">
                            </template>
                            <template x-if="!product.image_url">
                                <div class="w-full h-32 flex items-center justify-center bg-gray-50">
                                    <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </template>
                            
                            <template x-if="product.is_out_of_stock">
                                <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded">Habis</span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-3 flex flex-col flex-grow">
                            <h3 class="text-sm font-bold text-gray-900 line-clamp-2" x-text="product.name"></h3>
                            <div class="mt-auto pt-2 flex justify-between items-center">
                                <span class="text-primary font-bold tabular-nums text-sm" x-text="formatRupiah(product.base_price)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div x-show="filteredProducts.length === 0" class="text-center py-10 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p>Tidak ada produk yang cocok dengan pencarian.</p>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE: Cart -->
    <div class="w-full md:w-[380px] lg:w-[420px] flex-col h-full bg-white flex-shrink-0 z-20 shadow-[-4px_0_15px_-3px_rgba(0,0,0,0.05)] pt-16 transition-transform duration-300 absolute inset-0 md:relative md:flex" :class="showCartOnMobile ? 'flex' : 'hidden'">
        
        <!-- Cart Header -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-border bg-white flex-shrink-0">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Pesanan Saat Ini
            </h2>
            <div class="flex items-center gap-4">
                <button @click="clearCart()" x-show="cart.length > 0" class="text-xs text-red-500 hover:text-red-700 font-medium">Kosongkan</button>
                <button @click="showCartOnMobile = false" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Order Type Selector & Table Selection -->
        <div class="p-3 border-b border-gray-100 flex flex-col gap-2 bg-gray-50 flex-shrink-0">
            <div class="flex gap-2">
                <button @click="orderType = 'dine_in'; selectedTable = ''" :class="orderType === 'dine_in' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 border border-border hover:bg-gray-50'" class="flex-1 py-1.5 rounded text-sm font-medium transition-colors">Dine In</button>
                <button @click="orderType = 'take_away'; selectedTable = ''" :class="orderType === 'take_away' ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-600 border border-border hover:bg-gray-50'" class="flex-1 py-1.5 rounded text-sm font-medium transition-colors">Take Away</button>
            </div>
            
            <div x-show="orderType === 'dine_in'" x-collapse>
                <select x-model="selectedTable" class="w-full text-sm border-border rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition-colors py-1.5 px-3">
                    <option value="">-- Pilih Meja --</option>
                    <?php if (isset($tables) && !empty($tables)): ?>
                        <?php foreach($tables as $t): ?>
                            <option value="<?= $t['id'] ?>">Meja <?= htmlspecialchars($t['table_number']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Semua meja penuh</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-white">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-3">
                    <svg class="h-16 w-16 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-sm">Keranjang masih kosong</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.cartId">
                <div class="flex gap-3 bg-white border border-gray-100 rounded-lg p-3 shadow-sm group">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-sm font-bold text-gray-900 truncate pr-2" x-text="item.name"></h4>
                            <span class="text-sm font-bold text-gray-900 tabular-nums" x-text="formatRupiah(item.price * item.qty)"></span>
                        </div>
                        <template x-if="item.variant_name">
                            <p class="text-xs text-indigo-600 mb-1" x-text="'Varian: ' + item.variant_name"></p>
                        </template>
                        
                        <div class="flex items-center justify-between mt-2">
                            <!-- Qty Controls -->
                            <div class="flex items-center bg-gray-50 border border-border rounded-md">
                                <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-gray-100 rounded-l-md transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></button>
                                <span class="w-8 text-center text-sm font-semibold tabular-nums" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-gray-100 rounded-r-md transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                            </div>
                            
                            <div class="flex gap-2">
                                <button @click="openNoteModal(index)" class="text-gray-400 hover:text-indigo-600" title="Catatan">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="item.note ? 'text-indigo-500 fill-indigo-50' : ''"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="removeItem(index)" class="text-gray-400 hover:text-red-500" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Totals & Checkout Panel -->
        <div class="border-t border-border bg-gray-50 p-4 flex-shrink-0">
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-medium tabular-nums text-gray-900" x-text="formatRupiah(totals.subtotal)"></span>
                </div>
                <template x-if="settings.is_tax_active">
                    <div class="flex justify-between text-gray-500">
                        <span x-text="`Pajak (${settings.tax_rate}%)`"></span>
                        <span class="font-medium tabular-nums text-gray-900" x-text="formatRupiah(totals.tax)"></span>
                    </div>
                </template>
                <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span class="tabular-nums" x-text="formatRupiah(totals.grandTotal)"></span>
                </div>
            </div>
            
            <button @click="openPaymentModal()" :disabled="cart.length === 0" 
                    class="w-full py-3.5 bg-primary text-white text-base font-bold rounded-lg shadow-sm hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                Bayar Sekarang
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </div>

    <!-- Variant Selection Modal -->
    <div x-show="showVariantModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="showVariantModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Pilih Varian</h3>
                    <p class="text-sm text-gray-500 mt-1" x-text="selectedProductForVariant?.name"></p>
                </div>
                <div class="p-4 space-y-2 max-h-60 overflow-y-auto">
                    <template x-if="selectedProductForVariant">
                        <template x-for="variant in selectedProductForVariant.variants" :key="variant.id">
                            <button @click="addToCartWithVariant(variant)" class="w-full text-left p-3 border rounded-lg hover:border-primary hover:bg-primary/5 flex justify-between items-center transition-colors">
                                <span class="font-medium text-gray-900" x-text="variant.name"></span>
                                <span class="text-sm text-primary tabular-nums" x-text="formatRupiah(parseFloat(selectedProductForVariant.base_price) + parseFloat(variant.additional_price))"></span>
                            </button>
                        </template>
                    </template>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showVariantModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Note Modal -->
    <div x-show="showNoteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showNoteModal = false"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm relative z-10 p-6">
                <h3 class="text-lg font-bold mb-4">Catatan Pesanan</h3>
                <textarea x-model="tempNote" rows="3" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-primary focus:border-primary" placeholder="Misal: Kurangi gula, ekstra es..."></textarea>
                <div class="mt-4 flex justify-end gap-2">
                    <button @click="showNoteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">Batal</button>
                    <button @click="saveNote()" class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-md">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="!isProcessing ? showPaymentModal = false : null"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative z-10 flex flex-col max-h-[90vh]">
                <div class="p-5 border-b border-border text-center">
                    <h3 class="text-lg font-bold text-gray-900">Pembayaran</h3>
                    <p class="text-3xl font-black text-primary mt-2 tabular-nums" x-text="formatRupiah(totals.grandTotal)"></p>
                </div>
                
                <div class="p-5 overflow-y-auto flex-1 space-y-4">
                    
                    <!-- Table Selection for Dine In -->
                    <div x-show="orderType === 'dine_in'" x-collapse>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Meja <span class="text-danger">*</span></label>
                        <select x-model="selectedTable" class="w-full px-3 py-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition-colors">
                            <option value="">-- Pilih Meja --</option>
                            <?php if (isset($tables) && !empty($tables)): ?>
                                <?php foreach($tables as $t): ?>
                                    <option value="<?= $t['id'] ?>">Meja <?= htmlspecialchars($t['table_number']) ?> <?= isset($t['status']) && $t['status'] === 'occupied' ? '(Terisi)' : '' ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Semua meja penuh / tidak ada data</option>
                            <?php endif; ?>
                        </select>
                        <p class="text-xs text-red-500 mt-1" x-show="orderType === 'dine_in' && !selectedTable">Wajib memilih meja untuk Dine In</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'bg-primary/10 border-primary text-primary' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'" class="p-3 border rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Tunai
                            </button>
                            <button @click="paymentMethod = 'qris_static'" :class="paymentMethod === 'qris_static' ? 'bg-primary/10 border-primary text-primary' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'" class="p-3 border rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                QRIS
                            </button>
                        </div>
                    </div>
                    
                    <div x-show="paymentMethod === 'cash'" class="space-y-3 pt-2">
                        <label class="block text-sm font-medium text-gray-700">Uang Diterima</label>
                        <input type="number" x-model.number="cashReceived" class="w-full text-2xl font-bold p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary tabular-nums" placeholder="0">
                        
                        <!-- Quick Cash Buttons -->
                        <div class="grid grid-cols-3 gap-2">
                            <button @click="cashReceived = totals.grandTotal" class="p-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium">Uang Pas</button>
                            <button @click="cashReceived = Math.ceil(totals.grandTotal / 50000) * 50000" class="p-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium tabular-nums" x-text="formatRupiah(Math.ceil(totals.grandTotal / 50000) * 50000)"></button>
                            <button @click="cashReceived = Math.ceil(totals.grandTotal / 100000) * 100000" class="p-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium tabular-nums" x-text="formatRupiah(Math.ceil(totals.grandTotal / 100000) * 100000)"></button>
                        </div>

                        <div x-show="cashReceived > 0" class="p-3 bg-gray-50 rounded-lg border border-gray-200 mt-2 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Kembalian:</span>
                            <span class="text-lg font-bold" :class="cashReceived < totals.grandTotal ? 'text-red-500' : 'text-green-600'" x-text="formatRupiah(Math.max(0, cashReceived - totals.grandTotal))"></span>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-border flex gap-3">
                    <button @click="showPaymentModal = false" :disabled="isProcessing" class="flex-1 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg shadow-sm hover:bg-gray-50 disabled:opacity-50">
                        Batal
                    </button>
                    <button @click="processCheckout()" 
                            :disabled="isProcessing || (paymentMethod === 'cash' && cashReceived < totals.grandTotal) || (orderType === 'dine_in' && !selectedTable)" 
                            class="flex-1 py-3 bg-primary text-white font-bold rounded-lg shadow-sm hover:bg-primary-hover disabled:opacity-50 flex items-center justify-center">
                        <svg x-show="isProcessing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="isProcessing ? 'Memproses...' : 'Selesai'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('posApp', () => ({
        // Data injected from PHP
        products: <?= json_encode($products) ?>,
        settings: <?= json_encode($settings) ?>,
        
        // State
        searchQuery: '',
        selectedCategory: 'all',
        cart: [],
        orderType: 'dine_in',
        selectedTable: '',
        showCartOnMobile: false,
        
        // Modals
        showVariantModal: false,
        selectedProductForVariant: null,
        
        showNoteModal: false,
        activeNoteIndex: null,
        tempNote: '',
        
        showPaymentModal: false,
        paymentMethod: 'cash',
        cashReceived: 0,
        isProcessing: false,

        init() {
            // Watch for changes in cart to persist to localStorage if needed
            // For now, keep it in memory
        },

        get totalItems() {
            return this.cart.reduce((sum, item) => sum + item.qty, 0);
        },

        get filteredProducts() {
            let result = this.products;
            
            // Filter Category
            if (this.selectedCategory !== 'all') {
                result = result.filter(p => p.category_id == this.selectedCategory);
            }
            
            // Filter Search
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(p => p.name.toLowerCase().includes(q));
            }
            
            return result;
        },

        get totals() {
            let subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let tax = 0;
            
            if (this.settings.is_tax_active) {
                tax = subtotal * (this.settings.tax_rate / 100);
            }
            
            return {
                subtotal: subtotal,
                tax: tax,
                grandTotal: subtotal + tax
            };
        },

        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        },

        selectProduct(product) {
            if (product.variants && product.variants.length > 0) {
                this.selectedProductForVariant = product;
                this.showVariantModal = true;
            } else {
                this.addToCart(product, null);
            }
        },

        addToCartWithVariant(variant) {
            this.addToCart(this.selectedProductForVariant, variant);
            this.showVariantModal = false;
            this.selectedProductForVariant = null;
        },

        addToCart(product, variant) {
            let price = parseFloat(product.base_price);
            let variantName = '';
            let variantId = null;

            if (variant) {
                price += parseFloat(variant.additional_price);
                variantName = variant.name;
                variantId = variant.id;
            }

            // Generate unique cart item ID based on product and variant
            const cartId = product.id + '-' + (variantId || 'base');
            
            // Check if already in cart
            const existingIndex = this.cart.findIndex(item => item.cartId === cartId);
            
            if (existingIndex > -1) {
                this.cart[existingIndex].qty += 1;
            } else {
                this.cart.unshift({ // Add to top
                    cartId: cartId,
                    id: product.id,
                    name: product.name,
                    variant_id: variantId,
                    variant_name: variantName,
                    price: price,
                    qty: 1,
                    note: ''
                });
            }
        },

        updateQty(index, change) {
            const newQty = this.cart[index].qty + change;
            if (newQty > 0) {
                this.cart[index].qty = newQty;
            } else if (newQty === 0) {
                this.removeItem(index);
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        clearCart() {
            showDialog('warning', 'Kosongkan Keranjang?', 'Semua item akan dihapus dari keranjang saat ini.', true, () => {
                this.cart = [];
            });
        },

        openNoteModal(index) {
            this.activeNoteIndex = index;
            this.tempNote = this.cart[index].note;
            this.showNoteModal = true;
        },

        saveNote() {
            if (this.activeNoteIndex !== null) {
                this.cart[this.activeNoteIndex].note = this.tempNote;
            }
            this.showNoteModal = false;
        },

        openPaymentModal() {
            this.cashReceived = 0;
            this.paymentMethod = 'cash';
            this.showPaymentModal = true;
        },

        async processCheckout() {
            if (this.cart.length === 0) return;
            if (this.paymentMethod === 'cash' && this.cashReceived < this.totals.grandTotal) return;

            this.isProcessing = true;

            const payload = {
                order_type: this.orderType,
                table_id: this.orderType === 'dine_in' ? this.selectedTable : null,
                payment_method: this.paymentMethod,
                subtotal: this.totals.subtotal,
                tax_amount: this.totals.tax,
                grand_total: this.totals.grandTotal,
                cash_received: this.cashReceived,
                items: this.cart.map(item => ({
                    id: item.id,
                    variant_id: item.variant_id,
                    qty: item.qty,
                    price: item.price,
                    note: item.note
                }))
            };

            // Ensure base_url formatting is solid without trailing slash
            let apiEndpoint = BASE_URL.replace(/\/$/, '') + '/pos/checkout';
            
            try {
                const response = await fetch(apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 401) {
                         window.location.href = BASE_URL + '/login';
                         return;
                    }
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }

                if (data.success) {
                    this.showPaymentModal = false;
                    this.cart = [];
                    // Gunakan custom dialog dengan style tailwind
                    showDialog('success', 'Transaksi Berhasil', 'Pesanan telah dicatat.', true, () => {
                        window.open(BASE_URL + '/pos/print/' + data.order_id, '_blank', 'width=400,height=600');
                    });
                } else {
                    showDialog('error', 'Transaksi Gagal', data.message || 'Terjadi kesalahan sistem.');
                }
            } catch (error) {
                console.error("Checkout Error:", error);
                showDialog('error', 'Error', error.message || 'Gagal terhubung ke server.');
            } finally {
                this.isProcessing = false;
            }
        }
    }));
});
</script>
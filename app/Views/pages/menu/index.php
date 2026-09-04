<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Google Fonts: Playfair Display & Inter (Persuade Mode) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased pb-24" x-data="customerApp()">

    <!-- Header / Hero Section -->
    <div class="bg-brand-900 text-white px-4 py-8 md:py-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent"></div>
        <div class="relative z-10 max-w-lg mx-auto text-center">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl mx-auto mb-4 flex items-center justify-center border border-white/20">
                <span class="text-3xl font-serif font-bold text-white leading-none">G</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-serif font-bold mb-2">Good Coffee</h1>
            <p class="text-brand-100 font-medium tracking-wide text-sm md:text-base">Meja <?= htmlspecialchars($table['table_number']) ?></p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-lg mx-auto px-4 -mt-6 relative z-20">
        <!-- Categories (Horizontal Scroll) -->
        <div class="bg-white rounded-xl shadow-lg shadow-gray-200/50 p-2 flex overflow-x-auto no-scrollbar gap-2 mb-8 sticky top-4 z-30 border border-gray-100">
            <button @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-brand-900 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="whitespace-nowrap px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 ease-out">
                Semua
            </button>
            <?php foreach($categories as $cat): ?>
                <button @click="activeCategory = <?= $cat['id'] ?>; document.getElementById('category-<?= $cat['id'] ?>').scrollIntoView({behavior: 'smooth', block: 'start'})" 
                        :class="activeCategory === <?= $cat['id'] ?> ? 'bg-brand-900 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                        class="whitespace-nowrap px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 ease-out">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Menu List -->
        <div class="space-y-10">
            <?php foreach($categories as $cat): ?>
                <?php if(!empty($menuData[$cat['id']])): ?>
                    <div id="category-<?= $cat['id'] ?>" class="scroll-mt-24">
                        <h2 class="text-2xl font-serif font-bold text-gray-900 mb-4 px-1"><?= htmlspecialchars($cat['name']) ?></h2>
                        <div class="space-y-4">
                            <?php foreach($menuData[$cat['id']] as $prod): ?>
                                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex gap-4 hover:shadow-md transition-shadow">
                                    <!-- Image Placeholder -->
                                    <div class="w-24 h-24 bg-gray-100 rounded-xl flex-shrink-0 flex items-center justify-center border border-gray-200">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-1 flex flex-col justify-between py-1">
                                        <div>
                                            <h3 class="font-bold text-gray-900 leading-tight"><?= htmlspecialchars($prod['name']) ?></h3>
                                            <?php if(!empty($prod['description'])): ?>
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($prod['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="font-semibold text-brand-700">Rp <?= number_format($prod['base_price'], 0, ',', '.') ?></span>
                                            
                                            <?php
                                            // Prepare product data for JS
                                            $prodJs = [
                                                'id' => $prod['id'],
                                                'name' => $prod['name'],
                                                'base_price' => (float)$prod['base_price'],
                                                'variants' => array_map(function($v) {
                                                    return [
                                                        'id' => $v['id'],
                                                        'name' => $v['name'],
                                                        'additional_price' => (float)$v['additional_price']
                                                    ];
                                                }, $prod['variants'])
                                            ];
                                            ?>
                                            <button @click="openProductModal(<?= htmlspecialchars(json_encode($prodJs)) ?>)" 
                                                    class="w-8 h-8 bg-brand-50 text-brand-700 rounded-full flex items-center justify-center hover:bg-brand-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Product Detail Modal (Add to Cart) -->
    <div x-show="isModalOpen" style="display: none;" class="relative z-50">
        <!-- Backdrop -->
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        
        <!-- Bottom Sheet -->
        <div class="fixed inset-0 z-10 flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div x-show="isModalOpen" 
                 @click.away="closeProductModal()"
                 x-transition:enter="transform transition ease-out duration-300" 
                 x-transition:enter-start="translate-y-full sm:translate-y-4 sm:opacity-0" 
                 x-transition:enter-end="translate-y-0 sm:translate-y-0 sm:opacity-100" 
                 x-transition:leave="transform transition ease-in duration-200" 
                 x-transition:leave-start="translate-y-0 sm:translate-y-0 sm:opacity-100" 
                 x-transition:leave-end="translate-y-full sm:translate-y-4 sm:opacity-0" 
                 class="bg-white w-full max-w-lg rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Pull Indicator (Mobile) -->
                <div class="w-full flex justify-center pt-3 pb-1 sm:hidden">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>
                
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-serif font-bold text-gray-900" x-text="selectedProduct?.name"></h2>
                        <p class="text-brand-700 font-semibold mt-1" x-text="formatRupiah(selectedProduct?.base_price)"></p>
                    </div>
                    <button @click="closeProductModal()" class="w-8 h-8 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center hover:bg-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-6 overflow-y-auto">
                    <!-- Variants Selection -->
                    <template x-if="selectedProduct?.variants?.length > 0">
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Pilih Varian</h3>
                            <div class="space-y-2">
                                <!-- Default (No variant) -->
                                <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-colors"
                                       :class="selectedVariant === null ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                             :class="selectedVariant === null ? 'border-brand-500 bg-brand-500' : 'border-gray-300'">
                                            <div class="w-2 h-2 bg-white rounded-full" x-show="selectedVariant === null"></div>
                                        </div>
                                        <span class="font-medium text-gray-900">Original (Default)</span>
                                    </div>
                                    <span class="text-sm text-gray-500">+ Rp 0</span>
                                    <input type="radio" name="variant" :value="null" @change="selectedVariant = null" class="hidden">
                                </label>
                                
                                <!-- Product Variants -->
                                <template x-for="v in selectedProduct.variants" :key="v.id">
                                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-colors"
                                           :class="selectedVariant?.id === v.id ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:bg-gray-50'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                                 :class="selectedVariant?.id === v.id ? 'border-brand-500 bg-brand-500' : 'border-gray-300'">
                                                <div class="w-2 h-2 bg-white rounded-full" x-show="selectedVariant?.id === v.id"></div>
                                            </div>
                                            <span class="font-medium text-gray-900" x-text="v.name"></span>
                                        </div>
                                        <span class="text-sm text-gray-500" x-text="'+ ' + formatRupiah(v.additional_price)"></span>
                                        <input type="radio" name="variant" :value="v" @change="selectedVariant = v" class="hidden">
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Notes -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Catatan Tambahan</h3>
                        <textarea x-model="itemNotes" rows="2" class="w-full border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent text-sm placeholder-gray-400 bg-gray-50 focus:bg-white transition-colors" placeholder="Misal: Kurangi es, banyakin gula..."></textarea>
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="p-6 border-t border-gray-100 bg-white">
                    <div class="flex items-center justify-between gap-6">
                        <!-- Qty Selector -->
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50 h-14">
                            <button @click="quantity > 1 ? quantity-- : null" class="w-12 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <div class="w-10 text-center font-bold text-gray-900" x-text="quantity"></div>
                            <button @click="quantity++" class="w-12 h-full flex items-center justify-center text-brand-600 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Add Button -->
                        <button @click="addToCart()" class="flex-1 h-14 bg-brand-900 text-white rounded-xl font-bold flex items-center justify-between px-6 hover:bg-brand-800 transition-colors shadow-lg shadow-brand-900/30">
                            <span>Tambah</span>
                            <span x-text="formatRupiah((selectedProduct?.base_price + (selectedVariant?.additional_price || 0)) * quantity)"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Cart Button -->
    <div x-show="cart.length > 0" x-transition.opacity class="fixed bottom-6 left-0 right-0 z-40 px-4 flex justify-center pointer-events-none" style="display: none;">
        <div class="max-w-lg w-full">
            <button @click="isCartOpen = true" class="w-full bg-gray-900 text-white rounded-2xl p-4 shadow-xl shadow-gray-900/20 flex items-center justify-between pointer-events-auto hover:bg-black transition-transform active:scale-[0.98]">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                        <span x-text="totalCartItems()"></span>
                    </div>
                    <span class="font-medium text-sm text-gray-200">Lihat Pesanan</span>
                </div>
                <div class="font-bold text-lg" x-text="formatRupiah(cartTotal())"></div>
            </button>
        </div>
    </div>

    <!-- Cart Sidebar/Modal -->
    <div x-show="isCartOpen" style="display: none;" class="relative z-50">
        <!-- Backdrop -->
        <div x-show="isCartOpen" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        
        <!-- Cart Panel -->
        <div class="fixed inset-y-0 right-0 z-10 flex">
            <div x-show="isCartOpen" 
                 @click.away="isCartOpen = false"
                 x-transition:enter="transform transition ease-in-out duration-300" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transform transition ease-in-out duration-300" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col">
                
                <!-- Header -->
                <div class="p-5 border-b border-gray-100 flex items-center gap-3 bg-white">
                    <button @click="isCartOpen = false" class="w-10 h-10 bg-gray-50 text-gray-600 rounded-full flex items-center justify-center hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <h2 class="text-xl font-serif font-bold text-gray-900">Pesanan Anda</h2>
                </div>
                
                <!-- Items -->
                <div class="flex-1 overflow-y-auto p-5 bg-gray-50">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <p>Keranjang kosong</p>
                        </div>
                    </template>
                    
                    <div class="space-y-4" x-show="cart.length > 0">
                        <template x-for="(item, index) in cart" :key="index">
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900" x-text="item.name"></h3>
                                    <p x-show="item.variant" class="text-xs text-brand-700 font-medium mt-1" x-text="item.variant?.name"></p>
                                    <p x-show="item.notes" class="text-xs text-gray-500 italic mt-1" x-text="'Catatan: ' + item.notes"></p>
                                    <div class="text-brand-900 font-bold mt-2" x-text="formatRupiah(item.price)"></div>
                                </div>
                                <div class="flex flex-col items-end justify-between">
                                    <button @click="removeFromCart(index)" class="text-gray-400 hover:text-red-500 p-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg overflow-hidden h-8">
                                        <button @click="updateQty(index, -1)" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200">-</button>
                                        <div class="w-6 text-center text-sm font-bold text-gray-900" x-text="item.qty"></div>
                                        <button @click="updateQty(index, 1)" class="w-8 h-full flex items-center justify-center text-brand-600 hover:bg-gray-200">+</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Cart Footer -->
                <div class="p-5 border-t border-gray-100 bg-white" x-show="cart.length > 0">
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span class="font-medium" x-text="formatRupiah(cartTotal())"></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>PPN (11%)</span>
                            <span class="font-medium" x-text="formatRupiah(cartTotal() * 0.11)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t border-dashed border-gray-200">
                            <span>Total</span>
                            <span x-text="formatRupiah(cartTotal() * 1.11)"></span>
                        </div>
                    </div>
                    
                    <button @click="submitOrder()" :disabled="isSubmitting" class="w-full py-4 bg-brand-900 text-white rounded-xl font-bold flex items-center justify-center hover:bg-brand-800 transition-colors shadow-lg shadow-brand-900/30 disabled:opacity-50">
                        <span x-show="!isSubmitting">Pesan Sekarang</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div x-show="orderSuccess" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative z-10 text-center shadow-2xl transform transition-all">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-2">Pesanan Berhasil!</h2>
            <p class="text-gray-500 mb-8">Pesanan Anda telah masuk ke dapur kami dan akan segera diantar ke Meja <?= htmlspecialchars($table['table_number']) ?>.</p>
            <button @click="window.location.reload()" class="w-full py-3.5 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-colors">
                Tutup & Pesan Lagi
            </button>
        </div>
    </div>

    <script>
        function customerApp() {
            return {
                activeCategory: 'all',
                cart: [],
                isCartOpen: false,
                isModalOpen: false,
                orderSuccess: false,
                isSubmitting: false,
                
                // Modal State
                selectedProduct: null,
                selectedVariant: null,
                quantity: 1,
                itemNotes: '',
                
                formatRupiah(amount) {
                    if(!amount) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                },
                
                openProductModal(product) {
                    this.selectedProduct = product;
                    this.selectedVariant = null;
                    this.quantity = 1;
                    this.itemNotes = '';
                    this.isModalOpen = true;
                    // Pre-select variant if only one exists, but usually we let user click default
                },
                
                closeProductModal() {
                    this.isModalOpen = false;
                    setTimeout(() => { this.selectedProduct = null; }, 300);
                },
                
                addToCart() {
                    if(!this.selectedProduct) return;
                    
                    const price = parseFloat(this.selectedProduct.base_price) + (this.selectedVariant ? parseFloat(this.selectedVariant.additional_price) : 0);
                    
                    // Check if identical item exists (same product, same variant, same notes)
                    const existingIndex = this.cart.findIndex(i => 
                        i.product_id === this.selectedProduct.id && 
                        (i.variant?.id || null) === (this.selectedVariant?.id || null) &&
                        i.notes === this.itemNotes
                    );
                    
                    if (existingIndex >= 0) {
                        this.cart[existingIndex].qty += this.quantity;
                    } else {
                        this.cart.push({
                            product_id: this.selectedProduct.id,
                            name: this.selectedProduct.name,
                            variant: this.selectedVariant,
                            notes: this.itemNotes,
                            price: price,
                            qty: this.quantity
                        });
                    }
                    
                    this.closeProductModal();
                },
                
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    if(this.cart.length === 0) this.isCartOpen = false;
                },
                
                updateQty(index, change) {
                    const newQty = this.cart[index].qty + change;
                    if (newQty > 0) {
                        this.cart[index].qty = newQty;
                    } else if (newQty === 0) {
                        this.removeFromCart(index);
                    }
                },
                
                totalCartItems() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },
                
                cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },
                
                async submitOrder() {
                    if (this.cart.length === 0) return;
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch('<?= BASE_URL ?>/menu/submit', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                table_id: <?= $table['id'] ?>,
                                qr_token: '<?= $table['qr_code'] ?>',
                                items: this.cart,
                                subtotal: this.cartTotal(),
                                tax: this.cartTotal() * 0.11, // Match POS setting later
                                total: this.cartTotal() * 1.11
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.cart = [];
                            this.isCartOpen = false;
                            this.orderSuccess = true;
                        } else {
                            alert(result.message || 'Gagal mengirim pesanan.');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan jaringan.');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
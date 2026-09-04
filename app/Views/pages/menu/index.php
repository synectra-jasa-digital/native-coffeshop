<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Google Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine JS App Function -->
    <script>
        function customerAppDefinition() {
            return {
                activeCategory: 'all',
                searchQuery: '',
                cart: [],
                isCartOpen: false,
                isModalOpen: false,
                orderSuccess: false,
                isSubmitting: false,
                
                selectedProduct: null,
                selectedVariant: null,
                quantity: 1,
                itemNotes: '',
                
                formatRupiah(amount) {
                    if(!amount && amount !== 0) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
                },

                scrollToCategory(catId) {
                    const el = document.getElementById('category-' + catId);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },

                shouldShowCategory(catId) {
                    if (this.activeCategory !== 'all' && this.activeCategory !== catId) {
                        return false;
                    }
                    return true;
                },

                shouldShowProduct(name, desc) {
                    if (!this.searchQuery || this.searchQuery.trim() === '') return true;
                    const q = this.searchQuery.toLowerCase().trim();
                    return name.toLowerCase().includes(q) || (desc && desc.toLowerCase().includes(q));
                },

                openProductModal(product) {
                    this.selectedProduct = product;
                    this.selectedVariant = null;
                    this.quantity = 1;
                    this.itemNotes = '';
                    this.isModalOpen = true;
                },
                
                closeProductModal() {
                    this.isModalOpen = false;
                    setTimeout(() => { this.selectedProduct = null; }, 300);
                },
                
                addToCart() {
                    if(!this.selectedProduct) return;
                    
                    const price = parseFloat(this.selectedProduct.base_price) + (this.selectedVariant ? parseFloat(this.selectedVariant.additional_price) : 0);
                    
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
                                tax: this.cartTotal() * 0.11,
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
            };
        }

        // Global fallback for x-data="customerApp()"
        function customerApp() {
            return customerAppDefinition();
        }

        // Alpine 3 component registration
        document.addEventListener('alpine:init', () => {
            Alpine.data('customerApp', customerAppDefinition);
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#398263',
                            hover: '#2C6B4F',
                            light: '#E8F5E9'
                        },
                        brandDark: '#1b4332',
                        background: '#F8FAFC',
                        surface: '#FFFFFF',
                        border: '#E2E8F0',
                        textPrimary: '#1E293B',
                        textSecondary: '#64748B'
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
        .tabular-nums { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="bg-background text-textPrimary font-sans antialiased min-h-screen pb-28 lg:pb-12" x-data="customerApp">

    <?php
    $rawTableNum = $table['table_number'] ?? '01';
    $cleanTableNum = trim(preg_replace('/^meja\s*/i', '', $rawTableNum));
    ?>
    <!-- TOP HEADER / HERO BANNER -->
    <header class="bg-gradient-to-r from-[#123829] via-[#1b4332] to-[#2d6a4f] text-white relative overflow-hidden shadow-lg border-b border-emerald-800/40">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-emerald-300/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6 relative z-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                
                <!-- Left: Branding & Coffee Icon -->
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-400/20 to-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-inner shrink-0 group overflow-hidden">
                        <?php if (!empty($storeInfo['logo'])): ?>
                            <img src="<?= htmlspecialchars($storeInfo['logo']) ?>" alt="<?= htmlspecialchars($storeInfo['name']) ?>" class="w-full h-full object-contain p-1 group-hover:scale-110 transition-transform duration-300">
                        <?php else: ?>
                            <svg class="w-7 h-7 text-emerald-300 transform group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] sm:text-xs text-emerald-300/90 font-semibold tracking-widest uppercase">Digital Menu</span>
                            <span class="text-white/30">•</span>
                            <span class="text-[10px] sm:text-xs text-emerald-100/70 font-medium"><?= htmlspecialchars($storeInfo['name'] ?? 'Good Coffee') ?></span>
                        </div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-serif font-bold text-white tracking-tight leading-tight mt-0.5">
                            <?= htmlspecialchars($storeInfo['name'] ?? 'Good Coffee Shop') ?>
                        </h1>
                        <p class="text-xs sm:text-sm text-emerald-100/80 mt-0.5 hidden sm:block">
                            Nikmati sajian kopi rasa autentik terbaik langsung di meja Anda.
                        </p>
                    </div>
                </div>
                
                <!-- Right: Clean Table Status Badge -->
                <div class="self-stretch sm:self-auto flex items-center justify-between sm:justify-end gap-3 bg-black/20 backdrop-blur-md border border-white/15 px-4 py-2 rounded-2xl shadow-inner">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                        </span>
                        <span class="text-xs text-emerald-200/80 font-medium">Status Meja</span>
                    </div>
                    <span class="text-sm sm:text-base font-bold tracking-wide uppercase text-white bg-emerald-700/60 px-3 py-1 rounded-xl border border-emerald-400/30 shadow-sm">
                        Meja <?= htmlspecialchars($cleanTableNum) ?>
                    </span>
                </div>

            </div>
        </div>
    </header>

    <!-- MAIN RESPONSIVE CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        
        <!-- SEARCH & CATEGORY BAR (STICKY) -->
        <div class="sticky top-2 z-30 mb-8 space-y-3 bg-background/95 backdrop-blur-md pt-2 pb-3 border-b border-border/40">
            <!-- Live Search Bar -->
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-textSecondary">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari menu kopi, minuman, atau makanan..." 
                       class="block w-full pl-11 pr-4 py-2.5 bg-surface border border-border rounded-xl text-sm text-textPrimary placeholder-textSecondary shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <button x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-textSecondary hover:text-textPrimary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Categories Horizontal Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                <button @click="activeCategory = 'all'" 
                        :class="activeCategory === 'all' ? 'bg-primary text-white shadow-md shadow-primary/20 border-primary' : 'bg-surface text-textSecondary border-border hover:bg-gray-100 hover:text-textPrimary'"
                        class="whitespace-nowrap px-4 py-2 rounded-full border text-xs sm:text-sm font-semibold transition-all duration-200 cursor-pointer">
                    Semua Menu
                </button>
                <?php foreach($categories as $cat): ?>
                    <button @click="activeCategory = <?= $cat['id'] ?>; scrollToCategory(<?= $cat['id'] ?>)" 
                            :class="activeCategory === <?= $cat['id'] ?> ? 'bg-primary text-white shadow-md shadow-primary/20 border-primary' : 'bg-surface text-textSecondary border-border hover:bg-gray-100 hover:text-textPrimary'"
                            class="whitespace-nowrap px-4 py-2 rounded-full border text-xs sm:text-sm font-semibold transition-all duration-200 cursor-pointer">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- SPLIT LAYOUT FOR DESKTOP (MAIN MENU + STICKY DESKTOP CART) -->
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-start">
            
            <!-- LEFT COLUMN: MENU PRODUCTS (GRID) -->
            <div class="lg:col-span-8 xl:col-span-8 space-y-10">
                <?php foreach($categories as $cat): ?>
                    <?php if(!empty($menuData[$cat['id']])): ?>
                        <section id="category-<?= $cat['id'] ?>" class="scroll-mt-36" x-show="shouldShowCategory(<?= $cat['id'] ?>)">
                            <div class="flex items-center justify-between mb-4 border-b border-border/80 pb-2">
                                <h2 class="text-xl sm:text-2xl font-serif font-bold text-textPrimary flex items-center gap-2">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </h2>
                                <span class="text-xs font-semibold text-textSecondary bg-gray-200/60 px-2.5 py-1 rounded-full">
                                    <?= count($menuData[$cat['id']]) ?> Menu
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                                <?php foreach($menuData[$cat['id']] as $prod): ?>
                                    <?php
                                    $prodJs = [
                                        'id' => $prod['id'],
                                        'category_id' => $prod['category_id'],
                                        'name' => $prod['name'],
                                        'description' => $prod['description'] ?? '',
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
                                    
                                    <div x-show="shouldShowProduct(<?= htmlspecialchars(json_encode($prod['name']), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars(json_encode($prod['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)"
                                         class="bg-surface rounded-2xl border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                                        
                                        <div>
                                            <!-- Product Image -->
                                            <div class="w-full h-40 bg-gray-100 relative overflow-hidden flex items-center justify-center border-b border-border/60">
                                                <?php if (!empty($prod['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" 
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                <?php else: ?>
                                                    <div class="flex flex-col items-center justify-center text-textSecondary/60">
                                                        <svg class="w-10 h-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                        <span class="text-xs font-medium">Good Coffee</span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($prod['variants'])): ?>
                                                    <span class="absolute top-2.5 right-2.5 bg-surface/90 backdrop-blur-sm text-primary border border-primary/20 text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                        <?= count($prod['variants']) ?> Varian
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="p-4">
                                                <h3 class="font-bold text-textPrimary text-base leading-snug group-hover:text-primary transition-colors">
                                                    <?= htmlspecialchars($prod['name']) ?>
                                                </h3>
                                                <?php if(!empty($prod['description'])): ?>
                                                    <p class="text-xs text-textSecondary mt-1 line-clamp-2 leading-relaxed">
                                                        <?= htmlspecialchars($prod['description']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Price & Add Button -->
                                        <div class="px-4 pb-4 pt-2 flex items-center justify-between border-t border-border/40 mt-auto">
                                            <div>
                                                <span class="text-xs text-textSecondary block font-medium">Harga</span>
                                                <span class="font-bold text-primary tabular-nums text-sm sm:text-base">
                                                    Rp <?= number_format($prod['base_price'], 0, ',', '.') ?>
                                                </span>
                                            </div>

                                            <button @click="openProductModal(<?= htmlspecialchars(json_encode($prodJs), ENT_QUOTES, 'UTF-8') ?>)" 
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-hover active:scale-95 transition-all shadow-sm cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                <span>Tambah</span>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- RIGHT COLUMN: DESKTOP CART SIDEBAR (HIDDEN ON MOBILE, VISIBLE ON LG+) -->
            <div class="hidden lg:block lg:col-span-4 xl:col-span-4 sticky top-24">
                <div class="bg-surface border border-border rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <!-- Sidebar Header -->
                    <div class="p-4 bg-gray-50/80 border-b border-border flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <h2 class="font-bold text-textPrimary text-base">Pesanan Meja <?= htmlspecialchars($table['table_number']) ?></h2>
                        </div>
                        <span x-show="cart.length > 0" class="text-xs font-semibold bg-primary-light text-primary px-2.5 py-1 rounded-full tabular-nums" x-text="totalCartItems() + ' item'"></span>
                    </div>

                    <!-- Items List -->
                    <div class="p-4 max-h-[420px] overflow-y-auto space-y-3 bg-surface">
                        <template x-if="cart.length === 0">
                            <div class="py-10 text-center text-textSecondary">
                                <svg class="w-12 h-12 mx-auto text-border mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <p class="text-sm font-medium">Keranjang masih kosong</p>
                                <p class="text-xs text-textSecondary mt-1">Pilih menu favorit Anda dari daftar di samping.</p>
                            </div>
                        </template>

                        <template x-for="(item, index) in cart" :key="index">
                            <div class="p-3 bg-background rounded-xl border border-border/80 flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-textPrimary text-sm leading-tight" x-text="item.name"></h4>
                                    <p x-show="item.variant" class="text-xs font-medium text-primary mt-0.5" x-text="item.variant?.name"></p>
                                    <p x-show="item.notes" class="text-xs text-textSecondary italic mt-0.5" x-text="'Catatan: ' + item.notes"></p>
                                    <div class="text-xs font-bold text-textPrimary tabular-nums mt-1.5" x-text="formatRupiah(item.price * item.qty)"></div>
                                </div>
                                <div class="flex items-center gap-1 bg-surface border border-border rounded-lg p-0.5">
                                    <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-textSecondary hover:bg-gray-100 rounded text-xs font-bold">-</button>
                                    <span class="w-5 text-center text-xs font-bold tabular-nums text-textPrimary" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-primary hover:bg-gray-100 rounded text-xs font-bold">+</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Summary & Submit -->
                    <div x-show="cart.length > 0" class="p-4 border-t border-border bg-gray-50/50 space-y-3">
                        <div class="space-y-1.5 text-xs text-textSecondary">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-textPrimary tabular-nums" x-text="formatRupiah(cartTotal())"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>PPN (11%)</span>
                                <span class="font-semibold text-textPrimary tabular-nums" x-text="formatRupiah(cartTotal() * 0.11)"></span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-textPrimary pt-2 border-t border-border">
                                <span>Total Pesanan</span>
                                <span class="text-primary tabular-nums" x-text="formatRupiah(cartTotal() * 1.11)"></span>
                            </div>
                        </div>

                        <button @click="submitOrder()" :disabled="isSubmitting" 
                                class="w-full py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover transition-colors shadow-sm disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!isSubmitting">Kirim Pesanan Ke Kasir/Dapur</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MOBILE FLOATING CART BAR (VISIBLE ON MOBILE ONLY) -->
    <div x-show="cart.length > 0" x-transition.opacity class="lg:hidden fixed bottom-4 left-4 right-4 z-40">
        <button @click="isCartOpen = true" class="w-full bg-brandDark text-white rounded-2xl p-4 shadow-xl flex items-center justify-between active:scale-95 transition-transform cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm tabular-nums">
                    <span x-text="totalCartItems()"></span>
                </div>
                <span class="font-medium text-sm text-green-100">Lihat Keranjang</span>
            </div>
            <div class="font-bold text-base tabular-nums" x-text="formatRupiah(cartTotal() * 1.11)"></div>
        </button>
    </div>

    <!-- MOBILE CART DRAWER (SLIDE-UP SHEET) -->
    <div x-show="isCartOpen" class="lg:hidden relative z-50" x-cloak>
        <div x-show="isCartOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="isCartOpen = false"></div>
        <div class="fixed inset-x-0 bottom-0 z-10 bg-surface rounded-t-3xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto my-3 shrink-0"></div>
            
            <div class="px-5 pb-3 border-b border-border flex items-center justify-between">
                <h3 class="font-bold text-lg text-textPrimary">Pesanan Meja <?= htmlspecialchars($table['table_number']) ?></h3>
                <button @click="isCartOpen = false" class="p-1 text-textSecondary hover:text-textPrimary"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="p-5 overflow-y-auto flex-1 space-y-3">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="p-3 bg-background rounded-xl border border-border flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h4 class="font-bold text-textPrimary text-sm" x-text="item.name"></h4>
                            <p x-show="item.variant" class="text-xs text-primary font-medium mt-0.5" x-text="item.variant?.name"></p>
                            <p x-show="item.notes" class="text-xs text-textSecondary italic mt-0.5" x-text="'Catatan: ' + item.notes"></p>
                            <div class="text-xs font-bold text-textPrimary tabular-nums mt-1.5" x-text="formatRupiah(item.price * item.qty)"></div>
                        </div>
                        <div class="flex items-center gap-1 bg-surface border border-border rounded-lg p-0.5">
                            <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-textSecondary font-bold text-sm">-</button>
                            <span class="w-6 text-center text-xs font-bold tabular-nums text-textPrimary" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-primary font-bold text-sm">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-5 border-t border-border bg-gray-50 space-y-3">
                <div class="space-y-1 text-xs text-textSecondary">
                    <div class="flex justify-between"><span>Subtotal</span><span x-text="formatRupiah(cartTotal())"></span></div>
                    <div class="flex justify-between"><span>PPN (11%)</span><span x-text="formatRupiah(cartTotal() * 0.11)"></span></div>
                    <div class="flex justify-between text-sm font-bold text-textPrimary pt-2 border-t border-border"><span>Total</span><span class="text-primary" x-text="formatRupiah(cartTotal() * 1.11)"></span></div>
                </div>

                <button @click="submitOrder()" :disabled="isSubmitting" class="w-full py-3.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover transition-colors shadow-md disabled:opacity-50 cursor-pointer">
                    <span x-show="!isSubmitting">Kirim Pesanan Ke Kasir/Dapur</span>
                    <span x-show="isSubmitting">Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- PRODUCT DETAIL & VARIANT MODAL -->
    <div x-show="isModalOpen" class="relative z-50" x-cloak style="display: none;">
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeProductModal()"></div>
        <div class="fixed inset-0 z-10 flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div x-show="isModalOpen" 
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
                 x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                 class="bg-surface w-full max-w-lg rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-border flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-serif font-bold text-textPrimary" x-text="selectedProduct?.name"></h2>
                        <p class="text-primary font-bold mt-1 text-sm tabular-nums" x-text="formatRupiah(selectedProduct?.base_price)"></p>
                    </div>
                    <button @click="closeProductModal()" class="w-8 h-8 bg-gray-100 text-textSecondary rounded-full flex items-center justify-center hover:bg-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-5 overflow-y-auto space-y-5">
                    <!-- Variant Selection -->
                    <template x-if="selectedProduct?.variants?.length > 0">
                        <div>
                            <label class="block text-xs font-bold text-textSecondary uppercase tracking-wider mb-2">Pilih Varian Menu</label>
                            <div class="space-y-2">
                                <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-colors"
                                       :class="selectedVariant === null ? 'border-primary bg-primary-light/40' : 'border-border hover:bg-gray-50'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="variant" :value="null" @change="selectedVariant = null" :checked="selectedVariant === null" class="text-primary focus:ring-primary">
                                        <span class="font-semibold text-sm text-textPrimary">Original (Standar)</span>
                                    </div>
                                    <span class="text-xs font-medium text-textSecondary">+ Rp 0</span>
                                </label>

                                <template x-for="v in selectedProduct.variants" :key="v.id">
                                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer transition-colors"
                                           :class="selectedVariant?.id === v.id ? 'border-primary bg-primary-light/40' : 'border-border hover:bg-gray-50'">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="variant" :value="v" @change="selectedVariant = v" :checked="selectedVariant?.id === v.id" class="text-primary focus:ring-primary">
                                            <span class="font-semibold text-sm text-textPrimary" x-text="v.name"></span>
                                        </div>
                                        <span class="text-xs font-medium text-textSecondary" x-text="'+ ' + formatRupiah(v.additional_price)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Notes -->
                    <div>
                        <label for="itemNotes" class="block text-xs font-bold text-textSecondary uppercase tracking-wider mb-1.5">Catatan Khusus</label>
                        <textarea id="itemNotes" x-model="itemNotes" rows="2" class="w-full border border-border rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent placeholder-textSecondary/50 bg-background" placeholder="Misal: Kurangi manis, tanpa es, ekstra shot..."></textarea>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-5 border-t border-border bg-gray-50/60 flex items-center justify-between gap-4">
                    <div class="flex items-center border border-border rounded-xl overflow-hidden bg-surface h-11">
                        <button @click="quantity > 1 ? quantity-- : null" class="w-10 h-full flex items-center justify-center text-textSecondary hover:bg-gray-100 font-bold text-sm">-</button>
                        <span class="w-8 text-center font-bold text-textPrimary text-sm tabular-nums" x-text="quantity"></span>
                        <button @click="quantity++" class="w-10 h-full flex items-center justify-center text-primary hover:bg-gray-100 font-bold text-sm">+</button>
                    </div>
                    
                    <button @click="addToCart()" class="flex-1 h-11 bg-primary text-white rounded-xl font-bold text-sm flex items-center justify-between px-5 hover:bg-primary-hover transition-colors shadow-sm cursor-pointer">
                        <span>Tambah Ke Keranjang</span>
                        <span class="tabular-nums" x-text="formatRupiah((selectedProduct?.base_price + (selectedVariant?.additional_price || 0)) * quantity)"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS ORDER CONFIRMATION MODAL -->
    <div x-show="orderSuccess" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-surface rounded-3xl p-8 max-w-sm w-full relative z-10 text-center shadow-2xl border border-border">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 text-primary">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-serif font-bold text-textPrimary mb-2">Pesanan Berhasil Dikirim!</h2>
            <p class="text-sm text-textSecondary mb-6">Pesanan Anda telah diterima oleh Dapur/Barista dan sedang diproses untuk Meja <strong class="text-textPrimary"><?= htmlspecialchars($table['table_number']) ?></strong>.</p>
            <button @click="window.location.reload()" class="w-full py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover transition-colors shadow-md cursor-pointer">
                Pesankan Menu Lain
            </button>
        </div>
    </div>
</body>
</html>
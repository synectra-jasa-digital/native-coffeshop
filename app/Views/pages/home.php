<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, <?= htmlspecialchars($userName) ?>!</h1>
            <p class="text-gray-500 mt-1">Selamat datang di Dashboard <?= htmlspecialchars($userRole) ?>.</p>
        </div>
    </div>

    <!-- Admin & Manager Dashboard -->
    <?php if (in_array($userRole, ['Admin', 'Manager'])): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Omzet Hari Ini</p>
                <h3 class="text-xl font-bold text-gray-900">Rp <?= number_format($today_revenue ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                <h3 class="text-xl font-bold text-gray-900"><?= number_format($today_trx ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                <h3 class="text-xl font-bold text-gray-900"><?= number_format($low_stock ?? 0, 0, ',', '.') ?> Item</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Produk Aktif</p>
                <h3 class="text-xl font-bold text-gray-900"><?= number_format($active_products ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <h2 class="text-lg font-bold text-gray-900 mt-8 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?= BASE_URL ?>/reports/harian" class="bg-white p-4 rounded-xl border border-gray-200 text-center hover:border-primary hover:text-primary transition-colors group">
            <div class="w-10 h-10 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-primary/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <span class="font-medium text-sm">Lihat Laporan</span>
        </a>
        <a href="<?= BASE_URL ?>/inventory/opname" class="bg-white p-4 rounded-xl border border-gray-200 text-center hover:border-primary hover:text-primary transition-colors group">
            <div class="w-10 h-10 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-primary/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <span class="font-medium text-sm">Stock Opname</span>
        </a>
        <a href="<?= BASE_URL ?>/products" class="bg-white p-4 rounded-xl border border-gray-200 text-center hover:border-primary hover:text-primary transition-colors group">
            <div class="w-10 h-10 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-primary/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <span class="font-medium text-sm">Kelola Menu</span>
        </a>
        <a href="<?= BASE_URL ?>/pos" class="bg-white p-4 rounded-xl border border-gray-200 text-center hover:border-primary hover:text-primary transition-colors group">
            <div class="w-10 h-10 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-primary/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="font-medium text-sm">Buka Kasir</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Kasir Dashboard -->
    <?php if ($userRole === 'Kasir'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center text-primary shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Transaksi Shift Anda</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= number_format($shift_trx ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pesanan Belum Selesai</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= number_format($pending_orders ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="<?= BASE_URL ?>/pos" class="bg-primary text-white p-6 rounded-xl shadow hover:bg-primary/90 transition text-center flex flex-col items-center justify-center min-h-[150px]">
            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="text-lg font-bold">Buka Layar POS Kasir</span>
        </a>
        <a href="<?= BASE_URL ?>/shift/history" class="bg-white border-2 border-gray-200 text-gray-800 p-6 rounded-xl hover:border-primary hover:text-primary transition text-center flex flex-col items-center justify-center min-h-[150px]">
            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-lg font-bold">Riwayat Shift Kasir</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Barista Dashboard -->
    <?php if ($userRole === 'Barista'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center text-red-500 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Antrean Pesanan KDS</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= number_format($kds_queue ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center text-green-500 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Produk Tersedia</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= number_format($total_recipes ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="<?= BASE_URL ?>/kds" class="bg-gray-900 text-white p-6 rounded-xl shadow hover:bg-gray-800 transition text-center flex flex-col items-center justify-center min-h-[150px]">
            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <span class="text-lg font-bold">Buka Layar Dapur (KDS)</span>
        </a>
        <a href="<?= BASE_URL ?>/inventory/recipes" class="bg-white border-2 border-gray-200 text-gray-800 p-6 rounded-xl hover:border-primary hover:text-primary transition text-center flex flex-col items-center justify-center min-h-[150px]">
            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span class="text-lg font-bold">Cek Resep & Bahan</span>
        </a>
    </div>
    <?php endif; ?>
</div>
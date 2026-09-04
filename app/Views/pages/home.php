<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Stat Card 1 -->
    <div class="bg-surface rounded-lg border border-border p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-textSecondary uppercase tracking-wider">Total Penjualan</h3>
            <span class="p-2 bg-green-100 rounded-md">
                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>
        <div class="mt-4">
            <span class="text-3xl font-bold text-textPrimary tabular-nums">Rp 0</span>
            <p class="text-sm text-textSecondary mt-1">Hari ini</p>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-surface rounded-lg border border-border p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-textSecondary uppercase tracking-wider">Total Transaksi</h3>
            <span class="p-2 bg-blue-100 rounded-md">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </span>
        </div>
        <div class="mt-4">
            <span class="text-3xl font-bold text-textPrimary tabular-nums">0</span>
            <p class="text-sm text-textSecondary mt-1">Hari ini</p>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-surface rounded-lg border border-border p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-textSecondary uppercase tracking-wider">Stok Kritis</h3>
            <span class="p-2 bg-red-100 rounded-md">
                <svg class="h-5 w-5 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </span>
        </div>
        <div class="mt-4">
            <span class="text-3xl font-bold text-danger tabular-nums">0 <span class="text-xl">Item</span></span>
            <p class="text-sm text-textSecondary mt-1">Butuh restock</p>
        </div>
    </div>
    
    <!-- Stat Card 4 -->
    <div class="bg-surface rounded-lg border border-border p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-textSecondary uppercase tracking-wider">Status Shift</h3>
            <span class="p-2 bg-gray-100 rounded-md">
                <svg class="h-5 w-5 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </span>
        </div>
        <div class="mt-4">
            <span class="text-xl font-bold text-textPrimary">Belum Dibuka</span>
            <p class="text-sm text-textSecondary mt-1">Shift Kasir Aktif</p>
        </div>
    </div>
</div>

<!-- Welcome Section based on Role -->
<div class="bg-surface rounded-lg border border-border p-8 shadow-sm">
    <h2 class="text-xl font-bold text-textPrimary mb-4">Selamat datang, <?= htmlspecialchars(\App\Core\Session::get('user_name')) ?>!</h2>
    
    <div class="space-y-4 text-textSecondary leading-relaxed">
        <p>Anda masuk sebagai <strong class="text-textPrimary font-semibold"><?= htmlspecialchars($userRole ?? 'User') ?></strong>.</p>
        
        <?php if ($userRole === 'Admin' || $userRole === 'Manager'): ?>
            <p>Gunakan menu di sebelah kiri untuk mengelola produk, melihat laporan, dan memantau stok bahan baku.</p>
        <?php elseif ($userRole === 'Kasir'): ?>
            <p>Silakan buka shift Anda melalui menu Point of Sale untuk mulai melayani pelanggan.</p>
            <div class="mt-6">
                <?= $this->component('button', [
                    'text' => 'Ke Halaman POS',
                    'attributes' => ['onclick' => "window.location.href='" . BASE_URL . "/pos'"]
                ]) ?>
            </div>
        <?php elseif ($userRole === 'Barista'): ?>
            <p>Pantau pesanan yang masuk melalui Kitchen Display System (KDS) dan pastikan stok bahan baku selalu diperbarui.</p>
            <div class="mt-6">
                <?= $this->component('button', [
                    'text' => 'Ke Halaman KDS',
                    'attributes' => ['onclick' => "window.location.href='" . BASE_URL . "/kds'"]
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
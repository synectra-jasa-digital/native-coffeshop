<?php
/**
 * Sidebar Component
 */
$currentUri = $_SERVER['REQUEST_URI'];
$basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
$uriPath = str_replace($basePath, '', $currentUri);

// Helper function to check active state
function isActiveRoute($path, $uriPath) {
    if ($path === '/' && $uriPath === '/') return true;
    if ($path !== '/' && str_starts_with($uriPath, $path)) return true;
    return false;
}
?>

<!-- Mobile Sidebar Backdrop -->
<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 z-40 bg-black/50 lg:hidden" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    style="display: none;"
></div>

<!-- Sidebar -->
<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-surface border-r border-border transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:w-80"
>
    <!-- Logo/Brand -->
    <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-border">
        <a href="<?= BASE_URL ?>" class="flex items-center gap-2">
            <div class="h-8 w-8 bg-primary rounded-md flex items-center justify-center">
                <span class="text-white font-bold text-lg leading-none">G</span>
            </div>
            <span class="text-lg font-bold text-textPrimary">Good Coffee</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-textSecondary hover:text-primary">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-4 space-y-1">
        
        <p class="px-3 text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2 mt-4">Menu Utama</p>
        
        <a href="<?= BASE_URL ?>/" class="<?= isActiveRoute('/', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- POS (Kasir) -->
        <a href="<?= BASE_URL ?>/pos" class="<?= isActiveRoute('/pos', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/pos', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Point of Sale
        </a>

        <p class="px-3 text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2 mt-6">Operasional</p>
        
        <!-- Produk -->
        <a href="<?= BASE_URL ?>/products" class="<?= isActiveRoute('/products', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/products', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Produk & Menu
        </a>

        <!-- Inventory / Stok -->
        <div x-data="{ open: <?= str_starts_with($uriPath, '/inventory') ? 'true' : 'false' ?> }">
            <button @click="open = !open" class="w-full <?= str_starts_with($uriPath, '/inventory') ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center justify-between rounded-md px-3 py-2 text-sm font-medium transition-colors">
                <div class="flex items-center">
                    <svg class="<?= str_starts_with($uriPath, '/inventory') ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Stok & Resep
                </div>
                <svg :class="{'rotate-180': open}" class="h-4 w-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="pl-10 pr-3 py-1 space-y-1">
                <a href="<?= BASE_URL ?>/inventory/ingredients" class="<?= isActiveRoute('/inventory/ingredients', $uriPath) ? 'text-primary font-semibold' : 'text-textSecondary hover:text-textPrimary' ?> block py-2 text-sm transition-colors">Bahan Baku</a>
                <a href="<?= BASE_URL ?>/inventory/recipes" class="<?= isActiveRoute('/inventory/recipes', $uriPath) ? 'text-primary font-semibold' : 'text-textSecondary hover:text-textPrimary' ?> block py-2 text-sm transition-colors">Resep Produk</a>
                <a href="<?= BASE_URL ?>/inventory/movements" class="<?= isActiveRoute('/inventory/movements', $uriPath) ? 'text-primary font-semibold' : 'text-textSecondary hover:text-textPrimary' ?> block py-2 text-sm transition-colors">Pergerakan Stok</a>
                <a href="<?= BASE_URL ?>/inventory/opname" class="<?= isActiveRoute('/inventory/opname', $uriPath) ? 'text-primary font-semibold' : 'text-textSecondary hover:text-textPrimary' ?> block py-2 text-sm transition-colors">Stock Opname</a>
            </div>
        </div>

        <!-- KDS -->
        <a href="<?= BASE_URL ?>/kds" class="<?= isActiveRoute('/kds', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/kds', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Kitchen Display
        </a>

        <p class="px-3 text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2 mt-6">Laporan & Sistem</p>

        <!-- Laporan -->
        <a href="<?= BASE_URL ?>/reports" class="<?= isActiveRoute('/reports', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/reports', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Laporan
        </a>

        <!-- Pengguna -->
        <a href="<?= BASE_URL ?>/users" class="<?= isActiveRoute('/users', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/users', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Pengguna
        </a>

        <!-- Pengaturan -->
        <a href="<?= BASE_URL ?>/settings" class="<?= isActiveRoute('/settings', $uriPath) ? 'bg-primary/10 text-primary' : 'text-textPrimary hover:bg-background' ?> group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
            <svg class="<?= isActiveRoute('/settings', $uriPath) ? 'text-primary' : 'text-textSecondary group-hover:text-primary' ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Pengaturan
        </a>
    </nav>
</aside>
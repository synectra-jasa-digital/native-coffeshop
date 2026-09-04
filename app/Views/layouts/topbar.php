<?php
/**
 * Topbar Component
 */
?>
<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between bg-surface border-b border-border px-4 shadow-sm sm:px-6">
    <div class="flex items-center gap-4">
        <!-- Hamburger button for mobile -->
        <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="text-textSecondary hover:text-primary focus:outline-none lg:hidden"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-textPrimary">
            <?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?>
        </h1>
    </div>

    <!-- User Menu & Actions -->
    <div class="flex items-center gap-4">
        <!-- Optional: Notification Bell -->
        
        <!-- User Dropdown (Alpine.js) -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                @click.away="open = false"
                class="flex items-center gap-2 rounded-md p-2 hover:bg-background focus:outline-none transition-colors"
            >
                <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                    <?= substr(\App\Core\Session::get('user_name') ?? 'U', 0, 1) ?>
                </div>
                <div class="hidden text-left sm:block">
                    <div class="text-sm font-medium text-textPrimary leading-none">
                        <?= htmlspecialchars(\App\Core\Session::get('user_name') ?? 'User') ?>
                    </div>
                    <div class="text-xs text-textSecondary mt-1">
                        <?= htmlspecialchars(\App\Core\Session::get('user_role_name') ?? 'Role') ?>
                    </div>
                </div>
                <svg class="h-4 w-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 rounded-md bg-surface py-1 shadow-lg border border-border focus:outline-none"
                style="display: none;"
            >
                <a href="<?= BASE_URL ?>/profile" class="block px-4 py-2 text-sm text-textPrimary hover:bg-background">Profil Saya</a>
                <div class="border-t border-border my-1"></div>
                <button 
                    @click="showDialog('warning', 'Konfirmasi Keluar', 'Apakah Anda yakin ingin keluar dari sistem?', true, () => { window.location.href = '<?= BASE_URL ?>/logout'; })" 
                    class="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-background"
                >
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>
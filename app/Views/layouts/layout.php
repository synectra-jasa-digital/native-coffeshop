<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' : '' ?>POS Coffee Shop</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config for Design Guidelines (Operate Mode: Green Primary, Inter font, No heavy shadows) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#398263',
                            hover: '#2C6B4F'
                        },
                        warning: '#D97706',
                        danger: '#DC2626',
                        background: '#F3F4F6',
                        surface: '#FFFFFF',
                        border: '#E5E7EB',
                        textPrimary: '#1A1A1A',
                        textSecondary: '#6B7280'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'md': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)', // Overwrite md to be subtle
                        'lg': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)', // Overwrite lg
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for lightweight interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }
        /* Hide scrollbar for sidebar but allow scrolling */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Typography alignment */
        .tabular-nums { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="bg-background text-textPrimary font-sans antialiased" x-data="{ sidebarOpen: false }">

    <?php
    // Determine if we need to show the full app layout (sidebar + topbar) or just a plain layout (for login page)
    $isAppLayout = isset($isAppLayout) ? $isAppLayout : true;
    
    if ($isAppLayout) : 
    ?>
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar Component -->
            <?php require __DIR__ . '/sidebar.php'; ?>

            <!-- Main Content Area -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <!-- Topbar Component -->
                <?php require __DIR__ . '/topbar.php'; ?>

                <!-- Main Content Slot -->
                <main class="w-full grow p-6">
                    <?= $content ?>
                </main>
            </div>
        </div>
    <?php else : ?>
        <!-- Plain Layout (e.g., for Login, Error pages, Public QR Menu) -->
        <?php if (isset($isPosLayout) && $isPosLayout) : ?>
            <!-- Specifically for POS so it uses full screen without centering -->
            <div class="h-screen w-full flex flex-col overflow-hidden bg-background">
                <?= $content ?>
            </div>
        <?php else : ?>
            <div class="min-h-screen flex items-center justify-center">
                <?= $content ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

        <!-- Global Dialog/Alert Component container -->
    <div id="dialog-container">
        <?php 
        if (file_exists(__DIR__ . '/../components/dialog-alert.php')) {
            require __DIR__ . '/../components/dialog-alert.php';
        }
        ?>
    </div>

    <!-- Notification Sound -->
    <audio id="notification-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <!-- Notification Toast (Alpine.js) -->
    <div x-data="notificationSystem()" 
         x-show="showToast" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-white border border-green-200 shadow-xl rounded-lg pointer-events-auto overflow-hidden" 
         style="display: none;">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-gray-900" x-text="toastTitle">Pesanan Baru Masuk!</p>
                    <p class="mt-1 text-sm text-gray-500" x-text="toastMessage"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="showToast = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-end">
            <button @click="window.location.reload()" class="text-sm font-medium text-primary hover:text-primary-hover">Muat Ulang Halaman</button>
        </div>
    </div>
    
    <!-- Base URL for JS scripts -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        
        // Global Notification System Logic
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationSystem', () => ({
                showToast: false,
                toastTitle: '',
                toastMessage: '',
                lastCheck: null,
                pollingInterval: null,
                
                init() {
                    // Hanya jalankan polling jika user sudah login (berada di dalam AppLayout)
                    <?php if (isset($_SESSION['user_id'])): ?>
                        // Set initial time (UTC) from JS or pass from server
                        this.lastCheck = new Date().toISOString().slice(0, 19).replace('T', ' ');
                        
                        // Check every 10 seconds
                        this.pollingInterval = setInterval(() => this.checkForNewOrders(), 10000);
                    <?php endif; ?>
                },
                
                async checkForNewOrders() {
                    try {
                        const url = `${BASE_URL}/api/notifications/new-orders?last_check=${encodeURIComponent(this.lastCheck)}`;
                        const response = await fetch(url);
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update last check to server time so we don't get duplicate alerts
                            if (data.server_time) {
                                this.lastCheck = data.server_time;
                            }
                            
                            if (data.has_new_orders) {
                                this.triggerAlert(data.count);
                            }
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                },
                
                triggerAlert(count) {
                    this.toastTitle = 'Pesanan Baru Masuk!';
                    this.toastMessage = `Terdapat ${count} pesanan baru dari pelanggan (QR Order).`;
                    this.showToast = true;
                    
                    // Play Sound
                    const audio = document.getElementById('notification-sound');
                    if (audio) {
                        // Reset and play
                        audio.currentTime = 0;
                        let playPromise = audio.play();
                        // Catch auto-play policy rejections gracefully
                        if (playPromise !== undefined) {
                            playPromise.catch(error => console.log('Audio autoplay blocked by browser', error));
                        }
                    }
                    
                    // Auto hide after 15 seconds
                    setTimeout(() => {
                        this.showToast = false;
                    }, 15000);
                }
            }));
        });
    </script>
    
    <!-- Flash Messages Handler -->
    <?php if (\App\Core\Session::hasFlash('success') || \App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('info')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (\App\Core\Session::hasFlash('success')): ?>
                showDialog('success', 'Berhasil', '<?= addslashes(\App\Core\Session::getFlash('success')) ?>');
            <?php endif; ?>
            
            <?php if (\App\Core\Session::hasFlash('error')): ?>
                showDialog('error', 'Gagal', '<?= addslashes(\App\Core\Session::getFlash('error')) ?>');
            <?php endif; ?>
            
            <?php if (\App\Core\Session::hasFlash('info')): ?>
                showDialog('info', 'Informasi', '<?= addslashes(\App\Core\Session::getFlash('info')) ?>');
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>
</body>
</html>
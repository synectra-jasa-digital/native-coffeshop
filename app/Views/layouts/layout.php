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
    
    <!-- Base URL for JS scripts -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
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
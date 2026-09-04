<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
        <p class="text-sm text-textSecondary mt-1">Kelola daftar meja dan generate QR Code untuk pelanggan.</p>
    </div>
    
    <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
    <a href="<?= BASE_URL ?>/tables/create" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Meja
    </a>
    <?php endif; ?>
</div>

<div class="bg-surface border border-border rounded-lg shadow-sm overflow-hidden">
    <!-- List of Tables as Cards -->
    <?php if (empty($tables)): ?>
        <div class="p-10 text-center flex flex-col items-center">
            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <p class="text-gray-500 font-medium">Belum ada meja yang terdaftar.</p>
            <p class="text-sm text-gray-400 mt-1">Tambahkan meja pertama Anda untuk memulai.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            <?php foreach ($tables as $table): ?>
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#398263]/10 text-[#398263] px-2.5 py-1 rounded-md text-sm font-bold border border-[#398263]/20">
                                Meja <?= htmlspecialchars($table['table_number']) ?>
                            </span>
                        </div>
                        <?php if($table['status'] === 'occupied'): ?>
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                Terisi
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                Kosong
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- QR Code Body -->
                    <div class="p-4 flex-1 flex flex-col items-center justify-center bg-white">
                        <div class="border-2 border-dashed border-gray-200 p-2 rounded-lg bg-white relative group cursor-pointer" onclick="window.open('<?= $table['qr_image_url'] ?>', '_blank')">
                            <img src="<?= $table['qr_image_url'] ?>" alt="QR Code Meja <?= htmlspecialchars($table['table_number']) ?>" class="w-32 h-32 object-contain" />
                            
                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-black/60 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Buka & Print
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-center text-gray-500 mt-3 break-all px-2 leading-relaxed opacity-70 hover:opacity-100 transition-opacity">
                            <span class="font-medium">URL Pemesanan:</span><br>
                            <a href="<?= $table['menu_url'] ?>" target="_blank" class="text-blue-600 hover:underline"><?= $table['menu_url'] ?></a>
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <?php if(in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                    <div class="p-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-2">
                        <a href="<?= BASE_URL ?>/tables/edit/<?= $table['id'] ?>" class="flex-1 inline-flex justify-center items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#398263]">
                            Edit
                        </a>
                        
                        <!-- Reset QR Form -->
                        <form method="POST" action="<?= BASE_URL ?>/tables/regenerate/<?= $table['id'] ?>" onsubmit="return confirm('URL QR lama tidak akan berfungsi lagi. Anda harus mencetak ulang QR ini. Lanjutkan?');" class="flex-1">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-amber-500">
                                Reset QR
                            </button>
                        </form>
                        
                        <?php if(\App\Core\Session::get('user_role_name') === 'Admin'): ?>
                            <!-- Delete Form -->
                            <form method="POST" action="<?= BASE_URL ?>/tables/delete/<?= $table['id'] ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?');" class="flex-none">
                                <button type="submit" class="inline-flex justify-center items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded" <?= $table['status'] === 'occupied' ? 'disabled class="opacity-50 cursor-not-allowed"' : '' ?> title="Hapus Meja">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
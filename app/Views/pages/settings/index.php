<!-- Pengaturan Sistem -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
            <p class="text-gray-500 mt-1">Konfigurasi toko, perpajakan, dan struk belanja</p>
        </div>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/settings/save" enctype="multipart/form-data" class="space-y-6">
        <!-- Info Toko -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Informasi Toko & Logo Cafe</h2>
                <p class="text-xs text-gray-500">Logo dan identitas outlet akan dicetak pada struk belanja, header menu, dan laporan</p>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Logo Upload Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Cafe / Outlet</label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <!-- Current Logo Preview -->
                        <div class="relative w-24 h-24 bg-white rounded-2xl border border-gray-300 flex items-center justify-center overflow-hidden shadow-inner shrink-0 group">
                            <?php if (!empty($storeInfo['logo'])): ?>
                                <img id="logo-preview" src="<?= htmlspecialchars($storeInfo['logo']) ?>" alt="Logo Cafe" class="w-full h-full object-contain p-2">
                            <?php else: ?>
                                <div id="logo-placeholder" class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                                    </svg>
                                    <span class="text-[10px] font-semibold uppercase">No Logo</span>
                                </div>
                                <img id="logo-preview" src="" alt="Preview Logo" class="hidden w-full h-full object-contain p-2">
                            <?php endif; ?>
                        </div>

                        <!-- Upload Input & Instructions -->
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-[#398263] text-white text-xs font-semibold rounded-lg hover:bg-[#2C6B4F] transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span>Pilih Gambar Logo</span>
                                    <input type="file" name="store_logo" id="store_logo_input" accept="image/png, image/jpeg, image/webp, image/svg+xml" class="hidden" onchange="previewSelectedLogo(this)">
                                </label>

                                <?php if (!empty($storeInfo['logo'])): ?>
                                    <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 text-red-600 border border-red-200 text-xs font-semibold rounded-lg hover:bg-red-100 transition cursor-pointer">
                                        <input type="checkbox" name="remove_logo" value="1" class="rounded text-red-600 focus:ring-red-500">
                                        <span>Hapus Logo Saat Ini</span>
                                    </label>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Format yang didukung: <strong>PNG, JPG, WEBP, SVG</strong>. Disarankan rasio 1:1 (Persegi) atau transparan latar belakang untuk hasil struk & menu terbaik.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko / Outlet *</label>
                        <input type="text" name="store_name" required value="<?= htmlspecialchars($storeInfo['name']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Toko</label>
                        <input type="text" name="store_phone" value="<?= htmlspecialchars($storeInfo['phone']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Toko</label>
                        <textarea name="store_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none"><?= htmlspecialchars($storeInfo['address']) ?></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Footer Struk</label>
                        <input type="text" name="receipt_footer" value="<?= htmlspecialchars($storeInfo['footer']) ?>" placeholder="Misal: Terima kasih atas kunjungan Anda!" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pajak & Service Charge -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Pajak & Service Charge</h2>
                <p class="text-xs text-gray-500">Kalkulasi biaya otomatis pada layar kasir (POS)</p>
            </div>
            <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Pajak (PPN) -->
                <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900">Pajak (PPN)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_tax_active" value="1" <?= $taxSettings['is_tax_active'] ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#398263]"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tarif Pajak (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="tax_rate" value="<?= htmlspecialchars($taxSettings['tax_rate']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none font-mono">
                    </div>
                </div>

                <!-- Service Charge -->
                <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900">Service Charge</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_service_charge_active" value="1" <?= $taxSettings['is_service_charge_active'] ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#398263]"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tarif Service Charge (%)</label>
                        <input type="number" step="0.1" min="0" max="100" name="service_charge_rate" value="<?= htmlspecialchars($taxSettings['service_charge_rate']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#398263] outline-none font-mono">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#398263] text-white rounded-lg font-medium hover:bg-[#2C6B4F] shadow-sm transition cursor-pointer">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
function previewSelectedLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
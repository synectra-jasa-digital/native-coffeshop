<!-- Pengaturan Sistem -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
            <p class="text-gray-500 mt-1">Konfigurasi toko, perpajakan, dan struk belanja</p>
        </div>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/settings/save" class="space-y-6">
        <!-- Info Toko -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Informasi Toko</h2>
                <p class="text-xs text-gray-500">Akan dicetak pada struk belanja dan laporan</p>
            </div>
            <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
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
            <button type="submit" class="px-6 py-3 bg-[#398263] text-white rounded-lg font-medium hover:bg-[#2C6B4F] shadow-sm transition">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
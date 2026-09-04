<!-- Detail Transaksi -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
            <p class="text-gray-500 mt-1">Rincian lengkap transaksi #<?= $transaction['id'] ?></p>
        </div>
        <div class="flex gap-3">
            <a href="<?= BASE_URL ?>/pos" class="px-3 py-1.5 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 text-sm">← Kembali ke POS</a>
            <a href="<?= BASE_URL ?>/transactions" class="px-3 py-1.5 border border-gray-300 text-gray-600 rounded hover:bg-gray-50 text-sm">Daftar Transaksi</a>
        </div>
    </div>

    <?php if (!$transaction): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <h2 class="text-xl font-bold text-red-800">Transaksi Tidak Ditemukan</h2>
            <p class="text-gray-600 mt-2">Transaksi dengan ID yang diminta tidak ada dalam database.</p>
        </div>
    <?php else: ?>
        <!-- Informasi Transaksi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500">ID Transaksi</p>
                    <p class="text-2xl font-mono font-bold text-gray-900">#<?= $transaction['id'] ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Waktu Transaksi</p>
                    <p class="text-lg font-medium text-gray-900"><?= date('d/m/Y H:i:s', strtotime($transaction['created_at'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Order</p>
                    <p class="text-lg font-medium text-gray-900"><?= $transaction['order_type'] === 'dine_in' ? 'Dine In' : 'Take Away' ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                    <p class="text-lg font-medium text-gray-900">
                        <?php
                        $methods = [
                            'cash' => 'Tunai',
                            'qris_static' => 'QRIS Statis',
                            'qris_dynamic' => 'QRIS Dinamis',
                            'ewallet' => 'E-Wallet',
                            'card' => 'Kartu Debit/Kredit'
                        ];
                        echo $methods[$transaction['payment_method']] ?? $transaction['payment_method'];
                        ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Pembayaran</p>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium
                        <?= $transaction['payment_status'] === 'success' ? 'bg-green-100 text-green-800' : '' ?>
                        <?= $transaction['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                        <?= $transaction['payment_status'] === 'failed' ? 'bg-red-100 text-red-800' : '' ?>
                        <?= $transaction['payment_status'] === 'refunded' ? 'bg-red-100 text-red-800' : '' ?>
                        rounded-full">
                        <?= ucfirst($transaction['payment_status']) ?>
                    </span>
                </div>
            </div>

            <?php if ($transaction['table_number']): ?>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500">Nomor Meja</p>
                    <p class="text-xl font-bold text-gray-900"><?= $transaction['table_number'] ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($transaction['cashier_name'])): ?>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500">Kasir</p>
                    <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($transaction['cashier_name']) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($transaction['shift_id'])): ?>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500">ID Shift</p>
                    <p class="text-lg font-mono font-bold text-gray-900">#<?= $transaction['shift_id'] ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Rincian Tagihan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <h2 class="text-xl font-bold text-gray-900">Rincian Tagihan</h2>
                <p class="text-gray-500 mb-4">Berikut detail biaya transaksi ini:</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-medium tabular-nums text-gray-900">Rp <?= number_format($transaction['subtotal'], 0, ',', '.') ?></span>
                </div>
                
                <?php if ($transaction['tax'] > 0): ?>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Pajak (<?= $transaction['tax_rate'] ?>%)</span>
                        <span class="font-medium tabular-nums text-gray-900">Rp <?= number_format($transaction['tax'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($transaction['service_charge'] > 0): ?>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Service Charge (<?= $transaction['service_charge_rate'] ?>%)</span>
                        <span class="font-medium tabular-nums text-gray-900">Rp <?= number_format($transaction['service_charge'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($transaction['discount'] > 0): ?>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Diskon</span>
                        <span class="font-medium tabular-nums text-gray-900 text-red-600">-Rp <?= number_format($transaction['discount'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>

                <div class="border-t border-gray-200 pt-4 mb-4"></div>

                <div class="flex justify-between text-lg font-bold text-gray-900 pt-2">
                    <span>TOTAL</span>
                    <span class="tabular-nums text-gray-900">Rp <?= number_format($transaction['total'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Daftar Pesanan</h2>
            
            <?php if (empty($items)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Tidak ada item dalam transaksi ini.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $itemNo = 1; ?>
                    <?php foreach ($items as $item): ?>
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center h-10 w-10 bg-white rounded-lg border border-gray-200">
                                <span class="text-xl font-bold text-gray-900"><?= $itemNo++ ?></span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex justify-between">
                                <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h3>
                                <span class="text-xs text-gray-500"><?= $item['variant_name'] ?? '' ?></span>
                            </div>
                            <?php if (!empty($item['notes'])): ?>
                                <p class="text-sm text-gray-600 italic"><?= htmlspecialchars($item['notes']) ?></p>
                            <?php endif; ?>
                            <div class="mt-2 text-right">
                                <span class="font-medium tabular-nums text-gray-900">
                                    Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                </span>
                                <span class="text-xs text-gray-500 ml-2">
                                    (<?= $item['quantity'] ?> × Rp <?= number_format($item['price'], 0, ',', '.') ?>)
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tombol Void (hanya untuk Admin/Manager) -->
        <?php if (in_array(Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
            <div class="mt-6">
                <form method="POST" action="<?= BASE_URL ?>/transactions/<?= $transaction['id'] ?>/void" onsubmit="return confirm('Apakah Anda yakin ingin void transaksi ini? Tindakan ini tidak dapat dibatalkan.');">
                    <input type="hidden" name="reason" placeholder="Alasan void (wajib diisi)" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-transparent">
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                        Void Transaksi
                    </button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
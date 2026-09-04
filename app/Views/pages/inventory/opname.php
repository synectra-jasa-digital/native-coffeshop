<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Lakukan pengecekan stok fisik (Stock Opname) dan ajukan penyesuaian.</p>
        </div>
        <a href="<?= BASE_URL ?>/inventory/opname/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Buat Laporan Opname
        </a>
    </div>

    <!-- Alert -->
    <?php if (\App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('success')): ?>
        <!-- Handled by Layout Dialog -->
    <?php endif; ?>

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Sistem</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Fisik (Aktual)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oleh</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($opnames)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada laporan stock opname.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($opnames as $opname): 
                            $statusClass = '';
                            $statusLabel = '';
                            if ($opname['status'] === 'pending') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusLabel = 'Menunggu';
                            } elseif ($opname['status'] === 'approved') {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusLabel = 'Disetujui';
                            } else {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusLabel = 'Ditolak';
                            }

                            $diff = floatval($opname['difference']);
                            $diffClass = $diff < 0 ? 'text-red-600' : ($diff > 0 ? 'text-blue-600' : 'text-gray-500');
                            $diffSign = $diff > 0 ? '+' : '';
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d M Y H:i', strtotime($opname['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($opname['ingredient_name']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= floatval($opname['expected_qty']) ?> <?= htmlspecialchars($opname['unit']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= floatval($opname['actual_qty']) ?> <?= htmlspecialchars($opname['unit']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $diffClass ?>">
                                <?= $diffSign . $diff ?> <?= htmlspecialchars($opname['unit']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($opname['user_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <?php if($opname['status'] === 'pending' && in_array(\App\Core\Session::get('user_role_name'), ['Admin', 'Manager'])): ?>
                                        <button onclick="updateStatus(<?= $opname['id'] ?>, 'approved')" class="text-green-600 hover:text-green-800 bg-green-50 p-1.5 rounded border border-green-200 hover:bg-green-100 transition-colors duration-200 cursor-pointer" title="Setujui">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button onclick="updateStatus(<?= $opname['id'] ?>, 'rejected')" class="text-danger hover:text-red-800 bg-red-50 p-1.5 rounded border border-red-200 hover:bg-red-100 transition-colors duration-200 cursor-pointer" title="Tolak">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function updateStatus(id, status) {
        const statusText = status === 'approved' ? 'menyetujui' : 'menolak';
        showDialog('warning', 'Konfirmasi', `Apakah Anda yakin ingin ${statusText} laporan opname ini?`, true, async () => {
            try {
                const formBody = new URLSearchParams();
                formBody.append('status', status);

                const response = await fetch(`<?= BASE_URL ?>/inventory/opname/status/${id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formBody
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showDialog('success', 'Berhasil', result.message, false, () => {
                        window.location.reload();
                    });
                } else {
                    showDialog('error', 'Gagal', result.message);
                }
            } catch (error) {
                showDialog('error', 'Gagal', 'Terjadi kesalahan sistem.');
            }
        });
    }
</script>


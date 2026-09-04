<div class="flex flex-col h-full" x-data="kdsApp()">
    <!-- Page Header (KDS Style: compact, actionable) -->
    <div class="flex items-center justify-between gap-3 mb-6 bg-surface p-4 rounded-lg border border-border shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-textPrimary flex items-center gap-2">
                <?= htmlspecialchars($title) ?>
                <span class="relative flex h-3 w-3 ml-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            </h1>
            <p class="text-sm text-textSecondary mt-1">Sistem antrian pesanan dapur real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-textSecondary bg-background px-3 py-1.5 rounded-md border border-border">
                Memuat Ulang: <span x-text="countdown"></span>s
            </span>
            <button @click="fetchOrders()" class="p-2 text-textSecondary hover:text-primary transition-colors bg-background rounded-md border border-border" title="Refresh Sekarang">
                <svg :class="{'animate-spin': isRefreshing}" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Active Tickets Grid -->
    <div class="flex-1 min-h-[500px]">
        <?php if (empty($orders)): ?>
            <div class="h-full flex flex-col items-center justify-center text-gray-400 bg-surface rounded-lg border border-border border-dashed">
                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p class="text-lg font-medium text-textSecondary">Tidak ada pesanan aktif</p>
                <p class="text-sm mt-1">Pesanan baru akan muncul di sini secara otomatis.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 items-start">
                <?php foreach ($orders as $order): ?>
                    <!-- Ticket Card -->
                    <div class="bg-surface border-2 rounded-xl shadow-sm overflow-hidden flex flex-col transition-all duration-300"
                         :class="getStatusColorClass('<?= $order['status'] ?>', <?= $order['elapsed_minutes'] ?>)"
                         id="ticket-<?= $order['id'] ?>">
                         
                        <!-- Ticket Header -->
                        <div class="p-3 border-b border-border/50 flex justify-between items-center" 
                             :class="getHeaderColorClass('<?= $order['status'] ?>', <?= $order['elapsed_minutes'] ?>)">
                            <div>
                                <div class="font-black text-lg leading-none mb-1">
                                    <?= $order['order_type'] === 'dine_in' ? 'Meja ' . htmlspecialchars($order['table_number']) : 'Take Away' ?>
                                </div>
                                <div class="text-xs font-bold opacity-80 uppercase tracking-wider">
                                    #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?> • <?= $order['time'] ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-black tabular-nums leading-none">
                                    <?= $order['elapsed_minutes'] ?>'
                                </div>
                                <div class="text-xs font-bold opacity-80 uppercase mt-1">Menit</div>
                            </div>
                        </div>

                        <!-- Ticket Body (Items) -->
                        <div class="p-0 flex-1">
                            <ul class="divide-y divide-border/50">
                                <?php foreach ($order['items'] as $item): ?>
                                    <li class="p-3 hover:bg-black/5 transition-colors group cursor-pointer" @click="toggleCross($event)">
                                        <div class="flex items-start gap-3">
                                            <!-- Qty Box -->
                                            <div class="w-8 h-8 rounded bg-gray-100 border border-gray-200 flex items-center justify-center font-black text-lg text-textPrimary flex-shrink-0 group-hover:bg-white transition-colors">
                                                <?= $item['qty'] ?>
                                            </div>
                                            <!-- Details -->
                                            <div class="flex-1 pt-0.5">
                                                <div class="font-bold text-textPrimary leading-tight item-text transition-all">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </div>
                                                <?php if ($item['variant']): ?>
                                                    <div class="text-xs font-semibold text-primary mt-1 flex items-center gap-1 item-text transition-all">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-primary/50"></span>
                                                        <?= htmlspecialchars($item['variant']) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($item['notes']): ?>
                                                    <div class="text-xs font-medium text-danger mt-1 bg-red-50 p-1.5 rounded border border-red-100 item-text transition-all">
                                                        Catatan: <?= htmlspecialchars($item['notes']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Ticket Action -->
                        <div class="p-3 border-t border-border/50 bg-gray-50/50">
                            <?php if ($order['status'] === 'pending'): ?>
                                <button @click="updateStatus(<?= $order['id'] ?>, 'processing')" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm uppercase tracking-wider transition-colors shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                    Mulai Proses
                                </button>
                            <?php else: ?>
                                <button @click="updateStatus(<?= $order['id'] ?>, 'completed')" class="w-full py-3 bg-primary hover:bg-primary-hover text-white rounded-lg font-bold text-sm uppercase tracking-wider transition-colors shadow-sm focus:ring-2 focus:ring-primary focus:ring-offset-1">
                                    Pesanan Selesai
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Utility class to cross out text when clicked */
    .line-through-dim {
        text-decoration: line-through;
        opacity: 0.4;
    }
</style>

<script>
function kdsApp() {
    return {
        countdown: 15, // Refresh every 15 seconds
        isRefreshing: false,
        timer: null,

        init() {
            this.startTimer();
        },

        startTimer() {
            this.timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    this.fetchOrders();
                }
            }, 1000);
        },

        async fetchOrders() {
            this.isRefreshing = true;
            clearInterval(this.timer);
            
            // In a real SPA we would fetch JSON, but since we are SSR:
            // We just reload the page for now to keep it simple and native PHP friendly.
            // Alpine JS is handling the UI states perfectly.
            window.location.reload();
        },

        async updateStatus(orderId, newStatus) {
            try {
                const response = await fetch(`<?= BASE_URL ?>/kds/status/${orderId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const result = await response.json();
                if (result.success) {
                    // Instantly remove ticket from DOM for snappy feel
                    if (newStatus === 'completed') {
                        document.getElementById(`ticket-${orderId}`).remove();
                    } else {
                        this.fetchOrders(); // Reload to update color states
                    }
                } else {
                    showDialog('error', 'Gagal', result.message);
                }
            } catch (e) {
                showDialog('error', 'Error', 'Terjadi kesalahan sistem.');
            }
        },

        toggleCross(event) {
            // Find all item-text elements inside the clicked LI and toggle class
            const texts = event.currentTarget.querySelectorAll('.item-text');
            texts.forEach(el => {
                el.classList.toggle('line-through-dim');
            });
        },

        // Logic for highlighting late orders
        getStatusColorClass(status, minutes) {
            if (status === 'processing') {
                return 'border-blue-300';
            }
            // Pending states
            if (minutes >= 15) return 'border-red-400 bg-red-50/10 shadow-[0_0_15px_rgba(220,38,38,0.2)] animate-pulse';
            if (minutes >= 10) return 'border-amber-400';
            return 'border-gray-200';
        },

        getHeaderColorClass(status, minutes) {
            if (status === 'processing') {
                return 'bg-blue-100 text-blue-900 border-blue-200';
            }
            // Pending states
            if (minutes >= 15) return 'bg-red-500 text-white border-red-600';
            if (minutes >= 10) return 'bg-amber-100 text-amber-900 border-amber-200';
            return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    }
}
</script>
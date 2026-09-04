<?php
// Tampilan Struk / Receipt berukuran kecil (Thermal Printer Style)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #<?= htmlspecialchars($order['id']) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 10px;
            width: 58mm; /* Standar printer thermal kecil */
            color: #000;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 5px; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .item-row td { padding-bottom: 3px; }
        .brand-title { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .footer { margin-top: 10px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="text-center border-bottom">
        <div class="brand-title"><?= htmlspecialchars($settings['store_name'] ?? 'GOOD COFFEE') ?></div>
        <div><?= htmlspecialchars($settings['store_address'] ?? 'Alamat Toko') ?></div>
        <div>Telp: <?= htmlspecialchars($settings['store_phone'] ?? '-') ?></div>
    </div>

    <div style="margin-bottom: 5px;">
        <div>Order #: <?= htmlspecialchars($order['id']) ?></div>
        <div>Kasir: <?= htmlspecialchars(isset($shift['user_name']) ? $shift['user_name'] : 'Admin') ?></div>
        <div>Waktu: <?= date('d M Y H:i', strtotime($order['created_at'])) ?></div>
        <div>Tipe: <?= $order['order_type'] === 'dine_in' ? 'Dine In (Meja ' . (isset($order['table_number']) ? $order['table_number'] : '-') . ')' : 'Take Away' ?></div>
    </div>

    <div class="border-top border-bottom">
        <table>
            <?php foreach ($items as $item): ?>
            <tr class="item-row">
                <td colspan="3"><?= htmlspecialchars($item['product_name']) ?> <?= $item['variant_name'] ? '- ' . htmlspecialchars($item['variant_name']) : '' ?></td>
            </tr>
            <tr class="item-row">
                <td class="text-left"><?= $item['quantity'] ?>x</td>
                <td class="text-left">@<?= number_format($item['price'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($item['quantity'] * $item['price'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="border-bottom">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right"><?= number_format($transaction['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php if ($transaction['tax'] > 0): ?>
            <tr>
                <td>Pajak</td>
                <td class="text-right"><?= number_format($transaction['tax'], 0, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
            <tr class="font-bold">
                <td>Total</td>
                <td class="text-right"><?= number_format($transaction['total'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Tunai</td>
                <td class="text-right"><?= number_format($payment['cash_received'] ?? $transaction['total'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right"><?= number_format(max(0, ($payment['cash_received'] ?? $transaction['total']) - $transaction['total']), 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="text-center footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Follow IG: @goodcoffee</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Optional: Close window after printing
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>
</body>
</html>
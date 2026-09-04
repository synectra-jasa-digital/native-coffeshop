<?php
// Cetak Laporan Penjualan Akuntansi
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Laporan Penjualan') ?></title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; /* Font resmi dokumen/akuntansi */
            margin: 0 auto; 
            padding: 30px; 
            color: #000; 
            font-size: 11pt; 
            max-width: 800px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 15px; 
        }
        .title { font-size: 16pt; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase; }
        .company-name { font-size: 18pt; font-weight: bold; margin: 0 0 5px 0; }
        .subtitle { font-size: 11pt; margin-top: 5px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        
        table.accounting-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
        }
        table.accounting-table th, table.accounting-table td { 
            border: 1px solid #000; 
            padding: 8px 12px; 
            text-align: left; 
        }
        table.accounting-table th { 
            background-color: #f0f0f0; 
            font-weight: bold; 
            text-align: center;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        /* Format mata uang: rata kanan tapi Rp di kiri */
        .currency { display: flex; justify-content: space-between; }
        
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GOOD COFFEE SHOP</div>
        <h1 class="title"><?= htmlspecialchars($title ?? 'LAPORAN PENJUALAN') ?></h1>
        <div class="subtitle">Berdasarkan Catatan Sistem</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="150"><strong>Periode Laporan</strong></td>
            <td width="20">: <?= date('d/m/Y', strtotime($dateFrom ?? ($_GET['date'] ?? date('Y-m-d')))) ?> 
                <?= isset($dateTo) && $dateTo != $dateFrom ? 's/d ' . date('d/m/Y', strtotime($dateTo)) : '' ?>
            </td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>: <?= date('d/m/Y H:i:s') ?></td>
        </tr>
        <tr>
            <td><strong>Oleh</strong></td>
            <td>: <?= htmlspecialchars(\App\Core\Session::get('user_name') ?? 'System') ?> (<?= htmlspecialchars(\App\Core\Session::get('user_role_name') ?? 'Admin') ?>)</td>
        </tr>
    </table>

    <table class="accounting-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Keterangan Uraian</th>
                <th width="20%">Volume / Qty</th>
                <th width="40%">Total Nilai Pembukuan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-bold bg-light">A. RINGKASAN PENDAPATAN</td>
            </tr>
            <tr>
                <td class="text-center">1</td>
                <td>Pendapatan Tunai (Cash)</td>
                <td class="text-center">-</td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format($summary['cash_total'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Pendapatan Non-Tunai (Bank/Wallet)</td>
                <td class="text-center">-</td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format($summary['non_cash_total'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>Pajak & Servis Masuk</td>
                <td class="text-center">-</td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format(0, 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>
            <tr class="text-bold">
                <td colspan="2" class="text-right">TOTAL PENDAPATAN BRUTO</td>
                <td class="text-center"><?= number_format($summary['total_transactions'] ?? 0, 0, ',', '.') ?> Trx</td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format($summary['total_revenue'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="4" class="text-bold" style="border-left:none;border-right:none;border-bottom:none;">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="4" class="text-bold bg-light">B. RINCIAN ALIRAN KAS (BERDASARKAN TIPE)</td>
            </tr>
            <?php 
            $no = 1;
            $totalCount = 0; 
            $totalAmount = 0;
            foreach ($paymentMethods ?? [] as $key => $method): 
                if ($method['count'] > 0):
                    $totalCount += $method['count'];
                    $totalAmount += $method['amount'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td>Penerimaan via <?= htmlspecialchars($method['name']) ?></td>
                <td class="text-center"><?= number_format($method['count'], 0, ',', '.') ?></td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format($method['amount'], 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>
            <?php 
                endif;
            endforeach; 
            
            if ($totalCount == 0):
            ?>
            <tr><td colspan="4" class="text-center">Tidak ada aktivitas transaksi pada periode ini.</td></tr>
            <?php else: ?>
            <tr class="text-bold">
                <td colspan="2" class="text-right">JUMLAH ALIRAN KAS MASUK</td>
                <td class="text-center"><?= number_format($totalCount, 0, ',', '.') ?></td>
                <td>
                    <div class="currency">
                        <span>Rp</span>
                        <span><?= number_format($totalAmount, 0, ',', '.') ?></span>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui & Menyetujui,</p>
            <p><?= date('d F Y') ?></p>
            <div class="signature-line"></div>
            <p class="text-bold">( Manajer Operasional )</p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
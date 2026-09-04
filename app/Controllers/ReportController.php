<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Transaction;
use App\Models\Shift;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\User;

class ReportController extends Controller {
    private $transactionModel;
    private $shiftModel;
    private $ingredientModel;
    private $stockMovementModel;
    private $userModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Kasir'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke modul laporan.');
            $this->redirect('');
        }

        $this->transactionModel = new Transaction();
        $this->shiftModel = new Shift();
        $this->ingredientModel = new Ingredient();
        $this->stockMovementModel = new StockMovement();
        $this->userModel = new User();
    }

    /**
     * Laporan Penjualan Harian
     */
    public function harian() {
        $type = $_GET['type'] ?? 'daily';
        
        if ($type === 'monthly') {
            $date = $_GET['date_monthly'] ?? date('Y-m');
            $dateFrom = date('Y-m-01', strtotime($date));
            $dateTo = date('Y-m-t', strtotime($date));
            $titlePrefix = "Bulanan (" . date('F Y', strtotime($date)) . ")";
        } else if ($type === 'yearly') {
            $date = $_GET['date_yearly'] ?? date('Y');
            $dateFrom = date('Y-01-01', strtotime($date . '-01-01'));
            $dateTo = date('Y-12-31', strtotime($date . '-12-31'));
            $titlePrefix = "Tahunan (" . $date . ")";
        } else {
            $date = $_GET['date_daily'] ?? date('Y-m-d');
            $dateFrom = $date;
            $dateTo = $date;
            $titlePrefix = "Harian";
        }

        $summary = $this->transactionModel->getDailySummary($dateFrom, $dateTo);

        // Get transactions
        $transactions = $this->transactionModel->getAll(['date_from' => $dateFrom, 'date_to' => $dateTo], 10000);

        // Group by payment method
        $paymentMethods = [
            'cash' => ['name' => 'Tunai', 'amount' => 0, 'count' => 0],
            'qris_static' => ['name' => 'QRIS Statis', 'amount' => 0, 'count' => 0],
            'qris_dynamic' => ['name' => 'QRIS Dinamis', 'amount' => 0, 'count' => 0],
            'ewallet' => ['name' => 'E-Wallet', 'amount' => 0, 'count' => 0],
            'card' => ['name' => 'Kartu', 'amount' => 0, 'count' => 0]
        ];

        foreach ($transactions as $trx) {
            $method = $trx['payment_method'];
            if (isset($paymentMethods[$method])) {
                $paymentMethods[$method]['amount'] += $trx['total'];
                $paymentMethods[$method]['count']++;
            }
        }

        $title = 'Laporan Penjualan ' . $titlePrefix;

        if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
            require_once __DIR__ . '/../Views/pages/reports/print_harian.php';
            return;
        }

        $this->view('pages/reports/harian', [
            'title' => $title,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'type' => $type,
            'summary' => $summary,
            'transactions' => $transactions,
            'paymentMethods' => $paymentMethods
        ]);
    }

    /**
     * Laporan Stok & Nilai Stok
     */
    public function stok() {
        $ingredients = $this->ingredientModel->getAll();
        $movements = $this->stockMovementModel->getRecent(200);

        // Calculate stock value based on movements (simplified - using current_stock * last in price)
        $stockValues = [];
        foreach ($ingredients as $ing) {
            // Get last in-movement price for this ingredient
            $stmt = $this->db->prepare("
                SELECT quantity, notes FROM stock_movements 
                WHERE ingredient_id = :id AND type = 'in' 
                ORDER BY created_at DESC LIMIT 1
            ");
            $stmt->execute([':id' => $ing['id']]);
            $lastIn = $stmt->fetch(PDO::FETCH_ASSOC);

            // Simplified: assume last in-movement quantity is the price per unit
            $unitPrice = $lastIn ? (float)$lastIn['notes'] : 0; // This is simplified
            $stockValues[$ing['id']] = [
                'current_stock' => $ing['current_stock'],
                'unit_price' => $unitPrice,
                'total_value' => $ing['current_stock'] * $unitPrice
            ];
        }

        $this->view('pages/reports/stok', [
            'title' => 'Laporan Stok & Nilai Stok',
            'ingredients' => $ingredients,
            'stockValues' => $stockValues,
            'movements' => $movements
        ]);
    }

    /**
     * Laporan Kinerja Kasir per Shift
     */
    public function kasir_shift() {
        $dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');

        // Get all shifts in date range
        $shifts = $this->shiftModel->getAll(1000);

        // Filter by date range
        $filteredShifts = [];
        foreach ($shifts as $shift) {
            $shiftDate = date('Y-m-d', strtotime($shift['start_time']));
            if ($shiftDate >= $dateFrom && $shiftDate <= $dateTo) {
                // Get summary for each shift
                $summary = $this->shiftModel->getSummary($shift['id']);
                $shift['summary'] = $summary;
                $filteredShifts[] = $shift;
            }
        }

        // Group by user
        $userPerformance = [];
        foreach ($filteredShifts as $shift) {
            $userId = $shift['user_id'];
            if (!isset($userPerformance[$userId])) {
                $userPerformance[$userId] = [
                    'user_name' => $shift['user_name'],
                    'total_shifts' => 0,
                    'total_transactions' => 0,
                    'total_sales' => 0,
                    'total_cash' => 0,
                    'total_non_cash' => 0,
                    'total_difference' => 0
                ];
            }
            $userPerformance[$userId]['total_shifts']++;
            $userPerformance[$userId]['total_transactions'] += $shift['summary']['total_transactions'] ?? 0;
            $userPerformance[$userId]['total_sales'] += $shift['summary']['total_amount'] ?? 0;
            $userPerformance[$userId]['total_cash'] += $shift['summary']['cash_amount'] ?? 0;
            $userPerformance[$userId]['total_non_cash'] += $shift['summary']['non_cash_amount'] ?? 0;
            if ($shift['difference']) {
                $userPerformance[$userId]['total_difference'] += $shift['difference'];
            }
        }

        $this->view('pages/reports/kasir_shift', [
            'title' => 'Laporan Kinerja Kasir per Shift',
            'shifts' => $filteredShifts,
            'userPerformance' => $userPerformance,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }
}
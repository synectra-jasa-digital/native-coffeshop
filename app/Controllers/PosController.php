<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Setting;

class PosController extends Controller {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $shiftModel;
    private $settingModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        // Restrict to Admin, Manager, Kasir
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Kasir'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke POS.');
            $this->redirect('');
        }
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->shiftModel = new Shift();
        $this->settingModel = new Setting();
    }

    /**
     * Display POS Interface
     */
    public function index() {
        $userId = Session::get('user_id');

        // Cek apakah ada shift terbuka
        $openShift = $this->shiftModel->getOpenShift($userId);

        if (!$openShift) {
            // Belum ada shift terbuka, redirect ke buka shift
            Session::setFlash('warning', 'Anda harus membuka shift terlebih dahulu sebelum menggunakan POS.');
            $this->redirect('shift/open');
        }

        // Simpan shift_id di session
        Session::set('shift_id', $openShift['id']);

        $categories = $this->categoryModel->getAll(true); // Active only
        
        // Get all active products with variants
        $products = $this->productModel->getAll(null);
        $activeProducts = [];
        
        foreach ($products as $p) {
            if ($p['is_active']) {
                $p['variants'] = $this->productModel->getVariants($p['id']);
                $activeProducts[] = $p;
            }
        }

        // Fetch tables for dine-in selection
        $db = Database::getInstance()->getConnection();
        $tablesStmt = $db->query("SELECT id, table_number FROM tables WHERE status = 'empty' ORDER BY CAST(table_number AS UNSIGNED) ASC");
        $tables = $tablesStmt->fetchAll();

        // Get tax & service charge settings from database
        $taxSettings = $this->settingModel->getTaxSettings();

        $this->view('pages/pos/index', [
            'title' => 'Point of Sale',
            'categories' => $categories,
            'products' => $activeProducts,
            'settings' => $taxSettings,
            'shift' => $openShift,
            'tables' => $tables,
            'isAppLayout' => false,
            'isPosLayout' => true
        ]);
    }

    /**
     * Process checkout (AJAX)
     */
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid method'], 400);
        }

        $userId = Session::get('user_id');

        // Cek shift
        $openShift = $this->shiftModel->getOpenShift($userId);
        if (!$openShift) {
            return $this->json(['success' => false, 'message' => 'Tidak ada shift terbuka. Buka shift terlebih dahulu.'], 400);
        }

        // Read JSON payload from Alpine.js
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || empty($data['items'])) {
            return $this->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        // Validate and prepare data
        $orderData = [
            'order_type' => $data['order_type'] ?? 'dine_in',
            'table_id' => !empty($data['table_id']) ? (int)$data['table_id'] : null,
            'total_amount' => $data['grand_total']
        ];

        $transactionData = [
            'shift_id' => $openShift['id'],
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax_amount'],
            'service_charge' => $data['service_charge'] ?? 0,
            'discount' => $data['discount'] ?? 0,
            'total' => $data['grand_total'],
            'payment_method' => $data['payment_method'] ?? 'cash'
        ];

        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = [
                'product_id' => $item['id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'notes' => $item['note'] ?? ''
            ];
        }

        $orderId = $this->orderModel->createCompleteOrder($orderData, $items, $transactionData);

        if ($orderId) {
            return $this->json([
                'success' => true, 
                'message' => 'Transaksi berhasil.', 
                'order_id' => $orderId,
                'change' => max(0, ($data['cash_received'] ?? 0) - $data['grand_total'])
            ]);
        } else {
            return $this->json(['success' => false, 'message' => 'Gagal memproses transaksi.'], 500);
        }
    }
}
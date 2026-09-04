<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class PosController extends Controller {
    private $productModel;
    private $categoryModel;
    private $orderModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        // Everyone basically can access POS, except maybe strictly Barista.
        // For now, let's allow all active users.
        
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
    }

    /**
     * Display POS Interface
     */
    public function index() {
        // We will pass minimal layout or a full app layout. Let's use full app layout but customized.
        $categories = $this->categoryModel->getAll(true); // Active only
        
        // Let's get all active products with variants
        $products = $this->productModel->getAll(null);
        $activeProducts = [];
        
        foreach ($products as $p) {
            if ($p['is_active']) {
                $p['variants'] = $this->productModel->getVariants($p['id']);
                $activeProducts[] = $p;
            }
        }

        // Pass simple settings for Tax and Service Charge
        // In a real app, query from Settings table
        $settings = [
            'tax_rate' => 11.00, // 11%
            'is_tax_active' => true,
            'service_charge_rate' => 5.00,
            'is_service_charge_active' => false // Disable for simplicity unless requested
        ];

        $this->view('pages/pos/index', [
            'title' => 'Point of Sale',
            'categories' => $categories,
            'products' => $activeProducts,
            'settings' => $settings,
            // To make POS wider, we could pass a flag to layout to hide sidebar, but let's stick to default for now
        ]);
    }

    /**
     * Process checkout (AJAX)
     */
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid method'], 400);
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
            'table_id' => null, // Simplified for now
            'total_amount' => $data['grand_total']
        ];

        $transactionData = [
            'shift_id' => 1, // Dummy shift ID for now. Need Shift Management later.
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax_amount'],
            'service_charge' => 0,
            'discount' => 0,
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
            return $this->json(['success' => true, 'message' => 'Transaksi berhasil.', 'order_id' => $orderId]);
        } else {
            return $this->json(['success' => false, 'message' => 'Gagal memproses transaksi.'], 500);
        }
    }
}

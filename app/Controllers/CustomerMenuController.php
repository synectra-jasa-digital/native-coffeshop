<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Table;
use App\Models\Product;
use App\Models\Category;

class CustomerMenuController extends Controller {
    private $tableModel;
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->tableModel = new Table();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index($qr_token) {
        // Cari meja berdasarkan token QR
        $stmt = \App\Core\Database::getInstance()->getConnection()->prepare("SELECT * FROM tables WHERE qr_code = ?");
        $stmt->execute([$qr_token]);
        $table = $stmt->fetch();

        if (!$table) {
            $this->view('pages/menu/invalid_qr', ['isAppLayout' => false]);
            return;
        }

        // Ambil produk dan kategori (mirip POS Controller, hanya yang aktif dan tidak out_of_stock)
        $categories = $this->categoryModel->getAllActive();
        $products = $this->productModel->getAllActive();
        
        // Load varian (untuk mempermudah, karena kita tidak memakai ORM)
        $db = \App\Core\Database::getInstance()->getConnection();
        $variantsStmt = $db->query("SELECT * FROM product_variants");
        $allVariants = $variantsStmt->fetchAll();
        
        // Susun produk berdasarkan kategori dan attach variannya
        $menuData = [];
        foreach ($products as $p) {
            // Hanya tampilkan produk yang tidak habis
            if ($p['is_out_of_stock']) continue;
            
            $p['variants'] = array_values(array_filter($allVariants, function($v) use ($p) {
                return $v['product_id'] == $p['id'];
            }));
            
            $menuData[$p['category_id']][] = $p;
        }

        $this->view('pages/menu/index', [
            'title' => 'Menu - Good Coffee',
            'isAppLayout' => false, // Persuade Mode (tidak pakai sidebar admin)
            'table' => $table,
            'categories' => $categories,
            'menuData' => $menuData
        ]);
    }

    public function submitOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid method'], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['items'])) {
            $this->json(['success' => false, 'message' => 'Pesanan kosong']);
        }

        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $db->beginTransaction();

            // Insert into orders table (not transactions yet, as it's not paid)
            $stmt = $db->prepare("INSERT INTO orders (table_id, order_type, status, total_amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['table_id'],
                'dine_in',
                'pending',
                $input['total']
            ]);
            $orderId = $db->lastInsertId();

            // Insert items into order_items
            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($input['items'] as $item) {
                $variantId = isset($item['variant']) && $item['variant'] ? $item['variant']['id'] : null;
                $notes = isset($item['notes']) ? $item['notes'] : null;

                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $variantId,
                    $item['qty'],
                    $item['price'],
                    $notes
                ]);
            }

            // Update table status
            $tableStmt = $db->prepare("UPDATE tables SET status = 'occupied' WHERE id = ?");
            $tableStmt->execute([$input['table_id']]);

            $db->commit();
            $this->json(['success' => true, 'message' => 'Pesanan berhasil dikirim']);

        } catch (\Exception $e) {
            $db->rollBack();
            error_log('Error saving order: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
    }
}

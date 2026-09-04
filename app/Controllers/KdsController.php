<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class KdsController extends Controller {

    public function __construct() {
        // Only Admin, Manager, and Barista can access KDS
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Barista'])) {
            Session::setFlash('error', 'Akses ditolak. Halaman khusus Dapur/Barista.');
            $this->redirect('');
        }
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Ambil semua order yang belum selesai (pending atau processing)
        // Gabungkan dengan info meja dan item produk
        $query = "
            SELECT 
                o.id as order_id,
                o.table_id,
                o.order_type,
                o.status as order_status,
                o.created_at,
                t.table_number,
                oi.id as item_id,
                oi.product_id,
                oi.variant_id,
                oi.quantity,
                oi.notes,
                p.name as product_name,
                v.name as variant_name
            FROM orders o
            LEFT JOIN tables t ON o.table_id = t.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_variants v ON oi.variant_id = v.id
            WHERE o.status IN ('pending', 'processing')
            ORDER BY o.created_at ASC
        ";
        
        $stmt = $db->query($query);
        $rawOrders = $stmt->fetchAll();

        // Kelompokkan item berdasarkan order_id
        $orders = [];
        foreach ($rawOrders as $row) {
            $orderId = $row['order_id'];
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'id' => $row['order_id'],
                    'table_number' => $row['table_number'] ?? 'Take Away',
                    'order_type' => $row['order_type'],
                    'status' => $row['order_status'],
                    'time' => date('H:i', strtotime($row['created_at'])),
                    'elapsed_minutes' => round((time() - strtotime($row['created_at'])) / 60),
                    'items' => []
                ];
            }
            
            $orders[$orderId]['items'][] = [
                'id' => $row['item_id'],
                'name' => $row['product_name'],
                'variant' => $row['variant_name'],
                'qty' => $row['quantity'],
                'notes' => $row['notes']
            ];
        }

        $this->view('pages/kds/index', [
            'title' => 'Kitchen Display System',
            'orders' => array_values($orders)
        ]);
    }

    public function updateStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid method'], 405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $newStatus = $input['status'] ?? '';

        if (!in_array($newStatus, ['processing', 'completed'])) {
            $this->json(['success' => false, 'message' => 'Status tidak valid']);
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();
            
            // Check previous status
            $checkStmt = $db->prepare("SELECT status FROM orders WHERE id = ?");
            $checkStmt->execute([$orderId]);
            $currentOrder = $checkStmt->fetch(\PDO::FETCH_ASSOC);
            $previousStatus = $currentOrder['status'] ?? '';

            $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $orderId]);

            // Deduct stock if order becomes completed for the first time
            if ($newStatus === 'completed' && $previousStatus !== 'completed') {
                $userId = Session::get('user_id') ?? 1;
                $orderModel = new \App\Models\Order();
                $orderModel->deductStockForOrder($orderId, $userId);
            }

            $this->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()]);
        }
    }
}

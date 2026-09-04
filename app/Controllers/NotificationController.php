<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class NotificationController extends Controller {

    /**
     * Endpoint untuk mengecek apakah ada pesanan pending baru
     * Dipanggil via AJAX polling dari layar POS Kasir dan KDS
     */
    public function checkNewOrders() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->json(['success' => false], 405);
            return;
        }

        $lastCheck = isset($_GET['last_check']) ? $_GET['last_check'] : date('Y-m-d H:i:s', strtotime('-1 hour'));
        
        try {
            $db = Database::getInstance()->getConnection();
            // Cari orders baru yang statusnya pending dan dibuat SETELAH waktu last_check
            $stmt = $db->prepare("
                SELECT COUNT(*) as new_orders_count 
                FROM orders 
                WHERE status = 'pending' 
                AND created_at > ?
            ");
            $stmt->execute([$lastCheck]);
            $result = $stmt->fetch();
            
            $newOrdersCount = (int) $result['new_orders_count'];
            
            $this->json([
                'success' => true,
                'has_new_orders' => $newOrdersCount > 0,
                'count' => $newOrdersCount,
                'server_time' => date('Y-m-d H:i:s') // kembalikan waktu server saat ini untuk polling selanjutnya
            ]);
            
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

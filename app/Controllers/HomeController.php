<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Ingredient;
use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        // Simple auth check
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $role = Session::get('user_role_name');
        $db = Database::getInstance()->getConnection();
        
        $data = [
            'title' => 'Dashboard',
            'userRole' => $role,
            'userName' => Session::get('user_name')
        ];

        // Fetch Data berdasarkan Role
        if (in_array($role, ['Admin', 'Manager'])) {
            // Widget: Total Penjualan Hari Ini
            $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total_rev FROM transactions WHERE DATE(created_at) = CURDATE() AND payment_status = 'success'");
            $data['today_revenue'] = $stmt->fetchColumn();

            // Widget: Total Transaksi Hari Ini
            $stmt = $db->query("SELECT COUNT(id) FROM transactions WHERE DATE(created_at) = CURDATE() AND payment_status = 'success'");
            $data['today_trx'] = $stmt->fetchColumn();

            // Widget: Bahan Baku Menipis
            $stmt = $db->query("SELECT COUNT(id) FROM ingredients WHERE current_stock <= min_stock");
            $data['low_stock'] = $stmt->fetchColumn();

            // Widget: Produk Aktif
            $stmt = $db->query("SELECT COUNT(id) FROM products WHERE is_active = 1");
            $data['active_products'] = $stmt->fetchColumn();
        }

        if ($role === 'Kasir') {
            // Widget: Transaksi Shift Aktif (milik Kasir ini)
            $shiftId = Session::get('shift_id');
            if ($shiftId) {
                $stmt = $db->prepare("SELECT COUNT(id) FROM transactions WHERE shift_id = ? AND payment_status = 'success'");
                $stmt->execute([$shiftId]);
                $data['shift_trx'] = $stmt->fetchColumn();
            } else {
                $data['shift_trx'] = 0;
            }

            // Widget: Order Pending (belum bayar/checkout) - Dine In
            $stmt = $db->query("SELECT COUNT(id) FROM orders WHERE status = 'pending'");
            $data['pending_orders'] = $stmt->fetchColumn();
        }

        if ($role === 'Barista') {
            // Widget: Pesanan Masuk (Pending KDS)
            $stmt = $db->query("SELECT COUNT(id) FROM orders WHERE status = 'pending' OR status = 'processing'");
            $data['kds_queue'] = $stmt->fetchColumn();
            
            // Widget: Total Resep Produk
            $stmt = $db->query("SELECT COUNT(DISTINCT product_id) FROM recipes");
            $data['total_recipes'] = $stmt->fetchColumn();
        }

        $this->view('pages/home', $data);
    }
}

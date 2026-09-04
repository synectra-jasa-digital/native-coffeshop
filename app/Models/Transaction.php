<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all transactions with order details
     */
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        $sql = "
            SELECT t.*, o.order_type, o.table_id, u.name as cashier_name,
                   tbl.table_number
            FROM transactions t
            JOIN orders o ON t.order_id = o.id
            JOIN users u ON (SELECT user_id FROM shifts WHERE id = t.shift_id) = u.id
            LEFT JOIN tables tbl ON o.table_id = tbl.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(t.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(t.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        if (!empty($filters['payment_method'])) {
            $sql .= " AND t.payment_method = :payment_method";
            $params[':payment_method'] = $filters['payment_method'];
        }
        if (!empty($filters['shift_id'])) {
            $sql .= " AND t.shift_id = :shift_id";
            $params[':shift_id'] = $filters['shift_id'];
        }

        $sql .= " ORDER BY t.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get transaction by ID with full details
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT t.*, o.order_type, o.table_id, o.total_amount,
                   u.name as cashier_name, tbl.table_number
            FROM transactions t
            JOIN orders o ON t.order_id = o.id
            JOIN users u ON (SELECT user_id FROM shifts WHERE id = t.shift_id) = u.id
            LEFT JOIN tables tbl ON o.table_id = tbl.id
            WHERE t.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get order items for a transaction
     */
    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, pv.name as variant_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Void/cancel a transaction
     */
    public function void($id, $reason) {
        $this->db->beginTransaction();

        try {
            // Get transaction
            $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$transaction || $transaction['payment_status'] === 'refunded') {
                $this->db->rollBack();
                return false;
            }

            // Update transaction status
            $updateStmt = $this->db->prepare("
                UPDATE transactions SET payment_status = 'refunded' WHERE id = :id
            ");
            $updateStmt->execute([':id' => $id]);

            // Update order status
            $orderStmt = $this->db->prepare("
                UPDATE orders SET status = 'cancelled' WHERE id = :id
            ");
            $orderStmt->execute([':id' => $transaction['order_id']]);

            // TODO: Reverse stock deduction when void is processed

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get daily sales summary
     */
    public function getDailySummary($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_transactions,
                COALESCE(SUM(total), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN payment_method = 'cash' THEN total ELSE 0 END), 0) as cash_total,
                COALESCE(SUM(CASE WHEN payment_method != 'cash' THEN total ELSE 0 END), 0) as non_cash_total
            FROM transactions
            WHERE DATE(created_at) = :date AND payment_status = 'success'
        ");
        $stmt->execute([':date' => $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Count total transactions
     */
    public function countAll($filters = []) {
        $sql = "SELECT COUNT(*) FROM transactions t WHERE 1=1";
        $params = [];

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(t.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(t.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
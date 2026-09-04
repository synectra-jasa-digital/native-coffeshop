<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Shift {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get current open shift for a user
     */
    public function getOpenShift($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM shifts 
            WHERE user_id = :user_id AND status = 'open' 
            ORDER BY start_time DESC LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get open shift regardless of user (for admin override)
     */
    public function getAnyOpenShift() {
        $stmt = $this->db->query("SELECT * FROM shifts WHERE status = 'open' ORDER BY start_time DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Open a new shift
     */
    public function open($userId, $startingCash) {
        $stmt = $this->db->prepare("
            INSERT INTO shifts (user_id, start_time, starting_cash, status) 
            VALUES (:user_id, NOW(), :starting_cash, 'open')
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':starting_cash' => $startingCash
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Close a shift with reconciliation
     */
    public function close($shiftId, $endingCash) {
        $this->db->beginTransaction();

        try {
            // Get shift data
            $stmt = $this->db->prepare("SELECT * FROM shifts WHERE id = :id AND status = 'open'");
            $stmt->execute([':id' => $shiftId]);
            $shift = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$shift) {
                $this->db->rollBack();
                return false;
            }

            // Calculate expected cash from transactions
            $transStmt = $this->db->prepare("
                SELECT COALESCE(SUM(total), 0) as total_sales 
                FROM transactions 
                WHERE shift_id = :shift_id AND payment_method = 'cash' AND payment_status = 'success'
            ");
            $transStmt->execute([':shift_id' => $shiftId]);
            $totalSales = $transStmt->fetchColumn();

            $expectedCash = $shift['starting_cash'] + $totalSales;
            $difference = $endingCash - $expectedCash;

            // Update shift
            $updateStmt = $this->db->prepare("
                UPDATE shifts 
                SET end_time = NOW(), 
                    ending_cash = :ending_cash, 
                    expected_cash = :expected_cash, 
                    difference = :difference, 
                    status = 'closed' 
                WHERE id = :id
            ");
            $updateStmt->execute([
                ':ending_cash' => $endingCash,
                ':expected_cash' => $expectedCash,
                ':difference' => $difference,
                ':id' => $shiftId
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get shift summary (total transactions, cash)
     */
    public function getSummary($shiftId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(t.id) as total_transactions,
                COALESCE(SUM(t.total), 0) as total_amount,
                COALESCE(SUM(CASE WHEN t.payment_method = 'cash' THEN t.total ELSE 0 END), 0) as cash_amount,
                COALESCE(SUM(CASE WHEN t.payment_method != 'cash' THEN t.total ELSE 0 END), 0) as non_cash_amount
            FROM transactions t
            WHERE t.shift_id = :shift_id AND t.payment_status = 'success'
        ");
        $stmt->execute([':shift_id' => $shiftId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get shift history
     */
    public function getAll($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name,
                   (SELECT COUNT(t.id) FROM transactions t WHERE t.shift_id = s.id AND t.payment_status = 'success') as total_transactions,
                   (SELECT COALESCE(SUM(t.total), 0) FROM transactions t WHERE t.shift_id = s.id AND t.payment_status = 'success') as total_amount
            FROM shifts s 
            JOIN users u ON s.user_id = u.id 
            ORDER BY s.start_time DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get shift by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.name as user_name 
            FROM shifts s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
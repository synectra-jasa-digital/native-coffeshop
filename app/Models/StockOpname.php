<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class StockOpname {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all stock opnames with details
     */
    public function getAll($status = null) {
        $query = "SELECT so.*, i.name as ingredient_name, i.unit, u.name as user_name 
                  FROM stock_opnames so 
                  JOIN ingredients i ON so.ingredient_id = i.id 
                  JOIN users u ON so.user_id = u.id ";
        
        $params = [];
        if ($status) {
            $query .= " WHERE so.status = ? ";
            $params[] = $status;
        }
        
        $query .= " ORDER BY so.created_at DESC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new stock opname record
     */
    public function create($data) {
        $this->db->beginTransaction();

        try {
            // 1. Insert opname record as 'approved' automatically
            $stmt = $this->db->prepare("INSERT INTO stock_opnames (ingredient_id, user_id, expected_qty, actual_qty, difference, status) VALUES (?, ?, ?, ?, ?, 'approved')");
            $stmt->execute([
                $data['ingredient_id'],
                $data['user_id'],
                $data['expected_qty'],
                $data['actual_qty'],
                $data['difference']
            ]);
            
            $opnameId = $this->db->lastInsertId();

            // 2. Update actual ingredient stock to match actual_qty automatically
            $updateStock = $this->db->prepare("UPDATE ingredients SET current_stock = ? WHERE id = ?");
            $updateStock->execute([$data['actual_qty'], $data['ingredient_id']]);

            // 3. Automatically insert movement log (adjustment)
            $moveStmt = $this->db->prepare("INSERT INTO stock_movements (ingredient_id, user_id, type, quantity, reference_id, notes) VALUES (?, ?, 'adjustment', ?, ?, 'Otomatis: Stock Opname Update')");
            $moveStmt->execute([
                $data['ingredient_id'],
                $data['user_id'],
                $data['difference'],
                $opnameId
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Stock Opname error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve or reject a stock opname
     */
    public function updateStatus($id, $status) {
        $this->db->beginTransaction();

        try {
            // Update status
            $stmt = $this->db->prepare("UPDATE stock_opnames SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            if ($status === 'approved') {
                // If approved, update the actual ingredient stock to match the actual_qty
                $opnameStmt = $this->db->prepare("SELECT ingredient_id, actual_qty, user_id, difference FROM stock_opnames WHERE id = ?");
                $opnameStmt->execute([$id]);
                $opname = $opnameStmt->fetch(PDO::FETCH_ASSOC);

                if ($opname) {
                    // Update main table
                    $updateStock = $this->db->prepare("UPDATE ingredients SET current_stock = ? WHERE id = ?");
                    $updateStock->execute([$opname['actual_qty'], $opname['ingredient_id']]);

                    // Insert movement log
                    $moveStmt = $this->db->prepare("INSERT INTO stock_movements (ingredient_id, user_id, type, quantity, reference_id, notes) VALUES (?, ?, 'adjustment', ?, ?, 'Approved Stock Opname')");
                    $moveStmt->execute([
                        $opname['ingredient_id'],
                        $opname['user_id'], // Or the approver's ID if we track it
                        $opname['difference'],
                        $id
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class StockMovement {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get recent stock movements with ingredient and user details
     */
    public function getRecent($limit = 50) {
        $query = "SELECT sm.*, i.name as ingredient_name, i.unit, u.name as user_name 
                  FROM stock_movements sm 
                  JOIN ingredients i ON sm.ingredient_id = i.id 
                  JOIN users u ON sm.user_id = u.id 
                  ORDER BY sm.created_at DESC 
                  LIMIT " . (int)$limit;
                  
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Record a new stock movement and update current_stock
     */
    public function recordMovement($data) {
        $this->db->beginTransaction();

        try {
            // 1. Insert Movement Record
            $stmt = $this->db->prepare("INSERT INTO stock_movements (ingredient_id, user_id, type, quantity, reference_id, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['ingredient_id'],
                $data['user_id'],
                $data['type'], // 'in', 'out', 'adjustment'
                $data['quantity'],
                $data['reference_id'] ?? null,
                $data['notes'] ?? ''
            ]);

            // 2. Update Ingredient Current Stock
            // Calculate the delta based on type
            $qty = (float)$data['quantity'];
            $delta = 0;
            if ($data['type'] === 'in') {
                $delta = $qty;
            } elseif ($data['type'] === 'out') {
                $delta = -$qty;
            } elseif ($data['type'] === 'adjustment') {
                // If it's an adjustment, the 'quantity' might just be the delta, 
                // but usually adjustments are handled differently. 
                // For this implementation, we will treat 'adjustment' quantity as the exact delta (+ or -)
                $delta = $qty; 
            }

            $updateStmt = $this->db->prepare("UPDATE ingredients SET current_stock = current_stock + (?) WHERE id = ?");
            $updateStmt->execute([$delta, $data['ingredient_id']]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

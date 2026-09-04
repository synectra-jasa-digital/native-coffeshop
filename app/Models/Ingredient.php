<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Ingredient {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get database connection (for use in other models)
     */
    public function getConnection() {
        return $this->db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM ingredients ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ingredients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get ingredients with low stock (at or below minimum)
     */
    public function getLowStock() {
        $stmt = $this->db->query("
            SELECT * FROM ingredients 
            WHERE current_stock <= min_stock AND min_stock > 0
            ORDER BY current_stock ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO ingredients (name, unit, min_stock, current_stock) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['unit'],
            $data['min_stock'] ?? 0.00,
            $data['current_stock'] ?? 0.00
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE ingredients SET name = ?, unit = ?, min_stock = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['unit'],
            $data['min_stock'] ?? 0.00,
            $id
        ]);
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM ingredients WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            // Usually fails if ingredient is used in recipes or stock movements
            return false;
        }
    }
}
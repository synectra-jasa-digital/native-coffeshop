<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all categories
     */
    public function getAll($activeOnly = false) {
        $sql = "SELECT c.*, COUNT(p.id) as total_products 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id ";
        
        if ($activeOnly) {
            $sql .= "WHERE c.is_active = 1 ";
        }
        
        $sql .= "GROUP BY c.id ORDER BY c.sort_order ASC, c.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get category by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new category
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, sort_order, is_active) 
            VALUES (:name, :sort_order, :is_active)
        ");
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':sort_order' => $data['sort_order'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    /**
     * Update existing category
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE categories 
            SET name = :name, sort_order = :sort_order, is_active = :is_active 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':sort_order' => $data['sort_order'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    /**
     * Delete category
     */
    public function delete($id) {
        // First check if there are products using this category
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute([':id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Cannot delete category with products
        }

        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
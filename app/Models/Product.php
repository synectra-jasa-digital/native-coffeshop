<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all products with optional filters
     */
    public function getAll($categoryId = null, $activeOnly = false) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id WHERE 1=1";
        
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        if ($activeOnly) {
            $sql .= " AND p.is_active = 1";
        }
        
        $sql .= " ORDER BY c.sort_order ASC, p.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new product
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products (category_id, name, description, base_price, image_url, is_active, is_out_of_stock) 
            VALUES (:category_id, :name, :description, :base_price, :image_url, :is_active, :is_out_of_stock)
        ");
        
        if ($stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? '',
            ':base_price' => $data['base_price'],
            ':image_url' => $data['image_url'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
            ':is_out_of_stock' => $data['is_out_of_stock'] ?? 0
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update existing product
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET category_id = :category_id, 
                name = :name, 
                description = :description, 
                base_price = :base_price, 
                image_url = :image_url,
                is_active = :is_active, 
                is_out_of_stock = :is_out_of_stock 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? '',
            ':base_price' => $data['base_price'],
            ':image_url' => $data['image_url'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
            ':is_out_of_stock' => $data['is_out_of_stock'] ?? 0
        ]);
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get variants for a product
     */
    public function getVariants($productId) {
        $stmt = $this->db->prepare("SELECT * FROM product_variants WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create product variant
     */
    public function createVariant($data) {
        $stmt = $this->db->prepare("
            INSERT INTO product_variants (product_id, name, additional_price) 
            VALUES (:product_id, :name, :additional_price)
        ");
        
        return $stmt->execute([
            ':product_id' => $data['product_id'],
            ':name' => $data['name'],
            ':additional_price' => $data['additional_price'] ?? 0
        ]);
    }
    
    /**
     * Delete product variant
     */
    public function deleteVariant($id) {
        $stmt = $this->db->prepare("DELETE FROM product_variants WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
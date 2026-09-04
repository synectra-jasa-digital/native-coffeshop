<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Recipe {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get recipe by product_id and optional variant_id
     */
    public function getRecipeByProduct($productId, $variantId = null) {
        $query = "SELECT r.*, i.name as ingredient_name, i.unit 
                  FROM recipes r 
                  JOIN ingredients i ON r.ingredient_id = i.id 
                  WHERE r.product_id = :product_id";
        
        $params = [':product_id' => $productId];

        if ($variantId !== null) {
            $query .= " AND r.variant_id = :variant_id";
            $params[':variant_id'] = $variantId;
        } else {
            $query .= " AND r.variant_id IS NULL";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add or update ingredient in a recipe
     */
    public function saveItem($data) {
        // Check if ingredient already exists for this product/variant combination
        $query = "SELECT id FROM recipes WHERE product_id = :product_id AND ingredient_id = :ingredient_id";
        $params = [
            ':product_id' => $data['product_id'],
            ':ingredient_id' => $data['ingredient_id']
        ];

        if (!empty($data['variant_id'])) {
            $query .= " AND variant_id = :variant_id";
            $params[':variant_id'] = $data['variant_id'];
        } else {
            $query .= " AND variant_id IS NULL";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update quantity
            $stmt = $this->db->prepare("UPDATE recipes SET quantity = ? WHERE id = ?");
            return $stmt->execute([$data['quantity'], $existing['id']]);
        } else {
            // Insert new recipe item
            $stmt = $this->db->prepare("INSERT INTO recipes (product_id, variant_id, ingredient_id, quantity) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $data['product_id'],
                $data['variant_id'] ?: null,
                $data['ingredient_id'],
                $data['quantity']
            ]);
        }
    }

    /**
     * Delete an ingredient from a recipe
     */
    public function deleteItem($id) {
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a complete order with items and transaction, deduct stock
     */
    public function createCompleteOrder($orderData, $items, $transactionData) {
        $this->db->beginTransaction();

        try {
            // 1. Insert Order
            $stmt = $this->db->prepare("INSERT INTO orders (table_id, order_type, status, total_amount) VALUES (?, ?, 'completed', ?)");
            $stmt->execute([
                $orderData['table_id'] ?? null,
                $orderData['order_type'],
                $orderData['total_amount']
            ]);
            $orderId = $this->db->lastInsertId();

            // 2. Insert Order Items and Deduct Stock based on Recipe
            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes) VALUES (?, ?, ?, ?, ?, ?)");
            
            // Prepare query for getting recipe
            $recipeStmt = $this->db->prepare("SELECT ingredient_id, quantity FROM recipes WHERE product_id = ? AND (variant_id = ? OR variant_id IS NULL)");
            
            // Prepare query for stock deduction
            $stockStmt = $this->db->prepare("UPDATE ingredients SET current_stock = current_stock - ? WHERE id = ?");

            foreach ($items as $item) {
                // Insert Item
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['variant_id'] ?? null,
                    $item['quantity'],
                    $item['price'],
                    $item['notes'] ?? ''
                ]);

                // Get Recipe for this item
                $recipeStmt->execute([$item['product_id'], $item['variant_id'] ?? null]);
                $recipes = $recipeStmt->fetchAll(PDO::FETCH_ASSOC);

                // Deduct stock for each ingredient in the recipe
                foreach ($recipes as $recipe) {
                    $totalDeduction = $recipe['quantity'] * $item['quantity'];
                    $stockStmt->execute([$totalDeduction, $recipe['ingredient_id']]);
                }
            }

            // 3. Insert Transaction
            $transStmt = $this->db->prepare("INSERT INTO transactions (order_id, shift_id, subtotal, tax, service_charge, discount, total, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'success')");
            $transStmt->execute([
                $orderId,
                $transactionData['shift_id'], // Needs active shift logic
                $transactionData['subtotal'],
                $transactionData['tax'],
                $transactionData['service_charge'],
                $transactionData['discount'],
                $transactionData['total'],
                $transactionData['payment_method']
            ]);

            $this->db->commit();
            return $orderId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

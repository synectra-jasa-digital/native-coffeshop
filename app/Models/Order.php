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
            // 1. Insert Order (Set status to 'pending' so it appears on KDS for Barista/Kitchen)
            $stmt = $this->db->prepare("INSERT INTO orders (table_id, order_type, status, total_amount) VALUES (?, ?, 'pending', ?)");
            $stmt->execute([
                $orderData['table_id'] ?? null,
                $orderData['order_type'],
                $orderData['total_amount']
            ]);
            $orderId = $this->db->lastInsertId();

            // 2. Insert Order Items
            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes) VALUES (?, ?, ?, ?, ?, ?)");
            
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
            }

            // 3. Insert Transaction
            $transStmt = $this->db->prepare("INSERT INTO transactions (order_id, shift_id, subtotal, tax, service_charge, discount, total, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'success')");
            $transStmt->execute([
                $orderId,
                $transactionData['shift_id'],
                $transactionData['subtotal'],
                $transactionData['tax'],
                $transactionData['service_charge'] ?? 0,
                $transactionData['discount'] ?? 0,
                $transactionData['total'],
                $transactionData['payment_method']
            ]);

            // Optional: Jika ini pesanan Dine-In, ubah status meja jadi occupied 
            // (Walaupun POS langsung dianggap completed, mungkin ada baiknya meja tetap ditandai 'occupied' 
            // sampai pelanggan pergi. Jika mau meja lgsg kosong bisa dikomen baris di bwh ini)
            if (!empty($orderData['table_id'])) {
                $tableStmt = $this->db->prepare("UPDATE tables SET status = 'occupied' WHERE id = ?");
                $tableStmt->execute([$orderData['table_id']]);
            }

            $this->db->commit();
            return $orderId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log('Order creation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deduct stock for an existing order (used when KDS order is completed)
     */
    public function deductStockForOrder($orderId, $userId = 1) {
        try {
            // Fetch order items
            $itemStmt = $this->db->prepare("SELECT product_id, variant_id, quantity FROM order_items WHERE order_id = ?");
            $itemStmt->execute([$orderId]);
            $items = $itemStmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($items)) return false;

            $recipeStmt = $this->db->prepare("SELECT ingredient_id, quantity FROM recipes WHERE product_id = ? AND (variant_id = ? OR variant_id IS NULL)");
            $stockStmt = $this->db->prepare("UPDATE ingredients SET current_stock = current_stock - ? WHERE id = ?");
            $movementStmt = $this->db->prepare("INSERT INTO stock_movements (ingredient_id, user_id, type, quantity, reference_id, notes) VALUES (?, ?, 'out', ?, ?, ?)");

            foreach ($items as $item) {
                $recipeStmt->execute([$item['product_id'], $item['variant_id'] ?? null]);
                $recipes = $recipeStmt->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($recipes as $recipe) {
                    $totalDeduction = (float)$recipe['quantity'] * (float)$item['quantity'];
                    
                    // Deduct stock
                    $stockStmt->execute([$totalDeduction, $recipe['ingredient_id']]);
                    
                    // Record movement
                    $movementNotes = "Terjual KDS: Order #" . $orderId;
                    $movementStmt->execute([$recipe['ingredient_id'], $userId, $totalDeduction, $orderId, $movementNotes]);
                }
            }
            return true;
        } catch (\Exception $e) {
            error_log('Stock deduction error for order ' . $orderId . ': ' . $e->getMessage());
            return false;
        }
    }
}

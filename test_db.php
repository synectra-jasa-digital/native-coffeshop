<?php
require 'D:/Alternatif_D/laragon/www/native-coffeshop/config/config.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Core/Database.php';

$db = \App\Core\Database::getInstance()->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $db->beginTransaction();
    $stmt = $db->prepare('INSERT INTO orders (table_id, order_type, status, total_amount) VALUES (?, ?, ?, ?)');
    $stmt->execute([null, 'take_away', 'completed', 15000]);
    $orderId = $db->lastInsertId();
    echo 'Order inserted. ID: ' . $orderId . PHP_EOL;

    // Assuming product 1 exists
    $itemStmt = $db->prepare('INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes) VALUES (?, ?, ?, ?, ?, ?)');
    $itemStmt->execute([$orderId, 1, null, 1, 15000, 'Test']);
    echo 'Item inserted.' . PHP_EOL;

    // Movement test
    $movementStmt = $db->prepare("INSERT INTO stock_movements (ingredient_id, type, quantity, notes) VALUES (?, 'out', ?, ?)");
    $movementStmt->execute([1, 1, 'Terjual']);
    echo 'Movement inserted.' . PHP_EOL;

    $transStmt = $db->prepare("INSERT INTO transactions (order_id, shift_id, subtotal, tax, service_charge, discount, total, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'success')");
    $transStmt->execute([$orderId, 1, 15000, 0, 0, 0, 15000, 'cash']);
    echo 'Transaction inserted.' . PHP_EOL;
    
    $db->rollBack(); // clean up
} catch(Exception $e) {
    echo 'ERROR FOUND: ' . $e->getMessage();
}

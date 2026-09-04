<?php
require 'D:/Alternatif_D/laragon/www/native-coffeshop/config/config.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Core/Database.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Models/Order.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Models/Shift.php';

$db = \App\Core\Database::getInstance()->getConnection();
$orderModel = new \App\Models\Order();

$recipes = $db->query("SELECT r.product_id, r.variant_id, p.base_price, p.name 
                       FROM recipes r 
                       JOIN products p ON r.product_id = p.id LIMIT 1")->fetch();

if (!$recipes) die("No recipes");

$shift = $db->query("SELECT id FROM shifts ORDER BY id DESC LIMIT 1")->fetch();
$shift_id = $shift ? $shift['id'] : 1;

$orderData = ['order_type' => 'take_away', 'table_id' => null, 'total_amount' => $recipes['base_price']];
$items = [[
    'product_id' => $recipes['product_id'], 'variant_id' => $recipes['variant_id'],
    'quantity' => 1, 'price' => $recipes['base_price'], 'notes' => 'Testing bot'
]];
$transactionData = [
    'shift_id' => $shift_id, 'subtotal' => $recipes['base_price'], 'tax' => 0,
    'service_charge' => 0, 'discount' => 0, 'total' => $recipes['base_price'], 'payment_method' => 'cash'
];

try {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $orderId = $orderModel->createCompleteOrder($orderData, $items, $transactionData);
    if(!$orderId) echo "Rollbacked. Logic error inside method.";
    else echo "Success ID: " . $orderId;
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

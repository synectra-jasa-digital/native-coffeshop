<?php
require 'D:/Alternatif_D/laragon/www/native-coffeshop/config/config.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Core/Database.php';

$db = \App\Core\Database::getInstance()->getConnection();

echo "--- LATEST ORDERS ---\n";
$stmt = $db->query("SELECT * FROM orders ORDER BY id DESC LIMIT 3");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($orders);

if ($orders) {
    echo "\n--- ITEMS FOR LATEST ORDER (ID: {$orders[0]['id']}) ---\n";
    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->execute([$orders[0]['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($items);

    if ($items) {
        $product_id = $items[0]['product_id'];
        $variant_id = $items[0]['variant_id'];
        echo "\n--- RECIPES FOR PRODUCT {$product_id} (VARIANT: " . ($variant_id ? $variant_id : 'NULL') . ") ---\n";
        $recipeStmt = $db->prepare("SELECT ingredient_id, quantity FROM recipes WHERE product_id = ? AND (variant_id = ? OR variant_id IS NULL)");
        $recipeStmt->execute([$product_id, $variant_id]);
        $recipes = $recipeStmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($recipes);
    }
}

echo "\n--- LATEST STOCK MOVEMENTS ---\n";
$stmt = $db->query("SELECT * FROM stock_movements ORDER BY id DESC LIMIT 5");
$moves = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($moves);

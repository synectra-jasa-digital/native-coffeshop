<?php
require 'D:/Alternatif_D/laragon/www/native-coffeshop/config/config.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Core/Database.php';

$db = \App\Core\Database::getInstance()->getConnection();

$tables = ['orders', 'order_items', 'transactions', 'stock_movements', 'recipes', 'ingredients'];

foreach ($tables as $table) {
    try {
        $stmt = $db->query("SHOW CREATE TABLE $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $result['Create Table'] . "\n\n";
    } catch (Exception $e) {
        echo "Error on $table: " . $e->getMessage() . "\n\n";
    }
}

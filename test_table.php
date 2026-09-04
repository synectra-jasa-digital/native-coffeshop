
<?php
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/config/config.php';
require 'D:/Alternatif_D/laragon/www/native-coffeshop/app/Core/Database.php';

$db = \App\Core\Database::getInstance()->getConnection();

// Create table if not exists
$stmt = $db->query("SELECT * FROM tables LIMIT 1");
$table = $stmt->fetch();

if (!$table) {
    $token = bin2hex(random_bytes(16));
    $db->prepare("INSERT INTO tables (table_number, qr_code, status) VALUES (?, ?, ?)")
       ->execute(['15', $token, 'empty']);
    
    $stmt = $db->query("SELECT * FROM tables LIMIT 1");
    $table = $stmt->fetch();
}

echo json_encode([
    'url' => 'http://localhost/native-coffeshop/menu/' . $table['qr_code'],
    'token' => $table['qr_code']
]);

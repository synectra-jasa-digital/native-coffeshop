<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

try {
    $db = \App\Core\Database::getInstance()->getConnection();
    
    // Get a valid QR token
    $stmt = $db->query("SELECT qr_code FROM tables LIMIT 1");
    $token = $stmt->fetchColumn();
    echo "QR_TOKEN:" . $token;
    
} catch (Exception $e) {
    echo "ERROR:" . $e->getMessage();
}
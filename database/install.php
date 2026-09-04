<?php
/**
 * Database Installation & Seeding Script
 * Run via CLI: php database/install.php
 */

require_once __DIR__ . '/../config/config.php';

try {
    // 1. Connect without database selected first (in case it doesn't exist)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Create database if not exists
    echo "Creating database '" . DB_NAME . "'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`");
    echo "Database created/exists.\n";
    
    // 3. Connect to the actual database
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // 4. Read init.sql
    echo "Reading schema file...\n";
    $sql = file_get_contents(__DIR__ . '/init.sql');
    if (!$sql) {
        die("Could not read init.sql");
    }
    
    // 5. Execute schema
    echo "Executing schema...\n";
    $pdo->exec($sql);
    echo "Schema created successfully.\n";
    
    // 6. Seed initial Admin User
    echo "Seeding Admin user...\n";
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Get Admin Role ID
        $stmtRole = $pdo->prepare("SELECT id FROM roles WHERE name = 'Admin'");
        $stmtRole->execute();
        $roleId = $stmtRole->fetchColumn();
        
        if ($roleId) {
            $password = password_hash('password123', PASSWORD_DEFAULT);
            $stmtUser = $pdo->prepare("INSERT INTO users (role_id, name, username, email, password, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtUser->execute([$roleId, 'Super Admin', 'admin', 'admin@goodcoffee.com', $password, 'active']);
            echo "Admin user created successfully! (Username: admin, Password: password123)\n";
        } else {
            echo "Error: Admin role not found in database.\n";
        }
    } else {
        echo "Admin user already exists. Skipping...\n";
    }

    echo "\n=== Database Installation Completed Successfully! ===\n";
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

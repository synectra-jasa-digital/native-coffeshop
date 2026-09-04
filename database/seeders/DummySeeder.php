<?php
/**
 * Dummy Data Seeder for Testing
 * Run via CLI: php database/seeders/DummySeeder.php
 */

require_once __DIR__ . '/../../config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Starting Dummy Seeder...\n\n";

    // --- 1. SEED CATEGORIES ---
    echo "1. Seeding Categories...\n";
    $categories = [
        ['Coffee Based', 1],
        ['Non-Coffee', 2],
        ['Mocktails & Tea', 3],
        ['Pastry & Bakery', 4]
    ];
    
    $pdo->exec("DELETE FROM categories"); // Clean up
    $pdo->exec("ALTER TABLE categories AUTO_INCREMENT = 1");
    
    $stmt = $pdo->prepare("INSERT INTO categories (name, sort_order, is_active) VALUES (?, ?, 1)");
    foreach ($categories as $cat) {
        $stmt->execute($cat);
    }
    echo "   - Created " . count($categories) . " categories.\n";

    // --- 2. SEED PRODUCTS ---
    echo "2. Seeding Products...\n";
    $products = [
        // Coffee
        [1, 'Espresso', 'Single shot of pure Arabica extraction.', 15000, 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [1, 'Americano', 'Espresso diluted with hot water.', 18000, 'https://images.unsplash.com/photo-1551030173-122aabc4489c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [1, 'Cappuccino', 'Equal parts of espresso, steamed milk, and milk foam.', 22000, 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [1, 'Caffe Latte', 'Espresso with steamed milk and a light layer of foam.', 25000, 'https://images.unsplash.com/photo-1534778101976-62847782c213?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [1, 'Caramel Macchiato', 'Espresso, steamed milk, vanilla syrup, and caramel drizzle.', 28000, 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [1, 'Kopi Susu Gula Aren', 'Signature iced coffee with palm sugar.', 20000, 'https://images.unsplash.com/photo-1461023058943-07cb14c4e20b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        
        // Non-Coffee
        [2, 'Matcha Latte', 'Premium Japanese matcha with steamed milk.', 25000, 'https://images.unsplash.com/photo-1536514072410-5019a3c69182?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [2, 'Chocolate Latte', 'Rich dark chocolate with steamed milk.', 24000, 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [2, 'Red Velvet Latte', 'Creamy red velvet blend with milk.', 25000, 'https://images.unsplash.com/photo-1620360289473-b3c988eeab6c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        
        // Mocktails
        [3, 'Lychee Yakult', 'Refreshing lychee with yakult blend.', 22000, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [3, 'Lemon Tea', 'Classic iced lemon tea.', 15000, 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        
        // Pastry
        [4, 'Butter Croissant', 'Classic French butter pastry.', 18000, 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [4, 'Almond Croissant', 'Croissant filled and topped with almonds.', 22000, 'https://images.unsplash.com/photo-1626078298711-2eb2f6764d85?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'],
        [4, 'Chocolate Muffin', 'Soft muffin with chocolate chunks.', 20000, 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80']
    ];

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DELETE FROM products");
    $pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, description, base_price, image_url, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    foreach ($products as $p) {
        $stmt->execute($p);
    }
    echo "   - Created " . count($products) . " products with unsplash images.\n";

    // --- 3. SEED PRODUCT VARIANTS ---
    echo "3. Seeding Variants...\n";
    $variantsData = [
        [2, 'Ice', 2000],      // Americano Ice
        [3, 'Ice', 2000],      // Cappuccino Ice
        [4, 'Ice', 2000],      // Caffe Latte Ice
        [4, 'Oat Milk', 8000], // Caffe Latte Oat Milk
        [5, 'Ice', 2000],      // Caramel Macchiato Ice
        [7, 'Ice', 2000],      // Matcha Ice
        [8, 'Ice', 2000]       // Choco Ice
    ];

    $pdo->exec("DELETE FROM product_variants");
    $pdo->exec("ALTER TABLE product_variants AUTO_INCREMENT = 1");

    $stmt = $pdo->prepare("INSERT INTO product_variants (product_id, name, additional_price) VALUES (?, ?, ?)");
    foreach ($variantsData as $v) {
        $stmt->execute($v);
    }
    echo "   - Created " . count($variantsData) . " variants.\n";

    // --- 4. SEED INGREDIENTS ---
    echo "4. Seeding Ingredients...\n";
    $ingredients = [
        ['Biji Kopi Arabica', 'Gram', 1000, 5000],
        ['Susu Segar (Fresh Milk)', 'ML', 2000, 10000],
        ['Susu Oat (Oat Milk)', 'ML', 1000, 3000],
        ['Gula Aren Cair', 'ML', 500, 2000],
        ['Matcha Powder', 'Gram', 200, 1000],
        ['Chocolate Powder', 'Gram', 300, 1500],
        ['Sirup Caramel', 'ML', 300, 1500],
        ['Es Batu', 'Cup', 50, 200],
        ['Croissant Dough', 'Pcs', 10, 50]
    ];

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DELETE FROM ingredients");
    $pdo->exec("ALTER TABLE ingredients AUTO_INCREMENT = 1");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $stmt = $pdo->prepare("INSERT INTO ingredients (name, unit, min_stock, current_stock) VALUES (?, ?, ?, ?)");
    foreach ($ingredients as $i) {
        $stmt->execute($i);
    }
    echo "   - Created " . count($ingredients) . " ingredients.\n";

    // --- 5. SEED RECIPES (Optional/Basic) ---
    echo "5. Seeding Basic Recipes...\n";
    // For Espresso (Product 1) -> uses 18g Arabica (Ingredient 1)
    $pdo->exec("INSERT INTO recipes (product_id, ingredient_id, quantity) VALUES (1, 1, 18.00)");
    
    // For Americano (Product 2) -> uses 18g Arabica
    $pdo->exec("INSERT INTO recipes (product_id, ingredient_id, quantity) VALUES (2, 1, 18.00)");
    
    // For Kopi Susu Gula Aren (Product 6) -> 18g Arabica, 100ml Milk, 20ml Gula Aren
    $pdo->exec("INSERT INTO recipes (product_id, ingredient_id, quantity) VALUES (6, 1, 18.00)");
    $pdo->exec("INSERT INTO recipes (product_id, ingredient_id, quantity) VALUES (6, 2, 100.00)");
    $pdo->exec("INSERT INTO recipes (product_id, ingredient_id, quantity) VALUES (6, 4, 20.00)");
    
    echo "   - Recipes mapped.\n";

    echo "\n=== Seeding Completed Successfully! ===\n";

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

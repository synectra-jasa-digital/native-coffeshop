<?php
// Main routing file

use App\Core\Router;
use App\Core\Session;

$router = new Router();

// Middleware-like function to check auth
function requireAuth() {
    if (!Session::has('user_id')) {
        header("Location: " . BASE_URL . "/login");
        exit();
    }
}

// Auth Routes
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'processLogin');
$router->get('/logout', 'AuthController', 'logout');

// Protected Routes - Dashboard & Profile
$router->get('/', 'HomeController', 'index');
$router->get('/profile', 'ProfileController', 'index');
$router->post('/profile/save', 'ProfileController', 'save');

// Protected Routes - Products
$router->get('/products', 'ProductController', 'index');
$router->get('/products/create', 'ProductController', 'form');
$router->post('/products/save', 'ProductController', 'save');
$router->get('/products/edit/:id', 'ProductController', 'form');
$router->post('/products/save/:id', 'ProductController', 'save');
$router->post('/products/delete/:id', 'ProductController', 'delete');

// Protected Routes - Categories
$router->get('/categories', 'ProductController', 'categories');
$router->get('/categories/create', 'ProductController', 'formCategory');
$router->post('/categories/save', 'ProductController', 'saveCategory');
$router->get('/categories/edit/:id', 'ProductController', 'formCategory');
$router->post('/categories/save/:id', 'ProductController', 'saveCategory');
$router->post('/categories/delete/:id', 'ProductController', 'deleteCategory');

// Protected Routes - Inventory (Ingredients)
$router->get('/inventory/ingredients', 'InventoryController', 'ingredients');
$router->get('/inventory/ingredients/create', 'InventoryController', 'formIngredient');
$router->post('/inventory/ingredients/save', 'InventoryController', 'saveIngredient');
$router->get('/inventory/ingredients/edit/:id', 'InventoryController', 'formIngredient');
$router->post('/inventory/ingredients/save/:id', 'InventoryController', 'saveIngredient');
$router->post('/inventory/ingredients/delete/:id', 'InventoryController', 'deleteIngredient');

// Protected Routes - Inventory (Recipes)
$router->get('/inventory/recipes', 'InventoryController', 'recipes');
$router->get('/inventory/recipes/:id', 'InventoryController', 'manageRecipe');
$router->get('/inventory/recipes/:id/:variant_id', 'InventoryController', 'manageRecipe');
$router->post('/inventory/recipes/save', 'InventoryController', 'saveRecipeItem');
$router->post('/inventory/recipes/delete/:id', 'InventoryController', 'deleteRecipeItem');

// Protected Routes - Inventory (Stock Movements)
$router->get('/inventory/movements', 'InventoryController', 'movements');
$router->get('/inventory/movements/create', 'InventoryController', 'formMovement');
$router->post('/inventory/movements/record', 'InventoryController', 'recordMovement');

// Protected Routes - Inventory (Stock Opname)
$router->get('/inventory/opname', 'InventoryController', 'opname');
$router->get('/inventory/opname/create', 'InventoryController', 'formOpname');
$router->post('/inventory/opname/submit', 'InventoryController', 'submitOpname');
$router->post('/inventory/opname/status/:id', 'InventoryController', 'updateOpnameStatus');

// Protected Routes - POS
$router->get('/pos', 'PosController', 'index');
$router->post('/pos/checkout', 'PosController', 'checkout');
$router->get('/pos/print/(\d+)', 'PosController', 'printReceipt');

// Protected Routes - Shift
$router->get('/shift/open', 'ShiftController', 'formOpen');
$router->post('/shift/open', 'ShiftController', 'processOpen');
$router->get('/shift/close', 'ShiftController', 'formClose');
$router->post('/shift/close', 'ShiftController', 'processClose');
$router->get('/shift/history', 'ShiftController', 'history');

// Protected Routes - Transactions
$router->get('/transactions', 'TransactionController', 'index');
$router->get('/transactions/:id', 'TransactionController', 'detail');
$router->post('/transactions/:id/void', 'TransactionController', 'void');

// Protected Routes - Settings
$router->get('/settings', 'SettingController', 'index');
$router->post('/settings/save', 'SettingController', 'save');

// Customer Menu (Public)
$router->get('/menu/:id', 'CustomerMenuController', 'index');
$router->post('/menu/submit', 'CustomerMenuController', 'submitOrder');

// Kitchen Display System (KDS)
$router->get('/kds', 'KdsController', 'index');
$router->post('/kds/status/:id', 'KdsController', 'updateStatus');

// Table Management
$router->get('/tables', 'TableController', 'index');
$router->get('/tables/create', 'TableController', 'form');
$router->post('/tables/save', 'TableController', 'save');
$router->get('/tables/edit/:id', 'TableController', 'form');
$router->post('/tables/save/:id', 'TableController', 'save');
$router->post('/tables/regenerate/:id', 'TableController', 'regenerateQR');
$router->post('/tables/delete/:id', 'TableController', 'delete');

// Real-time Notifications Polling
$router->get('/api/notifications/new-orders', 'NotificationController', 'checkNewOrders');
        
// Protected Routes - Reports (Modul F)
$router->get('/laporan/penjualan-harian', 'ReportController', 'harian');
$router->get('/laporan/stok', 'ReportController', 'stok');
$router->get('/laporan/kasir-shift', 'ReportController', 'kasir_shift');

// Protected Routes - Reports
$router->get('/reports', 'ReportController', 'harian');
$router->get('/reports/harian', 'ReportController', 'harian');
$router->get('/reports/stok', 'ReportController', 'stok');
$router->get('/reports/kasir-shift', 'ReportController', 'kasir_shift');

// Protected Routes - Users (Modul G)
$router->get('/users', 'UserController', 'index');
$router->get('/users/create', 'UserController', 'create');
$router->post('/users/save', 'UserController', 'save');
$router->get('/users/edit/:id', 'UserController', 'edit');
$router->post('/users/save/:id', 'UserController', 'save');
$router->post('/users/delete/:id', 'UserController', 'delete');
$router->post('/users/reset-password/:id', 'UserController', 'resetPassword');
$router->get('/activity-logs', 'UserController', 'activityLogs');

return $router;

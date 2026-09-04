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

// Protected Routes - Dashboard
$router->get('/', 'HomeController', 'index');

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

return $router;

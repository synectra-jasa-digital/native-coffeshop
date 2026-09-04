<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\StockMovement;
use App\Models\StockOpname;

class InventoryController extends Controller {
    private $ingredientModel;
    private $productModel;
    private $recipeModel;
    private $stockMovementModel;
    private $stockOpnameModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        // Restrict to Admin, Manager, and Barista for inventory
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Barista'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke halaman inventory.');
            $this->redirect('');
        }

        $this->ingredientModel = new Ingredient();
        $this->productModel = new Product();
        $this->recipeModel = new Recipe();
        $this->stockMovementModel = new StockMovement();
        $this->stockOpnameModel = new StockOpname();
    }

    /**
     * Show all ingredients
     */
    public function ingredients() {
        $ingredients = $this->ingredientModel->getAll();
        
        $this->view('pages/inventory/ingredients', [
            'title' => 'Bahan Baku',
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Show form to add/edit ingredient
     */
    public function formIngredient($id = null) {
        $ingredient = null;
        if ($id) {
            $ingredient = $this->ingredientModel->getById($id);
            if (!$ingredient) {
                Session::setFlash('error', 'Bahan baku tidak ditemukan.');
                $this->redirect('inventory/ingredients');
            }
        }

        $this->view('pages/inventory/ingredient_form', [
            'title' => $id ? 'Edit Bahan Baku' : 'Tambah Bahan Baku',
            'ingredient' => $ingredient
        ]);
    }

    /**
     * Save ingredient (Form POST)
     */
    public function saveIngredient($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('inventory/ingredients');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'unit' => trim($_POST['unit'] ?? ''),
            'min_stock' => (float)($_POST['min_stock'] ?? 0)
        ];

        if (empty($data['name']) || empty($data['unit'])) {
            Session::setFlash('error', 'Nama dan Satuan wajib diisi.');
            $this->redirect($id ? "inventory/ingredients/edit/$id" : "inventory/ingredients/create");
        }

        if ($id) {
            $this->ingredientModel->update($id, $data);
            Session::setFlash('success', 'Bahan baku berhasil diperbarui.');
        } else {
            $data['current_stock'] = (float)($_POST['current_stock'] ?? 0);
            $this->ingredientModel->create($data);
            Session::setFlash('success', 'Bahan baku berhasil ditambahkan.');
        }

        $this->redirect('inventory/ingredients');
    }

    /**
     * Delete ingredient (AJAX)
     */
    public function deleteIngredient($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check role: only admin/manager can delete
            $role = Session::get('user_role_name');
            if (!in_array($role, ['Admin', 'Manager'])) {
                 return $this->json(['success' => false, 'message' => 'Hanya Admin/Manager yang dapat menghapus data.'], 403);
            }

            if ($this->ingredientModel->delete($id)) {
                return $this->json(['success' => true, 'message' => 'Bahan baku berhasil dihapus.']);
            } else {
                return $this->json(['success' => false, 'message' => 'Gagal menghapus: bahan baku sedang digunakan pada resep atau histori stok.'], 400);
            }
        }
        return $this->json(['success' => false, 'message' => 'Invalid request method'], 400);
    }

    /**
     * Show recipes page (list of products to manage recipes)
     */
    public function recipes() {
        $products = $this->productModel->getAll();
        
        // Format products to include variants if any
        $formattedProducts = [];
        foreach ($products as $product) {
            $variants = $this->productModel->getVariants($product['id']);
            $product['has_variants'] = !empty($variants);
            $product['variants'] = $variants;
            $formattedProducts[] = $product;
        }

        $this->view('pages/inventory/recipes', [
            'title' => 'Manajemen Resep',
            'products' => $formattedProducts
        ]);
    }

    /**
     * Manage recipe for a specific product or variant
     */
    public function manageRecipe($productId, $variantId = null) {
        $product = $this->productModel->getById($productId);
        if (!$product) {
            $this->redirect('inventory/recipes');
        }

        $variant = null;
        if ($variantId) {
            $variants = $this->productModel->getVariants($productId);
            foreach ($variants as $v) {
                if ($v['id'] == $variantId) {
                    $variant = $v;
                    break;
                }
            }
            if (!$variant) {
                $this->redirect("inventory/recipes/$productId");
            }
        }

        $recipeItems = $this->recipeModel->getRecipeByProduct($productId, $variantId);
        $ingredients = $this->ingredientModel->getAll();

        $this->view('pages/inventory/recipe_form', [
            'title' => 'Kelola Resep: ' . htmlspecialchars($product['name']) . ($variant ? ' - ' . htmlspecialchars($variant['name']) : ''),
            'product' => $product,
            'variant' => $variant,
            'recipeItems' => $recipeItems,
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Save recipe item (AJAX)
     */
    public function saveRecipeItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 400);
        }

        $data = [
            'product_id' => (int)$_POST['product_id'],
            'variant_id' => !empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null,
            'ingredient_id' => (int)$_POST['ingredient_id'],
            'quantity' => (float)$_POST['quantity']
        ];

        if (empty($data['product_id']) || empty($data['ingredient_id']) || $data['quantity'] <= 0) {
            return $this->json(['success' => false, 'message' => 'Pilih bahan baku dan masukkan takaran (harus lebih dari 0).'], 400);
        }

        if ($this->recipeModel->saveItem($data)) {
            return $this->json(['success' => true, 'message' => 'Bahan resep berhasil disimpan.']);
        } else {
            return $this->json(['success' => false, 'message' => 'Gagal menyimpan bahan resep.'], 500);
        }
    }

    /**
     * Delete recipe item (AJAX)
     */
    public function deleteRecipeItem($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->recipeModel->deleteItem($id)) {
                return $this->json(['success' => true, 'message' => 'Bahan resep berhasil dihapus.']);
            } else {
                return $this->json(['success' => false, 'message' => 'Gagal menghapus bahan resep.'], 500);
            }
        }
        return $this->json(['success' => false, 'message' => 'Invalid request method'], 400);
    }

    /**
     * Show Stock Movements
     */
    public function movements() {
        $movements = $this->stockMovementModel->getRecent(100);

        $this->view('pages/inventory/movements', [
            'title' => 'Pergerakan Stok',
            'movements' => $movements
        ]);
    }

    /**
     * Show form to record manual movement
     */
    public function formMovement() {
        $ingredients = $this->ingredientModel->getAll();

        $this->view('pages/inventory/movement_form', [
            'title' => 'Catat Pergerakan Stok',
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Record a manual stock movement (Form POST)
     */
    public function recordMovement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('inventory/movements');
        }

        $data = [
            'ingredient_id' => (int)$_POST['ingredient_id'],
            'user_id' => Session::get('user_id'),
            'type' => $_POST['type'], // 'in' or 'out' or 'adjustment'
            'quantity' => (float)$_POST['quantity'],
            'notes' => trim($_POST['notes'] ?? '')
        ];

        if (empty($data['ingredient_id']) || $data['quantity'] <= 0 || !in_array($data['type'], ['in', 'out', 'adjustment'])) {
            Session::setFlash('error', 'Data tidak valid. Pastikan bahan baku dipilih dan jumlah > 0.');
            $this->redirect('inventory/movements/create');
        }

        if ($this->stockMovementModel->recordMovement($data)) {
            Session::setFlash('success', 'Pergerakan stok berhasil dicatat.');
            $this->redirect('inventory/movements');
        } else {
            Session::setFlash('error', 'Gagal mencatat pergerakan stok.');
            $this->redirect('inventory/movements/create');
        }
    }

    /**
     * Show Stock Opnames
     */
    public function opname() {
        $opnames = $this->stockOpnameModel->getAll();

        $this->view('pages/inventory/opname', [
            'title' => 'Stock Opname',
            'opnames' => $opnames
        ]);
    }

    /**
     * Show form to submit new opname
     */
    public function formOpname() {
        $ingredients = $this->ingredientModel->getAll();

        $this->view('pages/inventory/opname_form', [
            'title' => 'Buat Laporan Opname',
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Submit new Stock Opname (Form POST)
     */
    public function submitOpname() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('inventory/opname');
        }

        $ingredientId = (int)($_POST['ingredient_id'] ?? 0);
        $actualQty = isset($_POST['actual_qty']) ? (float)$_POST['actual_qty'] : -1;

        if (empty($ingredientId) || $actualQty < 0) {
            Session::setFlash('error', 'Data tidak valid. Pastikan bahan baku dipilih dan jumlah aktual >= 0.');
            $this->redirect('inventory/opname/create');
        }

        $ingredient = $this->ingredientModel->getById($ingredientId);
        if (!$ingredient) {
            Session::setFlash('error', 'Bahan baku tidak ditemukan.');
            $this->redirect('inventory/opname/create');
        }

        $expectedQty = (float)$ingredient['current_stock'];
        $difference = $actualQty - $expectedQty;

        $data = [
            'ingredient_id' => $ingredientId,
            'user_id' => Session::get('user_id'),
            'expected_qty' => $expectedQty,
            'actual_qty' => $actualQty,
            'difference' => $difference
        ];

        if ($this->stockOpnameModel->create($data)) {
            Session::setFlash('success', 'Laporan stock opname berhasil disubmit dan stok diperbarui secara otomatis.');
            $this->redirect('inventory/opname');
        } else {
            Session::setFlash('error', 'Gagal mensubmit stock opname.');
            $this->redirect('inventory/opname/create');
        }
    }

    /**
     * Approve or Reject Stock Opname (AJAX)
     */
    public function updateOpnameStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 400);
        }

        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager'])) {
            return $this->json(['success' => false, 'message' => 'Hanya Admin dan Manager yang dapat menyetujui Stock Opname.'], 403);
        }

        $status = $_POST['status'] ?? ''; // 'approved' or 'rejected'
        if (!in_array($status, ['approved', 'rejected'])) {
            return $this->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }

        if ($this->stockOpnameModel->updateStatus($id, $status)) {
            return $this->json(['success' => true, 'message' => 'Status stock opname berhasil diperbarui.']);
        } else {
            return $this->json(['success' => false, 'message' => 'Gagal memperbarui status.'], 500);
        }
    }
}

<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller {
    
    private $categoryModel;
    private $productModel;

    public function __construct() {
        // Enforce Authentication
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }
        
        // Enforce Authorization (Only Admin and Manager)
        $role = Session::get('user_role_name');
        if ($role !== 'Admin' && $role !== 'Manager') {
            Session::setFlash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            $this->redirect('');
        }

        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }

    /**
     * Show all products
     */
    public function index() {
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        $products = $this->productModel->getAll($categoryId);
        $categories = $this->categoryModel->getAll();

        $this->view('pages/products/index', [
            'title' => 'Produk & Menu',
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId
        ]);
    }

    /**
     * Show form to create/edit product
     */
    public function form($id = null) {
        $product = null;
        $variants = [];
        
        if ($id) {
            $product = $this->productModel->getById($id);
            if (!$product) {
                Session::setFlash('error', 'Produk tidak ditemukan.');
                $this->redirect('products');
            }
            $variants = $this->productModel->getVariants($id);
        }

        $categories = $this->categoryModel->getAll();

        $this->view('pages/products/form', [
            'title' => $id ? 'Edit Produk' : 'Tambah Produk Baru',
            'product' => $product,
            'variants' => $variants,
            'categories' => $categories
        ]);
    }

    /**
     * Save product (Create or Update)
     */
    public function save($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('products');
        }

        $data = [
            'category_id' => $_POST['category_id'] ?? null,
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'base_price' => str_replace(',', '', $_POST['base_price'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_out_of_stock' => isset($_POST['is_out_of_stock']) ? 1 : 0
        ];

        // Validation
        if (empty($data['name']) || empty($data['category_id']) || !is_numeric($data['base_price'])) {
            Session::setFlash('error', 'Nama produk, kategori, dan harga wajib diisi dengan benar.');
            $this->redirect($id ? "products/edit/$id" : "products/create");
        }

        if ($id) {
            // Update
            $this->productModel->update($id, $data);
            Session::setFlash('success', 'Produk berhasil diperbarui.');
        } else {
            // Create
            $id = $this->productModel->create($data);
            if ($id) {
                Session::setFlash('success', 'Produk berhasil ditambahkan.');
            } else {
                Session::setFlash('error', 'Gagal menambahkan produk.');
            }
        }

        $this->redirect('products');
    }

    /**
     * Delete product
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productModel->delete($id)) {
                Session::setFlash('success', 'Produk berhasil dihapus.');
            } else {
                Session::setFlash('error', 'Gagal menghapus produk karena masih terkait dengan data transaksi atau resep.');
            }
        }
        $this->redirect('products');
    }

    // --- CATEGORY MANAGEMENT ---

    /**
     * Show all categories
     */
    public function categories() {
        $categories = $this->categoryModel->getAll();

        $this->view('pages/products/categories', [
            'title' => 'Kategori Menu',
            'categories' => $categories
        ]);
    }

    /**
     * Show form to add/edit category
     */
    public function formCategory($id = null) {
        $category = null;
        if ($id) {
            $category = $this->categoryModel->getById($id); // Assuming getById method exists in Category model
            if (!$category) {
                Session::setFlash('error', 'Kategori tidak ditemukan.');
                $this->redirect('categories');
            }
        }

        $this->view('pages/products/category_form', [
            'title' => $id ? 'Edit Kategori' : 'Tambah Kategori',
            'category' => $category
        ]);
    }

    /**
     * Save category (Form POST)
     */
    public function saveCategory($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('categories');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if (empty($data['name'])) {
            Session::setFlash('error', 'Nama kategori wajib diisi.');
            $this->redirect($id ? "categories/edit/$id" : "categories/create");
        }

        if ($id) {
            $this->categoryModel->update($id, $data);
            Session::setFlash('success', 'Kategori berhasil diperbarui.');
        } else {
            $this->categoryModel->create($data);
            Session::setFlash('success', 'Kategori berhasil ditambahkan.');
        }

        $this->redirect('categories');
    }

    /**
     * Delete category (AJAX)
     */
    public function deleteCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->categoryModel->delete($id)) {
                return $this->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
            } else {
                return $this->json(['success' => false, 'message' => 'Kategori tidak dapat dihapus karena masih memiliki produk.'], 400);
            }
        }
        return $this->json(['success' => false, 'message' => 'Invalid request method'], 400);
    }
}
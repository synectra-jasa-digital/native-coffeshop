<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Table;
use App\Core\Session;

class TableController extends Controller {
    private $tableModel;

    public function __construct() {
        // Only Admin and Manager can manage tables
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Cashier'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            $this->redirect('');
        }
        $this->tableModel = new Table();
    }

    public function index() {
        $tables = $this->tableModel->getAll();
        
        // Generate QR URLs
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $appPath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        $fullBaseUrl = rtrim($baseUrl . $appPath, '/');

        foreach ($tables as &$table) {
            $table['menu_url'] = $fullBaseUrl . '/menu/' . $table['qr_code'];
            // Using a public API to generate QR Code image based on URL
            $table['qr_image_url'] = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($table['menu_url']);
        }
        
        $this->view('pages/tables/index', [
            'title' => 'Manajemen Meja',
            'tables' => $tables
        ]);
    }

    public function form($id = null) {
        // Cashier can only view, not edit/add
        $role = Session::get('user_role_name');
        if ($role === 'Cashier' && $id == null) {
             Session::setFlash('error', 'Anda tidak berhak menambah meja.');
             $this->redirect('tables');
        }

        $table = null;
        if ($id) {
            $table = $this->tableModel->getById($id);
            if (!$table) {
                Session::setFlash('error', 'Meja tidak ditemukan.');
                $this->redirect('tables');
            }
        }

        $this->view('pages/tables/form', [
            'title' => $id ? 'Edit Meja' : 'Tambah Meja Baru',
            'table' => $table
        ]);
    }

    public function save($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tables');
        }

        // Only Admin and Manager
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager'])) {
            Session::setFlash('error', 'Akses ditolak.');
            $this->redirect('tables');
        }

        $data = [
            'table_number' => trim($_POST['table_number'] ?? ''),
            'status' => $_POST['status'] ?? 'empty'
        ];

        if (empty($data['table_number'])) {
            Session::setFlash('error', 'Nomor meja wajib diisi.');
            $this->redirect($id ? 'tables/edit/' . $id : 'tables/create');
            return;
        }

        // Check uniqueness
        $existing = $this->tableModel->getByNumber($data['table_number']);
        if ($existing && $existing['id'] != $id) {
            Session::setFlash('error', 'Nomor meja sudah terdaftar.');
            $this->redirect($id ? 'tables/edit/' . $id : 'tables/create');
            return;
        }

        if ($id) {
            $this->tableModel->update($id, $data);
            Session::setFlash('success', 'Meja berhasil diperbarui.');
        } else {
            $this->tableModel->create($data);
            Session::setFlash('success', 'Meja baru berhasil ditambahkan.');
        }

        $this->redirect('tables');
    }

    public function regenerateQR($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tables');
        }

        $this->tableModel->regenerateQR($id);
        Session::setFlash('success', 'QR Code berhasil diperbarui. QR yang lama tidak akan bisa digunakan lagi.');
        $this->redirect('tables');
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tables');
        }

        // Only Admin
        $role = Session::get('user_role_name');
        if ($role !== 'Admin') {
            Session::setFlash('error', 'Hanya Admin yang dapat menghapus meja.');
            $this->redirect('tables');
            return;
        }

        if ($this->tableModel->delete($id)) {
            Session::setFlash('success', 'Meja berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Gagal menghapus meja. Pastikan status meja kosong (empty).');
        }

        $this->redirect('tables');
    }
}

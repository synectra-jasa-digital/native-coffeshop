<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class UserController extends Controller {
    private $userModel;
    private $activityLogModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        // Hanya Admin yang bisa akses manajemen pengguna
        $role = Session::get('user_role_name');
        if ($role !== 'Admin') {
            Session::setFlash('error', 'Hanya Admin yang dapat mengakses manajemen pengguna.');
            $this->redirect('');
        }

        $this->userModel = new User();
        $this->activityLogModel = new ActivityLog();
    }

    /**
     * Daftar Pengguna
     */
    public function index() {
        $roleId = $_GET['role'] ?? null;
        $users = $this->userModel->getAll($roleId);
        $roles = $this->userModel->getRoles();

        $this->view('pages/users/index', [
            'title' => 'Manajemen Pengguna',
            'users' => $users,
            'roles' => $roles,
            'selectedRole' => $roleId
        ]);
    }

    /**
     * Form Tambah/Edit Pengguna
     */
    public function create() {
        $roles = $this->userModel->getRoles();
        $this->view('pages/users/form', [
            'title' => 'Tambah Pengguna Baru',
            'user' => null,
            'roles' => $roles
        ]);
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            Session::setFlash('error', 'Pengguna tidak ditemukan.');
            $this->redirect('users');
        }

        $roles = $this->userModel->getRoles();
        $this->view('pages/users/form', [
            'title' => 'Edit Pengguna',
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Simpan Pengguna
     */
    public function save($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role_id' => (int)($_POST['role_id'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
            'password' => $_POST['password'] ?? ''
        ];

        // Validasi
        if (empty($data['name']) || empty($data['username']) || empty($data['role_id'])) {
            Session::setFlash('error', 'Nama, Username, dan Role wajib diisi.');
            $this->redirect($id ? "users/edit/$id" : "users/create");
        }

        if (!$id && empty($data['password'])) {
            Session::setFlash('error', 'Password wajib diisi untuk pengguna baru.');
            $this->redirect('users/create');
        }

        try {
            if ($id) {
                $this->userModel->update($id, $data);
                $this->activityLogModel->log(Session::get('user_id'), 'UPDATE_USER', "Memperbarui data pengguna ID $id");
                Session::setFlash('success', 'Data pengguna berhasil diperbarui.');
            } else {
                $this->userModel->create($data);
                $this->activityLogModel->log(Session::get('user_id'), 'CREATE_USER', "Membuat pengguna baru: {$data['username']}");
                Session::setFlash('success', 'Pengguna baru berhasil ditambahkan.');
            }
        } catch (\PDOException $e) {
            Session::setFlash('error', 'Username atau Email sudah digunakan.');
            $this->redirect($id ? "users/edit/$id" : "users/create");
        }

        $this->redirect('users');
    }

    /**
     * Hapus Pengguna (AJAX/POST)
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($id == Session::get('user_id')) {
                Session::setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                $this->redirect('users');
            }

            if ($this->userModel->delete($id)) {
                $this->activityLogModel->log(Session::get('user_id'), 'DELETE_USER', "Menghapus/menonaktifkan pengguna ID $id");
                Session::setFlash('success', 'Pengguna berhasil dihapus/dinonaktifkan.');
            } else {
                Session::setFlash('error', 'Gagal menghapus pengguna.');
            }
        }
        $this->redirect('users');
    }

    /**
     * Log Aktivitas (Audit Trail)
     */
    public function activityLogs() {
        $filters = [];
        if (!empty($_GET['user_id'])) $filters['user_id'] = $_GET['user_id'];
        if (!empty($_GET['action'])) $filters['action'] = $_GET['action'];
        if (!empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
        if (!empty($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];

        $logs = $this->activityLogModel->getAll(200, $filters);
        $users = $this->userModel->getAll();

        $this->view('pages/users/activity_logs', [
            'title' => 'Log Aktivitas',
            'logs' => $logs,
            'users' => $users
        ]);
    }
}
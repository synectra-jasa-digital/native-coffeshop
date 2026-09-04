<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller {
    
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Show login page
     */
    public function showLogin() {
        // If already logged in, redirect to dashboard
        if (Session::has('user_id')) {
            $this->redirect('');
        }

        $this->view('pages/auth/login', [
            'title' => 'Login',
            'isAppLayout' => false // Tell layout not to show sidebar/topbar
        ]);
    }

    /**
     * Handle login POST request
     */
    public function processLogin() {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        // Basic CSRF validation (if implemented in session)
        // For now, we proceed with credentials check
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            Session::setFlash('error', 'Username dan Password wajib diisi.');
            $this->redirect('login');
        }

        // Authenticate
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Login successful
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role_id']);
            Session::set('user_role_name', $user['role_name']);
            
            Session::setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['name']) . '!');
            
            // Redirect to dashboard
            $this->redirect('');
        } else {
            // Login failed
            Session::setFlash('error', 'Username atau Password salah, atau akun dinonaktifkan.');
            $this->redirect('login');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        Session::destroy();
        Session::init(); // Start new session to set flash message
        Session::setFlash('info', 'Anda telah berhasil keluar dari sistem.');
        $this->redirect('login');
    }
}
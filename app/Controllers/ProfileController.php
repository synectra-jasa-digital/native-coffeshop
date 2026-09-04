<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class ProfileController extends Controller {
    private $userModel;
    private $activityLogModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $this->userModel = new User();
        $this->activityLogModel = new ActivityLog();
    }

    /**
     * Tampilkan Halaman Profil
     */
    public function index() {
        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);

        if (!$user) {
            Session::setFlash('error', 'Data akun tidak ditemukan.');
            $this->redirect('');
        }

        $this->view('pages/profile/index', [
            'title' => 'Profil Saya',
            'user' => $user
        ]);
    }

    /**
     * Update Informasi Profil & Password
     */
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);

        if (!$user) {
            Session::setFlash('error', 'Akun tidak ditemukan.');
            $this->redirect('profile');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name)) {
            Session::setFlash('error', 'Nama lengkap tidak boleh kosong.');
            $this->redirect('profile');
        }

        $avatarUrl = $user['avatar_url'] ?? null;

        // Handle avatar removal
        if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] == '1') {
            if ($avatarUrl) {
                $relativePath = parse_url($avatarUrl, PHP_URL_PATH);
                $filePathPublic = dirname(__DIR__, 2) . '/public' . $relativePath;
                $filePathRoot = dirname(__DIR__, 2) . $relativePath;
                if (file_exists($filePathPublic)) @unlink($filePathPublic);
                if (file_exists($filePathRoot)) @unlink($filePathRoot);
            }
            $avatarUrl = null;
        }

        // Handle file upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileType = mime_content_type($file['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $userId . '_' . time() . '.' . strtolower($ext);

                $uploadDirPublic = dirname(__DIR__, 2) . '/public/uploads/avatars';
                $uploadDirRoot = dirname(__DIR__, 2) . '/uploads/avatars';

                if (!is_dir($uploadDirPublic)) {
                    mkdir($uploadDirPublic, 0755, true);
                }
                if (!is_dir($uploadDirRoot)) {
                    mkdir($uploadDirRoot, 0755, true);
                }

                $targetPublic = $uploadDirPublic . '/' . $filename;
                $targetRoot = $uploadDirRoot . '/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $targetPublic)) {
                    @copy($targetPublic, $targetRoot);
                    $avatarUrl = BASE_URL . '/uploads/avatars/' . $filename;
                }
            } else {
                Session::setFlash('error', 'Format gambar avatar tidak didukung. Harap gunakan JPG, PNG, WEBP, atau GIF.');
            }
        }

        try {
            // Update basic info (name, email, avatar_url)
            $this->userModel->updateProfile($userId, ['name' => $name, 'email' => $email, 'avatar_url' => $avatarUrl]);
            Session::set('user_name', $name); // update session
            Session::set('user_avatar', $avatarUrl);

            // Check if password change was requested
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    Session::setFlash('error', 'Harap masukkan password saat ini untuk mengubah password.');
                    $this->redirect('profile');
                }

                if (!$this->userModel->verifyPassword($userId, $currentPassword)) {
                    Session::setFlash('error', 'Password saat ini salah.');
                    $this->redirect('profile');
                }

                if (strlen($newPassword) < 6) {
                    Session::setFlash('error', 'Password baru minimal 6 karakter.');
                    $this->redirect('profile');
                }

                if ($newPassword !== $confirmPassword) {
                    Session::setFlash('error', 'Konfirmasi password baru tidak cocok.');
                    $this->redirect('profile');
                }

                $this->userModel->updatePassword($userId, $newPassword);
                $this->activityLogModel->log($userId, 'CHANGE_PASSWORD', 'Pengguna mengubah password akunnya');
                Session::setFlash('success', 'Profil dan password berhasil diperbarui.');
            } else {
                $this->activityLogModel->log($userId, 'UPDATE_PROFILE', 'Pengguna memperbarui data profilnya');
                Session::setFlash('success', 'Profil berhasil diperbarui.');
            }

        } catch (\PDOException $e) {
            Session::setFlash('error', 'Email yang Anda masukkan sudah digunakan oleh akun lain.');
        }

        $this->redirect('profile');
    }
}

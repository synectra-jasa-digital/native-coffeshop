<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Setting;

class SettingController extends Controller {
    private $settingModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        // Hanya Admin yang bisa mengubah pengaturan
        $role = Session::get('user_role_name');
        if ($role !== 'Admin') {
            Session::setFlash('error', 'Hanya Admin yang dapat mengubah pengaturan.');
            $this->redirect('');
        }

        $this->settingModel = new Setting();
    }

    /**
     * Tampilkan halaman pengaturan
     */
    public function index() {
        $settings = $this->settingModel->getAll();
        $taxSettings = $this->settingModel->getTaxSettings();
        $storeInfo = $this->settingModel->getStoreInfo();

        $this->view('pages/settings/index', [
            'title' => 'Pengaturan Sistem',
            'settings' => $settings,
            'taxSettings' => $taxSettings,
            'storeInfo' => $storeInfo
        ]);
    }

    /**
     * Simpan pengaturan
     */
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('settings');
        }

        $data = [
            'store_name' => trim($_POST['store_name'] ?? 'Good Coffee'),
            'store_address' => trim($_POST['store_address'] ?? ''),
            'store_phone' => trim($_POST['store_phone'] ?? ''),
            'tax_rate' => (float)($_POST['tax_rate'] ?? 0),
            'service_charge_rate' => (float)($_POST['service_charge_rate'] ?? 0),
            'is_tax_active' => isset($_POST['is_tax_active']) ? 1 : 0,
            'is_service_charge_active' => isset($_POST['is_service_charge_active']) ? 1 : 0,
            'receipt_footer' => trim($_POST['receipt_footer'] ?? ''),
        ];

        // Handle logo deletion
        if (isset($_POST['remove_logo']) && $_POST['remove_logo'] == '1') {
            $currentLogo = $this->settingModel->get('store_logo');
            if ($currentLogo) {
                $relativePath = parse_url($currentLogo, PHP_URL_PATH);
                $filePathPublic = dirname(__DIR__, 2) . '/public' . $relativePath;
                $filePathRoot = dirname(__DIR__, 2) . $relativePath;
                if (file_exists($filePathPublic)) @unlink($filePathPublic);
                if (file_exists($filePathRoot)) @unlink($filePathRoot);
            }
            $data['store_logo'] = '';
        }

        // Handle file upload for cafe logo
        if (isset($_FILES['store_logo']) && $_FILES['store_logo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['store_logo'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif'];
            $fileType = mime_content_type($file['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'cafe_logo_' . time() . '.' . strtolower($ext);
                
                $uploadDirPublic = dirname(__DIR__, 2) . '/public/uploads/logo';
                $uploadDirRoot = dirname(__DIR__, 2) . '/uploads/logo';
                
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
                    $data['store_logo'] = BASE_URL . '/uploads/logo/' . $filename;
                }
            } else {
                Session::setFlash('error', 'Format file logo tidak didukung. Harap gunakan JPG, PNG, WEBP, atau SVG.');
            }
        }

        if ($this->settingModel->updateMultiple($data)) {
            Session::setFlash('success', 'Pengaturan berhasil disimpan.');
        } else {
            Session::setFlash('error', 'Gagal menyimpan pengaturan.');
        }

        $this->redirect('settings');
    }
}
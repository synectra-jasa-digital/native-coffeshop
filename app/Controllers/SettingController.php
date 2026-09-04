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

        if ($this->settingModel->updateMultiple($data)) {
            Session::setFlash('success', 'Pengaturan berhasil disimpan.');
        } else {
            Session::setFlash('error', 'Gagal menyimpan pengaturan.');
        }

        $this->redirect('settings');
    }
}
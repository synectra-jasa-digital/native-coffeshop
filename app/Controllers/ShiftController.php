<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Shift;
use App\Models\Setting;

class ShiftController extends Controller {
    private $shiftModel;
    private $settingModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Kasir'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke modul shift.');
            $this->redirect('');
        }

        $this->shiftModel = new Shift();
        $this->settingModel = new Setting();
    }

    /**
     * Form buka shift
     */
    public function formOpen() {
        // Cek apakah sudah ada shift terbuka
        $userId = Session::get('user_id');
        $openShift = $this->shiftModel->getOpenShift($userId);

        if ($openShift) {
            Session::setFlash('warning', 'Anda sudah memiliki shift terbuka. Tutup shift terlebih dahulu.');
            $this->redirect('pos');
        }

        $this->view('pages/shift/form_open', [
            'title' => 'Buka Shift Baru'
        ]);
    }

    /**
     * Proses buka shift
     */
    public function processOpen() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('shift/open');
        }

        $userId = Session::get('user_id');
        $startingCash = (float)($_POST['starting_cash'] ?? 0);

        if ($startingCash < 0) {
            Session::setFlash('error', 'Modal kas awal tidak boleh negatif.');
            $this->redirect('shift/open');
        }

        // Cek lagi apakah sudah ada shift terbuka (race condition check)
        $openShift = $this->shiftModel->getOpenShift($userId);
        if ($openShift) {
            Session::setFlash('warning', 'Anda sudah memiliki shift terbuka.');
            $this->redirect('pos');
        }

        $shiftId = $this->shiftModel->open($userId, $startingCash);

        if ($shiftId) {
            Session::set('shift_id', $shiftId);
            Session::setFlash('success', 'Shift berhasil dibuka dengan modal awal Rp ' . number_format($startingCash, 0, ',', '.'));
            $this->redirect('pos');
        } else {
            Session::setFlash('error', 'Gagal membuka shift.');
            $this->redirect('shift/open');
        }
    }

    /**
     * Form tutup shift
     */
    public function formClose() {
        $userId = Session::get('user_id');
        $openShift = $this->shiftModel->getOpenShift($userId);

        if (!$openShift) {
            Session::setFlash('warning', 'Tidak ada shift terbuka yang perlu ditutup.');
            $this->redirect('pos');
        }

        // Get summary
        $summary = $this->shiftModel->getSummary($openShift['id']);

        $this->view('pages/shift/form_close', [
            'title' => 'Tutup Shift',
            'shift' => $openShift,
            'summary' => $summary
        ]);
    }

    /**
     * Proses tutup shift
     */
    public function processClose() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('shift/close');
        }

        $userId = Session::get('user_id');
        $openShift = $this->shiftModel->getOpenShift($userId);

        if (!$openShift) {
            Session::setFlash('error', 'Tidak ada shift terbuka.');
            $this->redirect('pos');
        }

        $endingCash = (float)($_POST['ending_cash'] ?? 0);

        if ($endingCash < 0) {
            Session::setFlash('error', 'Jumlah kas fisik tidak boleh negatif.');
            $this->redirect('shift/close');
        }

        if ($this->shiftModel->close($openShift['id'], $endingCash)) {
            Session::remove('shift_id');
            Session::setFlash('success', 'Shift berhasil ditutup. Silakan cek selisih kas.');
            $this->redirect('shift/history');
        } else {
            Session::setFlash('error', 'Gagal menutup shift.');
            $this->redirect('shift/close');
        }
    }

    /**
     * Riwayat shift
     */
    public function history() {
        $shifts = $this->shiftModel->getAll(50);

        $this->view('pages/shift/history', [
            'title' => 'Riwayat Shift',
            'shifts' => $shifts
        ]);
    }
}
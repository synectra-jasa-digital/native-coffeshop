<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Transaction;
use App\Models\Setting;

class TransactionController extends Controller {
    private $transactionModel;
    private $settingModel;

    public function __construct() {
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager', 'Kasir'])) {
            Session::setFlash('error', 'Anda tidak memiliki akses ke modul transaksi.');
            $this->redirect('');
        }

        $this->transactionModel = new Transaction();
        $this->settingModel = new Setting();
    }

    /**
     * Daftar transaksi
     */
    public function index() {
        $filters = [];

        if (!empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (!empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }
        if (!empty($_GET['payment_method'])) {
            $filters['payment_method'] = $_GET['payment_method'];
        }
        if (!empty($_GET['shift_id'])) {
            $filters['shift_id'] = $_GET['shift_id'];
        }

        $transactions = $this->transactionModel->getAll($filters, 100);

        $this->view('pages/transactions/index', [
            'title' => 'Riwayat Transaksi',
            'transactions' => $transactions
        ]);
    }

    /**
     * Detail transaksi
     */
    public function detail($id) {
        $transaction = $this->transactionModel->getById($id);

        if (!$transaction) {
            Session::setFlash('error', 'Transaksi tidak ditemukan.');
            $this->redirect('transactions');
        }

        $items = $this->transactionModel->getOrderItems($transaction['order_id']);

        $this->view('pages/transactions/detail', [
            'title' => 'Detail Transaksi #' . $transaction['id'],
            'transaction' => $transaction,
            'items' => $items
        ]);
    }

    /**
     * Void transaksi
     */
    public function void($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('transactions');
        }

        // Hanya Manager dan Admin yang bisa void
        $role = Session::get('user_role_name');
        if (!in_array($role, ['Admin', 'Manager'])) {
            return $this->json(['success' => false, 'message' => 'Hanya Manager dan Admin yang dapat void transaksi.'], 403);
        }

        $reason = trim($_POST['reason'] ?? '');

        if (empty($reason)) {
            return $this->json(['success' => false, 'message' => 'Alasan void wajib diisi.'], 400);
        }

        if ($this->transactionModel->void($id, $reason)) {
            return $this->json(['success' => true, 'message' => 'Transaksi berhasil void.']);
        } else {
            return $this->json(['success' => false, 'message' => 'Gagal void transaksi.'], 500);
        }
    }
}
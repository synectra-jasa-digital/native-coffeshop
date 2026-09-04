<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class HomeController extends Controller {
    public function index() {
        // Simple auth check
        if (!Session::has('user_id')) {
            $this->redirect('login');
        }

        $this->view('pages/home', [
            'title' => 'Dashboard',
            'userRole' => Session::get('user_role_name')
        ]);
    }
}

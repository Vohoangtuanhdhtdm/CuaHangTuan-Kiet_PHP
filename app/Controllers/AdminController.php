<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Admin;

class AdminController extends Controller {

    public function __construct() {
        Middleware::requireRole(['admin']);
    }

    public function dashboard() {
        $adminModel = new Admin();
        $stats = $adminModel->getDashboardStats();

        $this->render('pages/admin/dashboard', ['stats' => $stats]);
    }
}
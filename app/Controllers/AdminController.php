<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Admin;

class AdminController extends Controller {

    public function __construct() {
        // TẤT CẢ request đi vào AdminController đều phải qua cửa kiểm duyệt này
        Middleware::requireRole(['admin']);
    }

    public function dashboard() {
        $adminModel = new Admin();
        $stats = $adminModel->getDashboardStats();

        // Render giao diện kèm dữ liệu thống kê
        $this->render('pages/admin/dashboard', ['stats' => $stats]);
    }
}
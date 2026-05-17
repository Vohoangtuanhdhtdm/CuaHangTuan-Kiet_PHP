<?php
namespace Controllers;

use Core\Controller;

class HomeController extends Controller {
    public function index() {
        // Gửi dữ liệu xuống View nếu cần
        $this->render('pages/home');
    }

    // THÊM MỚI: Hàm render trang Về chúng tôi
    public function about() {
        $this->render('pages/about');
    }

    public function contact() {
        $this->render('pages/contact');
    }
}
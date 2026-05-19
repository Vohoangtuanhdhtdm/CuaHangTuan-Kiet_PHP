<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\User;

class AuthController extends Controller {
    
    // Endpoint xử lý đăng nhập qua AJAX
    public function loginAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->jsonResponse(false, null, "Vui lòng nhập đầy đủ thông tin.");
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->jsonResponse(false, null, "Email không tồn tại trong hệ thống!");
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->jsonResponse(false, null, "Sai mật khẩu! ");
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_slug'];
        $_SESSION['name'] = $user['name'];

        $this->jsonResponse(true, ['redirect' => '/'], "Đăng nhập thành công!");
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function registerAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->jsonResponse(false, null, "Vui lòng nhập đầy đủ thông tin.");
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $this->jsonResponse(false, null, "Email này đã được sử dụng.");
        }

        if ($userModel->create($name, $email, $password)) {
            $this->jsonResponse(true, ['redirect' => '/login'], "Đăng ký thành công! Vui lòng đăng nhập.");
        } else {
            $this->jsonResponse(false, null, "Có lỗi xảy ra khi tạo tài khoản.");
        }
    }

    public function forgotPasswordAPI() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = trim($data['email'] ?? '');

        $userModel = new User();
        if (!$userModel->findByEmail($email)) {
            $this->jsonResponse(false, null, "Không tìm thấy tài khoản với email này.");
        }

        $newPass = '123456'; 
        $userModel->updatePassword($email, $newPass);
        $this->jsonResponse(true, null, "Mật khẩu đã được reset về: 123456. Vui lòng đăng nhập lại.");
    }

    public function updateProfileAPI() {
        Middleware::requireLogin(); 

        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');

        $userModel = new User();
        if ($userModel->updateProfile($_SESSION['user_id'], $name, $phone, $address)) {
            $_SESSION['name'] = $name; 
            $this->jsonResponse(true, null, "Cập nhật hồ sơ thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi cập nhật hồ sơ.");
        }
    }

    public function loginView() {
        $this->render('pages/login'); 
    }
    public function registerView() { $this->render('pages/register'); }
    public function forgotPasswordView() { $this->render('pages/forgot-password'); }
    public function profileView() {
        Middleware::requireLogin(); // Chặn truy cập nếu chưa đăng nhập
        $userModel = new User();
        $this->render('pages/profile', [
            'userName' => $_SESSION['name'],
            'userRole' => $_SESSION['role']
        ]);
    }
}
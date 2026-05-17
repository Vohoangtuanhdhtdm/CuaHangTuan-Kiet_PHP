<?php
namespace Core;

class Middleware {
    
    // Kiểm tra xem user đã đăng nhập chưa
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            // Nếu là request AJAX, trả về 401 Unauthorized
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện chức năng này.']);
                exit;
            }
            
            // Nếu là request bình thường, đá về trang đăng nhập
            header("Location: /login");
            exit;
        }
    }

    // Kiểm tra quyền (Role) cụ thể
    public static function requireRole($allowedRoles = []) {
        self::requireLogin(); // Phải đăng nhập trước
        
        $currentRole = $_SESSION['role'] ?? '';
        
        if (!in_array($currentRole, $allowedRoles)) {
            http_response_code(403);
            die("403 Forbidden - Bạn không có quyền truy cập khu vực này.");
        }
    }
}
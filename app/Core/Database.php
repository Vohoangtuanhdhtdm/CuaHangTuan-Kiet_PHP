<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Trong thực tế, các thông số này nên được đọc từ file .env
        $host = '127.0.0.1';
        $db   = 'tuan_ecommerce'; // Tên database của bạn
        $user = 'root';
        $pass = ''; // Mật khẩu database
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        // Cấu hình PDO tối ưu cho bảo mật và debug
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Luôn trả về mảng kết hợp
            PDO::ATTR_EMULATE_PREPARES   => false,            // Ép MySQL dùng Prepared Statements thật
        ];

        try {
            $this->conn = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Ghi log lỗi vào file thay vì in ra màn hình ở môi trường Production
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    // Lấy instance kết nối PDO
    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
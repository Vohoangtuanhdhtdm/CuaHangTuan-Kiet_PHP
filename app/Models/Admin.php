<?php
namespace Models;

use Core\Database;

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getDashboardStats() {
        $stats = [];
        
        // Tổng doanh thu (Chỉ tính đơn hàng đã hoàn thành hoặc đang giao, tạm thời tính tất cả trừ cancelled)
        $sqlRevenue = "SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'";
        $stats['revenue'] = $this->db->query($sqlRevenue)->fetch()['total'] ?? 0;

        // Tổng số đơn hàng
        $stats['total_orders'] = $this->db->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'];

        // Tổng số sản phẩm
        $stats['total_products'] = $this->db->query("SELECT COUNT(*) as count FROM products")->fetch()['count'];

        // Tổng số khách hàng (role_id = 3)
        $stats['total_customers'] = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role_id = 3")->fetch()['count'];

        // Lấy 5 đơn hàng mới nhất
        $sqlRecentOrders = "SELECT o.order_code, o.total_amount, o.status, o.created_at, u.name as customer_name 
                            FROM orders o 
                            LEFT JOIN users u ON o.user_id = u.id 
                            ORDER BY o.id DESC LIMIT 5";
        $stats['recent_orders'] = $this->db->query($sqlRecentOrders)->fetchAll();

        return $stats;
    }
}
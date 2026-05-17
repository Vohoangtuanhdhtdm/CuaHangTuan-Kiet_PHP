<?php
namespace Models;

use Core\Database;

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function createOrder($userId, $cartId, $address, $paymentMethod) {
        try {
            // 1. BẮT ĐẦU TRANSACTION
            $this->db->beginTransaction();

            // 2. Lấy thông tin giỏ hàng
            $cartModel = new Cart();
            $items = $cartModel->getCartItems($cartId);

            if (empty($items)) {
                throw new \Exception("Giỏ hàng trống.");
            }

            $totalAmount = 0;
            foreach ($items as $item) {
                $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
                $totalAmount += $price * $item['quantity'];
            }

            // 3. Tạo mã đơn hàng ngẫu nhiên (VD: ORD-168932)
            $orderCode = 'ORD-' . strtoupper(substr(uniqid(), -6));

            // 4. Insert vào bảng orders
            $sqlOrder = "INSERT INTO orders (user_id, order_code, total_amount, shipping_address, payment_method) 
                         VALUES (:user_id, :order_code, :total, :address, :payment)";
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                'user_id' => $userId,
                'order_code' => $orderCode,
                'total' => $totalAmount,
                'address' => $address,
                'payment' => $paymentMethod
            ]);
            
            $orderId = $this->db->lastInsertId();

            // 5. Insert vào order_items
            $sqlItem = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price) 
                        VALUES (:order_id, :product_id, :variant_id, :qty, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            // ĐÃ SỬA: Đổi tên tham số thành :qty1 và :qty2 để tránh lỗi HY093
            $sqlStockProd = "UPDATE products SET stock = stock - :qty1 WHERE id = :id AND stock >= :qty2";
            $stmtStockProd = $this->db->prepare($sqlStockProd);

            $sqlStockVar = "UPDATE product_variants SET stock = stock - :qty1 WHERE id = :id AND stock >= :qty2";
            $stmtStockVar = $this->db->prepare($sqlStockVar);

            foreach ($items as $item) {
                $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
                $variantId = $item['variant_id'] ?? null;
                
                $stmtItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'variant_id' => $variantId,
                    'qty' => $item['quantity'],
                    'price' => $price
                ]);

                // ĐÃ SỬA: Truyền giá trị quantity cho cả qty1 và qty2
                if ($variantId) {
                    $stmtStockVar->execute([
                        'qty1' => $item['quantity'], 
                        'id' => $variantId, 
                        'qty2' => $item['quantity']
                    ]);
                    if ($stmtStockVar->rowCount() === 0) throw new \Exception("Sản phẩm {$item['name']} không đủ số lượng.");
                } else {
                    $stmtStockProd->execute([
                        'qty1' => $item['quantity'], 
                        'id' => $item['product_id'], 
                        'qty2' => $item['quantity']
                    ]);
                    if ($stmtStockProd->rowCount() === 0) throw new \Exception("Sản phẩm {$item['name']} không đủ số lượng.");
                }
            }

            // 6. Xóa giỏ hàng (Chỉ xóa cart_items để giữ session cart)
            $sqlClearCart = "DELETE FROM cart_items WHERE cart_id = :cart_id";
            $this->db->prepare($sqlClearCart)->execute(['cart_id' => $cartId]);

            // 7. CHỐT GIAO DỊCH (Tất cả thành công)
            $this->db->commit();
            return $orderCode;

        } catch (\Exception $e) {
            // NẾU CÓ LỖI -> HOÀN TÁC TOÀN BỘ
            $this->db->rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    // --- CÁC HÀM DÀNH CHO ADMIN ---

    // Lấy toàn bộ danh sách đơn hàng
    public function getAllForAdmin() {
        $sql = "SELECT o.*, u.name as customer_name, u.email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy thông tin chung của 1 đơn hàng
    public function getById($id) {
        $sql = "SELECT o.*, u.name as customer_name, u.email, u.phone 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Lấy danh sách sản phẩm bên trong 1 đơn hàng
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.thumbnail, v.size, v.color 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                LEFT JOIN product_variants v ON oi.variant_id = v.id 
                WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    // --- CÁC HÀM DÀNH CHO USER (FRONTEND) ---

    // Lấy danh sách đơn hàng của 1 khách hàng cụ thể
    public function getByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    // Lấy chi tiết 1 đơn hàng (có kiểm tra quyền sở hữu của user đó)
    public function getOrderForUser($orderId, $userId) {
        $sql = "SELECT * FROM orders WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $orderId,
            'user_id' => $userId
        ]);
        return $stmt->fetch();
    }
}
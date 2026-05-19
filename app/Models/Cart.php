<?php
namespace Models;

use Core\Database;

class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getCurrentCartId() {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        if ($userId) {
            $sql = "SELECT id FROM carts WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
        } else {
            $sql = "SELECT id FROM carts WHERE session_id = :session_id AND user_id IS NULL LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['session_id' => $sessionId]);
        }

        $cart = $stmt->fetch();
        if ($cart) return $cart['id'];

        $sqlInsert = "INSERT INTO carts (user_id, session_id) VALUES (:user_id, :session_id)";
        $stmtInsert = $this->db->prepare($sqlInsert);
        $stmtInsert->execute([
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);

        return $this->db->lastInsertId();
    }

    // Lấy chi tiết các sản phẩm đang có trong giỏ
    public function getCartItems($cartId) {
        $sql = "SELECT ci.id as cart_item_id, ci.quantity, ci.variant_id,
                       p.id as product_id, p.name, p.slug, p.thumbnail, p.price, p.sale_price,
                       v.size, v.color
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                LEFT JOIN product_variants v ON ci.variant_id = v.id
                WHERE ci.cart_id = :cart_id
                ORDER BY ci.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetchAll();
    }
    // Thêm sản phẩm vào giỏ
    public function addItem($cartId, $productId, $variantId, $quantity) {
        if ($variantId === '') {
            $variantId = null;
        }

        $sqlCheck = "SELECT id, quantity FROM cart_items 
                     WHERE cart_id = :cart_id AND product_id = :product_id AND (variant_id = :variant_id OR (variant_id IS NULL AND :variant_id2 IS NULL))";
        
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'variant_id2' => $variantId 
        ]);
        
        $existingItem = $stmtCheck->fetch();

        if ($existingItem) {
            $newQty = $existingItem['quantity'] + $quantity;
            $sqlUpdate = "UPDATE cart_items SET quantity = :qty WHERE id = :id";
            return $this->db->prepare($sqlUpdate)->execute(['qty' => $newQty, 'id' => $existingItem['id']]);
        } else {
            $sqlInsert = "INSERT INTO cart_items (cart_id, product_id, variant_id, quantity) 
                          VALUES (:cart_id, :product_id, :variant_id, :qty)";
            return $this->db->prepare($sqlInsert)->execute([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'qty' => $quantity
            ]);
        }
    }

    // Đếm tổng số item trong giỏ (để hiển thị lên Header Badge)
    public function getCartCount($cartId) {
        $sql = "SELECT SUM(quantity) as total FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cart_id' => $cartId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Cập nhật Số lượng hoặc Biến thể (Size/Màu)
    public function updateItem($cartItemId, $cartId, $quantity, $variantId) {
        try {
            $sql = "UPDATE cart_items 
                    SET quantity = :quantity, variant_id = :variant_id 
                    WHERE id = :id AND cart_id = :cart_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'id' => $cartItemId,
                'cart_id' => $cartId
            ]);
        } catch (\PDOException $e) {
            return false; 
        }
    }

    // Xóa sản phẩm khỏi giỏ
    public function removeItem($cartItemId, $cartId) {
        $sql = "DELETE FROM cart_items WHERE id = :id AND cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $cartItemId,
            'cart_id' => $cartId
        ]);
    }
}
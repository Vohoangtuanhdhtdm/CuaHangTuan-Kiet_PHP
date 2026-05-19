<?php
namespace Controllers;

use Core\Controller;
use Models\Cart;
use Models\Product;

class CartController extends Controller {

    // Endpoint: POST /api/cart/add
    public function addAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $productId = $data['product_id'] ?? null;
        $variantId = $data['variant_id'] ?? null; 
        $quantity = $data['quantity'] ?? 1;

        if ($variantId === '') {
            $variantId = null;
        }

        if (!$productId || !is_numeric($quantity) || $quantity < 1) {
            $this->jsonResponse(false, null, "Dữ liệu không hợp lệ.");
        }

        $cartModel = new Cart();
        
        $cartId = $cartModel->getCurrentCartId();

        $success = $cartModel->addItem($cartId, $productId, $variantId, $quantity);

        if ($success) {
            $totalCount = $cartModel->getCartCount($cartId);
            $this->jsonResponse(true, ['cart_count' => $totalCount], "Đã thêm vào giỏ hàng!");
        } else {
            $this->jsonResponse(false, null, "Lỗi hệ thống, không thể thêm vào giỏ.");
        }
    }

    // Trang hiển thị giỏ hàng
    public function viewCart() {
        $cartModel = new Cart();
        $productModel = new Product(); 
        
        $cartId = $cartModel->getCurrentCartId();
        $items = $cartModel->getCartItems($cartId);
        $totalAmount = 0;

        foreach ($items as $key => $item) {
            $currentPrice = $item['sale_price'] ? $item['sale_price'] : $item['price'];
            $totalAmount += $currentPrice * $item['quantity'];
            $items[$key]['available_variants'] = $productModel->getProductVariants($item['product_id']);
        }

        $this->render('pages/cart', [
            'items' => $items,
            'totalAmount' => $totalAmount
        ]);
    }

    public function updateAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $cartItemId = $data['cart_item_id'] ?? null;
        $quantity = max(1, intval($data['quantity'] ?? 1)); 
        $variantId = $data['variant_id'] ?? null;

        $cartModel = new Cart();
        $cartId = $cartModel->getCurrentCartId();

        if ($cartModel->updateItem($cartItemId, $cartId, $quantity, $variantId)) {
            $cartCount = $cartModel->getCartCount($cartId);
            $this->jsonResponse(true, ['cart_count' => $cartCount], "Cập nhật thành công!");
        } else {
            $this->jsonResponse(false, null, "Sản phẩm phân loại này đã tồn tại trong giỏ, vui lòng gộp số lượng.");
        }
    }

    // THÊM MỚI: API Xóa item
    public function removeAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $cartItemId = $data['cart_item_id'] ?? null;

        $cartModel = new Cart();
        $cartId = $cartModel->getCurrentCartId();

        if ($cartModel->removeItem($cartItemId, $cartId)) {
            $cartCount = $cartModel->getCartCount($cartId);
            $this->jsonResponse(true, ['cart_count' => $cartCount], "Đã xóa sản phẩm khỏi giỏ.");
        } else {
            $this->jsonResponse(false, null, "Lỗi khi xóa sản phẩm.");
        }
    }
}
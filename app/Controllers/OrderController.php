<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Cart;
use Models\Order;
use Models\User;

class OrderController extends Controller {

    // Hiển thị giao diện thanh toán
    public function checkoutView() {
        Middleware::requireLogin(); 

        $cartModel = new Cart();
        $cartId = $cartModel->getCurrentCartId();
        $items = $cartModel->getCartItems($cartId);

        if (empty($items)) {
            header("Location: /cart");
            exit;
        }
        $userModel = new User();
        $user = $userModel->findByEmail($_SESSION['email'] ?? ''); 

        $this->render('pages/checkout', [
            'items' => $items,
            'user' => $user
        ]);
    }

    // Xử lý luồng đặt hàng AJAX
    public function processAPI() {
        Middleware::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $address = trim($data['shipping_address'] ?? '');
        $paymentMethod = $data['payment_method'] ?? 'COD';

        if (empty($address)) {
            $this->jsonResponse(false, null, "Vui lòng nhập địa chỉ giao hàng.");
        }

        $cartModel = new Cart();
        $cartId = $cartModel->getCurrentCartId();
        
        $orderModel = new Order();
        $result = $orderModel->createOrder($_SESSION['user_id'], $cartId, $address, $paymentMethod);

        if (is_array($result) && isset($result['error'])) {
            $this->jsonResponse(false, null, $result['error']); 
        } else {
            $this->jsonResponse(true, ['order_code' => $result], "Đặt hàng thành công!");
        }
    }

    // API để Frontend liên tục kiểm tra trạng thái thanh toán
    public function checkPaymentStatusAPI() {
        $orderCode = $_GET['order_code'] ?? '';
        if (empty($orderCode)) $this->jsonResponse(false, null, "Thiếu mã đơn hàng");

        $orderModel = new \Models\Order();
        $order = $orderModel->getByOrderCode($orderCode);

        if ($order) {
            $this->jsonResponse(true, ['payment_status' => $order['payment_status']], "Thành công");
        } else {
            $this->jsonResponse(false, null, "Không tìm thấy đơn hàng");
        }
    }

    // Hiển thị trang thành công
    public function successView($orderCode) {
       $orderModel = new Order();
        $order = $orderModel->getByOrderCode($orderCode);

        if (!$order) {
            header("Location: /");
            exit;
        }

        $this->render('pages/checkout-success', ['order' => $order]);
    }

    // Hiển thị danh sách lịch sử đơn hàng của User
    public function historyView() {
        Middleware::requireLogin(); 
        
        $orderModel = new Order();
        $orders = $orderModel->getByUserId($_SESSION['user_id']);
        
        $this->render('pages/profile/orders', [
            'orders' => $orders
        ]);
    }

    // Hiển thị chi tiết 1 đơn hàng của User
    public function historyDetailView($id) {
        Middleware::requireLogin();
        
        $orderModel = new Order();
        $order = $orderModel->getOrderForUser($id, $_SESSION['user_id']);
        
        if (!$order) {
            header("Location: /profile/orders");
            exit;
        }

        $items = $orderModel->getOrderItems($id);
        
        $this->render('pages/profile/order-detail', [
            'order' => $order,
            'items' => $items
        ]);
    }
}
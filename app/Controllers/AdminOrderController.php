<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Order;

class AdminOrderController extends Controller {

    public function __construct() {
        Middleware::requireRole(['admin']);
    }

    // Hiển thị danh sách đơn hàng
    public function index() {
        $orderModel = new Order();
        $orders = $orderModel->getAllForAdmin();
        $this->render('pages/admin/orders/index', ['orders' => $orders]);
    }

    // Hiển thị chi tiết 1 đơn hàng
    public function detailView($id) {
        $orderModel = new Order();
        $order = $orderModel->getById($id);

        if (!$order) {
            header("Location: /admin/orders");
            exit;
        }

        $items = $orderModel->getOrderItems($id);
        $this->render('pages/admin/orders/detail', [
            'order' => $order,
            'items' => $items
        ]);
    }

    // AJAX API: Cập nhật trạng thái
    public function updateStatusAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        $validStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        if (!$id || !in_array($status, $validStatuses)) {
            $this->jsonResponse(false, null, "Dữ liệu không hợp lệ.");
        }

        $orderModel = new Order();
        if ($orderModel->updateStatus($id, $status)) {
            $this->jsonResponse(true, null, "Cập nhật trạng thái thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi hệ thống khi cập nhật.");
        }
    }
}
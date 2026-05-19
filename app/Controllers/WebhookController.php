<?php
namespace Controllers;

use Core\Controller;
use Models\Order;

class WebhookController extends Controller {

    public function sepayHandler() {
        $payload = file_get_contents('php://input');
        file_put_contents('webhook_log.txt', "\n--- " . date('Y-m-d H:i:s') . " ---\nPAYLOAD: " . $payload . "\n", FILE_APPEND);

        $data = json_decode($payload, true);
        if (!$data) return;

        $amountIn = $data['transferAmount'] ?? 0; 
        $content = strtoupper($data['content'] ?? '');
        file_put_contents('webhook_log.txt', "Parsed -> Amount: $amountIn | Content: $content\n", FILE_APPEND);
        preg_match('/ORD-?[A-Z0-9]{6}/', $content, $matches);
        
        if (empty($matches)) {
            file_put_contents('webhook_log.txt', "LỖI: Không tìm thấy mã đơn hàng trong nội dung CK.\n", FILE_APPEND);
            return;
        }

        $rawCode = $matches[0]; 
        
        $cleanCode = str_replace('-', '', $rawCode); 
        $orderCode = 'ORD-' . substr($cleanCode, 3);
        
        file_put_contents('webhook_log.txt', "Mã sau khi chuẩn hóa: $orderCode\n", FILE_APPEND);

        $orderModel = new \Models\Order();
        $order = $orderModel->getByOrderCode($orderCode);

        if ($order) {
            file_put_contents('webhook_log.txt', "TÌM THẤY ĐƠN: ID={$order['id']}, Status hiện tại={$order['status']}, Cần thanh toán={$order['total_amount']}\n", FILE_APPEND);
            
            if (strtolower($order['status']) === 'pending') {
                if ((float)$amountIn >= (float)$order['total_amount']) {
                    $orderModel->updatePaymentStatus($order['id'], 'PAID');
                    
                    $orderModel->updateStatus($order['id'], 'processing'); 
                    
                    file_put_contents('webhook_log.txt', "THÀNH CÔNG: Đã cập nhật đơn hàng thành PAID!\n", FILE_APPEND);
                } else {
                    file_put_contents('webhook_log.txt', "LỖI: Khách chuyển thiếu tiền ($amountIn < {$order['total_amount']})\n", FILE_APPEND);
                }
            } else {
                file_put_contents('webhook_log.txt', "LỖI: Trạng thái đơn hàng không phải là pending.\n", FILE_APPEND);
            }
        } else {
            file_put_contents('webhook_log.txt', "LỖI: Mã $orderCode không tồn tại trong Database.\n", FILE_APPEND);
        }
        
        echo json_encode(['success' => true]);
    }
}
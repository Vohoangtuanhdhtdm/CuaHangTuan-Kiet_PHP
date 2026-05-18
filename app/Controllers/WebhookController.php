<?php
namespace Controllers;

use Core\Controller;
use Models\Order;

class WebhookController extends Controller {

    public function sepayHandler() {
        $payload = file_get_contents('php://input');
        
        // 1. GHI LOG ĐỂ DEBUG 
        file_put_contents('webhook_log.txt', "\n--- " . date('Y-m-d H:i:s') . " ---\nPAYLOAD: " . $payload . "\n", FILE_APPEND);

        $data = json_decode($payload, true);
        if (!$data) return;

        $amountIn = $data['transferAmount'] ?? 0; 
        $content = strtoupper($data['content'] ?? '');

        // Log biến đã lấy được
        file_put_contents('webhook_log.txt', "Parsed -> Amount: $amountIn | Content: $content\n", FILE_APPEND);

        // 2. Dò tìm mã đơn hàng (Dùng ? để cho phép có hoặc không có dấu gạch ngang)
        preg_match('/ORD-?[A-Z0-9]{6}/', $content, $matches);
        
        if (empty($matches)) {
            file_put_contents('webhook_log.txt', "LỖI: Không tìm thấy mã đơn hàng trong nội dung CK.\n", FILE_APPEND);
            return;
        }

        // --- BẮT ĐẦU ĐOẠN CHUẨN HÓA MÃ ĐƠN HÀNG ---
        $rawCode = $matches[0]; // Có thể là "ORDE91EF7" hoặc "ORD-E91EF7"
        
        // Cắt bỏ hoàn toàn dấu trừ (nếu có) để đưa về dạng thuần, sau đó ghép lại cho chuẩn
        $cleanCode = str_replace('-', '', $rawCode); 
        $orderCode = 'ORD-' . substr($cleanCode, 3); // Kết quả đảm bảo luôn luôn là: ORD-XXXXXX
        
        file_put_contents('webhook_log.txt', "Mã sau khi chuẩn hóa: $orderCode\n", FILE_APPEND);
        // --- KẾT THÚC ĐOẠN CHUẨN HÓA ---

        $orderModel = new \Models\Order();
        $order = $orderModel->getByOrderCode($orderCode);

        if ($order) {
            file_put_contents('webhook_log.txt', "TÌM THẤY ĐƠN: ID={$order['id']}, Status hiện tại={$order['status']}, Cần thanh toán={$order['total_amount']}\n", FILE_APPEND);
            
            // 3. So sánh KHÔNG phân biệt chữ hoa/thường
            if (strtolower($order['status']) === 'pending') {
                if ((float)$amountIn >= (float)$order['total_amount']) {
                    
                    // Cập nhật trạng thái thanh toán
                    $orderModel->updatePaymentStatus($order['id'], 'PAID');
                    
                    // Cập nhật trạng thái đơn thành processing khi đã thanh toán
                    $orderModel->updateStatus($order['id'], 'processing'); 
                    
                    file_put_contents('webhook_log.txt', "THÀNH CÔNG: Đã cập nhật đơn hàng thành PAID!\n", FILE_APPEND);
                } else {
                    // Thêm log chi tiết nếu khách chuyển thiếu tiền
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
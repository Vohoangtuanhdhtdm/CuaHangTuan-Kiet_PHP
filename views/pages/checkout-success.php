<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full bg-white p-10 md:p-16 border border-gray-100 shadow-sm text-center">
            
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h1 class="text-3xl font-black uppercase tracking-tighter mb-4">Đặt Hàng Thành Công</h1>
            <p class="text-gray-500 mb-10 text-lg">Mã đơn hàng của bạn: <strong class="text-black"><?= htmlspecialchars($order['order_code']) ?></strong></p>

            <?php if (strtoupper($order['payment_method'] ?? '') === 'VIETQR'): ?>
                
                <?php
                // --- ĐIỀN TÀI KHOẢN CỦA BẠN VÀO ĐÂY ĐỂ TEST ---
                $bankId = 'MB'; // Tên viết tắt ngân hàng
                $accountNo = '0000251714550'; // Số tài khoản
                $accountName = 'VO HOANG TUAN'; // Tên (Không dấu)
                
                $amount = $order['total_amount'];
                $orderInfo = 'THANH TOAN ' . $order['order_code'];
                
                $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($orderInfo) . "&accountName=" . urlencode($accountName);
                ?>

                <div id="qr-container" class="bg-gray-50 border border-gray-200 p-8 max-w-sm mx-auto">
                    <h2 class="text-sm font-bold uppercase tracking-widest mb-4 text-black">Thanh toán qua VietQR</h2>
                    <p class="text-sm text-gray-500 mb-6 font-light">Mở ứng dụng ngân hàng và quét mã QR bên dưới. Số tiền và nội dung sẽ được điền tự động.</p>
                    
                    <div class="bg-white p-4 shadow-sm border mb-6 flex justify-center">
                        <img src="<?= $qrUrl ?>" alt="VietQR Payment" class="w-64 h-64 object-contain mix-blend-multiply">
                    </div>

                    <div class="text-left text-sm space-y-3 border-t border-gray-200 pt-6">
                        <div class="flex justify-between"><span class="text-gray-500">Số tiền:</span> <strong class="text-black text-base"><?= number_format($amount, 0, ',', '.') ?> đ</strong></div>
                        <div class="flex justify-between"><span class="text-gray-500">Nội dung:</span> <strong class="text-black"><?= htmlspecialchars($orderInfo) ?></strong></div>
                    </div>
                </div>

            <?php else: ?>
                <div class="bg-gray-50 p-8 max-w-sm mx-auto border border-gray-100">
                    <p class="font-bold text-black uppercase tracking-widest text-sm mb-2">Thanh toán khi nhận hàng (COD)</p>
                    <p class="text-sm text-gray-500 font-light">Cảm ơn bạn đã mua sắm. Chúng tôi sẽ sớm liên hệ để xác nhận và giao đơn hàng này.</p>
                </div>
            <?php endif; ?>

            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                <a href="/profile/orders" class="inline-block bg-white text-black border border-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">
                    Xem Đơn Hàng
                </a>
                <a href="/" class="inline-block bg-black text-white border border-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors">
                    Về Trang Chủ
                </a>
            </div>
        </div>
    </main>

    <?php if (strtoupper($order['payment_method'] ?? '') === 'VIETQR' && strtoupper($order['payment_status'] ?? 'PENDING') === 'PENDING'): ?>
    <script>
        const orderCode = '<?= htmlspecialchars($order['order_code']) ?>';
        
        // Cứ mỗi 3 giây sẽ gọi API kiểm tra 1 lần
        const checkInterval = setInterval(async () => {
            try {
                const response = await fetch(`/api/order/status?order_code=${orderCode}`);
                const result = await response.json();

                // SỬA TẠI ĐÂY: Kiểm tra result.data.payment_status trả về có phải là 'PAID' hay không
                if (result.success && result.data.payment_status.toUpperCase() === 'PAID') {
                    
                    // 1. Dừng việc gọi API lại
                    clearInterval(checkInterval);
                    
                    // 2. Hiệu ứng: Thay thế toàn bộ khối QR bằng thông báo thành công
                    const qrContainer = document.getElementById('qr-container');
                    qrContainer.innerHTML = `
                        <div class="bg-green-50 border border-green-200 p-8 rounded flex flex-col items-center justify-center animate-pulse">
                            <svg class="w-16 h-16 text-green-600 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-lg font-bold text-green-800 uppercase tracking-widest mb-2">Đã nhận được tiền!</h3>
                            <p class="text-sm text-green-600 font-medium text-center">Hệ thống đã xác nhận thanh toán. Chúng tôi đang chuẩn bị hàng cho bạn.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.log('Đang chờ thanh toán...');
            }
        }, 3000);
    </script>
    <?php endif; ?>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>
</body>
</html>
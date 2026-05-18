<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Đơn hàng <?= $order['order_code'] ?> - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow max-w-4xl mx-auto py-12 px-4 w-full">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <a href="/profile/orders" class="text-blue-600 hover:underline text-sm mb-2 inline-block">&larr; Quay lại danh sách đơn hàng</a>
                <h1 class="text-3xl font-extrabold text-gray-900">Chi tiết đơn hàng: <?= $order['order_code'] ?></h1>
            </div>
            <div>
                <?php
                    $badges = [
                        'pending' => '<span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Chờ xử lý</span>',
                        'processing' => '<span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Đang chuẩn bị</span>',
                        'shipped' => '<span class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Đang giao hàng</span>',
                        'completed' => '<span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Hoàn thành</span>',
                        'cancelled' => '<span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Đã hủy</span>'
                    ];
                    echo $badges[$order['status']] ?? $badges['pending'];
                ?>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Thông tin nhận hàng</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <p><span class="text-gray-500 block mb-1">Ngày đặt hàng:</span> <strong><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></strong></p>
                <p><span class="text-gray-500 block mb-1">Phương thức thanh toán:</span> <strong><?= $order['payment_method'] === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản (VietQR)' ?></strong></p>
                
                <p class="md:col-span-2 flex items-center gap-2">
                    <span class="text-gray-500">Trạng thái thanh toán:</span> 
                    <?php if (strtoupper($order['payment_status'] ?? 'PENDING') === 'PAID'): ?>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded font-bold text-xs uppercase tracking-wider">Đã thanh toán</span>
                    <?php else: ?>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded font-bold text-xs uppercase tracking-wider">Chưa thanh toán</span>
                    <?php endif; ?>
                </p>

                <p class="md:col-span-2"><span class="text-gray-500 block mb-1">Địa chỉ giao hàng:</span> <strong><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></strong></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Sản phẩm đã mua</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                <?php foreach ($items as $item): ?>
                    <li class="p-6 flex items-center gap-4">
                        <img src="<?= htmlspecialchars($item['thumbnail']) ?>" class="w-20 h-20 object-cover rounded border">
                        <div class="flex-grow">
                            <a href="/product/<?= htmlspecialchars($item['slug'] ?? '') ?>" class="font-bold text-gray-900 hover:text-blue-600 text-lg"><?= htmlspecialchars($item['product_name']) ?></a>
                            <p class="text-sm text-gray-500 mt-1">Phân loại: <?= htmlspecialchars($item['size'] ?? '') ?> - <?= htmlspecialchars($item['color'] ?? '') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Số lượng: <strong><?= $item['quantity'] ?></strong></p>
                            <p class="font-extrabold text-blue-600 mt-1 text-lg"><?= number_format($item['unit_price'], 0, ',', '.') ?>đ</p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-4">
                <span class="text-gray-600 text-lg">Tổng thanh toán:</span>
                <span class="text-3xl font-extrabold text-red-600"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span>
            </div>
        </div>
    </main>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Đơn hàng <?= $order['order_code'] ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <a href="/admin/orders" class="text-blue-600 hover:underline text-sm mb-2 inline-block">&larr; Quay lại danh sách</a>
                <h1 class="text-3xl font-bold text-gray-900">Chi tiết Đơn hàng: <?= $order['order_code'] ?></h1>
            </div>
            
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
                <label class="text-sm font-medium text-gray-700">Trạng thái giao hàng:</label>
                <select id="orderStatus" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5 pl-3 pr-8">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang chuẩn bị</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Đang giao hàng</option>
                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                </select>
                <button onclick="updateStatus(<?= $order['id'] ?>)" class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-700 transition-colors">Cập nhật</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Thông tin Khách hàng</h3>
                    <p class="mb-2"><span class="text-gray-500">Tên:</span> <strong class="text-gray-900"><?= htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai') ?></strong></p>
                    <p class="mb-2"><span class="text-gray-500">Email:</span> <?= htmlspecialchars($order['email'] ?? 'N/A') ?></p>
                    <p><span class="text-gray-500">SĐT:</span> <?= htmlspecialchars($order['phone'] ?? 'N/A') ?></p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Giao hàng & Thanh toán</h3>
                    <p class="mb-2"><span class="text-gray-500">Ngày đặt:</span> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p class="mb-2 flex items-center gap-2">
                        <span class="text-gray-500">Hình thức:</span> 
                        <strong class="text-gray-900"><?= $order['payment_method'] === 'COD' ? 'Nhận hàng (COD)' : 'Chuyển khoản' ?></strong>
                    </p>

                    <p class="mb-2 flex items-center gap-2">
                        <span class="text-gray-500">Thanh toán:</span>
                        <?php if (strtoupper($order['payment_status'] ?? 'PENDING') === 'PAID'): ?>
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider">Chưa thanh toán</span>
                        <?php endif; ?>
                    </p>

                    <p class="mt-4 text-gray-500">Địa chỉ giao hàng:</p>
                    <p class="bg-gray-50 p-3 rounded mt-1 text-gray-800"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Sản phẩm đã đặt</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        <?php foreach ($items as $item): ?>
                            <li class="p-6 flex items-center gap-4">
                                <img src="<?= htmlspecialchars($item['thumbnail']) ?>" class="w-16 h-16 object-cover rounded border">
                                <div class="flex-grow">
                                    <h4 class="font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h4>
                                    <p class="text-sm text-gray-500 mt-1">Phân loại: <?= htmlspecialchars($item['size'] ?? '') ?> - <?= htmlspecialchars($item['color'] ?? '') ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600 text-sm">x<?= $item['quantity'] ?></p>
                                    <p class="font-bold text-blue-600 mt-1"><?= number_format($item['unit_price'], 0, ',', '.') ?>đ</p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-4">
                        <span class="text-gray-600">Tổng cộng:</span>
                        <span class="text-2xl font-extrabold text-red-600"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    <script>
        function showToast(message, isSuccess = true) {
            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-300 translate-y-10 opacity-0 px-4 py-3 rounded shadow-lg text-white text-sm font-medium ${isSuccess ? 'bg-green-600' : 'bg-red-600'}`;
            toast.innerText = message;
            document.getElementById('toast-container').appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-10');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function updateStatus(orderId) {
            const status = document.getElementById('orderStatus').value;
            
            try {
                const response = await fetch('/api/admin/orders/status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: orderId, status: status })
                });
                
                const result = await response.json();

                if (result.success) {
                    showToast(result.message, true);
                } else {
                    showToast(result.message, false);
                }
            } catch (error) {
                showToast('Lỗi kết nối máy chủ.', false);
            }
        }
    </script>
</body>
</html>
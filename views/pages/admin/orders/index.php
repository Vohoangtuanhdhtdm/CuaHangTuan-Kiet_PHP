<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Danh sách Đơn hàng</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                            <th class="px-6 py-4 font-semibold">Mã Đơn</th>
                            <th class="px-6 py-4 font-semibold">Khách hàng</th>
                            <th class="px-6 py-4 font-semibold">Ngày đặt</th>
                            <th class="px-6 py-4 font-semibold">Tổng tiền</th>
                            <th class="px-6 py-4 font-semibold text-center">Thanh toán</th> <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                            <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $o): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-blue-600"><?= $o['order_code'] ?></td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800"><?= htmlspecialchars($o['customer_name'] ?? 'Khách vãng lai') ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($o['email'] ?? '') ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-900"><?= number_format($o['total_amount'], 0, ',', '.') ?>đ</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <?php if (strtoupper($o['payment_status'] ?? 'PENDING') === 'PAID'): ?>
                                            <span class="bg-green-50 border border-green-200 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Đã TT</span>
                                        <?php else: ?>
                                            <span class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Chưa TT</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <?php
                                            $badges = [
                                                'pending' => '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">Chờ xử lý</span>',
                                                'processing' => '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">Đang chuẩn bị</span>',
                                                'shipped' => '<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">Đang giao</span>',
                                                'completed' => '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">Hoàn thành</span>',
                                                'cancelled' => '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">Đã hủy</span>'
                                            ];
                                            echo $badges[$o['status']] ?? $badges['pending'];
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="/admin/orders/detail/<?= $o['id'] ?>" class="text-blue-600 hover:underline text-sm font-medium">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
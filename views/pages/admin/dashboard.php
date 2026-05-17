<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tổng quan hệ thống</h1>
            <div class="flex items-center text-gray-600 bg-white px-4 py-2 rounded-lg shadow-sm">
                <span class="mr-2">Quản trị viên:</span>
                <strong class="text-gray-900"><?= htmlspecialchars($_SESSION['name']) ?></strong>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-blue-100 text-blue-600 rounded-full mr-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Doanh thu (VNĐ)</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['revenue'], 0, ',', '.') ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-green-100 text-green-600 rounded-full mr-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_orders'] ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-purple-100 text-purple-600 rounded-full mr-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sản phẩm</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_products'] ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-yellow-100 text-yellow-600 rounded-full mr-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Khách hàng</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_customers'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Đơn hàng gần đây</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-gray-500 text-sm">
                            <th class="px-6 py-4 font-medium">Mã Đơn</th>
                            <th class="px-6 py-4 font-medium">Khách hàng</th>
                            <th class="px-6 py-4 font-medium">Ngày đặt</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($stats['recent_orders'])): ?>
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào</td></tr>
                        <?php else: ?>
                            <?php foreach ($stats['recent_orders'] as $order): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-blue-600"><?= $order['order_code'] ?></td>
                                    <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai') ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $badges = [
                                                'pending' => '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">Chờ xử lý</span>',
                                                'completed' => '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">Hoàn thành</span>',
                                                'cancelled' => '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">Đã hủy</span>'
                                            ];
                                            echo $badges[$order['status']] ?? $badges['pending'];
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
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
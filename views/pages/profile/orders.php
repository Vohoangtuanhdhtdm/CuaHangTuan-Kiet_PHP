<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow max-w-5xl mx-auto py-12 px-4 w-full">
        <div class="mb-6">
            <a href="/profile" class="text-blue-600 hover:underline text-sm mb-2 inline-block">&larr; Quay lại Hồ sơ</a>
            <h1 class="text-3xl font-extrabold text-gray-900">Lịch sử đơn hàng của bạn</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                            <th class="px-6 py-4 font-semibold">Mã Đơn</th>
                            <th class="px-6 py-4 font-semibold">Ngày đặt</th>
                            <th class="px-6 py-4 font-semibold">Tổng tiền</th>
                            <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                            <th class="px-6 py-4 font-semibold text-right">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <p class="mb-4">Bạn chưa có đơn hàng nào.</p>
                                    <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Bắt đầu mua sắm</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $o): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-blue-600"><?= $o['order_code'] ?></td>
                                    <td class="px-6 py-4 text-gray-600 text-sm"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-900"><?= number_format($o['total_amount'], 0, ',', '.') ?>đ</td>
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
                                        <a href="/profile/orders/<?= $o['id'] ?>" class="inline-block px-3 py-1.5 border border-blue-600 text-blue-600 rounded text-sm font-medium hover:bg-blue-50 transition-colors">Xem</a>
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
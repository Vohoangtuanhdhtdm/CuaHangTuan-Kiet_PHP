<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản phẩm - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Danh sách Sản phẩm</h1>
            <a href="/admin/products/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Thêm sản phẩm mới
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                            <th class="px-6 py-4 font-semibold">Hình ảnh</th>
                            <th class="px-6 py-4 font-semibold">Tên sản phẩm</th>
                            <th class="px-6 py-4 font-semibold">Danh mục</th>
                            <th class="px-6 py-4 font-semibold">Giá bán</th>
                            <th class="px-6 py-4 font-semibold text-center">Tồn kho</th>
                            <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                            <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($products)): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Chưa có sản phẩm nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <img src="<?= htmlspecialchars($p['thumbnail']) ?>" alt="Thumb" class="w-12 h-12 rounded object-cover border">
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800 line-clamp-1" title="<?= htmlspecialchars($p['name']) ?>"><?= htmlspecialchars($p['name']) ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        <?= htmlspecialchars($p['category_name'] ?? 'Không có') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($p['sale_price']): ?>
                                            <p class="font-bold text-red-600"><?= number_format($p['sale_price'], 0, ',', '.') ?>đ</p>
                                            <p class="text-xs text-gray-400 line-through"><?= number_format($p['price'], 0, ',', '.') ?>đ</p>
                                        <?php else: ?>
                                            <p class="font-bold text-gray-800"><?= number_format($p['price'], 0, ',', '.') ?>đ</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none <?= $p['stock'] > 10 ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100' ?> rounded-full">
                                            <?= $p['stock'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($p['is_active']): ?>
                                            <span class="text-green-600 text-sm font-medium flex items-center justify-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Hiển thị</span>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm font-medium flex items-center justify-center"><span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>Đang ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="/admin/products/edit/<?= $p['id'] ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Sửa</a>
                                        <button onclick="deleteProduct(<?= $p['id'] ?>)" class="text-red-600 hover:text-red-800 text-sm font-medium">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        async function deleteProduct(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Dữ liệu không thể khôi phục!')) return;

            try {
                const response = await fetch('/api/admin/products/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                
                const result = await response.json();

                if (result.success) {
                    alert(result.message); // Có thể thay bằng Toast
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (error) {
                alert('Lỗi kết nối máy chủ.');
            }
        }
    </script>
</body>
</html>
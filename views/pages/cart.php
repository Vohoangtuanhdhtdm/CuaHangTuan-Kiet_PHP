<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Giỏ hàng của bạn</h1>

        <?php if (empty($items)): ?>
            <div class="bg-white p-10 text-center rounded-lg shadow border">
                <p class="text-gray-500 mb-4">Giỏ hàng của bạn đang trống.</p>
                <a href="/" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="lg:flex lg:gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-lg shadow overflow-hidden border">
                        <ul class="divide-y divide-gray-200" id="cart-item-list">
                            <?php foreach ($items as $item): ?>
                                <?php $price = $item['sale_price'] ? $item['sale_price'] : $item['price']; ?>
                                
                                <li class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6" id="cart-item-<?= $item['cart_item_id'] ?>">
                                    <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-24 h-24 object-cover rounded">
                                    
                                    <div class="flex-grow w-full">
                                        <a href="/product/<?= $item['slug'] ?>" class="text-lg font-semibold text-gray-800 hover:text-blue-600"><?= htmlspecialchars($item['name']) ?></a>
                                        <p class="text-blue-600 font-bold mt-1"><?= number_format($price, 0, ',', '.') ?>đ</p>
                                        
                                        <div class="mt-3 flex flex-wrap gap-4 items-center">
                                            <?php if (!empty($item['available_variants'])): ?>
                                                <select onchange="updateCartItem(<?= $item['cart_item_id'] ?>)" id="variant-<?= $item['cart_item_id'] ?>" class="text-sm border-gray-300 rounded-md shadow-sm py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                                                    <?php foreach ($item['available_variants'] as $v): ?>
                                                        <option value="<?= $v['id'] ?>" <?= ($item['size'] == $v['size'] && $item['color'] == $v['color']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($v['size']) ?> - <?= htmlspecialchars($v['color']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                            
                                            <div class="flex items-center border border-gray-300 rounded-md">
                                                <input type="number" id="qty-<?= $item['cart_item_id'] ?>" value="<?= $item['quantity'] ?>" min="1" onchange="updateCartItem(<?= $item['cart_item_id'] ?>)" class="w-16 text-center text-sm py-1.5 focus:ring-blue-500 focus:border-blue-500 border-none rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end gap-3 mt-4 sm:mt-0">
                                        <button onclick="removeCartItem(<?= $item['cart_item_id'] ?>)" class="text-sm text-red-500 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded transition-colors">
                                            Xóa
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="lg:w-1/3 mt-8 lg:mt-0">
                    <div class="bg-white p-6 rounded-lg shadow border sticky top-20">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Tóm tắt đơn hàng</h2>
                        <div class="flex justify-between border-b pb-4 mb-4">
                            <span class="text-gray-600">Tổng tiền sản phẩm</span>
                            <span class="font-bold text-gray-900"><?= number_format($totalAmount, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="flex justify-between mb-6">
                            <span class="text-base font-bold text-gray-900">Cần thanh toán</span>
                            <span class="text-xl font-extrabold text-red-600"><?= number_format($totalAmount, 0, ',', '.') ?>đ</span>
                        </div>
                        
                        <a href="/checkout" class="w-full block text-center bg-gray-900 text-white py-3 rounded-md font-medium hover:bg-gray-800 transition-colors">
                            Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Hàm AJAX Cập nhật (Số lượng & Biến thể)
        async function updateCartItem(cartItemId) {
            const qtyInput = document.getElementById(`qty-${cartItemId}`);
            const variantSelect = document.getElementById(`variant-${cartItemId}`);
            
            const data = {
                cart_item_id: cartItemId,
                quantity: qtyInput ? qtyInput.value : 1,
                variant_id: variantSelect ? variantSelect.value : null
            };

            try {
                const response = await fetch('/api/cart/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    // Cập nhật lại số lượng trên badge
                    const badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.textContent = result.data.cart_count;
                        badge.classList.add('scale-150', 'transition-transform');
                        setTimeout(() => badge.classList.remove('scale-150'), 300);
                    }
                    if (typeof showToast === 'function') showToast(result.message, true);
                    
                    // Nạp lại trang nhanh để tính toán lại cột Giá (Tránh làm JS tính toán phức tạp)
                    setTimeout(() => window.location.reload(), 500); 
                } else {
                    if (typeof showToast === 'function') showToast(result.message, false);
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Lỗi hệ thống', false);
            }
        }

        // Hàm AJAX Xóa sản phẩm
        async function removeCartItem(cartItemId) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

            try {
                const response = await fetch('/api/cart/remove', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart_item_id: cartItemId })
                });
                const result = await response.json();

                if (result.success) {
                    if (typeof showToast === 'function') showToast(result.message, true);
                    // Xóa phần tử HTML ra khỏi giao diện bằng animation mờ dần
                    const itemRow = document.getElementById(`cart-item-${cartItemId}`);
                    if (itemRow) {
                        itemRow.style.opacity = '0';
                        setTimeout(() => window.location.reload(), 300); // Reload để tính lại bill
                    }
                } else {
                    if (typeof showToast === 'function') showToast(result.message, false);
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Lỗi hệ thống', false);
            }
        }
    </script>
</body>
</html>
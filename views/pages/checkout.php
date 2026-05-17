<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php 
        require_once VIEW_PATH . '/partials/header.php'; 
        
        // Tính toán tổng tiền ngay tại View để hiển thị
        $totalAmount = 0;
        foreach ($items as $item) {
            $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
            $totalAmount += $price * $item['quantity'];
        }
    ?>

    <main class="max-w-7xl mx-auto py-12 px-4 w-full">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Thanh toán</h1>
        
        <div class="lg:flex lg:gap-8">
            <div class="lg:w-2/3">
                <form id="checkoutForm" class="bg-white p-6 rounded-lg shadow border space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Người nhận</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100 p-2 text-gray-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng chi tiết <span class="text-red-500">*</span></label>
                        <textarea name="shipping_address" required rows="3" placeholder="Ví dụ: Số 123, Đường Lê Lợi, Phường Bến Thành, Quận 1, TP.HCM" class="mt-1 block w-full border border-gray-300 rounded-md p-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Phương thức thanh toán</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="COD" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="BANK_TRANSFER" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-gray-900">Chuyển khoản ngân hàng</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" id="btn-checkout" class="w-full bg-blue-600 text-white py-4 rounded-md hover:bg-blue-700 font-bold text-lg shadow-sm transition-colors">
                        Xác nhận đặt hàng
                    </button>
                </form>
            </div>
            
            <div class="lg:w-1/3 mt-8 lg:mt-0">
                 <div class="bg-white p-6 rounded-lg shadow border sticky top-20">
                     <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-3">Tóm tắt đơn hàng (<?= count($items) ?> sản phẩm)</h2>
                     
                     <div class="flow-root mb-6 max-h-96 overflow-y-auto pr-2">
                         <ul class="-my-4 divide-y divide-gray-200">
                             <?php foreach ($items as $item): ?>
                                 <?php $price = $item['sale_price'] ? $item['sale_price'] : $item['price']; ?>
                                 <li class="flex items-center py-4">
                                     <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-16 w-16 rounded object-cover border border-gray-200">
                                     <div class="ml-4 flex-1">
                                         <h3 class="text-sm font-medium text-gray-900 line-clamp-2" title="<?= htmlspecialchars($item['name']) ?>">
                                             <?= htmlspecialchars($item['name']) ?>
                                         </h3>
                                         <p class="text-xs text-gray-500 mt-1">
                                             Phân loại: <?= htmlspecialchars($item['size'] ?? '') ?> - <?= htmlspecialchars($item['color'] ?? '') ?>
                                         </p>
                                         <div class="flex justify-between items-center mt-2">
                                             <span class="text-sm text-gray-600">SL: <?= $item['quantity'] ?></span>
                                             <span class="text-sm font-bold text-blue-600"><?= number_format($price * $item['quantity'], 0, ',', '.') ?>đ</span>
                                         </div>
                                     </div>
                                 </li>
                             <?php endforeach; ?>
                         </ul>
                     </div>

                     <div class="border-t border-gray-200 pt-4 space-y-3">
                         <div class="flex justify-between text-sm text-gray-600">
                             <p>Tạm tính</p>
                             <p><?= number_format($totalAmount, 0, ',', '.') ?>đ</p>
                         </div>
                         <div class="flex justify-between text-sm text-gray-600">
                             <p>Phí vận chuyển</p>
                             <p>Miễn phí</p>
                         </div>
                         <div class="flex justify-between text-lg font-extrabold text-gray-900 pt-2 border-t border-gray-100">
                             <p>Tổng cộng</p>
                             <p class="text-red-600"><?= number_format($totalAmount, 0, ',', '.') ?>đ</p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-checkout');
            btn.innerHTML = 'Đang xử lý...';
            btn.disabled = true;

            const data = Object.fromEntries(new FormData(e.target).entries());

            try {
                const response = await fetch('/api/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    window.location.href = '/checkout/success/' + result.data.order_code;
                } else {
                    if (typeof showToast === 'function') showToast(result.message, false);
                    btn.innerHTML = 'Xác nhận đặt hàng';
                    btn.disabled = false;
                }
            } catch (error) {
                if (typeof showToast === 'function') showToast('Lỗi kết nối.', false);
                btn.innerHTML = 'Xác nhận đặt hàng';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
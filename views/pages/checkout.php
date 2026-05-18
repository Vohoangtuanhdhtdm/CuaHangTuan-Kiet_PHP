<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .minimal-input { border: 1px solid #e5e7eb; padding: 1rem; width: 100%; outline: none; transition: border-color 0.3s ease; }
        .minimal-input:focus { border-color: #000; }
    </style>
</head>
<body class="bg-white text-black">
    <?php 
        require_once VIEW_PATH . '/partials/header.php'; 
        
        $totalAmount = 0;
        foreach ($items as $item) {
            $price = $item['sale_price'] ? $item['sale_price'] : $item['price'];
            $totalAmount += $price * $item['quantity'];
        }
    ?>

    <main class="max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-black uppercase tracking-tighter mb-12">Thanh Toán</h1>
        
        <div class="lg:flex lg:gap-16">
            <div class="lg:w-3/5">
                <form id="checkoutForm" class="space-y-8">
                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-3">Người Nhận</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly class="minimal-input bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-3">Địa Chỉ Giao Hàng <span class="text-red-500">*</span></label>
                        <textarea name="shipping_address" required rows="3" placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố..." class="minimal-input resize-none"></textarea>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold uppercase tracking-widest mb-4">Phương Thức Thanh Toán</label>
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-200 cursor-pointer hover:border-black transition-colors">
                                <input type="radio" name="payment_method" value="COD" checked class="h-4 w-4 text-black focus:ring-black accent-black">
                                <span class="ml-4 font-medium">Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 cursor-pointer hover:border-black transition-colors">
                                <input type="radio" name="payment_method" value="VIETQR" class="h-4 w-4 text-black focus:ring-black accent-black">
                                <span class="ml-4 font-medium">Chuyển khoản mã QR (VietQR)</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" id="btn-checkout" class="w-full bg-black text-white py-5 font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors mt-8">
                        Xác Nhận Đặt Hàng
                    </button>
                </form>
            </div>
            
            <div class="lg:w-2/5 mt-16 lg:mt-0">
                 <div class="bg-gray-50 p-8 border border-gray-100 sticky top-28">
                     <h2 class="text-sm font-bold uppercase tracking-widest mb-6 border-b border-gray-200 pb-4">Đơn Hàng (<?= count($items) ?>)</h2>
                     
                     <div class="flow-root mb-8 max-h-[400px] overflow-y-auto pr-2 no-scrollbar">
                         <ul class="-my-4 divide-y divide-gray-200">
                             <?php foreach ($items as $item): ?>
                                 <?php $price = $item['sale_price'] ? $item['sale_price'] : $item['price']; ?>
                                 <li class="flex py-6">
                                     <div class="h-24 w-20 flex-shrink-0 overflow-hidden bg-white border border-gray-200">
                                        <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-full w-full object-cover object-center">
                                     </div>
                                     <div class="ml-4 flex flex-1 flex-col">
                                         <div>
                                             <div class="flex justify-between text-base font-bold text-black mb-1">
                                                 <h3 class="line-clamp-2 pr-4"><?= htmlspecialchars($item['name']) ?></h3>
                                                 <p class="ml-4"><?= number_format($price * $item['quantity'], 0, ',', '.') ?>đ</p>
                                             </div>
                                             <p class="text-xs text-gray-500 uppercase tracking-widest">
                                                 <?= htmlspecialchars($item['size'] ?? '') ?> <?= htmlspecialchars($item['color'] ? ' / '.$item['color'] : '') ?>
                                             </p>
                                         </div>
                                         <div class="flex flex-1 items-end justify-between text-sm">
                                             <p class="text-gray-500">SL: <?= $item['quantity'] ?></p>
                                         </div>
                                     </div>
                                 </li>
                             <?php endforeach; ?>
                         </ul>
                     </div>

                     <div class="border-t border-gray-200 pt-6 space-y-4">
                         <div class="flex justify-between text-sm font-medium">
                             <p class="text-gray-600">Tạm tính</p>
                             <p><?= number_format($totalAmount, 0, ',', '.') ?>đ</p>
                         </div>
                         <div class="flex justify-between text-sm font-medium">
                             <p class="text-gray-600">Phí vận chuyển</p>
                             <p>Miễn phí</p>
                         </div>
                         <div class="flex justify-between text-xl font-black pt-4 border-t border-gray-200">
                             <p>Tổng cộng</p>
                             <p><?= number_format($totalAmount, 0, ',', '.') ?>đ</p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </main>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-checkout');
            btn.innerHTML = 'ĐANG XỬ LÝ...';
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
                    // Dựa trên URL của bạn, điều hướng sang trang thành công
                    window.location.href = '/checkout/success/' + result.data.order_code;
                } else {
                    if (typeof showToast === 'function') showToast(result.message, false);
                    else alert(result.message);
                    btn.innerHTML = 'XÁC NHẬN ĐẶT HÀNG';
                    btn.disabled = false;
                }
            } catch (error) {
                if (typeof showToast === 'function') showToast('Lỗi kết nối.', false);
                else alert('Lỗi kết nối tới máy chủ');
                btn.innerHTML = 'XÁC NHẬN ĐẶT HÀNG';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
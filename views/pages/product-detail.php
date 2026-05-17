<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <?php if (!isset($product)) { $product = ['name' => 'Product', 'thumbnail' => '', 'category_name' => '', 'price' => 0, 'sale_price' => null, 'description' => '', 'id' => 0]; } ?>
    <?php if (!isset($variants)) { $variants = []; } ?>
    <?php if (!isset($images)) { $images = []; } ?>
    <title><?= htmlspecialchars($product['name']) ?> - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 w-full">
        <div class="lg:flex lg:items-start lg:gap-x-12">
            
            <div class="lg:w-1/2">
                <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden border bg-white">
                    <img id="main-image" src="<?= htmlspecialchars($product['thumbnail']) ?>" class="w-full h-full object-center object-cover">
                </div>
                
                <?php if (!empty($images)): ?>
                <div class="mt-4 grid grid-cols-4 gap-4">
                    <button onclick="changeImage('<?= htmlspecialchars($product['thumbnail']) ?>')" class="border rounded hover:border-blue-500 overflow-hidden">
                        <img src="<?= htmlspecialchars($product['thumbnail']) ?>" class="h-20 w-full object-cover">
                    </button>
                    <?php foreach ($images as $img): ?>
                    <button onclick="changeImage('<?= htmlspecialchars($img['image_path']) ?>')" class="border rounded hover:border-blue-500 overflow-hidden">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" class="h-20 w-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="mt-10 px-4 sm:px-0 lg:mt-0 lg:w-1/2">
                <nav class="mb-4 text-sm text-gray-500">
                    <a href="/" class="hover:text-blue-600">Trang chủ</a> / 
                    <span><?= htmlspecialchars($product['category_name']) ?></span>
                </nav>
                
                <h1 class="text-3xl font-extrabold text-gray-900"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="mt-4 flex items-center gap-4">
                    <?php if ($product['sale_price']): ?>
                        <span class="text-3xl font-bold text-red-600"><?= number_format($product['sale_price'], 0, ',', '.') ?>đ</span>
                        <span class="text-xl text-gray-400 line-through"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
                    <?php else: ?>
                        <span class="text-3xl font-bold text-blue-600"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Mô tả</h3>
                    <div class="mt-2 text-base text-gray-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </div>
                </div>

                <form id="add-to-cart-form" class="mt-8">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chọn Size / Màu</label>
                            <select name="variant_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <?php if (!empty($variants)): ?>
                                    <?php foreach ($variants as $v): ?>
                                        <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['size']) ?> - <?= htmlspecialchars($v['color']) ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Sản phẩm đang cập nhật size</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số lượng</label>
                            <input type="number" name="quantity" value="1" min="1" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <button type="button" onclick="handleAddToCart()" 
                        class="mt-8 w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 transition-colors">
                        Thêm vào giỏ hàng
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function changeImage(src) {
            document.getElementById('main-image').src = src;
        }

        // Logic giỏ hàng AJAX
       async function handleAddToCart() {
            const form = document.getElementById('add-to-cart-form');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const btn = form.querySelector('button[type="button"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Đang xử lý...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    if (typeof showToast === 'function') showToast(result.message, true);
                    
                    // Cập nhật con số trên Giỏ hàng ngay lập tức
                    const badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.textContent = result.data.cart_count;
                        
                        // Hiệu ứng phình to nhẹ (Animation)
                        badge.classList.add('scale-150', 'transition-transform', 'duration-300');
                        setTimeout(() => badge.classList.remove('scale-150'), 300);
                    }
                } else {
                    if (typeof showToast === 'function') showToast(result.message, false);
                }
            } catch (error) {
                console.error(error);
                if (typeof showToast === 'function') showToast('Có lỗi xảy ra khi kết nối máy chủ.', false);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <div class="mb-8">
            <a href="/admin/products" class="text-blue-600 hover:underline text-sm mb-2 inline-block">&larr; Quay lại danh sách</a>
            <h1 class="text-3xl font-bold text-gray-900">Thêm sản phẩm mới</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
            <form id="createProductForm" class="p-8 space-y-6" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" required min="0" class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" name="sale_price" min="0" class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" required min="0" value="0" class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện (Thumbnail)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-300 rounded-md p-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả sản phẩm</label>
                    <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" id="btn-submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-md font-medium hover:bg-blue-700 transition-colors">
                        Lưu sản phẩm
                    </button>
                </div>
            </form>
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

        document.getElementById('createProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            btn.innerHTML = 'Đang lưu...';
            btn.disabled = true;

            // Dùng FormData để gói toàn bộ text và cả file upload
            const formData = new FormData(e.target);

            try {
                // Fetch API gửi dữ liệu POST
                const response = await fetch('/api/admin/products/create', {
                    method: 'POST',
                    body: formData // Không set header Content-Type, trình duyệt sẽ tự gán multipart/form-data
                });
                
                const result = await response.json();

                if (result.success) {
                    showToast(result.message, true);
                    setTimeout(() => window.location.href = result.data.redirect, 1500);
                } else {
                    showToast(result.message, false);
                    btn.innerHTML = 'Lưu sản phẩm';
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                showToast('Lỗi kết nối máy chủ.', false);
                btn.innerHTML = 'Lưu sản phẩm';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ của bạn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Hồ sơ cá nhân</h2>
        
        <div class="mb-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Vai trò: <?= htmlspecialchars($userRole ?? 'N/A') ?>
            </span>
        </div>

        <form id="profileForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Họ Tên</label>
                <input type="text" name="name" value="<?= htmlspecialchars($userName ?? '') ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($userPhone ?? '') ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                <textarea name="address" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"><?= htmlspecialchars($userAddress ?? '') ?></textarea>
            </div>
            <button type="button" onclick="updateProfile()" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Cập nhật thông tin</button>
        </form>
       
        
        <a href="/profile/orders" class="mt-3 w-full flex justify-center bg-gray-100 text-gray-700 py-2 border border-gray-300 rounded hover:bg-gray-200 transition-colors">
            Xem lịch sử mua hàng
        </a>
        <div id="profileAlert" class="mt-4 hidden p-3 rounded text-sm text-center"></div>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-blue-600 hover:underline">&larr; Quay lại trang chủ</a>
        </div>
    </div>

    <script>
        async function updateProfile() {
            const form = document.getElementById('profileForm');
            const alertBox = document.getElementById('profileAlert');
            const data = Object.fromEntries(new FormData(form).entries());

            const res = await fetch('/api/auth/profile/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            
            alertBox.textContent = result.message;
            alertBox.className = result.success ? 'mt-4 block p-3 rounded text-sm text-center bg-green-100 text-green-700' : 'mt-4 block p-3 rounded text-sm text-center bg-red-100 text-red-700';
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-lg shadow-md text-center max-w-md">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h2>
        <p class="text-gray-600 mb-6">Mã đơn hàng của bạn là: <strong class="text-blue-600"><?= $orderCode ?></strong></p>
        <a href="/" class="block w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Tiếp tục mua sắm</a>
    </div>
</body>
</html>
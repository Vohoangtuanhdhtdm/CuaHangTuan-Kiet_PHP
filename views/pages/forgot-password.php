<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Khôi phục mật khẩu</h2>
        <p class="text-sm text-gray-500 text-center mb-6">Nhập email của bạn để nhận hướng dẫn đặt lại mật khẩu.</p>
        
        <div id="forgotAlert" class="hidden mb-4 p-3 rounded text-sm text-center"></div>

        <form id="forgotForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email đã đăng ký</label>
                <input type="email" name="email" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit" id="forgotBtn"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                Gửi yêu cầu
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/login" class="text-sm font-medium text-blue-600 hover:text-blue-500">&larr; Quay lại Đăng nhập</a>
        </div>
    </div>

    <script src="/assets/js/auth.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-blue-600">TuanStore</h2>
            <p class="text-gray-500 mt-2">Tạo tài khoản mới để mua sắm ngay</p>
        </div>
        
        <div id="registerAlert" class="hidden mb-4 p-3 rounded text-sm text-center"></div>

        <form id="registerForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Họ và Tên</label>
                <input type="text" name="name" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input type="password" name="password" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Xác nhận</label>
                    <input type="password" name="confirm_password" required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <button type="submit" id="registerBtn"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                Đăng ký ngay
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Đã có tài khoản? 
                <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">Đăng nhập</a>
            </p>
        </div>
    </div>

    <script src="/assets/js/auth.js"></script>
</body>
</html>
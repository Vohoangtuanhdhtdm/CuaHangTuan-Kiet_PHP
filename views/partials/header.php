<?php
// Gọi Model Cart
$cartModel = new \Models\Cart();
$currentCartId = $cartModel->getCurrentCartId();
$cartCount = $cartModel->getCartCount($currentCartId);

// Lấy thông tin phân quyền từ Session (Dựa trên AuthController)
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userName = $isLoggedIn ? $_SESSION['name'] : '';
?>

<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <div class="flex items-center gap-4">
                <button onclick="toggleMobileMenu()" class="md:hidden text-gray-900 hover:text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" /></svg>
                </button>
                <a href="/" class="text-2xl font-black tracking-tighter text-black uppercase">TuanStore</a>
            </div>
            
            <nav class="hidden md:flex space-x-10">
                <a href="/" class="text-sm font-medium text-black relative group">
                    Trang chủ
                    <span class="absolute -bottom-1 left-0 w-full h-[2px] bg-black"></span>
                </a>
                <a href="/#shop" class="text-sm font-medium text-gray-500 hover:text-black relative group transition-colors">
                    Sản phẩm
                    <span class="absolute -bottom-1 left-0 w-0 h-[2px] bg-black transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/about" class="text-sm font-medium text-gray-500 hover:text-black relative group transition-colors">
                    Về chúng tôi
                    <span class="absolute -bottom-1 left-0 w-0 h-[2px] bg-black transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/blog" class="text-sm font-medium text-gray-500 hover:text-black relative group transition-colors">
                    Tạp chí
                    <span class="absolute -bottom-1 left-0 w-0 h-[2px] bg-black transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/contact" class="text-sm font-medium text-gray-500 hover:text-black relative group transition-colors">
                    Liên hệ
                    <span class="absolute -bottom-1 left-0 w-0 h-[2px] bg-black transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            <div class="flex items-center space-x-5 sm:space-x-6">
                <button onclick="toggleSearch()" class="text-gray-900 hover:text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </button>
                
                <div class="relative group hidden sm:block">
                    <a href="<?= $isLoggedIn ? '/profile' : '/login' ?>" class="text-gray-900 hover:text-gray-500 transition-colors pb-6 pt-6">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </a>
                    
                    <?php if ($isLoggedIn): ?>
                    <div class="absolute right-0 top-14 w-56 bg-white border border-gray-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 flex flex-col py-2 z-50">
                        <div class="px-5 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Xin chào,</p>
                            <p class="text-sm font-bold text-black truncate"><?= htmlspecialchars($userName) ?></p>
                        </div>
                        
                        <?php if ($isAdmin): ?>
                        <a href="/admin/dashboard" class="px-5 py-3 text-sm text-blue-600 font-semibold hover:bg-gray-50 flex items-center justify-between">
                            Quản trị Website
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                        <?php endif; ?>
                        
                        <a href="/profile" class="px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Tài khoản của tôi</a>
                        <a href="/orders" class="px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Đơn mua</a>
                        
                        <div class="border-t border-gray-100 mt-1">
                            <a href="/logout" class="block px-5 py-3 text-sm text-red-600 hover:bg-gray-50 transition-colors">Đăng xuất</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="/cart" class="relative text-gray-900 hover:text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    <span id="cart-badge" class="absolute -top-1.5 -right-2 bg-black text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center"><?= $cartCount ?></span>
                </a>
            </div>
        </div>
    </div>
</header>

<div id="search-modal" class="fixed inset-0 z-50 bg-white/95 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-500 flex flex-col items-center justify-center px-4">
    <button onclick="toggleSearch()" class="absolute top-8 right-8 text-black hover:text-gray-500 transition-colors p-2">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
    <div class="w-full max-w-3xl">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 text-center">Tìm kiếm sản phẩm</p>
        <form action="/" method="GET" class="relative">
            <input type="text" name="search" placeholder="Nhập từ khóa..." class="w-full text-4xl md:text-6xl font-black text-black bg-transparent border-b-2 border-black pb-4 focus:outline-none placeholder-gray-200 text-center">
        </form>
        <p class="text-sm text-gray-500 mt-6 text-center">Nhấn Enter để tìm kiếm</p>
    </div>
</div>

<div id="mobile-menu" class="fixed inset-0 z-50 bg-white transform -translate-x-full transition-transform duration-500 flex flex-col pt-8 px-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-12 border-b border-gray-100 pb-6">
        <a href="/" class="text-2xl font-black tracking-tighter text-black uppercase">TuanStore</a>
        <button onclick="toggleMobileMenu()" class="text-black hover:text-gray-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    
    <nav class="flex flex-col space-y-6 text-xl font-bold uppercase tracking-widest text-black">
        <a href="/" class="hover:text-gray-400 transition-colors">Trang chủ</a>
        <a href="/#shop" class="hover:text-gray-400 transition-colors">Sản phẩm</a>
        <a href="/about" class="hover:text-gray-400 transition-colors">Về chúng tôi</a>
        <a href="/blog" class="hover:text-gray-400 transition-colors">Tạp chí</a>
        <a href="/contact" class="hover:text-gray-400 transition-colors">Liên hệ</a>
    </nav>

    <div class="mt-auto py-10 border-t border-gray-100 flex flex-col gap-3">
        <?php if ($isLoggedIn): ?>
            <div class="text-center mb-4">
                <p class="text-xs text-gray-500">Đang đăng nhập với tư cách</p>
                <p class="text-base font-bold text-black"><?= htmlspecialchars($userName) ?></p>
            </div>
            
            <?php if ($isAdmin): ?>
                <a href="/admin/dashboard" class="bg-gray-100 text-blue-600 border border-gray-200 py-3 text-center text-sm font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Quản trị
                </a>
            <?php endif; ?>
            
            <a href="/profile" class="bg-black text-white py-3 text-center text-sm font-bold uppercase tracking-widest">Tài khoản của tôi</a>
            <a href="/logout" class="bg-white text-red-600 border border-red-600 py-3 text-center text-sm font-bold uppercase tracking-widest hover:bg-red-50">Đăng xuất</a>
        <?php else: ?>
            <a href="/login" class="bg-black text-white py-4 text-center text-sm font-bold uppercase tracking-widest">Đăng nhập / Đăng ký</a>
        <?php endif; ?>
    </div>
</div>

<div id="toast-container" class="fixed bottom-5 right-5 z-[60] flex flex-col gap-2"></div>

<script>
    function showToast(message, isSuccess = true) {
        const toast = document.createElement('div');
        toast.className = `transform transition-all duration-300 translate-y-10 opacity-0 px-6 py-3 shadow-lg text-sm font-medium ${isSuccess ? 'bg-black text-white' : 'bg-red-600 text-white'}`;
        toast.innerText = message;
        document.getElementById('toast-container').appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-10');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function toggleSearch() {
        const modal = document.getElementById('search-modal');
        if (modal.classList.contains('opacity-0')) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => modal.querySelector('input').focus(), 100);
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = ''; 
        }
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        if (menu.classList.contains('-translate-x-full')) {
            menu.classList.remove('-translate-x-full');
            document.body.style.overflow = 'hidden';
        } else {
            menu.classList.add('-translate-x-full');
            document.body.style.overflow = '';
        }
    }
</script>
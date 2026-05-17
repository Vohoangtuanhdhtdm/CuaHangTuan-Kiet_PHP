<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Chuyển bản đồ thành đen trắng để đồng bộ Monochrome */
        .map-grayscale iframe {
            filter: grayscale(100%) contrast(1.1) opacity(0.8);
            transition: all 0.5s ease;
        }
        .map-grayscale iframe:hover {
            filter: grayscale(0%) contrast(1) opacity(1);
        }

        /* Tùy chỉnh input Minimalist */
        .minimal-input {
            width: 100%;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #e5e7eb; /* xám nhạt */
            padding: 1rem 0;
            font-size: 0.875rem;
            color: #000;
            transition: border-color 0.3s ease;
            outline: none;
        }
        .minimal-input:focus {
            border-bottom-color: #000;
        }
        .minimal-input::placeholder {
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-white text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow">
        <section class="py-24 bg-gray-50 text-center border-b border-gray-100">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4">Trò Chuyện Cùng Chúng Tôi</h1>
            <p class="text-gray-500 font-light max-w-xl mx-auto">Mọi thắc mắc về đơn hàng, chính sách hoặc bộ sưu tập mới, đừng ngần ngại để lại lời nhắn. Đội ngũ TuanStore luôn lắng nghe bạn.</p>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                
                <div>
                    <h2 class="text-2xl font-bold uppercase tracking-tight mb-10">Thông Tin Liên Hệ</h2>
                    
                    <div class="space-y-10">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mt-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <div class="ml-6">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Trụ Sở Chính</h3>
                                <p class="text-gray-900 leading-relaxed font-medium text-lg">
                                    Thủ Dầu Một<br>
                                    Bình Dương, Việt Nam
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 mt-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            <div class="ml-6">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Thư Điện Tử</h3>
                                <p class="text-gray-900 leading-relaxed font-medium text-lg">
                                    hello@tuanstore.vn<br>
                                    support@tuanstore.vn
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 mt-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.89-1.46-5.35-3.92-6.81-6.81l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                            <div class="ml-6">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Đường Dây Nóng</h3>
                                <p class="text-gray-900 leading-relaxed font-medium text-lg">
                                    1900 6868 99<br>
                                    (09:00 - 21:00 hàng ngày)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold uppercase tracking-tight mb-10">Gửi Tin Nhắn</h2>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <input type="text" placeholder="Họ và tên *" required class="minimal-input">
                            </div>
                            <div>
                                <input type="email" placeholder="Email của bạn *" required class="minimal-input">
                            </div>
                        </div>
                        
                        <div>
                            <input type="text" placeholder="Số điện thoại" class="minimal-input">
                        </div>
                        
                        <div>
                            <textarea placeholder="Tin nhắn của bạn *" required rows="4" class="minimal-input resize-none"></textarea>
                        </div>
                        
                        <div class="pt-4">
                            <button type="button" class="bg-black text-white px-10 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors rounded-none w-full md:w-auto">
                                Gửi Yêu Cầu
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </section>

        <section class="w-full h-[500px] map-grayscale border-t border-gray-100">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125322.61053164923!2d106.5891103!3d10.9806497!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d115e5a22bb3%3A0xcda6028a7e0edec1!2zVGjhu6cgROG6p3UgTeG7mXQsIELDrG5oIETGsMahbmcsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1715828450000!5m2!1svi!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

    </main>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>

</body>
</html>
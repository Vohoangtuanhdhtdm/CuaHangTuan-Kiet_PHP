<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuanStore - Tối giản & Hiện đại</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hiệu ứng Soft UI hover cho Floating Button */
        .floating-btn:hover { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
        /* Ẩn scrollbar cho danh mục ngang */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Thêm vào thẻ <style> hiện tại của bạn */

/* Hiệu ứng Fade & Trượt mượt mà cho Carousel Tối giản */
        .hero-slide { transition: opacity 1s ease-in-out; }
        .hero-slide.active { z-index: 10; opacity: 1; pointer-events: auto; }
        .hero-slide:not(.active) { z-index: 0; opacity: 0; pointer-events: none; }
        
        /* Hiệu ứng Zoom chậm cho ảnh nền */
        .hero-img { transition: transform 6s ease-out; transform: scale(1.08); }
        .hero-slide.active .hero-img { transform: scale(1); }

        /* Hiệu ứng Text trượt lên có độ trễ */
        .anim-el { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .hero-slide.active .anim-el { opacity: 1; transform: translateY(0); }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }

        /* Scroll Down Indicator */
        @keyframes scrollDown { 0% { transform: translateY(-100%); } 100% { transform: translateY(200%); } }
        .animate-scroll { animation: scrollDown 2s infinite; }
    </style>
</head>
<body class="bg-white text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow">
        <section id="hero-carousel" class="relative w-full h-[90vh] min-h-[600px] overflow-hidden bg-gray-100">
            
            <div class="hero-slide active absolute inset-0 w-full h-full flex items-end justify-center lg:justify-start">
                <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=2071&auto=format&fit=crop" alt="Hero 1" class="hero-img absolute inset-0 w-full h-full object-cover object-top">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-32 flex justify-between items-end">
                    <div class="max-w-2xl text-left">
                        <p class="anim-el delay-100 text-white/80 uppercase tracking-[0.3em] text-xs font-bold mb-4">Bộ sưu tập Thu Đông 2026</p>
                        <h1 class="anim-el delay-200 text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 tracking-tighter leading-[0.9]">
                            TINH TẾ <br>
                            <span class="text-white/70 italic font-light">& ĐƯƠNG ĐẠI</span>
                        </h1>
                        <p class="anim-el delay-300 text-white/90 text-base md:text-lg font-light mb-10 max-w-md leading-relaxed">
                            Tập trung vào chất liệu nguyên bản và phom dáng tối giản. Tôn vinh khí chất của riêng bạn.
                        </p>
                        <div class="anim-el delay-400">
                            <a href="#shop" class="group inline-flex items-center justify-center bg-white text-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 rounded-none">
                                Khám phá ngay
                                <svg class="w-4 h-4 ml-3 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-slide absolute inset-0 w-full h-full flex items-end justify-center lg:justify-start">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" alt="Hero 2" class="hero-img absolute inset-0 w-full h-full object-cover object-top">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-32 flex justify-between items-end">
                    <div class="max-w-2xl text-left">
                        <p class="anim-el delay-100 text-white/80 uppercase tracking-[0.3em] text-xs font-bold mb-4">Must-have Items</p>
                        <h1 class="anim-el delay-200 text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 tracking-tighter leading-[0.9]">
                            BẢN SẮC <br>
                            <span class="text-white/70 italic font-light">& TỐI GIẢN</span>
                        </h1>
                        <p class="anim-el delay-300 text-white/90 text-base md:text-lg font-light mb-10 max-w-md leading-relaxed">
                            Lược bỏ mọi chi tiết thừa. Thiết kế lấy con người làm trung tâm, tôn dáng và thoải mái tuyệt đối.
                        </p>
                        <div class="anim-el delay-400">
                            <a href="#shop" class="group inline-flex items-center justify-center bg-white text-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 rounded-none">
                                Xem thiết kế mới
                                <svg class="w-4 h-4 ml-3 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-slide absolute inset-0 w-full h-full flex items-end justify-center lg:justify-start">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop" alt="Hero 3" class="hero-img absolute inset-0 w-full h-full object-cover object-top">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-32 flex justify-between items-end">
                    <div class="max-w-2xl text-left">
                        <p class="anim-el delay-100 text-white/80 uppercase tracking-[0.3em] text-xs font-bold mb-4">Phong cách Đô thị</p>
                        <h1 class="anim-el delay-200 text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 tracking-tighter leading-[0.9]">
                            CHUYỂN ĐỘNG <br>
                            <span class="text-white/70 italic font-light">& TỰ DO</span>
                        </h1>
                        <p class="anim-el delay-300 text-white/90 text-base md:text-lg font-light mb-10 max-w-md leading-relaxed">
                            Giao thoa giữa nhịp sống hối hả và sự thanh lịch thường nhật. Bắt nhịp mọi xu hướng.
                        </p>
                        <div class="anim-el delay-400">
                            <a href="#shop" class="group inline-flex items-center justify-center bg-white text-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 rounded-none">
                                Shop Urban
                                <svg class="w-4 h-4 ml-3 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-4 sm:left-6 lg:left-8 z-20 w-full max-w-7xl mx-auto flex items-center justify-between pr-8 sm:pr-12 lg:pr-16">
                <div class="flex items-center space-x-6 text-white">
                    <div class="font-light tracking-widest text-sm w-12">
                        <span id="current-slide-num" class="font-bold">01</span><span class="mx-1 text-white/50">/</span><span class="text-white/70">03</span>
                    </div>
                    <div class="hidden sm:block w-32 h-[1px] bg-white/20 relative overflow-hidden">
                        <div id="slide-progress" class="absolute top-0 left-0 h-full bg-white transition-all duration-[6000ms] ease-linear w-0"></div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button onclick="prevHeroSlide()" class="p-2 text-white/60 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" /></svg>
                    </button>
                    <button onclick="nextHeroSlide()" class="p-2 text-white/60 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </button>
                </div>
            </div>

            <div class="absolute bottom-12 right-4 sm:right-6 lg:right-8 z-20 hidden lg:flex flex-col items-center">
                <span class="text-white/60 text-[10px] uppercase tracking-widest mb-4" style="writing-mode: vertical-rl;">Cuộn xuống</span>
                <div class="w-[1px] h-16 bg-white/20 relative overflow-hidden">
                    <div class="absolute top-0 w-full h-1/2 bg-white animate-scroll"></div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 border-b border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
                    <h2 class="text-4xl font-black text-black tracking-tighter uppercase mb-4">Danh Mục</h2>
                    <p class="text-gray-500 max-w-md leading-relaxed font-light">Những thiết kế cơ bản tạo nên tủ đồ hoàn hảo của bạn, không chạy theo xu hướng nhất thời.</p>
                </div>
                <a href="/#shop" class="group hidden md:inline-flex items-center text-sm font-bold uppercase tracking-widest text-black hover:text-gray-500 transition-colors pb-2">
                    Xem tất cả 
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
                
                <a href="/#shop" class="md:col-span-7 group block relative">
                    <div class="relative overflow-hidden bg-gray-100 aspect-[4/5] md:aspect-[3/4]">
                        <img src="/benner/newBenenrSlider2.jpg" class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
                    </div>
                    <div class="mt-6 flex justify-between items-center border-b border-gray-200 pb-4 group-hover:border-black transition-colors">
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-black">Áo</h3>
                        <span class="text-gray-400 font-medium group-hover:text-black transition-colors">01 &mdash; Tĩnh tại</span>
                    </div>
                </a>

                <div class="md:col-span-5 flex flex-col gap-10 lg:gap-16 md:pt-24">
                    
                    <a href="/#shop" class="group block relative">
                        <div class="relative overflow-hidden bg-gray-100 aspect-square">
                            <img src="/benner/newBenenrSlider.jpg" alt="Quần" class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
                        </div>
                        <div class="mt-5 flex justify-between items-center border-b border-gray-200 pb-3 group-hover:border-black transition-colors">
                            <h3 class="text-xl font-bold uppercase tracking-tight text-black">Quần</h3>
                            <span class="text-gray-400 font-medium group-hover:text-black transition-colors">02 &mdash; Phóng khoáng</span>
                        </div>
                    </a>

                    <a href="/#shop" class="group block relative">
                        <div class="relative overflow-hidden bg-gray-100 aspect-[4/3]">
                            <img src="/benner/bai9.jpg" alt="Phụ kiện" class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
                        </div>
                        <div class="mt-5 flex justify-between items-center border-b border-gray-200 pb-3 group-hover:border-black transition-colors">
                            <h3 class="text-xl font-bold uppercase tracking-tight text-black">Phụ Kiện</h3>
                            <span class="text-gray-400 font-medium group-hover:text-black transition-colors">03 &mdash; Điểm nhấn</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section id="shop" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mb-20">
            <h2 class="text-3xl font-bold text-center mb-12 uppercase tracking-tight">Sản phẩm nổi bật</h2>
            
            <ul id="category-list" class="flex justify-center space-x-8 mb-12 overflow-x-auto no-scrollbar pb-2">
                <li class="text-gray-400 text-sm">Đang tải...</li>
            </ul>

            <div id="product-grid" class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
                </div>
        </section>
    </main>

    <button class="fixed bottom-8 right-8 w-14 h-14 bg-black text-white rounded-full flex items-center justify-center floating-btn transition-all duration-300 z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
    </button>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>
    <script src="/assets/js/products.js"></script>
    <script>
        const heroSlides = document.querySelectorAll('.hero-slide');
        const progressLine = document.getElementById('slide-progress');
        const currentSlideNum = document.getElementById('current-slide-num');
        let currentIdx = 0;
        let slideTimer;

        function showHeroSlide(index) {
            // Reset thanh progress
            progressLine.style.transition = 'none';
            progressLine.style.width = '0%';

            // Ẩn tất cả slide
            heroSlides.forEach(slide => slide.classList.remove('active'));
            
            // Hiện slide mới
            currentIdx = index;
            heroSlides[currentIdx].classList.add('active');
            currentSlideNum.innerText = '0' + (currentIdx + 1);

            // Chạy animation thanh progress
            setTimeout(() => {
                progressLine.style.transition = 'width 6000ms linear';
                progressLine.style.width = '100%';
            }, 50);

            // Đặt lại tự động chuyển
            clearInterval(slideTimer);
            slideTimer = setInterval(nextHeroSlide, 6000);
        }

        function nextHeroSlide() {
            let nextIdx = (currentIdx + 1) % heroSlides.length;
            showHeroSlide(nextIdx);
        }

        function prevHeroSlide() {
            let prevIdx = (currentIdx - 1 + heroSlides.length) % heroSlides.length;
            showHeroSlide(prevIdx);
        }

        // Khởi động carousel
        showHeroSlide(0);
    </script>
</body>
</html>
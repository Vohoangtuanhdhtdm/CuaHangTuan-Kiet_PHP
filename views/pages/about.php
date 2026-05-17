<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về chúng tôi - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Hiệu ứng Parallax nhẹ cho Hero Image */
        .bg-fixed-parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="bg-white text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow">
        <section class="relative w-full h-[70vh] min-h-[500px] flex items-center justify-center bg-fixed-parallax" style="background-image: url('https://images.unsplash.com/photo-1555529771-835f59fc5efe?q=80&w=2000&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
            
            <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
                <p class="text-white/80 uppercase tracking-[0.4em] text-xs font-bold mb-6">Câu chuyện của chúng tôi</p>
                <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter drop-shadow-md">
                    Bản Sắc Đường Phố
                </h1>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <div class="order-2 md:order-1">
                    <h2 class="text-3xl md:text-5xl font-black text-black mb-8 tracking-tighter uppercase leading-tight">
                        Thời trang không <br> phân biệt tuổi tác
                    </h2>
                    
                    <div class="space-y-6 text-gray-600 font-light text-lg leading-loose">
                        <p>
                            Bắt nguồn từ niềm đam mê chung mãnh liệt với công nghệ và văn hóa thời trang thành thị, TuanStore được khai sinh bởi năm người bạn: Hoàng Tuấn, Tuấn Kiệt, Minh Quân, Minh Nhật và Quốc Thái. 
                        </p>
                        <p>
                            Chúng tôi tin rằng quần áo không chỉ là những mảnh vải vô tri được khoác lên người. Nó là ngôn ngữ không lời, là cách bạn giao tiếp với thế giới xung quanh và khẳng định cái tôi độc bản. Không quan trọng bạn bao nhiêu tuổi, đến từ đâu hay làm công việc gì, TuanStore luôn có một mảnh ghép phù hợp dành cho bạn.
                        </p>
                        <p>
                            Bằng việc theo đuổi triết lý <strong>Minimalism (Tối giản)</strong>, chúng tôi lược bỏ đi những chi tiết rườm rà không cần thiết. Trọng tâm duy nhất được đặt vào phom dáng kiến trúc, chất liệu bền vững và những đường cắt may tỉ mỉ, mang đến trải nghiệm thoải mái tuyệt đối cho người mặc.
                        </p>
                    </div>

                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <p class="text-black font-medium tracking-widest uppercase text-sm">The Founders Team.</p>
                        <p class="text-gray-400 text-xs mt-1">Est. 2026</p>
                    </div>
                </div>

                <div class="order-1 md:order-2 relative">
                    <div class="relative bg-gray-100 aspect-[4/5] w-full max-w-md ml-auto z-10 overflow-hidden">
                        <img src="/benner/BOO.png" alt="Brand Story" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700">
                    </div>
                    <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-gray-100 -z-10"></div>
                </div>

            </div>
        </section>

        <section class="py-24 bg-gray-50 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center mb-16">
                    <p class="text-gray-400 uppercase tracking-[0.3em] text-xs font-bold mb-3">Thư viện ảnh</p>
                    <h2 class="text-3xl font-black text-black uppercase tracking-tighter">Visual Culture</h2>
                    <div class="w-12 h-[2px] bg-black mx-auto mt-6"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 auto-rows-[250px]">
                    
                    <div class="md:col-span-2 md:row-span-2 group overflow-hidden relative cursor-pointer">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
                        <img src="/benner/BST3.jpg" alt="Vietnam Street" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <p class="absolute bottom-6 left-6 text-white font-bold tracking-widest uppercase text-sm z-20">Nhịp Sống.</p>
                    </div>

                    <div class="md:col-span-2 group overflow-hidden relative cursor-pointer">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
                        <img src="/benner/BennerDongHo.jpg" alt="Details" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <p class="absolute bottom-4 left-6 text-white font-bold tracking-widest uppercase text-xs z-20">Giao Thoa.</p>
                    </div>

                    <div class="md:col-span-1 group overflow-hidden relative cursor-pointer">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
                        <img src="/benner/BST4.jpg" alt="Fashion" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                    </div>

                    <div class="md:col-span-1 group overflow-hidden relative cursor-pointer">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
                        <img src="/benner/para3.jpg" alt="Urban" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>

                </div>
            </div>
        </section>

        <section class="py-24 bg-white text-center">
            <h2 class="text-2xl md:text-4xl font-light text-black mb-8 max-w-2xl mx-auto leading-tight">
                Sẵn sàng định hình phong cách của riêng bạn cùng chúng tôi?
            </h2>
            <a href="/#shop" class="inline-block bg-black text-white px-10 py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors rounded-none">
                Đến Cửa Hàng
            </a>
        </section>

    </main>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>

</body>
</html>
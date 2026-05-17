<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạp chí - TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow">
        <section class="py-24 bg-white text-center border-b border-gray-100">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4">Tạp Chí TuanStore</h1>
            <p class="text-gray-500 font-light max-w-xl mx-auto">Nơi cập nhật những xu hướng thời trang mới nhất, phong cách sống hiện đại và những câu chuyện cảm hứng từ cộng đồng.</p>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <?php if(empty($posts)): ?>
                <div class="text-center py-20 text-gray-500">Chưa có bài viết nào được xuất bản.</div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    <?php foreach($posts as $post): ?>
                        <article class="group cursor-pointer flex flex-col">
                            <a href="/blog/<?= $post['slug'] ?>" class="block overflow-hidden bg-gray-50 aspect-[4/3] mb-6">
                                <img src="<?= htmlspecialchars($post['thumbnail']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </a>
                            
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                <?= date('d.m.Y', strtotime($post['created_at'])) ?> &mdash; THỜI TRANG
                            </div>
                            
                            <a href="/blog/<?= $post['slug'] ?>" class="block mb-3">
                                <h2 class="text-2xl font-bold text-black leading-tight group-hover:text-gray-600 transition-colors">
                                    <?= htmlspecialchars($post['title']) ?>
                                </h2>
                            </a>
                            
                            <p class="text-gray-600 text-sm leading-relaxed mb-6 flex-grow">
                                <?= htmlspecialchars($post['excerpt']) ?>
                            </p>
                            
                            <div class="mt-auto">
                                <a href="/blog/<?= $post['slug'] ?>" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-black hover:text-gray-500 transition-colors pb-1 border-b-2 border-black hover:border-gray-500">
                                    Đọc tiếp
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>
</body>
</html>
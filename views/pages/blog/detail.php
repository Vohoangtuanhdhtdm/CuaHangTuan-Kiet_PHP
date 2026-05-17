<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - Tạp chí TuanStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* CSS tuỳ chỉnh cho phần nội dung bài viết (Content) */
        .blog-content p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
            font-size: 1.125rem;
            color: #374151; /* gray-700 */
        }
        .blog-content h2, .blog-content h3 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: #000;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        .blog-content h2 { font-size: 1.5rem; }
    </style>
</head>
<body class="bg-white text-black flex flex-col min-h-screen">
    
    <?php require_once VIEW_PATH . '/partials/header.php'; ?>

    <main class="flex-grow">
        <article class="pt-16 pb-24">
            
            <header class="max-w-3xl mx-auto px-4 sm:px-6 text-center mb-12">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">
                    <?= date('d.m.Y', strtotime($post['created_at'])) ?> &mdash; CÂU CHUYỆN THỜI TRANG
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-black leading-tight tracking-tighter mb-8">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                <p class="text-lg text-gray-500 font-light max-w-2xl mx-auto leading-relaxed">
                    <?= htmlspecialchars($post['excerpt']) ?>
                </p>
            </header>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 mb-16">
                <div class="aspect-[16/9] w-full overflow-hidden bg-gray-100">
                    <img src="<?= htmlspecialchars($post['thumbnail']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="max-w-2xl mx-auto px-4 sm:px-6 blog-content font-serif">
                <?= $post['content'] ?>
            </div>
            
            <div class="max-w-2xl mx-auto px-4 sm:px-6 mt-16 pt-8 border-t border-gray-200 text-center">
                <a href="/blog" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-black hover:text-gray-500 transition-colors">
                    &larr; Quay lại Tạp chí
                </a>
            </div>

        </article>
    </main>

    <?php require_once VIEW_PATH . '/partials/footer.php'; ?>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Tạo bài viết mới - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>
    <main class="ml-64 p-8 max-w-5xl">
        <div class="mb-6"><a href="/admin/posts" class="text-sm text-blue-600 hover:underline">&larr; Quay lại</a></div>
        <h1 class="text-3xl font-bold mb-8">Tạo bài viết mới</h1>
        <form id="postForm" class="bg-white p-8 border rounded-xl space-y-6" enctype="multipart/form-data">
            <div>
                <label class="block text-sm font-medium mb-1">Tiêu đề bài viết</label>
                <input type="text" name="title" required class="w-full border p-2.5 focus:ring-1 focus:ring-black outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả ngắn (Excerpt)</label>
                <textarea name="excerpt" rows="2" class="w-full border p-2.5 focus:ring-1 focus:ring-black outline-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ảnh đại diện bài viết</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full border p-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nội dung bài viết (Hỗ trợ HTML)</label>
                <textarea name="content" rows="10" required class="w-full border p-2.5 font-serif focus:ring-1 focus:ring-black outline-none" placeholder="<p>Nhập nội dung bài viết tại đây...</p>"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_published" id="pub" checked class="w-4 h-4">
                <label for="pub" class="text-sm font-medium select-none">Xuất bản ngay lập tức</label>
            </div>
            <button type="submit" class="bg-black text-white px-6 py-3 font-medium hover:bg-gray-800">Đăng bài viết</button>
        </form>
    </main>
    <script>
        document.getElementById('postForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch('/api/admin/posts/create', { method: 'POST', body: new FormData(e.target) });
            const result = await res.json();
            alert(result.message);
            if(result.success) window.location.href = result.data.redirect;
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật bài viết - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-900">
    
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>
    
    <main class="ml-64 p-8 max-w-5xl">
        <div class="mb-6">
            <a href="/admin/posts" class="text-sm text-blue-600 hover:underline font-medium">&larr; Quay lại danh sách</a>
        </div>
        
        <h1 class="text-3xl font-bold mb-8">Cập nhật bài viết</h1>
        
        <form id="editPostForm" class="bg-white p-8 border rounded-xl shadow-sm space-y-6" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">

            <div>
                <label class="block text-sm font-semibold mb-2">Tiêu đề bài viết <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required 
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-1 focus:ring-black outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Mô tả ngắn (Excerpt)</label>
                <textarea name="excerpt" rows="2" 
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-1 focus:ring-black outline-none transition-colors"><?= htmlspecialchars($post['excerpt']) ?></textarea>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <label class="block text-sm font-semibold mb-3">Ảnh đại diện hiện tại</label>
                <?php if(!empty($post['thumbnail'])): ?>
                    <div class="mb-4">
                        <img src="<?= $post['thumbnail'] ?>" alt="Thumbnail" class="w-48 h-32 object-cover border border-gray-200 rounded shadow-sm bg-white">
                    </div>
                <?php endif; ?>
                
                <label class="block text-sm font-medium mb-2 text-gray-600">Tải ảnh mới lên (Bỏ trống nếu muốn giữ nguyên ảnh cũ)</label>
                <input type="file" name="thumbnail" accept="image/*" 
                    class="w-full border border-gray-300 p-2 rounded bg-white cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nội dung bài viết (Hỗ trợ mã HTML) <span class="text-red-500">*</span></label>
                <textarea name="content" rows="12" required 
                    class="w-full border border-gray-300 p-4 rounded-lg font-mono text-sm focus:ring-1 focus:ring-black outline-none transition-colors leading-relaxed bg-gray-50 focus:bg-white"><?= htmlspecialchars($post['content']) ?></textarea>
                <p class="text-xs text-gray-400 mt-2">Gợi ý: Sử dụng thẻ &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt; để định dạng bài viết.</p>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <input type="checkbox" name="is_published" id="pub" <?= $post['is_published'] ? 'checked' : '' ?> 
                    class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black cursor-pointer">
                <label for="pub" class="text-sm font-medium select-none cursor-pointer">Xuất bản bài viết (Hiển thị ngay trên trang Tạp chí ngoài Frontend)</label>
            </div>

            <div class="pt-8 border-t border-gray-100 flex gap-4">
                <button type="submit" class="bg-black text-white px-8 py-3 text-sm font-bold uppercase tracking-wider hover:bg-gray-800 transition-colors rounded shadow-md">
                    Lưu Thay Đổi
                </button>
                <a href="/admin/posts" class="bg-gray-100 text-gray-700 px-8 py-3 text-sm font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors rounded">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('editPostForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Khóa nút submit để tránh spam click khi mạng chậm
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = "ĐANG XỬ LÝ...";
            submitBtn.disabled = true;

            try {
                // Gọi API cập nhật
                const res = await fetch('/api/admin/posts/update', { 
                    method: 'POST', 
                    body: new FormData(e.target) 
                });
                
                const result = await res.json();
                
                // Hiển thị thông báo
                alert(result.message);
                
                // Nếu thành công, điều hướng về danh sách bài viết
                if (result.success && result.data && result.data.redirect) {
                    window.location.href = result.data.redirect;
                }
            } catch (error) {
                console.error("Lỗi Fetch API:", error);
                alert("Đã xảy ra lỗi kết nối đến máy chủ. Vui lòng thử lại!");
            } finally {
                // Mở khóa nút submit
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
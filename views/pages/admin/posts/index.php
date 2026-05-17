<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><title>Quản lý bài viết - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>
    <main class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Quản lý bài viết</h1>
            <a href="/admin/posts/create" class="bg-black text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">Viết bài mới</a>
        </div>
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b text-sm">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Bài viết</th>
                        <th class="px-6 py-4 font-semibold">Trạng thái</th>
                        <th class="px-6 py-4 font-semibold">Ngày đăng</th>
                        <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    <?php foreach($posts as $p): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 flex items-center gap-4">
                            <img src="<?= $p['thumbnail'] ?>" class="w-16 h-12 object-cover bg-gray-100">
                            <div>
                                <p class="font-bold text-gray-900"><?= htmlspecialchars($p['title']) ?></p>
                                <p class="text-xs text-gray-400 max-w-md truncate"><?= htmlspecialchars($p['excerpt']) ?></p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <?= $p['is_published'] ? '<span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-semibold">Đã xuất bản</span>' : '<span class="text-gray-500 bg-gray-50 px-2 py-1 rounded text-xs font-semibold">Bản nháp</span>' ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="/admin/posts/edit/<?= $p['id'] ?>" class="text-blue-600 hover:underline">Sửa</a>
                            <button onclick="deletePost(<?= $p['id'] ?>)" class="text-red-600 hover:underline">Xóa</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        async function deletePost(id) {
            if(!confirm("Xóa bài viết này? Hành động không thể hoàn tác.")) return;
            const res = await fetch('/api/admin/posts/delete', {
                method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({id})
            });
            const result = await res.json();
            if(result.success) window.location.reload();
            else alert(result.message);
        }
    </script>
</body>
</html>
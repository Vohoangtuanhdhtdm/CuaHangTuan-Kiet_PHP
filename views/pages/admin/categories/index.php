<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once VIEW_PATH . '/partials/admin_sidebar.php'; ?>

    <main class="ml-64 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Quản lý Danh mục</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 id="form-title" class="text-lg font-bold mb-4">Thêm danh mục mới</h2>
                    <form id="categoryForm" class="space-y-4">
                        <input type="hidden" id="category-id" name="id">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tên danh mục</label>
                            <input type="text" id="category-name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Danh mục cha (Tùy chọn)</label>
                            <select id="parent-id" name="parent_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500">
                                <option value="">Không có</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Lưu</button>
                            <button type="button" onclick="resetForm()" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold">Tên danh mục</th>
                                <th class="px-6 py-4 text-sm font-semibold">Danh mục cha</th>
                                <th class="px-6 py-4 text-sm font-semibold text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($cat['parent_name'] ?? '---') ?></td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <button onclick='editCategory(<?= json_encode($cat) ?>)' class="text-blue-600 hover:underline">Sửa</button>
                                        <button onclick="deleteCategory(<?= $cat['id'] ?>)" class="text-red-600 hover:underline">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        const form = document.getElementById('categoryForm');
        
        function editCategory(cat) {
            document.getElementById('form-title').innerText = 'Sửa danh mục: ' + cat.name;
            document.getElementById('category-id').value = cat.id;
            document.getElementById('category-name').value = cat.name;
            document.getElementById('parent-id').value = cat.parent_id || '';
        }

        function resetForm() {
            document.getElementById('form-title').innerText = 'Thêm danh mục mới';
            form.reset();
            document.getElementById('category-id').value = '';
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('category-id').value;
            const endpoint = id ? '/api/admin/categories/update' : '/api/admin/categories/create';
            
            const data = Object.fromEntries(new FormData(form).entries());

            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            alert(result.message);
            if (result.success) window.location.reload();
        });

        async function deleteCategory(id) {
            if (!confirm('Xóa danh mục này? Các sản phẩm thuộc danh mục sẽ bị mất liên kết.')) return;
            const res = await fetch('/api/admin/categories/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const result = await res.json();
            if (result.success) window.location.reload();
        }
    </script>
</body>
</html>
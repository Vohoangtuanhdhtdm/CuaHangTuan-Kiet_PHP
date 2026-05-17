<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Category;

class AdminCategoryController extends Controller {

    public function __construct() {
        Middleware::requireRole(['admin']);
    }

    public function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        $this->render('pages/admin/categories/index', ['categories' => $categories]);
    }

    public function storeAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $parentId = $data['parent_id'] ?? null;

        if (empty($name)) $this->jsonResponse(false, null, "Tên danh mục không được để trống.");

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();
        
        $categoryModel = new Category();
        if ($categoryModel->create($name, $slug, $parentId)) {
            $this->jsonResponse(true, null, "Thêm danh mục thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi khi thêm danh mục.");
        }
    }

    public function updateAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        $name = trim($data['name'] ?? '');
        $parentId = $data['parent_id'] ?? null;

        if (!$id || empty($name)) $this->jsonResponse(false, null, "Dữ liệu không hợp lệ.");

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();

        $categoryModel = new Category();
        if ($categoryModel->update($id, $name, $slug, $parentId)) {
            $this->jsonResponse(true, null, "Cập nhật thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi khi cập nhật.");
        }
    }

    public function deleteAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        $categoryModel = new Category();
        if ($categoryModel->delete($id)) {
            $this->jsonResponse(true, null, "Đã xóa danh mục thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi khi xóa.");
        }
    }
}
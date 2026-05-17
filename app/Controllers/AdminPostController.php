<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Post;

class AdminPostController extends Controller {

    public function __construct() {
        Middleware::requireRole(['admin']);
    }

    // Danh sách bài viết
    public function index() {
        $postModel = new Post();
        $posts = $postModel->getAllForAdmin();
        $this->render('pages/admin/posts/index', ['posts' => $posts]);
    }

    // Giao diện Thêm bài viết
    public function createView() {
        $this->render('pages/admin/posts/create');
    }

    // Xử lý API Thêm bài viết
    public function storeAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        if (empty($title) || empty($content)) {
            $this->jsonResponse(false, null, "Vui lòng nhập tiêu đề và nội dung bài viết.");
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time();
        $thumbnailPath = 'https://via.placeholder.com/800x600?text=No+Image';

        // Xử lý Upload Ảnh đại diện bài viết
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/assets/uploads/blog/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . $fileName;

            if (in_array(strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'])) {
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
                    $thumbnailPath = '/assets/uploads/blog/' . $fileName;
                }
            }
        }

        $postModel = new Post();
        if ($postModel->create($title, $slug, $excerpt, $content, $thumbnailPath, $isPublished)) {
            $this->jsonResponse(true, ['redirect' => '/admin/posts'], "Đăng bài viết thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi hệ thống khi lưu bài viết.");
        }
    }

    // Giao diện Sửa bài viết
    public function editView($id) {
        $postModel = new Post();
        $post = $postModel->getById($id);

        if (!$post) {
            header("Location: /admin/posts");
            exit;
        }

        $this->render('pages/admin/posts/edit', ['post' => $post]);
    }

    // Xử lý API Cập nhật
    public function updateAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $isPublished = isset($_POST['is_published']) ? 1 : 0;

        $postModel = new Post();
        $oldPost = $postModel->getById($id);
        if (!$oldPost) $this->jsonResponse(false, null, "Bài viết không tồn tại.");

        $slug = $oldPost['slug'];
        if ($title !== $oldPost['title']) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time();
        }

        $thumbnailPath = $oldPost['thumbnail'];
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/assets/uploads/blog/';
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
                // Xóa ảnh cũ trên server
                $oldPhysicalPath = ROOT_PATH . '/public' . $oldPost['thumbnail'];
                if (strpos($oldPost['thumbnail'], 'placeholder') === false && file_exists($oldPhysicalPath)) {
                    unlink($oldPhysicalPath);
                }
                $thumbnailPath = '/assets/uploads/blog/' . $fileName;
            }
        }

        if ($postModel->update($id, $title, $slug, $excerpt, $content, $thumbnailPath, $isPublished)) {
            $this->jsonResponse(true, ['redirect' => '/admin/posts'], "Cập nhật bài viết thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi cập nhật dữ liệu.");
        }
    }

    // API Xóa bài viết
    public function deleteAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        $postModel = new Post();
        $post = $postModel->getById($id);

        if ($post) {
            $imagePath = $post['thumbnail'];
            if ($postModel->delete($id)) {
                $oldPhysicalPath = ROOT_PATH . '/public' . $imagePath;
                if (strpos($imagePath, 'placeholder') === false && file_exists($oldPhysicalPath)) {
                    unlink($oldPhysicalPath);
                }
                $this->jsonResponse(true, null, "Xóa bài viết thành công!");
            }
        }
        $this->jsonResponse(false, null, "Không thể xóa bài viết.");
    }
}
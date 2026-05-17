<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Product;

class AdminProductController extends Controller {

    public function __construct() {
        // Bảo vệ toàn bộ class bằng Middleware Admin
        Middleware::requireRole(['admin']);
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAllForAdmin();

        $this->render('pages/admin/products/index', [
            'products' => $products
        ]);
    }

    // Hiển thị Form Thêm sản phẩm
    public function createView() {
        $productModel = new Product();
        $categories = $productModel->getCategories(); // Lấy danh mục để đổ vào Select Box
        
        $this->render('pages/admin/products/create', [
            'categories' => $categories
        ]);
    }

    // Xử lý API Thêm sản phẩm và Upload File
    public function storeAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }

        // Với FormData chứa file, ta không dùng file_get_contents("php://input")
        // mà lấy trực tiếp từ $_POST và $_FILES
        $name = trim($_POST['name'] ?? '');
        $categoryId = $_POST['category_id'] ?? null;
        $price = str_replace(',', '', $_POST['price'] ?? 0); // Xóa dấu phẩy nếu có
        $salePrice = !empty($_POST['sale_price']) ? str_replace(',', '', $_POST['sale_price']) : null;
        $stock = $_POST['stock'] ?? 0;
        $description = trim($_POST['description'] ?? '');

        if (empty($name) || empty($price)) {
            $this->jsonResponse(false, null, "Vui lòng nhập tên và giá sản phẩm.");
        }

        // Tạo Slug tự động từ Tên sản phẩm (thêm time() để chống trùng lặp)
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = $slug . '-' . time();

        // Xử lý Upload Ảnh Thumbnail
        $thumbnailPath = 'https://via.placeholder.com/300x300?text=No+Image'; // Ảnh mặc định
        
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/assets/uploads/';
            
            // Tự động tạo thư mục nếu chưa có
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Tạo tên file ngẫu nhiên để tránh trùng
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . $fileName;
            
            // Chỉ cho phép định dạng ảnh
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
                    $thumbnailPath = '/assets/uploads/' . $fileName;
                } else {
                    $this->jsonResponse(false, null, "Không thể lưu tệp hình ảnh.");
                }
            } else {
                $this->jsonResponse(false, null, "Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP).");
            }
        }

        // Lưu vào DB
        $productModel = new Product();
        $productId = $productModel->create($categoryId, $name, $slug, $description, $price, $salePrice, $stock, $thumbnailPath);

        if ($productId) {
            $this->jsonResponse(true, ['redirect' => '/admin/products'], "Thêm sản phẩm thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi hệ thống khi lưu vào CSDL.");
        }
    }

    // Hiển thị Form Sửa sản phẩm
    public function editView($id) {
        $productModel = new Product();
        $product = $productModel->getById($id);

        if (!$product) {
            header("Location: /admin/products");
            exit;
        }

        $categories = $productModel->getCategories();
        $this->render('pages/admin/products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    // Xử lý API Cập nhật sản phẩm
    public function updateAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $categoryId = $_POST['category_id'] ?? null;
        $price = str_replace(',', '', $_POST['price'] ?? 0);
        $salePrice = !empty($_POST['sale_price']) ? str_replace(',', '', $_POST['sale_price']) : null;
        $stock = $_POST['stock'] ?? 0;
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (!$id || empty($name) || empty($price)) {
            $this->jsonResponse(false, null, "Dữ liệu không hợp lệ.");
        }

        $productModel = new Product();
        $oldProduct = $productModel->getById($id);
        if (!$oldProduct) $this->jsonResponse(false, null, "Không tìm thấy sản phẩm.");

        // --- ĐÃ FIX: THÊM LẠI LOGIC XỬ LÝ SLUG BỊ THIẾU ---
        $slug = $oldProduct['slug']; // Mặc định giữ lại slug cũ
        if ($name !== $oldProduct['name']) {
            // Nếu admin đổi tên sản phẩm, ta tạo slug mới
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();
        }
        // -------------------------------------------------

        $thumbnailPath = $oldProduct['thumbnail'];
        
        // Kiểm tra nếu có file ảnh mới được tải lên
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/assets/uploads/';
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . $fileName;
            
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile)) {
                    
                    // Xóa ảnh cũ
                    $oldImageRelativePath = $oldProduct['thumbnail']; 
                    $oldFilePhysicalPath = ROOT_PATH . '/public' . $oldImageRelativePath;

                    if (strpos($oldImageRelativePath, 'placeholder') === false && file_exists($oldFilePhysicalPath)) {
                        unlink($oldFilePhysicalPath); 
                    }
                    
                    $thumbnailPath = '/assets/uploads/' . $fileName; 
                }
            }
        }

        // Thực hiện lệnh update vào DB 
        if ($productModel->update($id, $categoryId, $name, $slug, $description, $price, $salePrice, $stock, $thumbnailPath, $isActive)) {
            $this->jsonResponse(true, ['redirect' => '/admin/products'], "Cập nhật thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi cập nhật.");
        }
    }

    // Xử lý API Xóa sản phẩm
    // Cập nhật lại trong app/Controllers/AdminProductController.php
    public function deleteAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        $productModel = new Product();
        $product = $productModel->getById($id);

        if ($product) {
            // 1. Lưu đường dẫn ảnh trước khi xóa bản ghi
            $imagePath = $product['thumbnail'];
            $filePhysicalPath = ROOT_PATH . '/public' . $imagePath;

            // 2. Xóa trong Database
            if ($productModel->delete($id)) {
                
                // 3. Nếu xóa DB thành công, tiến hành xóa file vật lý
                if (strpos($imagePath, 'placeholder') === false && file_exists($filePhysicalPath)) {
                    unlink($filePhysicalPath);
                }

                $this->jsonResponse(true, null, "Đã xóa sản phẩm và hình ảnh liên quan!");
            } else {
                $this->jsonResponse(false, null, "Lỗi khi xóa trong cơ sở dữ liệu.");
            }
        } else {
            $this->jsonResponse(false, null, "Sản phẩm không tồn tại.");
        }
    }
}
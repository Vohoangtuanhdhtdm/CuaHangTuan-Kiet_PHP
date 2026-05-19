<?php
namespace Controllers;

use Core\Controller;
use Core\Middleware;
use Models\Product;

class AdminProductController extends Controller {

    public function __construct() {
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
        $categories = $productModel->getCategories(); 
        
        $this->render('pages/admin/products/create', [
            'categories' => $categories
        ]);
    }

    // Xử lý API Thêm sản phẩm và Upload File
    public function storeAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }

        $name = trim($_POST['name'] ?? '');
        $categoryId = $_POST['category_id'] ?? null;
        $price = str_replace(',', '', $_POST['price'] ?? 0); 
        $salePrice = !empty($_POST['sale_price']) ? str_replace(',', '', $_POST['sale_price']) : null;
        $stock = $_POST['stock'] ?? 0;
        $description = trim($_POST['description'] ?? '');

        if (empty($name) || empty($price)) {
            $this->jsonResponse(false, null, "Vui lòng nhập tên và giá sản phẩm.");
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = $slug . '-' . time();

        $thumbnailPath = 'https://via.placeholder.com/300x300?text=No+Image'; // Ảnh mặc định
        
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $targetFile = $uploadDir . $fileName;
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
            $variants = $_POST['variants'] ?? [];
            $productModel->syncVariants($productId, $variants);
            
            $this->jsonResponse(true, ['redirect' => '/admin/products'], "Lưu sản phẩm thành công!");
        } else {
            $this->jsonResponse(false, null, "Lỗi hệ thống khi lưu vào CSDL.");
        }
    }

    // Hiển thị Form Sửa sản phẩm
    public function editView($id) {
        $productModel = new Product();
        $product = $productModel->getById($id);
        $categories = $productModel->getCategories();
        $variants = $productModel->getProductVariants($id);

        $this->render('pages/admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants 
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

        $slug = $oldProduct['slug']; 
        if ($name !== $oldProduct['name']) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();
        }

        $thumbnailPath = $oldProduct['thumbnail'];
        
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
            $variants = $_POST['variants'] ?? [];
            $productModel->syncVariants($id, $variants);
            
            $this->jsonResponse(true, ['redirect' => '/admin/products'], "Cập nhật sản phẩm thành công!");
        }else {
                $this->jsonResponse(false, null, "Lỗi cập nhật.");
            }
    }

    // Xử lý API Xóa sản phẩm
    public function deleteAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->jsonResponse(false, null, "Method Not Allowed");

        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        $productModel = new Product();
        $product = $productModel->getById($id);

        if ($product) {
            $imagePath = $product['thumbnail'];
            $filePhysicalPath = ROOT_PATH . '/public' . $imagePath;
            if ($productModel->delete($id)) {
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
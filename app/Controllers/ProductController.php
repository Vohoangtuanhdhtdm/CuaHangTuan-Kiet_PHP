<?php
namespace Controllers;

use Core\Controller;
use Models\Product;

class ProductController extends Controller {

    // Endpoint trả dữ liệu cho luồng AJAX
    public function getProductsAPI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->jsonResponse(false, null, "Method Not Allowed");
        }

        $productModel = new Product();
        
        $categoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? $_GET['category_id'] : null;
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        // Logic tính toán phân trang
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8; // Chỉ lấy 8 sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;

        // Gọi DB
        $products = $productModel->getProducts($categoryId, $keyword, $limit, $offset);
        $totalProducts = $productModel->getTotalProductsCount($categoryId, $keyword);
        $totalPages = ceil($totalProducts / $limit);
        
        $categories = $productModel->getCategories();

        $this->jsonResponse(true, [
            'products' => $products,
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalProducts
            ]
        ], "Lấy dữ liệu thành công");
    }

    public function detail($slug) {
        $productModel = new Product();
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            http_response_code(404);
            die("Sản phẩm không tồn tại!");
        }

        $images = $productModel->getProductImages($product['id']);
        $variants = $productModel->getProductVariants($product['id']);

        $this->render('pages/product-detail', [
            'product'  => $product,
            'images'   => $images,
            'variants' => $variants
        ]);
    }
}
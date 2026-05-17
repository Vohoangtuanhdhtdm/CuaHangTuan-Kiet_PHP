<?php
namespace Models;

use Core\Database;
use PDO;

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ĐÃ NÂNG CẤP: Bổ sung Limit và Offset để phân trang
    public function getProducts($category_id = null, $keyword = null, $limit = 8, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1";
        
        if ($category_id) $sql .= " AND p.category_id = :category_id";
        if ($keyword) $sql .= " AND (p.name LIKE :keyword1 OR p.description LIKE :keyword2)";
        
        // Thêm Limit và Offset
        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // BẮT BUỘC dùng bindValue để ép kiểu về PDO::PARAM_INT cho LIMIT
        if ($category_id) $stmt->bindValue(':category_id', $category_id, \PDO::PARAM_INT);
        if ($keyword) {
            $stmt->bindValue(':keyword1', '%' . $keyword . '%', \PDO::PARAM_STR);
            $stmt->bindValue(':keyword2', '%' . $keyword . '%', \PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // THÊM MỚI: Hàm đếm tổng số sản phẩm để tính ra số trang
    public function getTotalProductsCount($category_id = null, $keyword = null) {
        $sql = "SELECT COUNT(*) as total FROM products p WHERE p.is_active = 1";
        
        if ($category_id) $sql .= " AND p.category_id = :category_id";
        if ($keyword) $sql .= " AND (p.name LIKE :keyword1 OR p.description LIKE :keyword2)";

        $stmt = $this->db->prepare($sql);

        if ($category_id) $stmt->bindValue(':category_id', $category_id, \PDO::PARAM_INT);
        if ($keyword) {
            $stmt->bindValue(':keyword1', '%' . $keyword . '%', \PDO::PARAM_STR);
            $stmt->bindValue(':keyword2', '%' . $keyword . '%', \PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    // Lấy toàn bộ danh mục để làm Menu Filter
    public function getCategories() {
        $sql = "SELECT * FROM categories";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy chi tiết 1 sản phẩm qua Slug
    public function getBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = :slug AND p.is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    // Lấy bộ sưu tập ảnh của sản phẩm
    public function getProductImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = :id ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $productId]);
        return $stmt->fetchAll();
    }

    // Lấy các biến thể (Size, Color)
    public function getProductVariants($productId) {
        $sql = "SELECT * FROM product_variants WHERE product_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $productId]);
        return $stmt->fetchAll();
    }

    // Admin
    // Lấy toàn bộ sản phẩm không lọc trạng thái
    public function getAllForAdmin() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm sản phẩm mới vào CSDL
    public function create($categoryId, $name, $slug, $description, $price, $salePrice, $stock, $thumbnail) {
        $sql = "INSERT INTO products (category_id, name, slug, description, price, sale_price, stock, thumbnail, is_active) 
                VALUES (:category_id, :name, :slug, :description, :price, :sale_price, :stock, :thumbnail, 1)";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => $stock,
            'thumbnail' => $thumbnail
        ]);

        return $success ? $this->db->lastInsertId() : false;
    }

    // Lấy thông tin 1 sản phẩm theo ID (dùng cho form Edit)
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Cập nhật sản phẩm
    public function update($id, $categoryId, $name, $slug, $description, $price, $salePrice, $stock, $thumbnail, $isActive) {
        $sql = "UPDATE products 
                SET category_id = :category_id, name = :name, slug = :slug, description = :description, 
                    price = :price, sale_price = :sale_price, stock = :stock, 
                    thumbnail = :thumbnail, is_active = :is_active 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'category_id' => $categoryId, 'name' => $name, 'slug' => $slug,
            'description' => $description, 'price' => $price, 'sale_price' => $salePrice,
            'stock' => $stock, 'thumbnail' => $thumbnail, 'is_active' => $isActive, 'id' => $id
        ]);
    }

    // Xóa sản phẩm
    public function delete($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}
<?php
namespace Models;

use Core\Database;

class Post {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Lấy danh sách các bài viết đã xuất bản
    public function getPublishedPosts() {
        $sql = "SELECT * FROM posts WHERE is_published = 1 ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy chi tiết 1 bài viết theo slug
    public function getBySlug($slug) {
        $sql = "SELECT * FROM posts WHERE slug = :slug AND is_published = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    // --- CÁC HÀM DÀNH CHO ADMIN ---

    // Lấy toàn bộ bài viết (kể cả chưa xuất bản)
    public function getAllForAdmin() {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy chi tiết bài viết theo ID
    public function getById($id) {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Tạo bài viết mới
    public function create($title, $slug, $excerpt, $content, $thumbnail, $isPublished) {
        $sql = "INSERT INTO posts (title, slug, excerpt, content, thumbnail, is_published) 
                VALUES (:title, :slug, :excerpt, :content, :thumbnail, :is_published)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'thumbnail' => $thumbnail,
            'is_published' => $isPublished
        ]);
    }

    // Cập nhật bài viết
    public function update($id, $title, $slug, $excerpt, $content, $thumbnail, $isPublished) {
        $sql = "UPDATE posts 
                SET title = :title, slug = :slug, excerpt = :excerpt, 
                    content = :content, thumbnail = :thumbnail, is_published = :is_published 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'thumbnail' => $thumbnail,
            'is_published' => $isPublished,
            'id' => $id
        ]);
    }

    // Xóa bài viết và hỗ trợ xóa file ảnh vật lý
    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
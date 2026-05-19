<?php
namespace Models;

use Core\Database;

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll() {
        $sql = "SELECT c1.*, c2.name as parent_name 
                FROM categories c1 
                LEFT JOIN categories c2 ON c1.parent_id = c2.id 
                ORDER BY c1.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $slug, $parentId) {
        $sql = "INSERT INTO categories (name, slug, parent_id) VALUES (:name, :slug, :parent_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => !empty($parentId) ? $parentId : null
        ]);
    }

    public function update($id, $name, $slug, $parentId) {
        $sql = "UPDATE categories SET name = :name, slug = :slug, parent_id = :parent_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => !empty($parentId) ? $parentId : null,
            'id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
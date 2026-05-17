<?php
namespace Models;

use Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByEmail($email) {
        $sql = "SELECT u.*, r.slug as role_slug 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($name, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        // Mặc định role_id = 3 (Khách hàng)
        $sql = "INSERT INTO users (name, email, password_hash, role_id) 
                VALUES (:name, :email, :password_hash, 3)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash
        ]);
    }

    public function updateProfile($id, $name, $phone, $address) {
        $sql = "UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'id' => $id
        ]);
    }

    public function updatePassword($email, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password_hash = :hash WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'hash' => $hash,
            'email' => $email
        ]);
    }
}
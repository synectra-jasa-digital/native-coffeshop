<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Authenticate user by username and password
     */
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = :username AND u.status = 'active'
        ");
        
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Don't return the password hash
            unset($user['password']);
            return $user;
        }
        
        return false;
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.name, u.username, u.email, u.status, u.role_id, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all users
     */
    public function getAll($roleId = null) {
        $sql = "
            SELECT u.id, u.name, u.username, u.email, u.status, u.role_id, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE 1=1
        ";
        $params = [];

        if ($roleId) {
            $sql .= " AND u.role_id = :role_id";
            $params[':role_id'] = $roleId;
        }

        $sql .= " ORDER BY u.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, name, username, email, password, status) 
            VALUES (:role_id, :name, :username, :email, :password, :status)
        ");
        return $stmt->execute([
            ':role_id' => $data['role_id'],
            ':name' => $data['name'],
            ':username' => $data['username'],
            ':email' => $data['email'] ?? null,
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':status' => $data['status'] ?? 'active'
        ]);
    }

    /**
     * Update an existing user
     */
    public function update($id, $data) {
        $sql = "UPDATE users SET role_id = :role_id, name = :name, username = :username, email = :email, status = :status";
        $params = [
            ':id' => $id,
            ':role_id' => $data['role_id'],
            ':name' => $data['name'],
            ':username' => $data['username'],
            ':email' => $data['email'] ?? null,
            ':status' => $data['status'] ?? 'active'
        ];

        // Jika password diisi, update password
        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete user
     */
    public function delete($id) {
        // Soft delete (update status) is better for users, but here is hard delete
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            // Usually fails due to foreign key constraints (e.g. shifts, logs)
            // Fallback to soft delete
            $stmt = $this->db->prepare("UPDATE users SET status = 'inactive' WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        }
    }

    /**
     * Get all roles
     */
    public function getRoles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
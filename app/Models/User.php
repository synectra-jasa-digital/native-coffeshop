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
}
<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Catat log aktivitas baru
     */
    public function log($userId, $action, $description) {
        $stmt = $this->db->prepare("
            INSERT INTO activity_logs (user_id, action, description, created_at) 
            VALUES (:user_id, :action, :description, NOW())
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':action' => $action,
            ':description' => $description
        ]);
    }

    /**
     * Ambil log aktivitas dengan filter
     */
    public function getAll($limit = 100, $filters = []) {
        $sql = "
            SELECT l.*, u.name as user_name, u.username 
            FROM activity_logs l 
            JOIN users u ON l.user_id = u.id 
            WHERE 1=1
        ";
        
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND l.user_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(l.created_at) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(l.created_at) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        if (!empty($filters['action'])) {
            $sql .= " AND l.action = :action";
            $params[':action'] = $filters['action'];
        }

        $sql .= " ORDER BY l.created_at DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
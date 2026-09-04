<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Table {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all tables
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tables ORDER BY table_number ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get available (empty) tables
     */
    public function getAvailable() {
        $stmt = $this->db->query("SELECT * FROM tables WHERE status = 'empty' ORDER BY table_number ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get table by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tables WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new table
     */
    public function create($tableNumber) {
        $stmt = $this->db->prepare("INSERT INTO tables (table_number, status) VALUES (:number, 'empty')");
        $stmt->execute([':number' => $tableNumber]);
        return $this->db->lastInsertId();
    }

    /**
     * Update table status
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE tables SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Delete a table
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tables WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
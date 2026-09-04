<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Table {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tables ORDER BY CAST(table_number AS UNSIGNED), table_number ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tables WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByNumber($number) {
        $stmt = $this->db->prepare("SELECT * FROM tables WHERE table_number = ?");
        $stmt->execute([$number]);
        return $stmt->fetch();
    }

    public function create($data) {
        // Generate unique token for QR code
        $token = bin2hex(random_bytes(16));
        
        $stmt = $this->db->prepare("INSERT INTO tables (table_number, qr_code, status) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['table_number'],
            $token, // We save a token, the full URL will be generated in the view
            $data['status'] ?? 'empty'
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tables SET table_number = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['table_number'],
            $data['status'],
            $id
        ]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE tables SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function regenerateQR($id) {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->db->prepare("UPDATE tables SET qr_code = ? WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public function delete($id) {
        // Check if table has active transactions (can't delete if occupied)
        $stmt = $this->db->prepare("SELECT status FROM tables WHERE id = ?");
        $stmt->execute([$id]);
        $table = $stmt->fetch();
        
        if ($table && $table['status'] === 'occupied') {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM tables WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

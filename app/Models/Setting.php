<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Setting {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get a setting value by key
     */
    public function get($key) {
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = :key");
        $stmt->execute([':key' => $key]);
        return $stmt->fetchColumn();
    }

    /**
     * Get all settings as key-value array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM settings ORDER BY setting_key ASC");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Set a setting value (insert or update)
     */
    public function set($key, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (:key, :value) 
            ON DUPLICATE KEY UPDATE setting_value = :value2
        ");
        return $stmt->execute([
            ':key' => $key,
            ':value' => $value,
            ':value2' => $value
        ]);
    }

    /**
     * Update multiple settings at once
     */
    public function updateMultiple($data) {
        $this->db->beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get store info for receipt
     */
    public function getStoreInfo() {
        return [
            'name' => $this->get('store_name') ?? 'Good Coffee',
            'address' => $this->get('store_address') ?? '',
            'phone' => $this->get('store_phone') ?? '',
            'footer' => $this->get('receipt_footer') ?? '',
        ];
    }

    /**
     * Get tax and service charge settings
     */
    public function getTaxSettings() {
        return [
            'tax_rate' => (float)($this->get('tax_rate') ?? 0),
            'is_tax_active' => (bool)($this->get('is_tax_active') ?? 0),
            'service_charge_rate' => (float)($this->get('service_charge_rate') ?? 0),
            'is_service_charge_active' => (bool)($this->get('is_service_charge_active') ?? 0),
        ];
    }
}
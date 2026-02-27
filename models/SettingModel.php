<?php
/**
 * Setting Model
 * Handles all settings-related database operations
 */

require_once __DIR__ . '/../core/Model.php';

class SettingModel extends Model {
    
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $fillable = ['setting_key', 'setting_value'];

    /**
     * Constructor - ensure settings table exists
     */
    public function __construct() {
        parent::__construct();
        $this->createTableIfNotExists();
    }

    /**
     * Create settings table if it doesn't exist
     */
    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->getConnection()->exec($sql);
        } catch (Exception $e) {
            error_log("Error creating settings table: " . $e->getMessage());
        }
    }

    /**
     * Get setting value by key
     */
    public function get($key, $default = null) {
        try {
            $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            return $result ? $result['setting_value'] : $default;
        } catch (PDOException $e) {
            error_log("Get setting error: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Set setting value
     */
    public function set($key, $value) {
        try {
            // Check if setting exists
            $sql = "SELECT id FROM {$this->table} WHERE setting_key = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$key]);
            $exists = $stmt->fetch();

            if ($exists) {
                // Update existing
                $sql = "UPDATE {$this->table} SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?";
                $stmt = $this->db->getConnection()->prepare($sql);
                return $stmt->execute([$value, $key]);
            } else {
                // Insert new
                $sql = "INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?)";
                $stmt = $this->db->getConnection()->prepare($sql);
                return $stmt->execute([$key, $value]);
            }
        } catch (PDOException $e) {
            error_log("Set setting error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all settings as key-value pair
     */
    public function getAll() {
        try {
            $sql = "SELECT setting_key, setting_value FROM {$this->table} ORDER BY setting_key ASC";
            $stmt = $this->db->getConnection()->query($sql);
            $results = $stmt->fetchAll();
            
            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        } catch (PDOException $e) {
            error_log("GetAll settings error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update or create setting
     */
    public function updateOrCreate($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * Get multiple settings by keys
     */
    public function getMultiple($keys) {
        if (empty($keys)) {
            return [];
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($keys), '?'));
            $sql = "SELECT setting_key, setting_value FROM {$this->table} 
                    WHERE setting_key IN ({$placeholders})";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute($keys);
            $results = $stmt->fetchAll();
            
            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            
            return $settings;
        } catch (PDOException $e) {
            error_log("GetMultiple settings error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Set multiple settings at once
     */
    public function setMultiple($settings) {
        $success = true;
        foreach ($settings as $key => $value) {
            if (!$this->set($key, $value)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Delete setting
     */
    public function delete($key) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE setting_key = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([$key]);
        } catch (PDOException $e) {
            error_log("Delete setting error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if setting exists
     */
    public function has($key) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE setting_key = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$key]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Has setting error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all settings with pagination - FIXED to match parent signature
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'setting_key', $direction = 'ASC') {
        try {
            $page = max(1, (int)$page);
            $perPage = max(1, (int)$perPage);
            $offset = ($page - 1) * $perPage;
            
            // Validate direction
            $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
            
            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
            $stmt = $this->db->getConnection()->query($countSql);
            $total = $stmt->fetch()['total'];
            
            // Get paginated data
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction} LIMIT ? OFFSET ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$perPage, $offset]);
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("Paginate settings error: " . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => 0
            ];
        }
    }

    /**
     * Search settings
     */
    public function search($keyword) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE setting_key LIKE ? OR setting_value LIKE ?
                    ORDER BY setting_key ASC";
            $keyword = "%{$keyword}%";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$keyword, $keyword]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Search settings error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Initialize default settings
     */
    public function initializeDefaults() {
        $defaults = [
            'hospital_name' => 'Deltota Divisional Hospital',
            'address' => 'Main Street, Deltota',
            'phone' => '081-1234567',
            'email' => 'info@deltotahospital.lk',
            'working_hours' => 'Mon-Fri: 8:00 AM - 8:00 PM, Sat: 8:00 AM - 2:00 PM',
            'appointment_duration' => '30',
            'max_appointments_per_day' => '20',
            'currency' => 'LKR',
            'timezone' => 'Asia/Colombo',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s'
        ];
        
        foreach ($defaults as $key => $value) {
            if (!$this->has($key)) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Override all method
     */
    public function all($orderBy = 'setting_key', $direction = 'ASC') {
        try {
            $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("All settings error: " . $e->getMessage());
            return [];
        }
    }
}
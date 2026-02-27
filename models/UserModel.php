<?php
/**
 * User Model - PLAIN TEXT PASSWORD VERSION
 * WARNING: This stores passwords in plain text - INSECURE!
 * Only for local testing!
 */

require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model {
    protected $table = 'users';
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        try {
            $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("FindByEmail Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("FindByUsername Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by email or username (for login)
     */
    public function findByEmailOrUsername($login) {
        try {
            $sql = "SELECT * FROM users WHERE email = ? OR username = ? LIMIT 1";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$login, $login]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("FindByEmailOrUsername Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new user with PLAIN TEXT password (INSECURE!)
     */
    public function createUser($data) {
        try {
            // ❌ NO HASHING - Store plain password directly
            $plain_password = $data['password']; // Just store as-is
            
            // Remove password fields from data array
            unset($data['password']);
            unset($data['confirm_password']);
            
            $sql = "INSERT INTO users 
                    (username, email, password_hash, full_name, phone, address, profile_image, role, is_active, created_at) 
                    VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $result = $stmt->execute([
                $data['username'],
                $data['email'],
                $plain_password, // ⚠️ Plain text password stored!
                $data['full_name'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['profile_image'] ?? 'default-avatar.png',
                $data['role'] ?? 'patient',
                $data['is_active'] ?? 1
            ]);
            
            if ($result) {
                return $this->db->getConnection()->lastInsertId();
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("CreateUser Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user password with PLAIN TEXT (INSECURE!)
     */
    public function updatePassword($userId, $newPassword) {
        try {
            // ❌ Store plain text password directly
            $sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([$newPassword, $userId]);
        } catch (PDOException $e) {
            error_log("UpdatePassword Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update last login time
     */
    public function updateLastLogin($userId) {
        try {
            $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("UpdateLastLogin Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all doctors
     */
    public function getAllDoctors() {
        try {
            $sql = "SELECT u.*, d.id as doctor_id, d.specialization, d.qualification, 
                           d.experience_years, d.license_number, d.consultation_fee,
                           d.available_days, d.available_time_start, d.available_time_end,
                           d.max_patients_per_day, d.bio, d.is_available,
                           dep.department_name, dep.id as department_id
                    FROM users u 
                    JOIN doctors d ON u.id = d.user_id 
                    LEFT JOIN departments dep ON d.department_id = dep.id
                    WHERE u.role = 'doctor' AND u.is_active = 1 
                    ORDER BY u.full_name ASC";
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("GetAllDoctors Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all patients
     */
    public function getAllPatients() {
        try {
            $sql = "SELECT u.*, p.* FROM users u 
                    JOIN patients p ON u.id = p.user_id 
                    WHERE u.role = 'patient' 
                    ORDER BY u.created_at DESC";
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("GetAllPatients Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all admins
     */
    public function getAllAdmins() {
        try {
            $sql = "SELECT * FROM users WHERE role = 'admin' ORDER BY full_name ASC";
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("GetAllAdmins Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Toggle user active status
     */
    public function toggleStatus($userId) {
        try {
            $user = $this->find($userId);
            if (!$user) return false;
            
            $newStatus = $user['is_active'] ? 0 : 1;
            
            $sql = "UPDATE users SET is_active = ? WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([$newStatus, $userId]);
            
        } catch (PDOException $e) {
            error_log("ToggleStatus Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($userId, $data) {
        try {
            $allowed = ['full_name', 'phone', 'address', 'profile_image'];
            $updates = [];
            $params = [];
            
            foreach ($allowed as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($updates)) return true;
            
            $params[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("UpdateProfile Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by ID
     */
    public function find($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Find Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users - matching parent signature
     */
    public function all($orderBy = 'created_at', $direction = 'DESC') {
        try {
            // Validate direction
            $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
            $orderBy = preg_replace('/[^a-zA-Z0-9_]/', '', $orderBy);
            
            $sql = "SELECT * FROM users ORDER BY $orderBy $direction";
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("All Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count users
     */
    public function count($where = '1', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE $where";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Search users
     */
    public function search($keyword) {
        try {
            $sql = "SELECT * FROM users 
                    WHERE full_name LIKE ? OR email LIKE ? OR username LIKE ? OR phone LIKE ?
                    ORDER BY full_name ASC";
            $keyword = "%{$keyword}%";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Search Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recent users
     */
    public function getRecent($limit = 10) {
        try {
            $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT " . (int)$limit;
            $stmt = $this->db->getConnection()->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("GetRecent Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verify password - DIRECT COMPARISON (INSECURE!)
     */
    public function verifyPassword($userId, $password) {
        try {
            $user = $this->find($userId);
            if ($user) {
                // ❌ Direct string comparison - NO password_verify!
                return ($password == $user['password_hash']);
            }
            return false;
        } catch (PDOException $e) {
            error_log("VerifyPassword Error: " . $e->getMessage());
            return false;
        }
    }
}
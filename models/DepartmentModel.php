<?php
/**
 * Department Model
 * Handles all department-related database operations
 * Location: /models/DepartmentModel.php
 */

class DepartmentModel extends Model {
    
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'department_name', 'description', 'head_doctor_id', 
        'floor_number', 'extension_number', 'is_active'
    ];

    /**
     * Get departments with head doctor details
     */
    public function getWithHeadDoctor() {
        $sql = "SELECT d.*, u.full_name as head_doctor_name
                FROM departments d
                LEFT JOIN users u ON d.head_doctor_id = u.id
                ORDER BY d.department_name ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get active departments
     */
    public function getActive() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY department_name ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get department with doctor count
     */
    public function getWithDoctorCount() {
        $sql = "SELECT d.*, 
                       COUNT(doc.id) as doctor_count,
                       u.full_name as head_doctor_name
                FROM departments d
                LEFT JOIN doctors doc ON d.id = doc.department_id
                LEFT JOIN users u ON d.head_doctor_id = u.id
                GROUP BY d.id
                ORDER BY d.department_name ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Check if department has doctors
     */
    public function hasDoctors($departmentId) {
        $count = $this->db->count(
            'doctors',
            'department_id = :dept_id',
            ['dept_id' => $departmentId]
        );
        return $count > 0;
    }

    /**
     * Get department statistics
     */
    public function getStats($departmentId) {
        $sql = "SELECT 
                    d.*,
                    COUNT(DISTINCT doc.id) as doctor_count,
                    COUNT(DISTINCT a.id) as appointment_count,
                    COUNT(DISTINCT CASE WHEN a.status = 'completed' THEN a.id END) as completed_appointments
                FROM departments d
                LEFT JOIN doctors doc ON d.id = doc.department_id
                LEFT JOIN appointments a ON doc.id = a.doctor_id
                WHERE d.id = :dept_id
                GROUP BY d.id";
        return $this->db->fetchOne($sql, ['dept_id' => $departmentId]);
    }

    /**
     * Get department by name
     */
    public function getByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE department_name = :name LIMIT 1";
        return $this->db->fetchOne($sql, ['name' => $name]);
    }

    /**
     * Search departments
     */
    public function search($keyword) {
        $sql = "SELECT d.*, u.full_name as head_doctor_name
                FROM departments d
                LEFT JOIN users u ON d.head_doctor_id = u.id
                WHERE d.department_name LIKE :keyword 
                   OR d.description LIKE :keyword
                   OR u.full_name LIKE :keyword
                ORDER BY d.department_name ASC";
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Override count method
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }

    /**
     * Override all method
     */
    public function all($orderBy = 'department_name', $direction = 'ASC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        return $this->db->fetchAll($sql);
    }
}
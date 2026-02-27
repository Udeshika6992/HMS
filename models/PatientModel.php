<?php
/**
 * Patient Model
 * Handles all patient-specific database operations
 * Location: /models/PatientModel.php
 */

class PatientModel extends Model {
    
    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'date_of_birth', 'gender', 'blood_group',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        'allergies', 'chronic_conditions', 'current_medications',
        'registration_date', 'registration_fee_paid'
    ];

    /**
     * Create patient record from user registration
     */
    public function createFromUser($userId, $data = []) {
        $patientData = [
            'user_id' => $userId,
            'registration_date' => date('Y-m-d'),
            'registration_fee_paid' => false
        ];

        // Add optional fields if provided
        $optionalFields = ['date_of_birth', 'gender', 'blood_group', 'allergies'];
        foreach ($optionalFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $patientData[$field] = $data[$field];
            }
        }

        return $this->create($patientData);
    }

    /**
     * Get patient by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.address, u.profile_image,
                       u.is_active, u.created_at as user_created_at
                FROM patients p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = :user_id";
        return $this->db->fetchOne($sql, ['user_id' => $userId]);
    }

    /**
     * Get patient with all details
     */
    public function getPatientWithDetails($patientId) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.address, u.profile_image,
                       u.is_active, u.created_at as user_created_at
                FROM patients p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.id = :patient_id";
        return $this->db->fetchOne($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient's medical history
     */
    public function getMedicalHistory($patientId) {
        $sql = "SELECT mr.*, u.full_name as doctor_name, d.specialization,
                       a.appointment_date, a.appointment_time
                FROM medical_records mr
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                LEFT JOIN appointments a ON mr.appointment_id = a.id
                WHERE mr.patient_id = :patient_id
                ORDER BY mr.record_date DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient's appointments
     */
    public function getAppointments($patientId, $status = null) {
        $sql = "SELECT a.*, u.full_name as doctor_name, d.specialization,
                       d.id as doctor_id
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                WHERE a.patient_id = :patient_id";
        
        $params = ['patient_id' => $patientId];
        
        if ($status) {
            $sql .= " AND a.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($patientId) {
        $sql = "SELECT a.*, u.full_name as doctor_name, d.specialization
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                WHERE a.patient_id = :patient_id 
                  AND a.appointment_date >= CURDATE()
                  AND a.status IN ('pending', 'confirmed')
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get past appointments
     */
    public function getPastAppointments($patientId) {
        $sql = "SELECT a.*, u.full_name as doctor_name, d.specialization,
                       mr.diagnosis, mr.prescriptions
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                LEFT JOIN medical_records mr ON a.id = mr.appointment_id
                WHERE a.patient_id = :patient_id 
                  AND (a.appointment_date < CURDATE() OR a.status = 'completed')
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient's prescriptions
     */
    public function getPrescriptions($patientId) {
        $sql = "SELECT p.*, u.full_name as doctor_name, d.specialization,
                       mr.record_date, mr.diagnosis
                FROM prescriptions p
                JOIN doctors d ON p.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                JOIN medical_records mr ON p.medical_record_id = mr.id
                WHERE p.patient_id = :patient_id
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient's vital signs history
     */
    public function getVitalsHistory($patientId, $limit = 10) {
        $sql = "SELECT pv.*, u.full_name as doctor_name,
                       a.appointment_date
                FROM patient_vitals pv
                LEFT JOIN doctors d ON pv.doctor_id = d.id
                LEFT JOIN users u ON d.user_id = u.id
                LEFT JOIN appointments a ON pv.appointment_id = a.id
                WHERE pv.patient_id = :patient_id
                ORDER BY pv.record_date DESC
                LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient's progress tracking data
     */
    public function getProgressData($patientId, $metricName = null) {
        $sql = "SELECT pt.*, u.full_name as doctor_name
                FROM progress_tracking pt
                JOIN doctors d ON pt.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                WHERE pt.patient_id = :patient_id";
        
        $params = ['patient_id' => $patientId];
        
        if ($metricName) {
            $sql .= " AND pt.metric_name = :metric_name";
            $params['metric_name'] = $metricName;
        }
        
        $sql .= " ORDER BY pt.tracking_date ASC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get patient statistics
     */
    public function getPatientStats($patientId) {
        $stats = [];
        
        // Total appointments
        $stats['total_appointments'] = $this->db->count(
            'appointments',
            'patient_id = :patient_id',
            ['patient_id' => $patientId]
        );
        
        // Completed appointments
        $stats['completed_appointments'] = $this->db->count(
            'appointments',
            'patient_id = :patient_id AND status = "completed"',
            ['patient_id' => $patientId]
        );
        
        // Upcoming appointments
        $stats['upcoming_appointments'] = $this->db->count(
            'appointments',
            'patient_id = :patient_id AND appointment_date >= CURDATE() AND status IN ("pending", "confirmed")',
            ['patient_id' => $patientId]
        );
        
        // Total medical records
        $stats['total_medical_records'] = $this->db->count(
            'medical_records',
            'patient_id = :patient_id',
            ['patient_id' => $patientId]
        );
        
        // Last visit date
        $lastVisit = $this->db->fetchOne(
            "SELECT MAX(appointment_date) as last_visit 
             FROM appointments 
             WHERE patient_id = :patient_id AND status = 'completed'",
            ['patient_id' => $patientId]
        );
        $stats['last_visit'] = $lastVisit['last_visit'] ?? 'No visits yet';
        
        return $stats;
    }

    /**
     * Update patient profile
     */
    public function updateProfile($patientId, $data) {
        $allowed = [
            'date_of_birth', 'gender', 'blood_group',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
            'allergies', 'chronic_conditions', 'current_medications'
        ];
        
        $updateData = [];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return true;
        }
        
        return $this->update($patientId, $updateData);
    }

    /**
     * Search patients
     */
    public function search($keyword) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.profile_image,
                       u.is_active, u.created_at
                FROM patients p
                JOIN users u ON p.user_id = u.id
                WHERE u.full_name LIKE :keyword 
                   OR u.email LIKE :keyword
                   OR u.phone LIKE :keyword
                   OR p.blood_group LIKE :keyword
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Get patients by blood group
     */
    public function getByBloodGroup($bloodGroup) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.profile_image
                FROM patients p
                JOIN users u ON p.user_id = u.id
                WHERE p.blood_group = :blood_group
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql, ['blood_group' => $bloodGroup]);
    }

    /**
     * Get recent patients
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.profile_image
                FROM patients p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC
                LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }

    /**
     * Get patient count by gender
     */
    public function countByGender() {
        $sql = "SELECT gender, COUNT(*) as count 
                FROM patients 
                WHERE gender IS NOT NULL 
                GROUP BY gender";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get patient count by blood group
     */
    public function countByBloodGroup() {
        $sql = "SELECT blood_group, COUNT(*) as count 
                FROM patients 
                WHERE blood_group IS NOT NULL 
                GROUP BY blood_group";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get monthly registration stats
     */
    public function getMonthlyRegistrations($year = null) {
        $year = $year ?: date('Y');
        
        $sql = "SELECT MONTH(created_at) as month, COUNT(*) as count
                FROM patients
                WHERE YEAR(created_at) = :year
                GROUP BY MONTH(created_at)
                ORDER BY month";
        return $this->db->fetchAll($sql, ['year' => $year]);
    }

    /**
     * Override paginate method
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'created_at', $direction = 'DESC') {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.profile_image,
                       u.is_active, u.created_at
                FROM patients p
                JOIN users u ON p.user_id = u.id
                ORDER BY {$orderBy} {$direction}";
        
        return $this->db->fetchPaginated($sql, [], $page, $perPage);
    }

    /**
     * Override count method
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
}
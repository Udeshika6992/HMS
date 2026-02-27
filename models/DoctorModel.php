<?php
/**
 * Doctor Model
 * Handles all doctor-specific database operations
 * Location: /models/DoctorModel.php
 */

class DoctorModel extends Model {
    
    protected $table = 'doctors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'department_id', 'specialization', 'qualification',
        'experience_years', 'license_number', 'consultation_fee',
        'available_days', 'available_time_start', 'available_time_end',
        'max_patients_per_day', 'bio', 'is_available'
    ];

    /**
     * Get doctor by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT d.*, u.full_name, u.email, u.phone, u.address, u.profile_image,
                       u.is_active, u.created_at as user_created_at,
                       dep.department_name
                FROM doctors d 
                JOIN users u ON d.user_id = u.id 
                LEFT JOIN departments dep ON d.department_id = dep.id
                WHERE d.user_id = :user_id";
        return $this->db->fetchOne($sql, ['user_id' => $userId]);
    }

    /**
     * Get doctor with all details
     */
    public function getDoctorWithDetails($doctorId) {
        $sql = "SELECT d.*, u.full_name, u.email, u.phone, u.address, u.profile_image,
                       u.is_active, u.created_at as user_created_at,
                       dep.department_name, dep.description as department_description
                FROM doctors d 
                JOIN users u ON d.user_id = u.id 
                LEFT JOIN departments dep ON d.department_id = dep.id
                WHERE d.id = :doctor_id";
        return $this->db->fetchOne($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get all available doctors
     */
    public function getAvailableDoctors($departmentId = null) {
        $sql = "SELECT d.*, u.full_name, u.email, u.phone, u.profile_image,
                       dep.department_name
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                LEFT JOIN departments dep ON d.department_id = dep.id
                WHERE d.is_available = 1 AND u.is_active = 1";
        
        $params = [];
        
        if ($departmentId) {
            $sql .= " AND d.department_id = :department_id";
            $params['department_id'] = $departmentId;
        }
        
        $sql .= " ORDER BY u.full_name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get doctors by department
     */
    public function getByDepartment($departmentId) {
        $sql = "SELECT d.*, u.full_name, u.email, u.phone, u.profile_image
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                WHERE d.department_id = :department_id AND d.is_available = 1
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql, ['department_id' => $departmentId]);
    }

    /**
     * Get doctor's appointments
     */
    public function getAppointments($doctorId, $date = null, $status = null) {
        $sql = "SELECT a.*, u.full_name as patient_name, u.phone as patient_phone,
                       p.date_of_birth, p.blood_group
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = :doctor_id";
        
        $params = ['doctor_id' => $doctorId];
        
        if ($date) {
            $sql .= " AND a.appointment_date = :date";
            $params['date'] = $date;
        }
        
        if ($status) {
            $sql .= " AND a.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get today's appointments
     */
    public function getTodayAppointments($doctorId) {
        return $this->getAppointments($doctorId, date('Y-m-d'));
    }

    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($doctorId) {
        $sql = "SELECT a.*, u.full_name as patient_name, u.phone as patient_phone,
                       p.date_of_birth, p.blood_group
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = :doctor_id 
                  AND a.appointment_date >= CURDATE()
                  AND a.status IN ('pending', 'confirmed')
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get doctor's patients
     */
    public function getPatients($doctorId) {
        $sql = "SELECT DISTINCT p.id, u.full_name, u.email, u.phone, u.profile_image,
                       p.date_of_birth, p.blood_group, p.allergies,
                       MAX(a.appointment_date) as last_visit
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = :doctor_id
                GROUP BY p.id
                ORDER BY last_visit DESC";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get patient details for doctor
     */
    public function getPatientDetails($doctorId, $patientId) {
        $sql = "SELECT p.*, u.full_name, u.email, u.phone, u.address, u.profile_image,
                       u.created_at as registered_date
                FROM patients p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :patient_id";
        return $this->db->fetchOne($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get patient medical history for doctor
     */
    public function getPatientMedicalHistory($patientId) {
        $sql = "SELECT mr.*, a.appointment_date, a.appointment_time,
                       d.id as doctor_id, u.full_name as doctor_name
                FROM medical_records mr
                LEFT JOIN appointments a ON mr.appointment_id = a.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                WHERE mr.patient_id = :patient_id
                ORDER BY mr.record_date DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Check if doctor is available at specific time
     */
    public function isAvailable($doctorId, $date, $time) {
        // Check if date is within available days
        $doctor = $this->find($doctorId);
        if (!$doctor) return false;
        
        $dayOfWeek = date('D', strtotime($date));
        $availableDays = explode(',', $doctor['available_days']);
        
        if (!in_array($dayOfWeek, $availableDays)) {
            return false;
        }
        
        // Check if time is within working hours
        $appointmentTime = strtotime($time);
        $startTime = strtotime($doctor['available_time_start']);
        $endTime = strtotime($doctor['available_time_end']);
        
        if ($appointmentTime < $startTime || $appointmentTime > $endTime) {
            return false;
        }
        
        // Check if already booked
        $count = $this->db->count(
            'appointments',
            'doctor_id = :doctor_id AND appointment_date = :date AND appointment_time = :time AND status NOT IN ("cancelled", "no_show")',
            ['doctor_id' => $doctorId, 'date' => $date, 'time' => $time]
        );
        
        return $count == 0;
    }

    /**
     * Get available time slots for a date
     */
    public function getAvailableTimeSlots($doctorId, $date) {
        $doctor = $this->find($doctorId);
        if (!$doctor) return [];
        
        $dayOfWeek = date('D', strtotime($date));
        $availableDays = explode(',', $doctor['available_days']);
        
        if (!in_array($dayOfWeek, $availableDays)) {
            return [];
        }
        
        // Generate time slots (30-minute intervals)
        $slots = [];
        $start = strtotime($doctor['available_time_start']);
        $end = strtotime($doctor['available_time_end']);
        
        // Get booked appointments
        $booked = $this->db->fetchAll(
            "SELECT appointment_time FROM appointments 
             WHERE doctor_id = :doctor_id AND appointment_date = :date 
             AND status NOT IN ('cancelled', 'no_show')",
            ['doctor_id' => $doctorId, 'date' => $date]
        );
        
        $bookedTimes = array_column($booked, 'appointment_time');
        
        for ($time = $start; $time < $end; $time += 1800) { // 30 minutes = 1800 seconds
            $timeStr = date('H:i:s', $time);
            if (!in_array($timeStr, $bookedTimes)) {
                $slots[] = date('h:i A', $time);
            }
        }
        
        return $slots;
    }

    /**
     * Get doctor statistics
     */
    public function getDoctorStats($doctorId) {
        $stats = [];
        
        // Total appointments
        $stats['total_appointments'] = $this->db->count(
            'appointments',
            'doctor_id = :doctor_id',
            ['doctor_id' => $doctorId]
        );
        
        // Today's appointments
        $stats['today_appointments'] = $this->db->count(
            'appointments',
            'doctor_id = :doctor_id AND appointment_date = CURDATE() AND status NOT IN ("cancelled", "no_show")',
            ['doctor_id' => $doctorId]
        );
        
        // Completed appointments
        $stats['completed_appointments'] = $this->db->count(
            'appointments',
            'doctor_id = :doctor_id AND status = "completed"',
            ['doctor_id' => $doctorId]
        );
        
        // Pending appointments
        $stats['pending_appointments'] = $this->db->count(
            'appointments',
            'doctor_id = :doctor_id AND appointment_date >= CURDATE() AND status IN ("pending", "confirmed")',
            ['doctor_id' => $doctorId]
        );
        
        // Total patients
        $result = $this->db->fetchOne(
            "SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = :doctor_id",
            ['doctor_id' => $doctorId]
        );
        $stats['total_patients'] = $result['count'] ?? 0;
        
        return $stats;
    }

    /**
     * Update doctor availability
     */
    public function updateAvailability($doctorId, $isAvailable) {
        return $this->update($doctorId, ['is_available' => $isAvailable]);
    }

    /**
     * Update doctor schedule
     */
    public function updateSchedule($doctorId, $data) {
        $allowed = ['available_days', 'available_time_start', 'available_time_end', 'max_patients_per_day'];
        $updateData = [];
        
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return true;
        }
        
        return $this->update($doctorId, $updateData);
    }

    /**
     * Search doctors
     */
    public function search($keyword) {
        $sql = "SELECT d.*, u.full_name, u.email, u.phone, dep.department_name
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                LEFT JOIN departments dep ON d.department_id = dep.id
                WHERE u.full_name LIKE :keyword 
                   OR d.specialization LIKE :keyword
                   OR dep.department_name LIKE :keyword
                   OR d.license_number LIKE :keyword
                ORDER BY u.full_name ASC";
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Get monthly appointment stats
     */
    public function getMonthlyStats($doctorId, $year = null) {
        $year = $year ?: date('Y');
        
        $sql = "SELECT MONTH(appointment_date) as month, 
                       COUNT(*) as total,
                       SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM appointments
                WHERE doctor_id = :doctor_id AND YEAR(appointment_date) = :year
                GROUP BY MONTH(appointment_date)
                ORDER BY month";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId, 'year' => $year]);
    }

    /**
     * Get recent patients
     */
    public function getRecentPatients($doctorId, $limit = 10) {
        $sql = "SELECT p.id, u.full_name, u.phone, u.profile_image,
                       MAX(a.appointment_date) as last_visit,
                       COUNT(a.id) as visit_count
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = :doctor_id
                GROUP BY p.id
                ORDER BY last_visit DESC
                LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Search patients for a doctor
     */
    public function searchPatients($doctorId, $keyword) {
        $sql = "SELECT DISTINCT p.id, u.full_name, u.email, u.phone, u.profile_image,
                       p.date_of_birth, p.blood_group,
                       MAX(a.appointment_date) as last_visit
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = :doctor_id
                  AND (u.full_name LIKE :keyword OR u.phone LIKE :keyword OR u.email LIKE :keyword)
                GROUP BY p.id
                ORDER BY last_visit DESC";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId, 'keyword' => "%{$keyword}%"]);
    }

    /**
     * Override count method to accept where clause
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
}
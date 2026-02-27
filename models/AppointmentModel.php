<?php
/**
 * Appointment Model
 * Handles all appointment-related database operations
 * Location: /models/AppointmentModel.php
 */

class AppointmentModel extends Model {
    
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_number', 'patient_id', 'doctor_id', 'appointment_date',
        'appointment_time', 'end_time', 'status', 'symptoms', 'diagnosis',
        'notes', 'follow_up_date', 'created_by', 'cancelled_by', 'cancellation_reason'
    ];

    /**
     * Generate appointment number
     */
    public function generateAppointmentNumber() {
        $date = date('Ymd');
        $lastAppointment = $this->db->fetchOne(
            "SELECT appointment_number FROM {$this->table} 
             WHERE appointment_number LIKE 'APT-{$date}-%' 
             ORDER BY id DESC LIMIT 1"
        );
        
        if ($lastAppointment) {
            $lastNumber = intval(substr($lastAppointment['appointment_number'], -5));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return "APT-{$date}-{$newNumber}";
    }

    /**
     * Create new appointment
     */
    public function create($data) {
        if (!isset($data['appointment_number'])) {
            $data['appointment_number'] = $this->generateAppointmentNumber();
        }
        return parent::create($data);
    }

    /**
     * Get appointment with patient and doctor details
     */
    public function getAppointmentWithDetails($id) {
        $sql = "SELECT a.*, 
                       p.id as patient_id, p.user_id as patient_user_id, 
                       pu.full_name as patient_name, pu.phone as patient_phone, pu.email as patient_email,
                       p.date_of_birth, p.blood_group, p.allergies,
                       d.id as doctor_id, d.user_id as doctor_user_id,
                       du.full_name as doctor_name, du.phone as doctor_phone, du.email as doctor_email,
                       d.specialization, dep.department_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                LEFT JOIN departments dep ON d.department_id = dep.id
                WHERE a.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
/**
 * Get appointments by doctor ID
 */
public function getByDoctorId($doctorId) {
    try {
        $sql = "SELECT a.*, 
                       p.user_id as patient_user_id,
                       pu.full_name as patient_name, 
                       pu.phone as patient_phone
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE a.doctor_id = ?
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getByDoctorId Error: " . $e->getMessage());
        return [];
    }
}
    /**
     * Check if time slot is available
     */
    public function checkAvailability($doctorId, $date, $time) {
        $count = $this->db->count(
            $this->table,
            'doctor_id = :doctor_id AND appointment_date = :date AND appointment_time = :time AND status NOT IN ("cancelled", "no_show")',
            ['doctor_id' => $doctorId, 'date' => $date, 'time' => $time]
        );
        
        return $count == 0;
    }

    /**
     * Get available time slots for a doctor on a specific date
     */
    public function getAvailableTimeSlots($doctorId, $date) {
        // Get doctor's working hours
        $doctor = $this->db->fetchOne(
            "SELECT available_time_start, available_time_end, max_patients_per_day 
             FROM doctors WHERE id = :id",
            ['id' => $doctorId]
        );
        
        if (!$doctor) {
            return [];
        }
        
        // Get booked appointments
        $booked = $this->db->fetchAll(
            "SELECT appointment_time FROM appointments 
             WHERE doctor_id = :doctor_id AND appointment_date = :date 
             AND status NOT IN ('cancelled', 'no_show')",
            ['doctor_id' => $doctorId, 'date' => $date]
        );
        
        $bookedTimes = array_column($booked, 'appointment_time');
        
        // Generate time slots
        $slots = [];
        $start = strtotime($doctor['available_time_start']);
        $end = strtotime($doctor['available_time_end']);
        
        for ($time = $start; $time < $end; $time += 1800) { // 30-minute intervals
            $timeStr = date('H:i:s', $time);
            if (!in_array($timeStr, $bookedTimes)) {
                $slots[] = date('h:i A', $time);
            }
        }
        
        return $slots;
    }

    /**
     * Update appointment status
     */
    public function updateStatus($id, $status, $notes = null) {
        $data = ['status' => $status];
        if ($notes) {
            $data['notes'] = $notes;
        }
        return $this->update($id, $data);
    }

    /**
     * Cancel appointment
     */
    public function cancel($id, $cancelledBy, $reason) {
        return $this->update($id, [
            'status' => 'cancelled',
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $reason
        ]);
    }

    /**
     * Get appointments by date range
     */
    public function getByDateRange($startDate, $endDate, $doctorId = null) {
        $sql = "SELECT a.*, 
                       pu.full_name as patient_name, pu.phone as patient_phone,
                       du.full_name as doctor_name, d.specialization
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                WHERE a.appointment_date BETWEEN :start_date AND :end_date";
        
        $params = ['start_date' => $startDate, 'end_date' => $endDate];
        
        if ($doctorId) {
            $sql .= " AND a.doctor_id = :doctor_id";
            $params['doctor_id'] = $doctorId;
        }
        
        $sql .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get today's appointments
     */
    public function getTodayAppointments($doctorId = null) {
        return $this->getByDateRange(date('Y-m-d'), date('Y-m-d'), $doctorId);
    }

    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($doctorId = null, $days = 7) {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->getByDateRange(date('Y-m-d'), $endDate, $doctorId);
    }

    /**
     * Get appointment statistics
     */
    public function getStats($startDate = null, $endDate = null) {
        if (!$startDate) {
            $startDate = date('Y-m-01'); // First day of current month
        }
        if (!$endDate) {
            $endDate = date('Y-m-t'); // Last day of current month
        }
        
        $sql = "SELECT 
                    DATE(appointment_date) as date,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM appointments
                WHERE appointment_date BETWEEN :start_date AND :end_date
                GROUP BY DATE(appointment_date)
                ORDER BY date ASC";
        return $this->db->fetchAll($sql, ['start_date' => $startDate, 'end_date' => $endDate]);
    }

    /**
     * Get monthly statistics
     */
    public function getMonthlyStats($year = null) {
        $year = $year ?: date('Y');
        
        $sql = "SELECT 
                    MONTH(appointment_date) as month,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM appointments
                WHERE YEAR(appointment_date) = :year
                GROUP BY MONTH(appointment_date)
                ORDER BY month";
        return $this->db->fetchAll($sql, ['year' => $year]);
    }

    /**
     * Override count method
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
}
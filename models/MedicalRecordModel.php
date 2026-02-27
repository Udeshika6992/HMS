<?php
/**
 * Medical Record Model
 * Handles all medical record-related database operations
 * Location: /models/MedicalRecordModel.php
 */

class MedicalRecordModel extends Model {
    
    protected $table = 'medical_records';
    protected $primaryKey = 'id';
    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'record_date',
        'visit_type', 'chief_complaint', 'symptoms', 'diagnosis',
        'treatment_plan', 'doctor_notes', 'follow_up_required',
        'follow_up_date', 'prescriptions', 'lab_tests', 'attachments',
        'is_confidential'
    ];

    /**
     * Get records by patient
     */
    public function getByPatient($patientId) {
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
     * Get records by doctor
     */
    public function getByDoctor($doctorId) {
        $sql = "SELECT mr.*, pu.full_name as patient_name, pu.phone as patient_phone
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                WHERE mr.doctor_id = :doctor_id
                ORDER BY mr.record_date DESC";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get record with all details
     */
    public function getWithDetails($id) {
        $sql = "SELECT mr.*, 
                       pu.full_name as patient_name, pu.phone as patient_phone, pu.email as patient_email,
                       p.date_of_birth, p.blood_group, p.allergies,
                       du.full_name as doctor_name, du.phone as doctor_phone,
                       d.specialization, d.license_number,
                       a.appointment_date, a.appointment_time
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                LEFT JOIN appointments a ON mr.appointment_id = a.id
                WHERE mr.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Get prescriptions for a record
     */
    public function getPrescriptions($recordId) {
        $sql = "SELECT * FROM prescriptions WHERE medical_record_id = :record_id";
        return $this->db->fetchAll($sql, ['record_id' => $recordId]);
    }

    /**
     * Add prescription to record
     */
    public function addPrescription($recordId, $data) {
        $data['medical_record_id'] = $recordId;
        return $this->db->insert('prescriptions', $data);
    }

    /**
     * Search medical records
     */
    public function search($keyword) {
        $sql = "SELECT mr.*, pu.full_name as patient_name, du.full_name as doctor_name
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                WHERE mr.diagnosis LIKE :keyword 
                   OR mr.symptoms LIKE :keyword
                   OR mr.doctor_notes LIKE :keyword
                   OR pu.full_name LIKE :keyword
                ORDER BY mr.record_date DESC";
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Get follow-up records
     */
    public function getFollowUps($date = null) {
        $date = $date ?: date('Y-m-d');
        
        $sql = "SELECT mr.*, pu.full_name as patient_name, pu.phone as patient_phone,
                       du.full_name as doctor_name
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                WHERE mr.follow_up_required = 1 
                  AND mr.follow_up_date <= :date
                  AND NOT EXISTS (
                      SELECT 1 FROM appointments a 
                      WHERE a.patient_id = mr.patient_id 
                      AND a.doctor_id = mr.doctor_id 
                      AND a.appointment_date >= mr.follow_up_date
                  )
                ORDER BY mr.follow_up_date ASC";
        return $this->db->fetchAll($sql, ['date' => $date]);
    }

    /**
     * Get recent records
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT mr.*, pu.full_name as patient_name, du.full_name as doctor_name
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                ORDER BY mr.record_date DESC
                LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }

    /**
     * Get records by date range
     */
    public function getByDateRange($startDate, $endDate, $doctorId = null, $patientId = null) {
        $sql = "SELECT mr.*, pu.full_name as patient_name, du.full_name as doctor_name
                FROM medical_records mr
                JOIN patients p ON mr.patient_id = p.id
                JOIN users pu ON p.user_id = pu.id
                JOIN doctors d ON mr.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                WHERE mr.record_date BETWEEN :start_date AND :end_date";
        
        $params = ['start_date' => $startDate, 'end_date' => $endDate];
        
        if ($doctorId) {
            $sql .= " AND mr.doctor_id = :doctor_id";
            $params['doctor_id'] = $doctorId;
        }
        
        if ($patientId) {
            $sql .= " AND mr.patient_id = :patient_id";
            $params['patient_id'] = $patientId;
        }
        
        $sql .= " ORDER BY mr.record_date DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get statistics for a patient
     */
    public function getPatientStats($patientId) {
        $sql = "SELECT 
                    COUNT(*) as total_visits,
                    COUNT(DISTINCT doctor_id) as total_doctors,
                    MIN(record_date) as first_visit,
                    MAX(record_date) as last_visit,
                    SUM(CASE WHEN follow_up_required = 1 THEN 1 ELSE 0 END) as pending_followups
                FROM medical_records
                WHERE patient_id = :patient_id";
        return $this->db->fetchOne($sql, ['patient_id' => $patientId]);
    }

    /**
     * Override count method
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
}
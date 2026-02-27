<?php
/**
 * Prescription Model
 * Handles all prescription-related database operations
 * Location: /models/PrescriptionModel.php
 */

class PrescriptionModel extends Model {
    
    protected $table = 'prescriptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'medical_record_id', 'patient_id', 'doctor_id',
        'medicine_name', 'dosage', 'frequency', 'duration',
        'instructions', 'quantity', 'refills', 'start_date',
        'end_date', 'is_active'
    ];

    /**
     * Get prescriptions by patient
     */
    public function getByPatient($patientId) {
        $sql = "SELECT p.*, u.full_name as doctor_name, d.specialization,
                       mr.record_date, mr.diagnosis
                FROM prescriptions p
                JOIN doctors d ON p.doctor_id = d.id
                JOIN users u ON d.user_id = u.id
                LEFT JOIN medical_records mr ON p.medical_record_id = mr.id
                WHERE p.patient_id = :patient_id
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get prescriptions by doctor
     */
    public function getByDoctor($doctorId) {
        $sql = "SELECT p.*, pu.full_name as patient_name
                FROM prescriptions p
                JOIN patients pt ON p.patient_id = pt.id
                JOIN users pu ON pt.user_id = pu.id
                WHERE p.doctor_id = :doctor_id
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get active prescriptions
     */
    public function getActivePrescriptions($patientId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE patient_id = :patient_id 
                AND is_active = 1 
                AND (end_date IS NULL OR end_date >= CURDATE())
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['patient_id' => $patientId]);
    }

    /**
     * Get prescription with details
     */
    public function getWithDetails($id) {
        $sql = "SELECT p.*, 
                       pu.full_name as patient_name, pu.phone as patient_phone,
                       du.full_name as doctor_name, d.specialization,
                       mr.record_date, mr.diagnosis
                FROM prescriptions p
                JOIN patients pt ON p.patient_id = pt.id
                JOIN users pu ON pt.user_id = pu.id
                JOIN doctors d ON p.doctor_id = d.id
                JOIN users du ON d.user_id = du.id
                LEFT JOIN medical_records mr ON p.medical_record_id = mr.id
                WHERE p.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
}
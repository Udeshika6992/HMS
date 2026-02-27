<?php
/**
 * Progress Tracking Model
 * Handles patient progress tracking
 * Location: /models/ProgressModel.php
 */

class ProgressModel extends Model {
    
    protected $table = 'progress_tracking';
    protected $primaryKey = 'id';
    protected $fillable = [
        'patient_id', 'doctor_id', 'tracking_date',
        'metric_name', 'metric_value', 'metric_unit', 'notes'
    ];

    /**
     * Get progress by patient
     */
    public function getByPatient($patientId, $metricName = null) {
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
     * Get progress by metric
     */
    public function getByMetric($patientId, $metricName, $limit = null) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE patient_id = :patient_id AND metric_name = :metric_name
                ORDER BY tracking_date DESC";
        
        $params = [
            'patient_id' => $patientId,
            'metric_name' => $metricName
        ];
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get latest progress value
     */
    public function getLatest($patientId, $metricName) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE patient_id = :patient_id AND metric_name = :metric_name
                ORDER BY tracking_date DESC LIMIT 1";
        return $this->db->fetchOne($sql, [
            'patient_id' => $patientId,
            'metric_name' => $metricName
        ]);
    }

    /**
     * Get progress statistics
     */
    public function getStats($patientId, $metricName) {
        $sql = "SELECT 
                    AVG(metric_value) as average,
                    MIN(metric_value) as minimum,
                    MAX(metric_value) as maximum,
                    COUNT(*) as readings
                FROM {$this->table} 
                WHERE patient_id = :patient_id AND metric_name = :metric_name";
        return $this->db->fetchOne($sql, [
            'patient_id' => $patientId,
            'metric_name' => $metricName
        ]);
    }
}
<?php
/**
 * Appointment Factory
 * Creates appointment objects with different statuses
 * Location: /factories/AppointmentFactory.php
 */

class AppointmentFactory {
    
    /**
     * Create a new appointment
     */
    public static function create($data = []) {
        $appointment = new stdClass();
        
        $appointment->appointment_number = self::generateAppointmentNumber();
        $appointment->patient_id = $data['patient_id'] ?? 0;
        $appointment->doctor_id = $data['doctor_id'] ?? 0;
        $appointment->appointment_date = $data['appointment_date'] ?? date('Y-m-d');
        $appointment->appointment_time = $data['appointment_time'] ?? '09:00:00';
        $appointment->end_time = $data['end_time'] ?? null;
        $appointment->status = $data['status'] ?? 'pending';
        $appointment->symptoms = $data['symptoms'] ?? '';
        $appointment->diagnosis = $data['diagnosis'] ?? '';
        $appointment->notes = $data['notes'] ?? '';
        $appointment->follow_up_date = $data['follow_up_date'] ?? null;
        $appointment->created_by = $data['created_by'] ?? null;
        $appointment->cancelled_by = $data['cancelled_by'] ?? null;
        $appointment->cancellation_reason = $data['cancellation_reason'] ?? '';
        
        $appointment->created_at = date('Y-m-d H:i:s');
        $appointment->updated_at = date('Y-m-d H:i:s');
        
        return $appointment;
    }
    
    /**
     * Create a pending appointment
     */
    public static function createPending($data = []) {
        $data['status'] = 'pending';
        return self::create($data);
    }
    
    /**
     * Create a confirmed appointment
     */
    public static function createConfirmed($data = []) {
        $data['status'] = 'confirmed';
        return self::create($data);
    }
    
    /**
     * Create a completed appointment
     */
    public static function createCompleted($data = []) {
        $data['status'] = 'completed';
        $appointment = self::create($data);
        
        // Set end time to 30 minutes after start time
        if ($appointment->appointment_time) {
            $start = strtotime($appointment->appointment_time);
            $appointment->end_time = date('H:i:s', $start + 1800); // +30 minutes
        }
        
        return $appointment;
    }
    
    /**
     * Create a cancelled appointment
     */
    public static function createCancelled($data = [], $reason = '') {
        $data['status'] = 'cancelled';
        $data['cancellation_reason'] = $reason;
        return self::create($data);
    }
    
    /**
     * Create a no-show appointment
     */
    public static function createNoShow($data = []) {
        $data['status'] = 'no_show';
        return self::create($data);
    }
    
    /**
     * Generate unique appointment number
     */
    private static function generateAppointmentNumber() {
        $prefix = 'APT';
        $date = date('Ymd');
        $random = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        return $prefix . '-' . $date . '-' . $random;
    }
    
    /**
     * Create appointment from array (for database results)
     */
    public static function createFromArray($data) {
        return self::create($data);
    }
    
    /**
     * Validate appointment data
     */
    public static function validate($data) {
        $errors = [];
        
        // Required fields
        $required = ['patient_id', 'doctor_id', 'appointment_date', 'appointment_time'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        // Date validation
        if (!empty($data['appointment_date']) && strtotime($data['appointment_date']) < strtotime('today')) {
            $errors['appointment_date'] = 'Appointment date cannot be in the past';
        }
        
        // Time validation
        if (!empty($data['appointment_time']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $data['appointment_time'])) {
            $errors['appointment_time'] = 'Invalid time format';
        }
        
        // Status validation
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'];
        if (!empty($data['status']) && !in_array($data['status'], $validStatuses)) {
            $errors['status'] = 'Invalid status';
        }
        
        return $errors;
    }
    
    /**
     * Calculate appointment end time
     */
    public static function calculateEndTime($startTime, $duration = 30) {
        $start = strtotime($startTime);
        return date('H:i:s', $start + ($duration * 60));
    }
    
    /**
     * Check if appointment is in the future
     */
    public static function isUpcoming($appointment) {
        $appointmentDateTime = strtotime($appointment->appointment_date . ' ' . $appointment->appointment_time);
        return $appointmentDateTime > time();
    }
    
    /**
     * Get appointment status badge class
     */
    public static function getStatusBadgeClass($status) {
        switch ($status) {
            case 'pending':
                return 'warning';
            case 'confirmed':
                return 'success';
            case 'completed':
                return 'info';
            case 'cancelled':
                return 'secondary';
            case 'no_show':
                return 'danger';
            default:
                return 'secondary';
        }
    }
    
    /**
     * Get appointment status text
     */
    public static function getStatusText($status) {
        switch ($status) {
            case 'pending':
                return 'Pending';
            case 'confirmed':
                return 'Confirmed';
            case 'completed':
                return 'Completed';
            case 'cancelled':
                return 'Cancelled';
            case 'no_show':
                return 'No Show';
            default:
                return ucfirst($status);
        }
    }
    
    /**
     * Convert appointment to array for database
     */
    public static function toArray($appointment) {
        return (array) $appointment;
    }
}
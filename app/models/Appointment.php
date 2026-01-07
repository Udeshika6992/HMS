<?php
/**
 * ------------------------------------------------------------
 * Appointment.php (Model)
 * ------------------------------------------------------------
 * Handles all database operations related to appointments.
 * Works with AppointmentController.php and follows MVC pattern.
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/Database.php';

class Appointment {
    private $conn;

    /**
     * Constructor — establish DB connection using Singleton
     */
    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // ------------------------------------------------------------
    // 🔹 CREATE — Add a new appointment
    // ------------------------------------------------------------
    public function addAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $description) {
        try {
            $query = $this->conn->prepare("
                INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, description)
                VALUES (:patient_id, :doctor_id, :appointment_date, :appointment_time, :description)
            ");
            $query->bindParam(':patient_id', $patient_id);
            $query->bindParam(':doctor_id', $doctor_id);
            $query->bindParam(':appointment_date', $appointment_date);
            $query->bindParam(':appointment_time', $appointment_time);
            $query->bindParam(':description', $description);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error adding appointment: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------
    // 🔹 READ — Get all appointments
    // ------------------------------------------------------------
    public function getAllAppointments() {
        try {
            $stmt = $this->conn->prepare("
                SELECT a.id, 
                       p.name AS patient_name, 
                       d.name AS doctor_name, 
                       a.appointment_date, 
                       a.appointment_time, 
                       a.description, 
                       a.created_at
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                ORDER BY a.appointment_date DESC, a.appointment_time DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching appointments: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------
    // 🔹 READ — Get a single appointment by ID
    // ------------------------------------------------------------
    public function getAppointmentById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM appointments WHERE id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching appointment: " . $e->getMessage());
            return null;
        }
    }

    // ------------------------------------------------------------
    // 🔹 UPDATE — Edit appointment details
    // ------------------------------------------------------------
    public function updateAppointment($id, $patient_id, $doctor_id, $appointment_date, $appointment_time, $description) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE appointments 
                SET patient_id = :patient_id, 
                    doctor_id = :doctor_id, 
                    appointment_date = :appointment_date,
                    appointment_time = :appointment_time,
                    description = :description
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':patient_id', $patient_id);
            $stmt->bindParam(':doctor_id', $doctor_id);
            $stmt->bindParam(':appointment_date', $appointment_date);
            $stmt->bindParam(':appointment_time', $appointment_time);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating appointment: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------
    // 🔹 DELETE — Remove appointment
    // ------------------------------------------------------------
    public function deleteAppointment($id) {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM appointments WHERE id = :id
            ");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting appointment: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------
    // 🔹 FILTER — Search appointments by patient/doctor name or date
    // ------------------------------------------------------------
    public function filterAppointments($searchTerm) {
        try {
            $stmt = $this->conn->prepare("
                SELECT a.id, 
                       p.name AS patient_name, 
                       d.name AS doctor_name, 
                       a.appointment_date, 
                       a.appointment_time, 
                       a.description
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                WHERE p.name LIKE :search OR d.name LIKE :search OR a.appointment_date LIKE :search
                ORDER BY a.appointment_date DESC
            ");
            $term = "%" . $searchTerm . "%";
            $stmt->bindParam(':search', $term);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error filtering appointments: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------
    // 🔹 DASHBOARD — Get total appointment count (for admin stats)
    // ------------------------------------------------------------
    public function getDashboardAppointments() {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total_appointments FROM appointments
            ");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['total_appointments'] : 0;
        } catch (PDOException $e) {
            error_log("Error counting appointments: " . $e->getMessage());
            return 0;
        }
    }
}
?>

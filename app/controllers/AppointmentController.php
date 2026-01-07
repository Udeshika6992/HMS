<?php
/**
 * ------------------------------------------------------------
 * AppointmentController.php
 * ------------------------------------------------------------
 * Handles CRUD operations for patient appointments
 * Works with app/models/Appointment.php
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/../models/Appointment.php';

class AppointmentController {
    private $appointmentModel;

    /**
     * Constructor — initialize Appointment model
     */
    public function __construct() {
        $this->appointmentModel = new Appointment();
    }

    // ------------------------------------------------------------
    // 🔹 ADD NEW APPOINTMENT
    // ------------------------------------------------------------
    public function addAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $description) {
        try {
            if (empty($patient_id) || empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
                return "<div class='alert alert-warning'>⚠️ All fields are required!</div>";
            }

            $result = $this->appointmentModel->addAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $description);

            if ($result) {
                return "<div class='alert alert-success'>✅ Appointment added successfully!</div>";
            } else {
                return "<div class='alert alert-danger'>❌ Failed to add appointment.</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 GET ALL APPOINTMENTS
    // ------------------------------------------------------------
    public function getAllAppointments() {
        try {
            return $this->appointmentModel->getAllAppointments();
        } catch (Exception $e) {
            return [];
        }
    }

    // ------------------------------------------------------------
    // 🔹 GET SINGLE APPOINTMENT BY ID
    // ------------------------------------------------------------
    public function getAppointmentById($id) {
        try {
            return $this->appointmentModel->getAppointmentById($id);
        } catch (Exception $e) {
            return null;
        }
    }

    // ------------------------------------------------------------
    // 🔹 UPDATE APPOINTMENT
    // ------------------------------------------------------------
    public function updateAppointment($id, $patient_id, $doctor_id, $appointment_date, $appointment_time, $description) {
        try {
            if (empty($id) || empty($patient_id) || empty($doctor_id)) {
                return "<div class='alert alert-warning'>⚠️ Missing required fields.</div>";
            }

            $result = $this->appointmentModel->updateAppointment($id, $patient_id, $doctor_id, $appointment_date, $appointment_time, $description);

            if ($result) {
                return "<div class='alert alert-success'>✅ Appointment updated successfully!</div>";
            } else {
                return "<div class='alert alert-danger'>❌ Failed to update appointment.</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 DELETE APPOINTMENT
    // ------------------------------------------------------------
    public function deleteAppointment($id) {
        try {
            $result = $this->appointmentModel->deleteAppointment($id);

            if ($result) {
                return "<div class='alert alert-success'>🗑️ Appointment deleted successfully!</div>";
            } else {
                return "<div class='alert alert-danger'>❌ Failed to delete appointment.</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error deleting appointment: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 GET APPOINTMENTS FOR DASHBOARD STATISTICS
    // ------------------------------------------------------------
    public function getDashboardAppointments() {
        try {
            return $this->appointmentModel->getDashboardAppointments();
        } catch (Exception $e) {
            return [];
        }
    }

    // ------------------------------------------------------------
    // 🔹 FILTER APPOINTMENTS BY PATIENT OR DOCTOR
    // ------------------------------------------------------------
    public function filterAppointments($searchTerm) {
        try {
            return $this->appointmentModel->filterAppointments($searchTerm);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>

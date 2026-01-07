<?php
/**
 * PatientController.php
 * ----------------------------------------------------------
 * Handles all patient-related actions for the HMS system.
 * Works as the middle layer between the View and Model.
 * ----------------------------------------------------------
 */

require_once __DIR__ . '/../models/Patient.php';

class PatientController {
    private $patientModel;

    /**
     * Constructor — initializes the Patient model
     */
    public function __construct() {
        $this->patientModel = new Patient();
    }

    // ------------------------------------------------------------------
    // 🔹 GET ALL PATIENTS
    // ------------------------------------------------------------------
    public function getAllPatients() {
        try {
            return $this->patientModel->getAllPatients();
        } catch (Exception $e) {
            error_log("Error fetching patients: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------------
    // 🔹 GET PATIENT BY ID
    // ------------------------------------------------------------------
    public function getPatientById($id) {
        try {
            return $this->patientModel->getPatientById($id);
        } catch (Exception $e) {
            error_log("Error fetching patient by ID: " . $e->getMessage());
            return null;
        }
    }

    // ------------------------------------------------------------------
    // ➕ ADD NEW PATIENT
    // ------------------------------------------------------------------
    public function addPatient($name, $email, $phone, $gender, $age, $address, $disease) {
        if (empty($name) || empty($email) || empty($phone)) {
            return "⚠️ Name, email, and phone are required fields!";
        }

        try {
            $exists = $this->patientModel->findByEmail($email);
            if ($exists) {
                return "⚠️ A patient with this email already exists!";
            }

            $result = $this->patientModel->addPatient($name, $email, $phone, $gender, $age, $address, $disease);
            return $result ? "✅ Patient added successfully!" : "❌ Failed to add patient.";
        } catch (Exception $e) {
            error_log("Error adding patient: " . $e->getMessage());
            return "❌ An error occurred while adding the patient.";
        }
    }

    // ------------------------------------------------------------------
    // ✏️ UPDATE PATIENT
    // ------------------------------------------------------------------
    public function updatePatient($id, $name, $email, $phone, $gender, $age, $address, $disease) {
        if (empty($id) || empty($name) || empty($email)) {
            return "⚠️ ID, name, and email are required!";
        }

        try {
            $result = $this->patientModel->updatePatient($id, $name, $email, $phone, $gender, $age, $address, $disease);
            return $result ? "✏️ Patient updated successfully!" : "❌ Failed to update patient.";
        } catch (Exception $e) {
            error_log("Error updating patient: " . $e->getMessage());
            return "❌ An error occurred while updating patient.";
        }
    }

    // ------------------------------------------------------------------
    // 🗑️ DELETE PATIENT
    // ------------------------------------------------------------------
    public function deletePatient($id) {
        if (empty($id)) {
            return "⚠️ Invalid patient ID!";
        }

        try {
            $result = $this->patientModel->deletePatient($id);
            return $result ? "🗑️ Patient deleted successfully!" : "❌ Failed to delete patient.";
        } catch (Exception $e) {
            error_log("Error deleting patient: " . $e->getMessage());
            return "❌ An error occurred while deleting the patient.";
        }
    }

    // ------------------------------------------------------------------
    // 🔍 SEARCH PATIENTS
    // ------------------------------------------------------------------
    public function searchPatients($keyword) {
        try {
            return $this->patientModel->searchPatients($keyword);
        } catch (Exception $e) {
            error_log("Error searching patients: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------------
    // 📊 GET PATIENT PROGRESS (AI Integration Placeholder)
    // ------------------------------------------------------------------
    public function getPatientProgress($id) {
        try {
            return $this->patientModel->getPatientProgress($id);
        } catch (Exception $e) {
            error_log("Error fetching patient progress: " . $e->getMessage());
            return null;
        }
    }
}
?>

<?php
/**
 * DoctorController.php
 * -----------------------------------------------------
 * Controller for managing doctors in the HMS system.
 * Communicates with Doctor model to perform CRUD.
 * -----------------------------------------------------
 */

require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Department.php';

class DoctorController {
    private $doctorModel;
    private $departmentModel;

    /**
     * Constructor — initializes doctor and department models
     */
    public function __construct() {
        $this->doctorModel = new Doctor();
        $this->departmentModel = new Department();
    }

    // ------------------------------------------------------------------
    // 🔹 Get All Doctors
    // ------------------------------------------------------------------
    public function getAllDoctors() {
        try {
            return $this->doctorModel->getAllDoctors();
        } catch (Exception $e) {
            error_log("Error fetching doctors: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------------
    // 🔹 Get All Departments (for dropdown selection)
    // ------------------------------------------------------------------
    public function getDepartments() {
        return $this->departmentModel->getAllDepartments();
    }

    // ------------------------------------------------------------------
    // ➕ Add New Doctor
    // ------------------------------------------------------------------
    public function addDoctor($name, $email, $phone, $department_id) {
        if (empty($name) || empty($email) || empty($phone) || empty($department_id)) {
            return "⚠️ Please fill all required fields.";
        }

        try {
            $existing = $this->doctorModel->findByEmail($email);
            if ($existing) {
                return "⚠️ Doctor with this email already exists!";
            }

            $result = $this->doctorModel->addDoctor($name, $email, $phone, $department_id);
            return $result ? "✅ Doctor added successfully!" : "❌ Failed to add doctor!";
        } catch (Exception $e) {
            error_log("Error adding doctor: " . $e->getMessage());
            return "❌ Error adding doctor!";
        }
    }

    // ------------------------------------------------------------------
    // ✏️ Update Doctor
    // ------------------------------------------------------------------
    public function updateDoctor($id, $name, $email, $phone, $department_id) {
        if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($department_id)) {
            return "⚠️ All fields are required.";
        }

        try {
            $result = $this->doctorModel->updateDoctor($id, $name, $email, $phone, $department_id);
            return $result ? "✏️ Doctor updated successfully!" : "❌ Failed to update doctor!";
        } catch (Exception $e) {
            error_log("Error updating doctor: " . $e->getMessage());
            return "❌ Error updating doctor!";
        }
    }

    // ------------------------------------------------------------------
    // 🗑️ Delete Doctor
    // ------------------------------------------------------------------
    public function deleteDoctor($id) {
        if (empty($id)) {
            return "⚠️ Invalid doctor ID.";
        }

        try {
            $result = $this->doctorModel->deleteDoctor($id);
            return $result ? "🗑️ Doctor deleted successfully!" : "❌ Failed to delete doctor!";
        } catch (Exception $e) {
            error_log("Error deleting doctor: " . $e->getMessage());
            return "❌ Error deleting doctor!";
        }
    }

    // ------------------------------------------------------------------
    // 🔍 Get Doctor by ID
    // ------------------------------------------------------------------
    public function getDoctorById($id) {
        if (empty($id)) {
            return null;
        }

        try {
            return $this->doctorModel->getDoctorById($id);
        } catch (Exception $e) {
            error_log("Error fetching doctor by ID: " . $e->getMessage());
            return null;
        }
    }
}
?>

<?php
/**
 * DepartmentController.php
 * --------------------------
 * Controller for managing departments.
 * Works with the Department model.
 */

require_once __DIR__ . '/../models/Department.php';

class DepartmentController {
    private $departmentModel;

    /**
     * Constructor - initializes model
     */
    public function __construct() {
        $this->departmentModel = new Department();
    }

    // ------------------------------------------------------------------
    // 🧩 GET ALL DEPARTMENTS
    // ------------------------------------------------------------------
    public function getAllDepartments() {
        try {
            return $this->departmentModel->getAllDepartments();
        } catch (Exception $e) {
            error_log("Error fetching departments: " . $e->getMessage());
            return [];
        }
    }

    // ------------------------------------------------------------------
    // ➕ ADD NEW DEPARTMENT
    // ------------------------------------------------------------------
    public function addDepartment($department_name) {
        if (empty($department_name)) {
            return false;
        }

        try {
            return $this->departmentModel->addDepartment($department_name);
        } catch (Exception $e) {
            error_log("Error adding department: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------------
    // ✏️ UPDATE DEPARTMENT
    // ------------------------------------------------------------------
    public function updateDepartment($id, $department_name) {
        if (empty($id) || empty($department_name)) {
            return false;
        }

        try {
            return $this->departmentModel->updateDepartment($id, $department_name);
        } catch (Exception $e) {
            error_log("Error updating department: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------------
    // 🗑️ DELETE DEPARTMENT
    // ------------------------------------------------------------------
    public function deleteDepartment($id) {
        if (empty($id)) {
            return false;
        }

        try {
            return $this->departmentModel->deleteDepartment($id);
        } catch (Exception $e) {
            error_log("Error deleting department: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------------
    // 🔍 GET SINGLE DEPARTMENT (optional)
    // ------------------------------------------------------------------
    public function getDepartmentById($id) {
        if (empty($id)) {
            return null;
        }

        try {
            return $this->departmentModel->getDepartmentById($id);
        } catch (Exception $e) {
            error_log("Error getting department by ID: " . $e->getMessage());
            return null;
        }
    }
}
?>

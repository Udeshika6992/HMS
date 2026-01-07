<?php
/**
 * Department.php
 * -------------------------------------------------------
 * MODEL LAYER for handling Department data in HMS
 * -------------------------------------------------------
 * This class interacts with the 'departments' table and
 * supports Create, Read, Update, Delete (CRUD) operations.
 */

require_once __DIR__ . '/Database.php';

class Department {
    private $conn;

    /**
     * Constructor — establishes a database connection using Singleton pattern
     */
    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // ------------------------------------------------------------------
    // 🔹 GET ALL DEPARTMENTS
    // ------------------------------------------------------------------
    public function getAllDepartments() {
    $stmt = $this->conn->prepare("SELECT id, name, description FROM departments ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // ------------------------------------------------------------------
    // 🔹 GET DEPARTMENT BY ID
    // ------------------------------------------------------------------
    public function getDepartmentById($id) {
        $query = "SELECT * FROM departments WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 🔹 ADD NEW DEPARTMENT
    // ------------------------------------------------------------------
    public function addDepartment($department_name) {
        // Prevent duplicates
        $check = $this->conn->prepare("SELECT * FROM departments WHERE department_name = :name");
        $check->bindParam(':name', $department_name);
        $check->execute();
        if ($check->rowCount() > 0) {
            return false; // already exists
        }

        $stmt = $this->conn->prepare("INSERT INTO departments (department_name) VALUES (:name)");
        $stmt->bindParam(':name', $department_name);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔹 UPDATE DEPARTMENT
    // ------------------------------------------------------------------
    public function updateDepartment($id, $department_name) {
        $stmt = $this->conn->prepare("UPDATE departments SET department_name = :name WHERE id = :id");
        $stmt->bindParam(':name', $department_name);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔹 DELETE DEPARTMENT
    // ------------------------------------------------------------------
    public function deleteDepartment($id) {
        $stmt = $this->conn->prepare("DELETE FROM departments WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔹 SEARCH DEPARTMENT (optional enhancement)
    // ------------------------------------------------------------------
    public function searchDepartments($keyword) {
        $query = "SELECT * FROM departments 
                  WHERE department_name LIKE :keyword OR id LIKE :keyword
                  ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $search = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

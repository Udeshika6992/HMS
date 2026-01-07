<?php
/**
 * Doctor.php
 * -------------------------------------------------------
 * MODEL LAYER — Handles all doctor data operations.
 * -------------------------------------------------------
 * This class connects with the 'doctors' table in the
 * database and provides CRUD methods.
 */

require_once __DIR__ . '/Database.php';

class Doctor {
    private $conn;

    /**
     * Constructor — uses Singleton pattern to connect to DB
     */
    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // ------------------------------------------------------------------
    // 🔹 GET ALL DOCTORS (with department name)
    // ------------------------------------------------------------------
    public function getAllDoctors() {
        $query = "
            SELECT d.id, d.name, d.email, d.phone, 
                   dp.department_name AS department
            FROM doctors d
            LEFT JOIN departments dp ON d.department_id = dp.id
            ORDER BY d.id DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 🔹 GET DOCTOR BY ID
    // ------------------------------------------------------------------
    public function getDoctorById($id) {
        $query = "SELECT * FROM doctors WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 🔹 FIND DOCTOR BY EMAIL
    // ------------------------------------------------------------------
    public function findByEmail($email) {
        $query = "SELECT * FROM doctors WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // ➕ ADD NEW DOCTOR
    // ------------------------------------------------------------------
    public function addDoctor($name, $email, $phone, $department_id) {
        // Prevent duplicates by checking existing email
        $check = $this->conn->prepare("SELECT * FROM doctors WHERE email = :email");
        $check->bindParam(':email', $email);
        $check->execute();
        if ($check->rowCount() > 0) {
            return false; // doctor already exists
        }

        $stmt = $this->conn->prepare("
            INSERT INTO doctors (name, email, phone, department_id)
            VALUES (:name, :email, :phone, :department_id)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':department_id', $department_id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // ✏️ UPDATE DOCTOR DETAILS
    // ------------------------------------------------------------------
    public function updateDoctor($id, $name, $email, $phone, $department_id) {
        $stmt = $this->conn->prepare("
            UPDATE doctors 
            SET name = :name, email = :email, phone = :phone, department_id = :department_id
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':department_id', $department_id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🗑️ DELETE DOCTOR
    // ------------------------------------------------------------------
    public function deleteDoctor($id) {
        $stmt = $this->conn->prepare("DELETE FROM doctors WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔍 SEARCH DOCTORS (optional)
    // ------------------------------------------------------------------
    public function searchDoctors($keyword) {
        $query = "
            SELECT d.id, d.name, d.email, d.phone, dp.department_name AS department
            FROM doctors d
            LEFT JOIN departments dp ON d.department_id = dp.id
            WHERE d.name LIKE :keyword OR d.email LIKE :keyword OR dp.department_name LIKE :keyword
            ORDER BY d.id DESC
        ";
        $stmt = $this->conn->prepare($query);
        $search = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<?php
/**
 * Patient.php
 * -------------------------------------------------------
 * MODEL LAYER for handling Patient data in HMS
 * -------------------------------------------------------
 * Handles CRUD operations for patients and links to 
 * progress data for health tracking and analytics.
 */

require_once __DIR__ . '/Database.php';

class Patient {
    private $conn;

    /**
     * Constructor — creates a DB connection using Singleton
     */
    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // ------------------------------------------------------------------
    // 🔹 AUTO-GENERATE UNIQUE PATIENT ID (e.g. P000001)
    // ------------------------------------------------------------------
    private function generatePatientID() {
        $query = "SELECT patient_id FROM patients ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last) {
            $num = intval(substr($last['patient_id'], 1)) + 1;
            return "P" . str_pad($num, 6, "0", STR_PAD_LEFT);
        } else {
            return "P000001";
        }
    }

    // ------------------------------------------------------------------
    // ➕ ADD NEW PATIENT
    // ------------------------------------------------------------------
    public function addPatient($name, $email, $phone, $gender, $age, $address, $disease) {
        $patient_id = $this->generatePatientID();

        $stmt = $this->conn->prepare("
            INSERT INTO patients (patient_id, name, email, phone, gender, age, address, disease)
            VALUES (:patient_id, :name, :email, :phone, :gender, :age, :address, :disease)
        ");

        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':disease', $disease);

        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔹 GET ALL PATIENTS
    // ------------------------------------------------------------------
    public function getAllPatients() {
        $stmt = $this->conn->prepare("SELECT * FROM patients ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 🔹 GET PATIENT BY ID
    // ------------------------------------------------------------------
    public function getPatientById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM patients WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 🔹 FIND PATIENT BY EMAIL
    // ------------------------------------------------------------------
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM patients WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // ✏️ UPDATE PATIENT DETAILS
    // ------------------------------------------------------------------
    public function updatePatient($id, $name, $email, $phone, $gender, $age, $address, $disease) {
        $stmt = $this->conn->prepare("
            UPDATE patients
            SET name = :name, email = :email, phone = :phone, gender = :gender,
                age = :age, address = :address, disease = :disease
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':disease', $disease);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🗑️ DELETE PATIENT
    // ------------------------------------------------------------------
    public function deletePatient($id) {
        $stmt = $this->conn->prepare("DELETE FROM patients WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ------------------------------------------------------------------
    // 🔍 SEARCH PATIENTS (By Name, ID, or Email)
    // ------------------------------------------------------------------
    public function searchPatients($keyword) {
        $query = "
            SELECT * FROM patients 
            WHERE name LIKE :keyword OR email LIKE :keyword OR patient_id LIKE :keyword
            ORDER BY id DESC
        ";
        $stmt = $this->conn->prepare($query);
        $search = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // 📊 GET PATIENT PROGRESS (For AI or Chart.js)
    // ------------------------------------------------------------------
    public function getPatientProgress($patient_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM patient_progress 
            WHERE patient_id = :pid 
            ORDER BY date ASC
        ");
        $stmt->bindParam(':pid', $patient_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

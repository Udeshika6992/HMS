<?php
require_once __DIR__ . '/../config/Database.php';

class Doctor
{
    // =========================
    // GET ALL DOCTORS
    // =========================
    public function getAll()
    {
        $db = Database::connect();

        $sql = "
            SELECT 
                id,
                name,
                specialization,
                description
            FROM users
            WHERE role = 'doctor'
            ORDER BY name
        ";

        return $db->query($sql);
    }

    // =========================
    // GET DOCTOR BY ID
    // =========================
    public function getById($doctorId)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT id, name, specialization, description
             FROM users
             WHERE id = ? AND role = 'doctor'"
        );
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // =========================
    // SEARCH DOCTORS BY SPECIALIZATION / DISEASE
    // =========================
    public function searchBySpecialization($keyword)
    {
        $db = Database::connect();

        $search = "%" . $keyword . "%";

        $stmt = $db->prepare(
            "SELECT id, name, specialization, description
             FROM users
             WHERE role = 'doctor' AND specialization LIKE ?"
        );
        $stmt->bind_param("s", $search);
        $stmt->execute();

        return $stmt->get_result();
    }

    // =========================
    // COUNT TOTAL DOCTORS
    // =========================
    public function countAll()
    {
        $db = Database::connect();
        $result = $db->query("SELECT COUNT(*) AS total FROM users WHERE role = 'doctor'");
        return $result->fetch_assoc()['total'];
    }

  
}

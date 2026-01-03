<?php
require_once __DIR__ . '/../config/Database.php';

class Patient
{
    // =========================
    // GET PATIENT BY USER ID
    // =========================
    public function getById($patientId)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT id, name, email
             FROM users
             WHERE id = ? AND role = 'patient'"
        );
        $stmt->bind_param("i", $patientId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // =========================
    // GET ALL PATIENTS (ADMIN)
    // =========================
    public function getAll()
    {
        $db = Database::connect();

        $sql = "
            SELECT id, name, email
            FROM users
            WHERE role = 'patient'
            ORDER BY name
        ";

        return $db->query($sql);
    }

    // =========================
    // COUNT TOTAL PATIENTS
    // =========================
    public function countAll()
    {
        $db = Database::connect();
        $result = $db->query(
            "SELECT COUNT(*) AS total FROM users WHERE role = 'patient'"
        );

        return $result->fetch_assoc()['total'];
    }

    // =========================
    // SEARCH PATIENT BY ID OR NAME
    // =========================
    public function search($keyword)
    {
        $db = Database::connect();

        // If numeric → search by ID, else by name
        if (is_numeric($keyword)) {
            $stmt = $db->prepare(
                "SELECT id, name, email
                 FROM users
                 WHERE role = 'patient' AND id = ?"
            );
            $stmt->bind_param("i", $keyword);
        } else {
            $search = "%" . $keyword . "%";
            $stmt = $db->prepare(
                "SELECT id, name, email
                 FROM users
                 WHERE role = 'patient' AND name LIKE ?"
            );
            $stmt->bind_param("s", $search);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // =========================
    // GET PATIENT VISIT COUNT
    // =========================
    public function getVisitCount($patientId)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS total
             FROM visits
             WHERE patient_id = ?"
        );
        $stmt->bind_param("i", $patientId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // =========================
    // GET PATIENT VISIT HISTORY
    // =========================
    public function getVisitHistory($patientId)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT visit_date, notes
             FROM visits
             WHERE patient_id = ?
             ORDER BY visit_date DESC"
        );
        $stmt->bind_param("i", $patientId);
        $stmt->execute();

        return $stmt->get_result();
    }
}

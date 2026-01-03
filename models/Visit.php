<?php
require_once dirname(__DIR__) . '/config/Database.php';

class Visit
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // GET VISITS BY PATIENT
    // =========================
    public function getByPatient($patientId)
    {
        $stmt = $this->db->prepare(
            "SELECT visit_date, notes
             FROM visits
             WHERE patient_id = ?
             ORDER BY visit_date DESC"
        );
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // =========================
    // COUNT BY PATIENT
    // =========================
    public function countByPatient($patientId)
{
    $db = Database::connect();
    $stmt = $db->prepare(
        "SELECT COUNT(*) AS total FROM visits WHERE patient_id = ?"
    );
    $stmt->bind_param("i", $patientId);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}


    // =========================
    // GET VISITS BY DOCTOR
    // =========================
    public function getByDoctor($doctorId)
    {
        $stmt = $this->db->prepare(
            "SELECT v.*, u.name AS patient_name
             FROM visits v
             JOIN users u ON v.patient_id = u.id
             WHERE v.doctor_id = ?
             ORDER BY v.visit_date DESC"
        );
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // =========================
// ADD NEW VISIT (DOCTOR)
// =========================
public function addVisit($patientId, $doctorId, $notes)
{
    $stmt = $this->db->prepare(
        "INSERT INTO visits (patient_id, doctor_id, visit_date, notes)
         VALUES (?, ?, NOW(), ?)"
    );
    $stmt->bind_param("iis", $patientId, $doctorId, $notes);
    return $stmt->execute();
}

// =========================
// SEARCH PATIENT HISTORY (DOCTOR)
// =========================
public function searchHistory($keyword)
{
    $search = "%" . $keyword . "%";

    $stmt = $this->db->prepare(
        "SELECT v.*, u.name AS patient_name
         FROM visits v
         JOIN users u ON v.patient_id = u.id
         WHERE u.name LIKE ? OR u.id LIKE ?
         ORDER BY v.visit_date DESC"
    );
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();

    return $stmt->get_result();
}

}

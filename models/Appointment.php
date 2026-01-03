<?php
require_once __DIR__ . '/../config/Database.php';

class Appointment
{
    // =========================
    // CREATE APPOINTMENT (PATIENT)
    // =========================
    public function create($patientId, $doctorId, $date)
    {
        $db = Database::connect();

        $status = 'Pending';

        $stmt = $db->prepare(
            "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiss", $patientId, $doctorId, $date, $status);

        return $stmt->execute();
    }

    // =========================
    // GET ALL APPOINTMENTS (DOCTOR VIEW)
    // =========================
    public function getAll()
    {
        $db = Database::connect();

        $sql = "
            SELECT 
                appointments.id,
                appointments.patient_id,
                appointments.appointment_date,
                appointments.status,
                users.name AS patient_name
            FROM appointments
            JOIN users ON appointments.patient_id = users.id
            ORDER BY appointments.appointment_date DESC
        ";

        return $db->query($sql);
    }

    // =========================
    // UPDATE APPOINTMENT STATUS (APPROVE / REJECT)
    // =========================
    public function updateStatus($appointmentId, $status)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "UPDATE appointments SET status = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $status, $appointmentId);

        return $stmt->execute();
    }

    // =========================
    // COUNT APPOINTMENTS BY DOCTOR
    // =========================
    public function countByDoctor($doctorId)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ?"
        );
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    // =========================
    // COUNT ALL APPOINTMENTS (ADMIN / DOCTOR DASHBOARD)
    // =========================
    public function countAll()
    {
        $db = Database::connect();
        $result = $db->query("SELECT COUNT(*) AS total FROM appointments");
        return $result->fetch_assoc()['total'];
    }

    // =========================
    // COUNT APPOINTMENTS BY STATUS
    // =========================
    public function countByStatus($status)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS total FROM appointments WHERE status = ?"
        );
        $stmt->bind_param("s", $status);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    
}

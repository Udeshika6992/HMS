<?php
require_once dirname(__DIR__) . '/config/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        // Correct database connection
        $this->db = Database::connect();
    }

    // =========================
    // CREATE USER (ADMIN / DOCTOR / PATIENT)
    // =========================
    public function create($name, $email, $password, $role)
    {
        $sql = "INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        return $stmt->execute();
    }

    // =========================
    // FIND USER BY EMAIL (LOGIN)
    // =========================
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // =========================
    // GET ALL USERS (ADMIN)
    // =========================
    public function getAll()
    {
        return $this->db->query("SELECT * FROM users ORDER BY id DESC");
    }

    // =========================
    // DELETE USER
    // =========================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // =========================
    // COUNT ALL
    // =========================
    public function countAll()
{
    $result = $this->db->query("SELECT COUNT(*) AS total FROM users");
    $row = $result->fetch_assoc();
    return $row['total'];
}

// =========================
// GET ALL DOCTORS
// =========================
public function getDoctors()
{
    return $this->db->query(
        "SELECT id, name, email
         FROM users
         WHERE role = 'doctor'
         ORDER BY name"
    );
}

}

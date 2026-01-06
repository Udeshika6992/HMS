<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    // Database connection
    public $conn; // ✅ Made public so it can be accessed from manage_admins.php
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // ✅ Register new patient user
    public function register($name, $email, $password) {
        // Check if email exists
        $checkQuery = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->rowCount() > 0) {
            return false;
        }

        $hashedPassword = md5($password);
        $role = 'patient';

        $query = "INSERT INTO $this->table (name, email, password, role)
                  VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        return true;
    }

    // ✅ Login for all roles (admin, doctor, patient)
    public function login($email, $password) {
        $query = "SELECT * FROM $this->table WHERE email = :email AND password = :password LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':email' => $email,
            ':password' => md5($password)
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Find user by email (used in forgot password)
    public function findUserByEmail($email) {
        $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Update password (used in reset password)
    public function updatePassword($email, $newPassword) {
        $hashed = md5($newPassword);
        $query = "UPDATE $this->table SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':password' => $hashed,
            ':email' => $email
        ]);
    }

    // ✅ Get all users (for admin)
    public function getAllUsers() {
        $query = "SELECT id, name, email, role, created_at FROM $this->table ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Delete user by ID
    public function deleteUser($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // ✅ Update user details (for editing)
    public function updateUser($id, $name, $email) {
        $query = "UPDATE $this->table SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $id
        ]);
    }

    // ✅ Add new admin (used in Manage Admins)
    public function addAdmin($name, $email, $password) {
        $hashed = md5($password);
        $query = "INSERT INTO $this->table (name, email, password, role)
                  VALUES (:name, :email, :password, 'admin')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashed
        ]);
    }

    // ✅ Get database connection (optional getter)
    public function getConnection() {
        return $this->conn;
    }
}
?>

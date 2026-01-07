<?php
/**
 * -----------------------------------------------------------
 * User.php (Model Layer)
 * -----------------------------------------------------------
 * Handles user operations (plain-text version for campus demo)
 * -----------------------------------------------------------
 */

require_once __DIR__ . '/Database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    public function getConnection() {
    return $this->conn;
}
//ADD ADMIN//

    public function addAdmin($name, $email, $password) {
    $query = $this->conn->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (:name, :email, :password, 'admin')
    ");
    $query->bindParam(':name', $name);
    $query->bindParam(':email', $email);
    $query->bindParam(':password', $password);
    return $query->execute();
}

    // -------------------------------------------------------------------
    // 🔹 REGISTER (Patient Only)
    // -------------------------------------------------------------------
    public function register($name, $email, $password) {
        // Check if email already exists
        $check = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $check->bindParam(':email', $email);
        $check->execute();

        if ($check->rowCount() > 0) {
            return false; // Email already exists
        }

        // 🚫 No hashing here — store as plain text
        $stmt = $this->conn->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (:name, :email, :password, 'patient')
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    // -------------------------------------------------------------------
    // 🔹 LOGIN (Admin, Doctor, Patient)
    // -------------------------------------------------------------------
    public function login($email, $password) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE email = :email AND password = :password
        ");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------
    // 🔹 FIND USER BY EMAIL
    // -------------------------------------------------------------------
    public function findUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------
    // 🔹 UPDATE PASSWORD
    // -------------------------------------------------------------------
    public function updatePassword($email, $newPassword) {
        $stmt = $this->conn->prepare("
            UPDATE users SET password = :password WHERE email = :email
        ");
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // -------------------------------------------------------------------
    // 🔹 GET ALL USERS
    // -------------------------------------------------------------------
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------
    // 🔹 DELETE USER
    // -------------------------------------------------------------------
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // -------------------------------------------------------------------
    // 🔹 UPDATE USER DETAILS
    // -------------------------------------------------------------------
    public function updateUser($id, $name, $email, $role) {
        $stmt = $this->conn->prepare("
            UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // -------------------------------------------------------------------
    // 🔹 GET USER BY ID
    // -------------------------------------------------------------------
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------
    // 🔹 FACTORY PATTERN (Create User by Role)
    // -------------------------------------------------------------------
    public static function createUser($role, $name, $email, $password) {
        $userModel = new User();

        $stmt = $userModel->conn->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (:name, :email, :password, :role)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
        
    }

    public function generatePatientCode() {
    $stmt = $this->conn->prepare("SELECT MAX(id) AS max_id FROM users WHERE role = 'patient'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextId = $result['max_id'] ? $result['max_id'] + 1 : 1;
    return "P" . str_pad($nextId, 6, "0", STR_PAD_LEFT);
}
}
?>

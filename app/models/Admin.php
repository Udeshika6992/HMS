<?php
/**
 * -----------------------------------------------------------
 * Admin.php (Model Layer)
 * -----------------------------------------------------------
 * Handles all admin-related database operations.
 * Works with AdminController.php and follows MVC principles.
 * -----------------------------------------------------------
 */

require_once __DIR__ . '/Database.php';

class Admin {
    private $conn;

    /**
     * Constructor establishes DB connection using Singleton
     */
    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // -----------------------------------------------------------
    // 🔹 CREATE — Add a new Admin
    // -----------------------------------------------------------
    public function addAdmin($name, $email, $password) {
        try {
            // Check if email exists
            $check = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $check->bindParam(':email', $email);
            $check->execute();

            if ($check->rowCount() > 0) {
                return "⚠️ Email already exists!";
            }

            $query = $this->conn->prepare("
                INSERT INTO users (name, email, password, role)
                VALUES (:name, :email, :password, 'admin')
            ");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password); // no hashing, as per your request

            if ($query->execute()) {
                return "✅ Admin added successfully!";
            } else {
                return "❌ Failed to add admin.";
            }
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        }
    }

    // -----------------------------------------------------------
    // 🔹 READ — Get All Admins
    // -----------------------------------------------------------
    public function getAllAdmins() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role = 'admin' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------------------
    // 🔹 READ — Get Admin by ID
    // -----------------------------------------------------------
    public function getAdminById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id AND role = 'admin'");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------------------
    // 🔹 UPDATE — Update Admin Details
    // -----------------------------------------------------------
    public function updateAdmin($id, $name, $email) {
        try {
            $query = $this->conn->prepare("
                UPDATE users 
                SET name = :name, email = :email
                WHERE id = :id AND role = 'admin'
            ");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':id', $id);

            if ($query->execute()) {
                return "✅ Admin updated successfully!";
            } else {
                return "❌ Failed to update admin.";
            }
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        }
    }

    // -----------------------------------------------------------
    // 🔹 DELETE — Remove Admin
    // -----------------------------------------------------------
    public function deleteAdmin($id) {
        try {
            $query = $this->conn->prepare("DELETE FROM users WHERE id = :id AND role = 'admin'");
            $query->bindParam(':id', $id);
            if ($query->execute()) {
                return "🗑️ Admin deleted successfully!";
            } else {
                return "❌ Failed to delete admin.";
            }
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        }
    }

    // -----------------------------------------------------------
    // 🔹 LOGIN — Admin Authentication
    // -----------------------------------------------------------
    public function loginAdmin($email, $password) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE email = :email AND password = :password AND role = 'admin'
        ");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // plain password
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------------------
    // 🔹 DASHBOARD STATS — Admin Summary
    // -----------------------------------------------------------
    public function getStats() {
        $stats = [];

        $stats['admins'] = $this->conn->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
        $stats['doctors'] = $this->conn->query("SELECT COUNT(*) FROM users WHERE role='doctor'")->fetchColumn();
        $stats['patients'] = $this->conn->query("SELECT COUNT(*) FROM users WHERE role='patient'")->fetchColumn();

        // Optional: if you have an appointments table
        try {
            $stats['appointments'] = $this->conn->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
        } catch (PDOException $e) {
            $stats['appointments'] = 0;
        }

        return $stats;
    }
}
?>

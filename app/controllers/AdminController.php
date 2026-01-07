<?php
/**
 * ------------------------------------------------------------
 * AdminController.php
 * ------------------------------------------------------------
 * Handles admin-related operations (CRUD + dashboard stats)
 * Works with the Admin model (app/models/Admin.php)
 * ------------------------------------------------------------
 */

require_once __DIR__ . '/../models/Admin.php';

class AdminController {
    private $adminModel;

    /**
     * Constructor — initialize the Admin model
     */
    public function __construct() {
        $this->adminModel = new Admin();
    }

    // ------------------------------------------------------------
    // 🔹 GET ALL ADMINS
    // ------------------------------------------------------------
    public function getAllAdmins() {
        try {
            return $this->adminModel->getAllAdmins();
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 ADD NEW ADMIN
    // ------------------------------------------------------------
    public function addAdmin($name, $email, $password) {
        try {
            $result = $this->adminModel->addAdmin($name, $email, $password);

            if (strpos($result, "✅") !== false) {
                return "<div class='alert alert-success'>$result</div>";
            } elseif (strpos($result, "⚠️") !== false) {
                return "<div class='alert alert-warning'>$result</div>";
            } else {
                return "<div class='alert alert-danger'>$result</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error adding admin: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 UPDATE ADMIN
    // ------------------------------------------------------------
    public function updateAdmin($id, $name, $email) {
        try {
            $result = $this->adminModel->updateAdmin($id, $name, $email);

            if (strpos($result, "✅") !== false) {
                return "<div class='alert alert-success'>$result</div>";
            } else {
                return "<div class='alert alert-danger'>$result</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error updating admin: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 DELETE ADMIN
    // ------------------------------------------------------------
    public function deleteAdmin($id) {
        try {
            $result = $this->adminModel->deleteAdmin($id);

            if (strpos($result, "🗑️") !== false) {
                return "<div class='alert alert-success'>$result</div>";
            } else {
                return "<div class='alert alert-danger'>$result</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error deleting admin: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 LOGIN ADMIN
    // ------------------------------------------------------------
    public function login($email, $password) {
        try {
            $admin = $this->adminModel->loginAdmin($email, $password);

            if ($admin) {
                // Start session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user'] = [
                    'id' => $admin['id'],
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'role' => $admin['role']
                ];

                // Redirect to admin dashboard
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                return "<div class='alert alert-danger'>❌ Invalid email or password!</div>";
            }
        } catch (Exception $e) {
            return "<div class='alert alert-danger'>Error during login: " . $e->getMessage() . "</div>";
        }
    }

    // ------------------------------------------------------------
    // 🔹 LOGOUT ADMIN
    // ------------------------------------------------------------
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ../auth/login.php");
        exit();
    }

    // ------------------------------------------------------------
    // 🔹 DASHBOARD STATS
    // ------------------------------------------------------------
    public function getDashboardStats() {
        try {
            return $this->adminModel->getStats();
        } catch (Exception $e) {
            return [
                'admins' => 0,
                'doctors' => 0,
                'patients' => 0,
                'appointments' => 0
            ];
        }
    }
}
?>

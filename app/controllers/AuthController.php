<?php
/**
 * -----------------------------------------------------------
 * AuthController.php
 * -----------------------------------------------------------
 * Controller for handling all authentication features:
 *  - Login (Admin, Doctor, Patient)
 *  - Register (Patient)
 *  - Forgot Password
 *  - Reset Password
 *  - Logout
 * 
 * This version uses PLAIN-TEXT passwords (for local demo use)
 * -----------------------------------------------------------
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();

        // Start session once
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // -------------------------------------------------------------------
    // 🔹 LOGIN (Admin, Doctor, Patient)
    // -------------------------------------------------------------------
    public function login($email, $password) {
        $user = $this->userModel->login($email, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role']
            ];

            // ✅ Redirect user based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../../views/admin/admin_dashboard.php");
                    break;

                case 'doctor':
                    header("Location: ../../views/doctor/doctor_dashboard.php");
                    break;

                case 'patient':
                    header("Location: ../../views/patient/patient_dashboard.php");
                    break;

                default:
                    echo "<script>alert('Unknown role!'); window.location.href='login.php';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
            exit;
        }
    }

    // -------------------------------------------------------------------
    // 🔹 REGISTER (Patients Only)
    // -------------------------------------------------------------------
    public function register($name, $email, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match!'); window.location.href='register.php';</script>";
            exit;
        }

        $result = $this->userModel->register($name, $email, $password);

        if ($result) {
            echo "<script>alert('Registration successful! Please login now.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Email already exists! Try another one.'); window.location.href='register.php';</script>";
        }
        exit;
    }

    // -------------------------------------------------------------------
    // 🔹 FORGOT PASSWORD (Email verification)
    // -------------------------------------------------------------------
    public function forgotPassword($email) {
        $user = $this->userModel->findUserByEmail($email);

        if (!$user) {
            echo "<script>alert('No account found with this email!'); window.location.href='forgot_password.php';</script>";
            exit;
        }

        // ✅ Save email temporarily for reset
        $_SESSION['reset_email'] = $email;

        echo "<script>alert('Account found! You can now reset your password.'); window.location.href='reset_password.php';</script>";
        exit;
    }

    // -------------------------------------------------------------------
    // 🔹 RESET PASSWORD (Plain Text)
    // -------------------------------------------------------------------
    public function resetPassword($email, $newPassword, $confirmPassword) {
        if ($newPassword !== $confirmPassword) {
            echo "<script>alert('Passwords do not match!'); window.location.href='reset_password.php';</script>";
            exit;
        }

        $result = $this->userModel->updatePassword($email, $newPassword);

        if ($result) {
            unset($_SESSION['reset_email']);
            echo "<script>alert('Password updated successfully! Please login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Failed to update password! Please try again.'); window.location.href='reset_password.php';</script>";
        }
        exit;
    }

    // -------------------------------------------------------------------
    // 🔹 LOGOUT
    // -------------------------------------------------------------------
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: ../../views/auth/login.php");
        exit;
    }
}
?>

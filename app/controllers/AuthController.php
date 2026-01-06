<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            if ($password !== $confirm) {
                echo "<script>alert('Passwords do not match!');</script>";
                return;
            }

            $success = $this->user->register($name, $email, $password);
            if ($success) {
                echo "<script>alert('Registered successfully! You can now log in.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Email already exists or registration failed.');</script>";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->user->login($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;

                if ($user['role'] === 'admin') {
    header('Location: ../admin/dashboard.php');
} elseif ($user['role'] === 'doctor') {
    header('Location: ../doctor/dashboard.php');
} else {
    header('Location: ../patient/dashboard.php');
}

            } else {
                echo "<script>alert('Invalid email or password!');</script>";
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: login.php');
    }
}
?>

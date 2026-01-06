<?php
require_once dirname(__DIR__) . '/models/User.php';

    // =========================
    // LOGIN
    // =========================
    
require_once 'config/Database.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::connect();

            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user && $user['password'] === $password) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: index.php?page=admin");
                } elseif ($user['role'] === 'doctor') {
                    header("Location: index.php?page=doctor");
                } else {
                    header("Location: index.php?page=patient");
                }
                exit;
            } else {
                $error = "Invalid email or password";
            }
        }

        require 'views/auth/login.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::connect();

            $stmt = $db->prepare(
                "INSERT INTO users (name,email,password,phone,gender,role)
                 VALUES (?,?,?,?,?,?)"
            );
            $stmt->bind_param(
                "ssssss",
                $_POST['name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['phone'],
                $_POST['gender'],
                $_POST['role']
            );
            $stmt->execute();

            header("Location: index.php?page=login");
            exit;
        }

        require 'views/auth/register.php';
    }
}



            header("Location: index.php?page=login&error=1");
            exit;
        

        require_once dirname(__DIR__) . '/views/auth/login.php';
    


    // =========================
    // REGISTER
    // =========================

   public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name  = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password']; // NO HASH

        $userModel = new User();
        $userModel->create($name, $email, $password, 'patient');

        header("Location: index.php?page=login");
        exit;
    }

    require_once dirname(__DIR__) . '/views/auth/register.php';
}

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }


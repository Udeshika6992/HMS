<?php
require_once dirname(__DIR__) . '/models/User.php';

class AuthController
{
    // =========================
    // LOGIN
    // =========================
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && $password === $user['password']) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['role']    = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: index.php?page=admin");
    } elseif ($user['role'] === 'doctor') {
        header("Location: index.php?page=doctor");
    } else {
        header("Location: index.php?page=patient");
    }
    exit;
}

            header("Location: index.php?page=login&error=1");
            exit;
        }

        require_once dirname(__DIR__) . '/views/auth/login.php';
    }


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
}

<?php
/**
 * -----------------------------------------------------------
 * HMS Login Page
 * -----------------------------------------------------------
 * Handles user login for Admin, Doctor, and Patient.
 * Uses AuthController and User model (MVC + OOP).
 * -----------------------------------------------------------
 */

require_once '../../controllers/AuthController.php';

// ✅ Prevent session duplication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Hide warnings for clean UI
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$auth = new AuthController();
$msg = "";

// ✅ Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user = $auth->login($email, $password);

    if (!$user) {
        $msg = "<div class='alert alert-danger text-center'>❌ Invalid email or password!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS Login</title>
    <link rel="stylesheet" href="../../../public/css/style.css">
    <style>
        /* --- Inline fallback CSS --- */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            text-align: center;
            width: 400px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🩺 HMS Login</h2>

        <!-- Display login message -->
        <?php if (!empty($msg)) echo $msg; ?>

        <!-- Login Form -->
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <hr>
        <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

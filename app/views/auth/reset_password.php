<?php
/**
 * -----------------------------------------------------------
 * reset_password.php
 * -----------------------------------------------------------
 * This page allows users to reset their password
 * after verifying their email in forgot_password.php
 * -----------------------------------------------------------
 */

require_once '../../controllers/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth = new AuthController();
$message = "";

// ✅ Check if email is available in session
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];

// ✅ Handle password reset submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Call controller method
    $auth->resetPassword($email, $newPassword, $confirmPassword);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | HMS</title>
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
        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 400px;
            text-align: center;
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
    </style>
</head>

<body>
    <div class="container">
        <h2>🔒 Reset Password</h2>

        <?php if (!empty($message)) echo $message; ?>

        <!-- Reset Password Form -->
        <form method="POST" action="">
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit" name="reset">Update Password</button>
        </form>

        <p><a href="login.php">⬅ Back to Login</a></p>
    </div>
</body>
</html>

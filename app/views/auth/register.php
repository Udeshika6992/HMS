<?php
/**
 * -----------------------------------------------------------
 * register.php
 * -----------------------------------------------------------
 * Patient Registration Page
 * Uses AuthController to handle form submission.
 * Stores passwords as plain text (for academic demo).
 * -----------------------------------------------------------
 */

require_once '../../controllers/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth = new AuthController();
$message = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    $auth->register($name, $email, $password, $confirm);
}

$patient_code = $userModel->generatePatientCode();
$stmt = $conn->prepare("INSERT INTO users (patient_code, name, email, password, role)
                        VALUES (:patient_code, :name, :email, :password, 'patient')");
$stmt->execute([
    ':patient_code' => $patient_code,
    ':name' => $name,
    ':email' => $email,
    ':password' => $password
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration | HMS</title>
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
        .register-container {
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
            margin: 8px 0;
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
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }
        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>🩺 Patient Registration</h2>

        <!-- Display any message -->
        <?php if (!empty($message)) echo $message; ?>

        <!-- Registration Form -->
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Enter your full name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

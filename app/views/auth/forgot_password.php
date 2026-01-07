<?php
require_once '../../controllers/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth = new AuthController();
$msg = "";

// ✅ When user submits email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    if ($auth->forgotPassword($email)) {
        $_SESSION['reset_email'] = $email;
        header("Location: reset_password.php");
        exit;
    } else {
        $msg = "<div class='alert alert-danger text-center'>❌ No account found with that email address.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | HMS</title>
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
    <div class="container">
        <h2>🔑 Forgot Password</h2>

        <!-- Display message -->
        <?php if (!empty($msg)) echo $msg; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <button type="submit" name="reset">Continue</button>
        </form>

        <p><a href="login.php">⬅ Back to Login</a></p>
    </div>
</body>
</html>

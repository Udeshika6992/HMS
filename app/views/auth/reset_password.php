<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../controllers/AuthController.php';
require_once '../../models/User.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = new User();
$message = "";

if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit;
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($newPassword !== $confirm) {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        $user->updatePassword($email, $newPassword);
        unset($_SESSION['reset_email']);
        echo "<script>alert('Password reset successfully! You can now log in.'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4">
          <h3 class="text-center mb-3">🔐 Reset Password</h3>
          <?= $message ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Update Password</button>
          </form>
          <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

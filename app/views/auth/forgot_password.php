<?php
require_once '../../controllers/AuthController.php';
require_once '../../models/User.php';

$user = new User();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $userData = $user->findUserByEmail($email);

    if ($userData) {
        // Store email temporarily in session
        session_start();
        $_SESSION['reset_email'] = $email;
        header('Location: reset_password.php');
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4">
          <h3 class="text-center mb-3">Forgot Password</h3>
          <?= $message ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Enter your registered email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Next</button>
          </form>
          <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

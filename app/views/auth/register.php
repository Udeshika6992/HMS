<?php
require_once '../../controllers/AuthController.php';
$auth = new AuthController();
$auth->register();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Registration - HMS</title>

  <link rel="stylesheet" href="../../../public/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4 bg-light">
          <h3 class="text-center mb-4">🩺 Patient Registration</h3>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="confirm" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>
          <p class="text-center mt-3">
            Already have an account?
            <a href="login.php" class="text-decoration-none">Login Here</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

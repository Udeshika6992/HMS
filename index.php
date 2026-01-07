<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container text-center mt-5">
  <h1 class="mb-3">🏥 Welcome to Deltota Divitional Hospital</h1>
  <p class="lead">A complete healthcare management solution for Admins, Doctors, and Patients.</p>
  <div class="mt-4">
    <?php if (!isset($_SESSION['user'])): ?>
      <a href="app/views/auth/login.php" class="btn btn-primary btn-lg">Login</a>
      <a href="app/views/auth/register.php" class="btn btn-outline-secondary btn-lg">Register as Patient</a>
    <?php else: ?>
      <a href="app/views/admin/dashboard.php" class="btn btn-success btn-lg">Go to Dashboard</a>
      <a href="app/views/auth/logout.php" class="btn btn-danger btn-lg">Logout</a>
    <?php endif; ?>
  </div>
</div>

</body>
</html>

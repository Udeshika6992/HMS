<?php
session_start();

// Redirect if not logged in or not a patient
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient') {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../../models/User.php';
require_once '../../models/PatientProgress.php';

// Create model instances
$userModel = new User();
$progressModel = new PatientProgress();

// Fetch logged-in patient details
$patient = $userModel->getUserById($_SESSION['user']['id']);

// Fetch patient progress data (dummy data for example)
$progressData = $progressModel->getProgressByPatientId($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Dashboard - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
      body {
          background-color: #f2f4f8;
      }
      .navbar {
          background-color: #007bff;
      }
      .navbar-brand, .nav-link, .navbar-text {
          color: #fff !important;
      }
      .card {
          border-radius: 8px;
          box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      }
      .chart-container {
          position: relative;
          height: 300px;
          width: 100%;
      }
  </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🏥 HMS - Patient Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($patient['name']); ?> 👋</span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <div class="row g-4">

        <!-- Patient Info -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5>👤 Your Profile</h5>
                <hr>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($patient['role']); ?></p>
            </div>
        </div>

        <!-- Doctor Channeling Section -->
        <div class="col-md-8">
            <div class="card p-3">
                <h5>🩺 Doctor Channeling</h5>
                <hr>
                <form action="find_doctor.php" method="GET" class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="disease" class="form-control" placeholder="Enter your disease (e.g. Fever, Diabetes)" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Find Doctor</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Health Progress Section -->
        <div class="col-md-12">
            <div class="card p-3">
                <h5>📊 Health Progress</h5>
                <hr>
                <div class="chart-container">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Convert PHP array to JS for Chart.js
const labels = <?php echo json_encode(array_column($progressData, 'date')); ?>;
const progress = <?php echo json_encode(array_column($progressData, 'progress_value')); ?>;

// Render Chart
const ctx = document.getElementById('progressChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Health Progress (%)',
            data: progress,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, max: 100 }
        }
    }
});
</script>

</body>
</html>

<?php
// ✅ Secure session check
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - HMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../public/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="dashboard-container">

  <!-- ✅ Sidebar -->
  <div class="sidebar">
    <h2>HMS Admin</h2>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manage_admins.php"><i class="bi bi-person-gear"></i> Manage Admins</a>
    <a href="manage_doctors.php"><i class="bi bi-person-badge"></i> Manage Doctors</a>
    <a href="manage_patients.php"><i class="bi bi-person"></i> Manage Patients</a>
    <a href="appointments.php"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../auth/logout.php" class="btn btn-danger w-100 mt-4"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- ✅ Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <h2 class="mb-4">👋 Welcome, <?php echo $_SESSION['user']['name']; ?> (Admin)</h2>
      
      <div class="row">
        <!-- Stats Cards -->
        <div class="col-md-3">
          <div class="card text-center p-3 shadow">
            <h4><i class="bi bi-person-badge"></i></h4>
            <p class="fw-bold">Doctors</p>
            <h3>12</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center p-3 shadow">
            <h4><i class="bi bi-person"></i></h4>
            <p class="fw-bold">Patients</p>
            <h3>58</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center p-3 shadow">
            <h4><i class="bi bi-calendar-check"></i></h4>
            <p class="fw-bold">Appointments</p>
            <h3>27</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center p-3 shadow">
            <h4><i class="bi bi-cash-stack"></i></h4>
            <p class="fw-bold">Earnings</p>
            <h3>$1,250</h3>
          </div>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="chart-container mt-5">
        <h5>📊 Patient Growth Chart</h5>
        <canvas id="patientsChart"></canvas>
      </div>
    </div>
  </div>

</div>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('patientsChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
      label: 'Registered Patients',
      data: [5, 10, 15, 12, 18, 25],
      borderColor: '#4e54c8',
      backgroundColor: 'rgba(78, 84, 200, 0.3)',
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    plugins: {
      legend: { display: true, position: 'bottom' }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Doctor Dashboard</h1>
    <div>
        <a href="index.php?page=doctorAppointments">Appointments</a>
        <a href="index.php?page=doctorHistory">Patient History</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- WELCOME CARD -->
<div class="card">
    <h2>Welcome, Dr. <?= htmlspecialchars($_SESSION['name'] ?? '') ?></h2>

    <p>
        This dashboard allows you to manage patient appointments,
        approve or reject channeling requests, and review patient history.
    </p>
</div>

<!-- QUICK STATS -->
<div class="card">
    <h3>Quick Overview</h3>
    <ul>
        <li><strong>Total Appointment Requests:</strong> <?= $appointmentCount ?></li>
        <li><strong>Pending Appointments:</strong> <?= $pendingCount ?></li>
        <li><strong>Approved Appointments:</strong> <?= $approvedCount ?></li>
    </ul>
</div>

<!-- ACTIONS -->
<div class="card">
    <h3>Doctor Actions</h3>
    <ul>
        <li>
            <a href="index.php?page=doctorAppointments">
                View Appointment Requests
            </a>
        </li>
        <li>
            <a href="index.php?page=doctorAppointments">
                Approve / Reject Appointments
            </a>
        </li>
        <li>
            <a href="index.php?page=doctorHistory">
                View Patient History
            </a>
        </li>
    </ul>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Admin Dashboard</h1>
    <div>
        <a href="index.php?page=adminUsers">Manage Users</a>
        <a href="index.php?page=adminHistory">Patient History</a>
        <a href="index.php?page=manageDoctor" class="card">
        <h3>👨‍⚕️ Manage Doctors</h3>
        <p>Add / Edit / View / Delete Doctors</p>
    </a>

    <a href="index.php?page=managePatient" class="card">
        <h3>🧑‍🤝‍🧑 Manage Patients</h3>
    </a>

    <a href="index.php?page=manageAppointments" class="card">
        <h3>📅 Manage Appointments</h3>
    </a>
        <a href="index.php?page=logout">Logout</a>
</a>
    </div>
</div>

<!-- WELCOME CARD -->
<div class="card">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></h2>

    <p>
        This dashboard allows administrators to monitor hospital users,
        review patient records, and ensure smooth system operation.
    </p>
</div>

<!-- SYSTEM OVERVIEW -->
<div class="card">
    <h3>System Overview</h3>
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="icon">👥</div>
            <h3>Total Users</h3>
            <div class="count"><?= $totalUsers ?></div>
        </div>

        <div class="dashboard-card">
            <div class="icon">🧑‍⚕️</div>
            <h3>Total Doctors</h3>
            <div class="count"><?= $doctorCount ?></div>
        </div>

        <div class="dashboard-card">
            <div class="icon">🧑‍🦽</div>
            <h3>Total Patients</h3>
            <div class="count"><?= $patientCount ?></div>
        </div>

        <div class="dashboard-card">
            <div class="icon">📅</div>
            <h3>Total Appointments</h3>
            <div class="count"><?= $appointmentCount ?></div>
        </div>
    </div>
</div>

<!-- ADMIN ACTIONS -->
<div class="card">
    <h3>Administrative Actions</h3>
    <ul>
        <li>
            <a href="index.php?page=adminUsers">
                Manage Users
            </a>
        </li>
        <li>
            <a href="index.php?page=adminHistory">
                View Patient History
            </a>
        </li>
    </ul>
</div>

<div class="card">
    <h2>System Overview</h2>

    
</div>


<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

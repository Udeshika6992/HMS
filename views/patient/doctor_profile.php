<!DOCTYPE html>
<html>
<head>
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Doctor Profile</h1>
    <div>
        <a href="index.php?page=appointment">Back to Appointments</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- DOCTOR PROFILE CARD -->
<div class="card">
    <h2><?= htmlspecialchars($doctor['name']) ?></h2>

    <p>
        <strong>Specialization:</strong>
        <?= htmlspecialchars($doctor['specialization']) ?>
    </p>

    <p>
        <strong>About Doctor:</strong><br>
        <?= nl2br(htmlspecialchars($doctor['description'])) ?>
    </p>

    <p>
        <strong>Total Appointments:</strong>
        <?= $appointmentCount ?>
    </p>
</div>

<!-- ACTION CARD -->
<div class="card">
    <h3>Channel This Doctor</h3>

    <p>
        You can request an appointment with this doctor.
        This service is provided free of charge as part of the government hospital system.
    </p>

    <form method="post" action="index.php?page=appointment">
        <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
        <button type="submit">Request Appointment</button>
    </form>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

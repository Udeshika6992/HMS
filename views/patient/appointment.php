<!DOCTYPE html>
<html>
<head>
    <title>Doctor Channeling</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Doctor Channeling</h1>
    <div>
        <a href="index.php?page=patient">Dashboard</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- FILTER BY DISEASE -->
<div class="card">
    <h2>Select Doctor by Disease</h2>

    <form method="get" action="index.php">
        <input type="hidden" name="page" value="appointment">

        <label>Disease Type</label>
        <select name="disease" onchange="this.form.submit()">
            <option value="">-- All Doctors --</option>
            <option value="General" <?= (($_GET['disease'] ?? '') === 'General') ? 'selected' : '' ?>>General</option>
            <option value="Cardiology" <?= (($_GET['disease'] ?? '') === 'Cardiology') ? 'selected' : '' ?>>Heart Disease</option>
            <option value="Neurology" <?= (($_GET['disease'] ?? '') === 'Neurology') ? 'selected' : '' ?>>Nervous System</option>
        </select>
    </form>
</div>

<!-- DOCTOR LIST & APPOINTMENT FORM -->
<div class="card">
    <h2>Request Appointment</h2>

    <?php if ($doctors->num_rows > 0): ?>
        <form method="post" action="index.php?page=appointment">

            <label>Select Doctor</label>
            <select name="doctor_id" required>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <?php
                        // Appointment count for each doctor
                        $count = (new Appointment())->countByDoctor($doc['id']);
                    ?>
                    <option value="<?= $doc['id'] ?>">
                        <?= htmlspecialchars($doc['name']) ?>
                        (<?= htmlspecialchars($doc['specialization']) ?>)
                        - <?= $count ?> Appointments
                    </option>
                <?php endwhile; ?>
            </select>

            <br><br>

            <label>Appointment Date</label>
            <input type="date" name="appointment_date" required>

            <br><br>

            <button type="submit">Request Appointment</button>
        </form>
    <?php else: ?>
        <p>No doctors available for the selected disease.</p>
    <?php endif; ?>
</div>

<!-- DOCTOR PROFILES -->
<div class="card">
    <h2>Doctor Profiles</h2>

    <?php
    // Reset pointer to loop doctors again
    $doctors->data_seek(0);
    ?>

    <?php if ($doctors->num_rows > 0): ?>
        <ul>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($doc['name']) ?></strong>
                    (<?= htmlspecialchars($doc['specialization']) ?>)
                    —
                    <a href="index.php?page=doctorProfile&id=<?= $doc['id'] ?>">
                        View Profile
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital</p>
</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Patient History</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Doctor Panel</h1>
    <div>
        <a href="index.php?page=doctor">Dashboard</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- SEARCH CARD -->
<div class="card">
    <h2>Search Patient History</h2>

    <form method="get" action="index.php">
        <input type="hidden" name="page" value="doctorHistory">

        <label>Patient ID or Name</label>
        <input type="text" name="search"
               placeholder="Enter Patient ID or Patient Name"
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
               required>

        <button type="submit">Search</button>
    </form>
</div>

<!-- HISTORY RESULTS -->
<div class="card">
    <h2>Patient Visit History</h2>

    <?php if (isset($history)): ?>
        <?php if ($history && $history->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Visit Date</th>
                    <th>Doctor Notes</th>
                </tr>

                <?php while ($row = $history->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patient_id']) ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['visit_date']) ?></td>
                        <td><?= htmlspecialchars($row['notes']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No patient history found.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Please search using Patient ID or Name.</p>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

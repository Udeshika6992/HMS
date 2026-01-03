<!DOCTYPE html>
<html>
<head>
    <title>Patient History</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>Admin Panel</h1>
    <a href="index.php?page=admin">Dashboard</a>
</div>

<div class="card">
    <h2>Patient History Viewer</h2>

    <form method="get" action="index.php">
        <input type="hidden" name="page" value="adminHistory">
        <input type="number" name="patient_id" placeholder="Patient ID" required>
        <button type="submit">Search</button>
    </form>
</div>

<?php if ($history): ?>
<div class="card">
    <h3>Visit History</h3>

    <?php if ($history->num_rows > 0): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Notes</th>
            </tr>
            <?php while ($row = $history->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['visit_date']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No history found.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

</body>
</html>

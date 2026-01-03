<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointments</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Doctor Dashboard</h1>
    <div>
        <a href="index.php?page=doctor">Dashboard</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- APPOINTMENTS LIST -->
<div class="card">
    <h2>Appointment Requests</h2>

    <?php if ($appointments && $appointments->num_rows > 0): ?>
        <table>
            <tr>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['patient_id']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>

                    <!-- STATUS WITH COLOR -->
                    <td>
                        <?php
                            if ($row['status'] === 'Approved') {
                                echo '<span class="status-label status-approved">Approved</span>';
                            } elseif ($row['status'] === 'Rejected') {
                                echo '<span class="status-label status-rejected">Rejected</span>';
                            } else {
                                echo '<span class="status-label status-pending">Pending</span>';
                            }
                        ?>
                    </td>

                    <!-- ACTION -->
                    <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <a href="index.php?page=doctorApprove&id=<?= $row['id'] ?>&status=Approved">
                                Approve
                            </a>
                            |
                            <a href="index.php?page=doctorApprove&id=<?= $row['id'] ?>&status=Rejected">
                                Reject
                            </a>
                        <?php else: ?>
                            --
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No appointment requests found.</p>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

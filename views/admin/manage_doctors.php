<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>Admin Panel</h1>
    <div>
        <a href="index.php?page=admin">Dashboard</a>
        <a href="index.php?page=logout">Logout</a>
        <a href="index.php?page=addDoctor" class="btn">Add Doctor</a>
        <a href="index.php?page=editDoctor&id=<?= $row['id'] ?>" class="btn">Edit</a>
    </div>
</div>

<div class="card">
    <h2>Manage Doctors</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Doctor Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php if ($doctors && $doctors->num_rows > 0): ?>
            <?php while ($row = $doctors->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <!-- Actions will be added next step -->
                        <span style="color:gray;">Edit | Delete</span>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No doctors found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital</p>
</div>

</body>
</html>

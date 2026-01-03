<!DOCTYPE html>
<html>
<head>
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Admin Panel</h1>
    <div>
        <a href="index.php?page=admin">Dashboard</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<!-- USERS TABLE -->
<div class="card">
    <h2>System Users</h2>

    <?php if ($users && $users->num_rows > 0): ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['role'])) ?></td>

                    <td>
                        <?php if ($row['role'] !== 'admin'): ?>
                            <a href="index.php?page=deleteUser&id=<?= $row['id'] ?>"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                Delete
                            </a>
                        <?php else: ?>
                            --
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

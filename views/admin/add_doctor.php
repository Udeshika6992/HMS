<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>Admin Panel</h1>
    <div>
        <a href="index.php?page=admin">Dashboard</a>
        <a href="index.php?page=adminUsers">Users</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<div class="card">
    <h2>Add New Doctor</h2>

    <form method="post">
        <label>Doctor Name</label>
        <input type="text" name="name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn btn-success">Add Doctor</button>
        <a href="index.php?page=adminUsers" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital</p>
</div>

</body>
</html>

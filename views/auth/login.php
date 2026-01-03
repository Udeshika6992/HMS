<!DOCTYPE html>
<html>
<head>
    <title>Login | Delthota Divisional Hospital</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>Delthota Divisional Hospital</h1>
</div>

<div class="card" style="max-width:400px;">
    <h2 style="text-align:center;">User Login</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red; text-align:center;">
            Invalid email or password
        </p>
    <?php endif; ?>

    <form method="post" action="index.php?page=login">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn btn-primary" style="width:100%;">
            Login
        </button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        New patient?
        <a href="index.php?page=register">Register here</a>
    </p>
</div>

<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

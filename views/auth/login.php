<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/auth-modern.css">
</head>
<body>

<div class="topbar">
    <div class="logo">🏥 GLOBAL HOSPITALS</div>
</div>

<div class="auth-wrapper">
    <div class="left-panel">Welcome Back</div>

    <div class="right-panel">
        <div class="form-title">User Login</div>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="index.php?page=login">
            <div class="form-grid">
                <input class="full" type="email" name="email" placeholder="Email" required>
                <input class="full" type="password" name="password" placeholder="Password" required>
            </div>

            <button class="submit-btn">Login</button>

            <a class="link" href="index.php?page=register">New patient? Register here</a>
        </form>
    </div>
</div>

</body>
</html>

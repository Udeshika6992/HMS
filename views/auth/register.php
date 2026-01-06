<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="container">
    <div class="left">Welcome Back</div>

    <div class="right">
        <h2>User Login</h2>

        <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button>Login</button>
        </form>

        <p>New patient?
            <a href="index.php?page=register">Register here</a>
        </p>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration | Delthota Divisional Hospital</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>Delthota Divisional Hospital</h1>
    <div>
        <a href="index.php">Home</a>
        <a href="index.php?page=login">Login</a>
    </div>
</div>

<!-- REGISTRATION CARD -->
<div class="card" style="max-width:450px; margin:40px auto;">
    <h2 style="text-align:center;">Patient Registration</h2>

    <p class="info" style="text-align:center;">
        Please fill in the details below to register as a patient.
        Registration is free of charge.
    </p>

    <form method="post" action="index.php?page=register">

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" style="width:100%;">Register</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        Already registered?
        <a href="index.php?page=login">Login here</a>
    </p>
</div>

<!-- FOOTER -->
<div class="footer">
    <p>&copy; 2026 Delthota Divisional Hospital | Government Healthcare Service</p>
</div>

</body>
</html>

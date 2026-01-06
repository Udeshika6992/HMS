<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h2>Edit Doctor</h2>
    <div>
        <a href="index.php?page=manageDoctors">Back</a>
        <a href="index.php?page=logout">Logout</a>
    </div>
</div>

<div class="card">
    <h2>Edit Doctor Details</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Doctor Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($doctor['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($doctor['email']) ?>" required>

        <button type="submit" class="btn">Update Doctor</button>
    </form>
</div>

</body>
</html>

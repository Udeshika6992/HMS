<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../models/User.php';
$userModel = new User();
$conn = $userModel->getConnection();

$msg = "";

if (!isset($_GET['id'])) {
    header("Location: manage_patients.php");
    exit;
}

$id = $_GET['id'];

// Fetch patient details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id AND role = 'patient'");
$stmt->execute([':id' => $id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("Patient not found!");
}

// ✅ UPDATE PATIENT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_patient'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id AND role = 'patient'";
    $stmt = $conn->prepare($query);
    $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password, ':id' => $id]);
    $msg = "<div class='alert alert-success'>✅ Patient updated successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef2f3;
            text-align: center;
            padding: 40px;
        }
        .form-box {
            background: white;
            width: 400px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 18px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>✏️ Edit Patient</h2>
        <?= $msg; ?>
        <form method="POST">
            <input type="text" name="name" value="<?= htmlspecialchars($patient['name']); ?>" required><br>
            <input type="email" name="email" value="<?= htmlspecialchars($patient['email']); ?>" required><br>
            <input type="text" name="password" value="<?= htmlspecialchars($patient['password']); ?>" required><br>
            <button type="submit" name="update_patient">Update Patient</button>
        </form>
        <a href="manage_patients.php">⬅️ Back to Patients</a>
    </div>
</body>
</html>

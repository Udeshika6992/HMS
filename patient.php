<?php
session_start();
include("config.php"); // DB connection file

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get patient ID from URL
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : null;

if(!$patient_id){
    echo "Invalid patient ID.";
    exit();
}

// Fetch patient info
$patient_query = mysqli_query($conn, "SELECT * FROM patients WHERE patient_id='$patient_id'");
$patient = mysqli_fetch_assoc($patient_query);

// Save new progress note (Doctor/Nurse only)
if(isset($_POST['add_progress'])){
    $user_id = $_SESSION['user_id'];
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $date = date("Y-m-d H:i:s");

    $insert = mysqli_query($conn,
        "INSERT INTO progress_reports(patient_id, user_id, note, created_at)
        VALUES('$patient_id', '$user_id', '$note', '$date')"
    );

    if($insert){
        $msg = "Progress added successfully!";
    } else {
        $msg = "Error adding progress.";
    }
}

// Fetch progress history
$progress_query = mysqli_query($conn,
    "SELECT pr.*, u.username, u.role 
     FROM progress_reports pr
     JOIN users u ON pr.user_id = u.user_id
     WHERE patient_id='$patient_id'
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Progress</title>
    <style>
        body { font-family: Arial; background: #f4f6f8; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { color: #333; }
        .progress-box { padding: 15px; background: #f9f9f9; margin-bottom: 10px; border-left: 4px solid #007bff; }
        .add-box { padding: 20px; background: #eef5ff; border-radius: 10px; margin-bottom: 20px; }
        textarea { width: 100%; height: 100px; padding: 10px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-top: 10px; }
        button:hover { background: #0056b3; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>Patient Progress - <?php echo $patient['name']; ?></h2>
    <p><strong>Patient ID:</strong> <?php echo $patient['patient_id']; ?></p>
    <p><strong>Gender:</strong> <?php echo $patient['gender']; ?></p>
    <p><strong>Age:</strong> <?php echo $patient['age']; ?></p>

    <?php if(isset($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <!-- Add Progress Form (Doctor/Nurse only) -->
    <?php if($_SESSION['role'] == 'doctor' || $_SESSION['role'] == 'nurse') { ?>
    <div class="add-box">
        <h3>Add Progress Note</h3>
        <form method="post">
            <textarea name="note" required placeholder="Enter progress details..."></textarea>
            <button type="submit" name="add_progress">Save Progress</button>
        </form>
    </div>
    <?php } ?>

    <h3>Progress History</h3>

    <?php 
    if(mysqli_num_rows($progress_query) > 0){
        while($row = mysqli_fetch_assoc($progress_query)){
            echo "
            <div class='progress-box'>
                <strong>{$row['created_at']}</strong><br>
                <em>Updated by: {$row['username']} ({$row['role']})</em>
                <p>{$row['note']}</p>
            </div>";
        }
    } else {
        echo "<p>No progress notes found.</p>";
    }
    ?>

</div>
</body>
</html>

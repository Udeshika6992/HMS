<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::getInstance()->connect();

if (isset($_GET['patient_id'])) {
    $stmt = $pdo->prepare("SELECT date, progress_level FROM patient_progress WHERE patient_id = :pid ORDER BY date ASC");
    $stmt->bindParam(':pid', $_GET['patient_id']);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>

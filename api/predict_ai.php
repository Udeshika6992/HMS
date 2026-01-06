<?php
$symptom = $_POST['symptom'];
$data = json_encode(["symptom" => $symptom]);

$ch = curl_init("http://127.0.0.1:5000/predict");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
echo json_encode($result);
?>

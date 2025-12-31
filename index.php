<?php

// Enable error reporting (ALWAYS at the top)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load controller
require_once __DIR__ . '/controllers/PatientController.php';

// Create controller object
$controller = new PatientController();

// Call function
$controller->showProgress(1); // patient_id = 1

<?php
// index.php – Main Entry Point

session_start();

// Default page
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

switch ($page) {

    case 'patient':
        require_once 'controllers/PatientController.php';
        $controller = new PatientController();
        $controller->dashboard();
        break;

    case 'doctor':
        require_once 'controllers/DoctorController.php';
        $controller = new DoctorController();
        $controller->dashboard();
        break;

    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'login':
    default:
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
}

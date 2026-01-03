<?php
session_start();

require_once __DIR__ . '/config/Database.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/DoctorController.php';
require_once __DIR__ . '/controllers/PatientController.php';

// Default page
$page = $_GET['page'] ?? 'home';

switch ($page) {

    // =========================
    // HOME
    // =========================
    case 'home':
        include 'views/home.php';
        break;

    // =========================
    // AUTHENTICATION
    // =========================
    case 'login':
        require_once 'controllers/AuthController.php';
        (new AuthController())->login();
        break;
        

    case 'register':
        require_once 'controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // =========================
    // PATIENT
    // =========================
    case 'patient':
        require_once 'controllers/PatientController.php';
        (new PatientController())->dashboard();
        break;

    case 'appointment':
        require_once 'controllers/PatientController.php';
        (new PatientController())->appointment();
        break;

    case 'doctorProfile':
        require_once 'controllers/PatientController.php';
        (new PatientController())->doctorProfile();
        break;
        

    // =========================
    // DOCTOR
    // =========================
    case 'doctor':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->dashboard();
        break;

    case 'doctorAppointments':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->appointments();
        break;

    case 'doctorApprove':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->approveAppointments();
        break;

    case 'doctorVisits':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->visits();
        break;

    case 'doctorAddVisit':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->addVisit();
        break;

    case 'doctorHistory':
        require_once 'controllers/DoctorController.php';
        (new DoctorController())->patientHistory();
        break;

        case 'manageDoctors':
    require_once __DIR__ . '/controllers/AdminController.php';
    (new AdminController())->manageDoctors();
    break;


    // =========================
    // ADMIN
    // =========================
    case 'admin':
        require_once 'controllers/AdminController.php';
        (new AdminController())->dashboard();
        break;

    case 'adminUsers':
        require_once 'controllers/AdminController.php';
        (new AdminController())->users();
        break;

    case 'deleteUser':
        require_once 'controllers/AdminController.php';
        (new AdminController())->deleteUser();
        break;

    case 'adminHistory':
        require_once 'controllers/AdminController.php';
        (new AdminController())->patientHistory();
        break;

        case 'addDoctor':
        $controller = new AdminController();
        $controller->addDoctor();
    break;

        case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

        case 'logout':
        $controller = new AuthController();
        $controller->logout();
    break;



    // =========================
    // DEFAULT (404)
    // =========================
    default:
        echo "<h2 style='text-align:center'>Page not found</h2>";
        break;
}

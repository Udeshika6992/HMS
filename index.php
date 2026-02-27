<?php
/**
 * Front Controller - Entry Point
 * Hospital Management System
 * Complete Final Version
 */

// =====================================================
// ERROR REPORTING - Turn on for development
// =====================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// =====================================================
// Load Configuration FIRST
// =====================================================
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/Database.php';

// =====================================================
// Load Core Classes SECOND
// =====================================================
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

// =====================================================
// Load Helper Functions THIRD
// =====================================================
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validation.php';

// =====================================================
// Start Session if not already started
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// Autoloader for other classes
// =====================================================
spl_autoload_register(function ($className) {
    $directories = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/middleware/',
        __DIR__ . '/factories/',
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// =====================================================
// Initialize Router
// =====================================================
$router = new Router();

// =====================================================
// DEFINE ALL ROUTES
// =====================================================

// =============================================
// HOME ROUTES
// =============================================
$router->add('GET', '', 'HomeController', 'index');
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', 'home', 'HomeController', 'index');
$router->add('GET', 'about', 'HomeController', 'about');
$router->add('GET', 'contact', 'HomeController', 'contact');

// =============================================
// AUTH ROUTES
// =============================================
$router->add('GET', 'login', 'AuthController', 'login');
$router->add('POST', 'do-login', 'AuthController', 'doLogin');
$router->add('GET', 'register', 'AuthController', 'register');
$router->add('POST', 'do-register', 'AuthController', 'doRegister');
$router->add('GET', 'logout', 'AuthController', 'logout');
$router->add('GET', 'forgot-password', 'AuthController', 'forgotPassword');
$router->add('POST', 'do-forgot-password', 'AuthController', 'doForgotPassword');
$router->add('GET', 'reset-password/{token}', 'AuthController', 'resetPassword');
$router->add('POST', 'do-reset-password', 'AuthController', 'doResetPassword');

// =============================================
// PATIENT ROUTES (with middleware)
// =============================================
$router->add('GET', 'patient/dashboard', 'PatientController', 'dashboard', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/book-appointment', 'PatientController', 'bookAppointment', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/do-book-appointment', 'PatientController', 'doBookAppointment', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/check-availability', 'PatientController', 'checkAvailability', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/my-appointments', 'PatientController', 'myAppointments', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/view-appointment/{id}', 'PatientController', 'viewAppointment', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/cancel-appointment/{id}', 'PatientController', 'cancelAppointment', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/medical-history', 'PatientController', 'medicalHistory', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/view-medical-record/{id}', 'PatientController', 'viewMedicalRecord', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/prescriptions', 'PatientController', 'prescriptions', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/view-prescription/{id}', 'PatientController', 'viewPrescription', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/progress-charts', 'PatientController', 'progressCharts', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/get-chart-data', 'PatientController', 'getChartData', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('GET', 'patient/profile', 'PatientController', 'profile', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/update-profile', 'PatientController', 'updateProfile', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/update-medical-info', 'PatientController', 'updateMedicalInfo', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/update-emergency-contact', 'PatientController', 'updateEmergencyContact', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/change-password', 'PatientController', 'changePassword', ['AuthMiddleware', 'PatientMiddleware']);
$router->add('POST', 'patient/add-feedback/{id}', 'PatientController', 'addFeedback', ['AuthMiddleware', 'PatientMiddleware']);

// =============================================
// DOCTOR ROUTES (with middleware)
// =============================================
$router->add('GET', 'doctor/dashboard', 'DoctorController', 'dashboard', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/appointments', 'DoctorController', 'appointments', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/view-appointment/{id}', 'DoctorController', 'viewAppointment', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/update-appointment-status/{id}', 'DoctorController', 'updateAppointmentStatus', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/patients', 'DoctorController', 'patients', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/view-patient/{id}', 'DoctorController', 'viewPatient', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/add-medical-record/{id}', 'DoctorController', 'addMedicalRecord', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/edit-medical-record/{id}', 'DoctorController', 'editMedicalRecord', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/update-medical-record/{id}', 'DoctorController', 'updateMedicalRecord', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/add-prescription/{id}', 'DoctorController', 'addPrescription', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/add-vitals/{id}', 'DoctorController', 'addVitals', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/add-progress/{id}', 'DoctorController', 'addProgress', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/schedule', 'DoctorController', 'schedule', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/update-schedule', 'DoctorController', 'updateSchedule', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/profile', 'DoctorController', 'profile', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/update-profile', 'DoctorController', 'updateProfile', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('POST', 'doctor/change-password', 'DoctorController', 'changePassword', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/search-patients', 'DoctorController', 'searchPatients', ['AuthMiddleware', 'DoctorMiddleware']);
$router->add('GET', 'doctor/get-patient-progress/{id}', 'DoctorController', 'getPatientProgress', ['AuthMiddleware', 'DoctorMiddleware']);

// =============================================
// ADMIN ROUTES (with middleware)
// =============================================
$router->add('GET', 'admin/dashboard', 'AdminController', 'dashboard', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/users', 'AdminController', 'users', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/users/create', 'AdminController', 'createUser', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/users/create', 'AdminController', 'createUser', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/users/edit/{id}', 'AdminController', 'editUser', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/users/edit/{id}', 'AdminController', 'editUser', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/users/delete/{id}', 'AdminController', 'deleteUser', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/users/toggle-status/{id}', 'AdminController', 'toggleUserStatus', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/doctors', 'AdminController', 'doctors', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/patients', 'AdminController', 'patients', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/view-patient/{id}', 'AdminController', 'viewPatient', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/departments', 'AdminController', 'departments', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/departments/create', 'AdminController', 'createDepartment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/departments/create', 'AdminController', 'createDepartment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/departments/edit/{id}', 'AdminController', 'editDepartment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/departments/edit/{id}', 'AdminController', 'editDepartment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/departments/delete/{id}', 'AdminController', 'deleteDepartment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/appointments', 'AdminController', 'appointments', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/view-appointment/{id}', 'AdminController', 'viewAppointment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/cancel-appointment/{id}', 'AdminController', 'cancelAppointment', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/reports', 'AdminController', 'reports', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/export-report/{type}', 'AdminController', 'exportReport', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/settings', 'AdminController', 'settings', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/settings', 'AdminController', 'settings', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('GET', 'admin/profile', 'AdminController', 'profile', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/update-profile', 'AdminController', 'updateProfile', ['AuthMiddleware', 'AdminMiddleware']);
$router->add('POST', 'admin/change-password', 'AdminController', 'changePassword', ['AuthMiddleware', 'AdminMiddleware']);

// =============================================
// API ROUTES (AJAX) - No middleware needed
// =============================================
$router->add('GET', 'api/get-doctors-by-department', 'ApiController', 'getDoctorsByDepartment');
$router->add('GET', 'api/check-doctor-availability', 'ApiController', 'checkDoctorAvailability');
$router->add('GET', 'api/get-notifications', 'ApiController', 'getNotifications');
$router->add('POST', 'api/mark-notification-read/{id}', 'ApiController', 'markNotificationRead');
$router->add('GET', 'api/search-patients', 'ApiController', 'searchPatients');
$router->add('GET', 'api/get-patient-progress/{id}', 'ApiController', 'getPatientProgress');

// =============================================
// TEST ROUTE (Remove in production)
// =============================================
// $router->add('GET', 'test', 'TestController', 'index');

// =============================================
// ERROR ROUTES
// =============================================
$router->add('GET', '404', 'ErrorController', 'notFound');
$router->add('GET', '403', 'ErrorController', 'forbidden');
$router->add('GET', '500', 'ErrorController', 'serverError');

// =============================================
// CATCH-ALL ROUTE - This must be the LAST route
// =============================================
$router->add('GET', '{any}', 'ErrorController', 'notFound');

// =====================================================
// DISPATCH THE REQUEST
// =====================================================

// Get the requested URL
$requestUri = $_SERVER['REQUEST_URI'];
$baseUrl = BASE_URL; // Defined in config.php

// Remove base URL from request
if (strpos($requestUri, $baseUrl) === 0) {
    $url = substr($requestUri, strlen($baseUrl));
} else {
    $url = $requestUri;
}

// Remove query string
$url = explode('?', $url)[0];
$url = trim($url, '/');

// For debugging (uncomment if needed)
// error_log("Dispatching URL: " . $url);

try {
    $router->dispatch($url);
} catch (Exception $e) {
    error_log("Router Error: " . $e->getMessage());
    http_response_code(500);
    
    if (file_exists(__DIR__ . '/views/errors/500.php')) {
        require_once __DIR__ . '/views/errors/500.php';
    } else {
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<p>Please check the error log for more details.</p>";
    }
}

// =====================================================
// END OF index.php
// =====================================================
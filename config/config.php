<?php
/**
 * Configuration File
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hms_db');

// Application Configuration
define('APP_NAME', 'Hospital Management System');
define('BASE_URL', '/HMS/');
define('APP_ROOT', dirname(__DIR__));

// Paths
define('UPLOAD_PATH', APP_ROOT . '/uploads/');
define('PROFILE_PATH', UPLOAD_PATH . 'profiles/');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
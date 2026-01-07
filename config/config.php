<?php
/**
 * -----------------------------------------------------------
 * File: config.php
 * Project: Hospital Management System (HMS)
 * -----------------------------------------------------------
 * This file defines global configuration settings for the
 * entire HMS project such as base URLs, default paths,
 * timezone, and global constants.
 * 
 * 🧠 OOP & Design Pattern Context:
 * --------------------------------
 * - Works together with `database.php` (Singleton pattern)
 * - Ensures all paths and settings are centrally managed
 * 
 * 💡 Benefits:
 * - Easier project maintenance
 * - Avoids hardcoding repeated values
 * - Central place for environment configuration
 * -----------------------------------------------------------
 */

 // -----------------------------------------------------------
 // 🌍 Application Environment Settings
 // -----------------------------------------------------------

// Set default timezone
date_default_timezone_set('Asia/Colombo'); // 🇱🇰 Change if needed

// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// -----------------------------------------------------------
// 🔗 Base URL and Paths
// -----------------------------------------------------------

// Project root directory
define('ROOT_PATH', dirname(__DIR__));

// Base URL (adjust according to your setup)
define('BASE_URL', 'http://localhost/HMS/');

// Application folders
define('APP_PATH', ROOT_PATH . '/app/');
define('CONFIG_PATH', ROOT_PATH . '/config/');
define('HELPER_PATH', ROOT_PATH . '/helpers/');
define('VIEW_PATH', APP_PATH . 'views/');
define('MODEL_PATH', APP_PATH . 'models/');
define('CONTROLLER_PATH', APP_PATH . 'controllers/');

// -----------------------------------------------------------
// 🔐 Security and Session Configuration
// -----------------------------------------------------------

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Secure session timeout (in seconds)
define('SESSION_TIMEOUT', 3600); // 1 hour

// Check session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = time();

// -----------------------------------------------------------
// ⚙️ Application Metadata
// -----------------------------------------------------------
define('APP_NAME', 'Hospital Management System');
define('APP_VERSION', '1.0.0');
define('DEVELOPER', 'Udeshika');

// -----------------------------------------------------------
// 🧩 Include Database Connection
// -----------------------------------------------------------
require_once __DIR__ . '/database.php';

// Optional: You can auto-load helpers or common libraries here
// require_once HELPER_PATH . 'functions.php';

?>

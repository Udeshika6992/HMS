<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hms_db');
define('APP_NAME', 'Hospital Management System');
define('BASE_URL', '/HMS/');
define('APP_ROOT', dirname(__DIR__));

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
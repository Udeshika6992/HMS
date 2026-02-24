<?php
// Application Configuration
define('APP_NAME', 'Delthota Divisional Hospital Management System');
define('APP_URL', 'http://localhost/HMS');
define('APP_DEBUG', true); // Set to false in production
define('APP_TIMEZONE', 'Asia/Colombo');
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

date_default_timezone_set(APP_TIMEZONE);
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'includes/functions.php';
require_once 'controllers/AuthController.php';

echo "<h1>Testing AuthController</h1>";

try {
    $auth = new AuthController();
    echo "✅ AuthController loaded successfully";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 HMS Debug Tool</h1>";
echo "<h2>PHP Version: " . phpversion() . "</h2>";

// Check for common errors
$error_log = [];
$files_to_check = [
    'config/config.php',
    'config/Database.php',
    'core/Controller.php',
    'core/Model.php',
    'core/Router.php',
    'includes/functions.php',
    'models/UserModel.php',
    'controllers/HomeController.php'
];

echo "<h3>File Status:</h3><ul>";
foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '/' . $file;
    if (file_exists($full_path)) {
        $size = filesize($full_path);
        echo "<li style='color:green'>✅ $file - OK ($size bytes)</li>";
    } else {
        echo "<li style='color:red'>❌ $file - MISSING</li>";
    }
}
echo "</ul>";

// Test database connection
echo "<h3>Database Connection Test:</h3>";
try {
    require_once 'config/config.php';
    require_once 'config/Database.php';
    $db = Database::getInstance();
    echo "<p style='color:green'>✅ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Database error: " . $e->getMessage() . "</p>";
}
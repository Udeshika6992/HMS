<?php
/**
 * Test file to verify IDE recognition
 */

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Controller.php';

echo "<h1>IDE Recognition Test</h1>";

// Test 1: Check if Database class is found
if (class_exists('Database')) {
    echo "<p style='color:green'>✅ Database class found</p>";
} else {
    echo "<p style='color:red'>❌ Database class not found</p>";
}

// Test 2: Check if Controller class is found
if (class_exists('Controller')) {
    echo "<p style='color:green'>✅ Controller class found</p>";
} else {
    echo "<p style='color:red'>❌ Controller class not found</p>";
}

// Test 3: Check BASE_URL constant
if (defined('BASE_URL')) {
    echo "<p style='color:green'>✅ BASE_URL defined as: " . BASE_URL . "</p>";
} else {
    echo "<p style='color:red'>❌ BASE_URL not defined</p>";
}

echo "<h2>Next Steps:</h2>";
echo "<ul>";
echo "<li>If you're using VS Code, create the .vscode/settings.json file</li>";
echo "<li>If you're using PHPStorm, create the .phpstorm.meta.php file</li>";
echo "<li>Restart your IDE after adding these files</li>";
echo "</ul>";
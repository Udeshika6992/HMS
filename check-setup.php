<?php
/**
 * Setup Check Script
 * Location: /check-setup.php
 */

echo "<h1 style='color: #333; font-family: Arial;'>🔍 HMS Setup Check</h1>";

echo "<h2 style='color: #555;'>File Locations:</h2>";
echo "<ul style='background: #f9f9f9; padding: 15px; border-radius: 5px; list-style: none;'>";
echo "<li>index.php exists: " . (file_exists('index.php') ? '✅' : '❌') . "</li>";
echo "<li>.htaccess exists: " . (file_exists('.htaccess') ? '✅' : '❌') . "</li>";
echo "<li>config/config.php exists: " . (file_exists('config/config.php') ? '✅' : '❌') . "</li>";
echo "<li>config/Database.php exists: " . (file_exists('config/Database.php') ? '✅' : '❌') . "</li>";
echo "<li>config/constants.php exists: " . (file_exists('config/constants.php') ? '✅' : '❌') . "</li>";
echo "<li>core/Router.php exists: " . (file_exists('core/Router.php') ? '✅' : '❌') . "</li>";
echo "<li>core/Controller.php exists: " . (file_exists('core/Controller.php') ? '✅' : '❌') . "</li>";
echo "<li>core/Model.php exists: " . (file_exists('core/Model.php') ? '✅' : '❌') . "</li>";
echo "<li>controllers/HomeController.php exists: " . (file_exists('controllers/HomeController.php') ? '✅' : '❌') . "</li>";
echo "<li>controllers/ErrorController.php exists: " . (file_exists('controllers/ErrorController.php') ? '✅' : '❌') . "</li>";
echo "<li>includes/functions.php exists: " . (file_exists('includes/functions.php') ? '✅' : '❌') . "</li>";
echo "<li>includes/validation.php exists: " . (file_exists('includes/validation.php') ? '✅' : '❌') . "</li>";
echo "</ul>";

echo "<h2 style='color: #555;'>Server Info:</h2>";
echo "<ul style='background: #f9f9f9; padding: 15px; border-radius: 5px; list-style: none;'>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</li>";
echo "<li><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</li>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "</ul>";

echo "<h2 style='color: #555;'>Next Steps:</h2>";
echo "<ul style='background: #e8f0fe; padding: 15px; border-radius: 5px;'>";
echo "<li>1. Run <a href='test_db.php'>test_db.php</a> to test database connection</li>";
echo "<li>2. Import database/migration.sql to create tables</li>";
echo "<li>3. Import database/seed.sql to add sample data</li>";
echo "<li>4. Visit <a href='/HMS/'>Homepage</a> to see your site</li>";
echo "</ul>";
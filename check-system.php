<?php
echo "<h1>🔍 HMS System Check</h1>";

// Check PHP Version
echo "<h2>PHP Version: " . phpversion() . "</h2>";

// Check required extensions
$extensions = ['pdo_mysql', 'mysqli', 'gd', 'mbstring', 'json'];
echo "<h3>Required Extensions:</h3><ul>";
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    $color = $loaded ? 'green' : 'red';
    $status = $loaded ? '✅ Loaded' : '❌ Not Loaded';
    echo "<li style='color: $color'>$ext: $status</li>";
}
echo "</ul>";

// Check config file
echo "<h3>Configuration:</h3>";
if (file_exists('config/config.php')) {
    require 'config/config.php';
    echo "✅ config.php found<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
    echo "Database: " . DB_NAME . "<br>";
} else {
    echo "❌ config.php not found<br>";
}

// Check database connection
echo "<h3>Database Connection:</h3>";
try {
    require 'config/Database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "✅ Connected to database<br>";
    
    // Check users table
    $users = $db->fetchOne("SELECT COUNT(*) as count FROM users");
    echo "Users in database: " . $users['count'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
}

// Check .htaccess
echo "<h3>.htaccess:</h3>";
if (file_exists('.htaccess')) {
    echo "✅ .htaccess exists<br>";
} else {
    echo "❌ .htaccess missing<br>";
}

echo "<hr>";
echo "<a href='" . BASE_URL . "'>Go to Homepage</a>";
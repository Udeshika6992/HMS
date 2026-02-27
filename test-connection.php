<?php
/**
 * Quick Database Connection Test
 * Location: /test-connection.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Quick Database Connection Test</h1>";

// Test 1: Can we include config?
echo "<h2>Test 1: Loading config</h2>";
if (file_exists('config/config.php')) {
    require_once 'config/config.php';
    echo "✅ config.php loaded<br>";
    echo "Database name: " . DB_NAME . "<br>";
} else {
    echo "❌ config.php not found<br>";
    exit;
}

// Test 2: Can we include Database class?
echo "<h2>Test 2: Loading Database class</h2>";
if (file_exists('config/Database.php')) {
    require_once 'config/Database.php';
    echo "✅ Database.php loaded<br>";
} else {
    echo "❌ Database.php not found<br>";
    exit;
}

// Test 3: Can we connect?
echo "<h2>Test 3: Connecting to database</h2>";
try {
    $db = Database::getInstance();
    echo "✅ Database instance created<br>";
    
    $conn = $db->getConnection();
    echo "✅ Connection established<br>";
    
    // Test 4: Simple query
    echo "<h2>Test 4: Running simple query</h2>";
    $result = $db->fetchOne("SELECT 1 as test");
    echo "✅ Query executed, result: " . $result['test'] . "<br>";
    
    // Test 5: Check if users table exists
    echo "<h2>Test 5: Checking users table</h2>";
    if ($db->tableExists('users')) {
        echo "✅ users table exists<br>";
        $count = $db->count('users');
        echo "Number of users: " . $count . "<br>";
    } else {
        echo "❌ users table does not exist<br>";
        echo "You need to create your tables. Run migration.sql in phpMyAdmin.<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Error type: " . get_class($e) . "<br>";
}
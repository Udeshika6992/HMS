<?php
/**
 * Simple Database Connection Test
 * Location: /test-simple.php
 */

require_once 'config/config.php';
require_once 'config/Database.php';

echo "<h1>🔧 Simple Database Test</h1>";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<p style='color: green;'>✅ Connected to database successfully!</p>";
    
    // Simple test query
    $result = $db->fetchOne("SELECT 'Connection OK' as status");
    echo "<p>📊 Test query result: " . $result['status'] . "</p>";
    
    // Show database info
    echo "<h2>Database Info:</h2>";
    echo "<ul>";
    echo "<li>Database: " . $db->getDatabaseName() . "</li>";
    echo "<li>Host: " . $db->getHost() . "</li>";
    echo "<li>Server: " . $db->getServerInfo() . "</li>";
    echo "</ul>";
    
    // List tables
    $tables = $db->fetchAll("SHOW TABLES");
    echo "<h2>Tables in database:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        foreach ($table as $tableName) {
            echo "<li>" . $tableName . "</li>";
        }
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
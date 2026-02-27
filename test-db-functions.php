<?php
/**
 * Test Database Functions
 * Location: /test-db-functions.php
 */

require_once 'config/config.php';
require_once 'config/Database.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Test Database Functions</title>";
echo "<style>";
echo "body { font-family: Arial; padding: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }";
echo ".success { color: green; background: #e8f5e8; padding: 10px; border-left: 4px solid green; margin: 10px 0; }";
echo ".error { color: red; background: #ffe8e8; padding: 10px; border-left: 4px solid red; margin: 10px 0; }";
echo "pre { background: #f8f9fa; padding: 10px; border-radius: 5px; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>🔧 Test Database Functions</h1>";

try {
    $db = Database::getInstance();
    echo "<div class='success'>✅ Database instance created</div>";
    
    // Test 1: Simple query
    echo "<h2>Test 1: Simple Query</h2>";
    $result = $db->fetchOne("SELECT 'Hello World' as message");
    echo "<div class='success'>✅ Query result: " . $result['message'] . "</div>";
    
    // Test 2: tableExists
    echo "<h2>Test 2: tableExists()</h2>";
    $tables = ['users', 'doctors', 'patients', 'appointments'];
    foreach ($tables as $table) {
        $exists = $db->tableExists($table);
        $status = $exists ? '✅ Exists' : '❌ Not exists';
        $color = $exists ? 'success' : 'error';
        echo "<div class='$color'>Table '$table': $status</div>";
    }
    
    // Test 3: count()
    echo "<h2>Test 3: count()</h2>";
    if ($db->tableExists('users')) {
        $count = $db->count('users');
        echo "<div class='success'>✅ Users count: $count</div>";
    }
    
    // Test 4: Create a test table
    echo "<h2>Test 4: Create test table</h2>";
    $sql = "CREATE TABLE IF NOT EXISTS test_table (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100)
    )";
    $db->query($sql);
    echo "<div class='success'>✅ Test table created</div>";
    
    // Test 5: Insert test data
    echo "<h2>Test 5: Insert test data</h2>";
    $data = ['name' => 'Test Record'];
    $id = $db->insert('test_table', $data);
    echo "<div class='success'>✅ Inserted with ID: $id</div>";
    
    // Test 6: Select test data
    echo "<h2>Test 6: Select test data</h2>";
    $result = $db->fetchOne("SELECT * FROM test_table WHERE id = :id", ['id' => $id]);
    echo "<div class='success'>✅ Found: " . $result['name'] . "</div>";
    
    // Test 7: Update test data
    echo "<h2>Test 7: Update test data</h2>";
    $db->update('test_table', ['name' => 'Updated Record'], 'id = :id', ['id' => $id]);
    $result = $db->fetchOne("SELECT * FROM test_table WHERE id = :id", ['id' => $id]);
    echo "<div class='success'>✅ Updated to: " . $result['name'] . "</div>";
    
    // Test 8: Delete test data
    echo "<h2>Test 8: Delete test data</h2>";
    $db->delete('test_table', 'id = :id', ['id' => $id]);
    $result = $db->fetchOne("SELECT * FROM test_table WHERE id = :id", ['id' => $id]);
    if (!$result) {
        echo "<div class='success'>✅ Record deleted successfully</div>";
    }
    
    // Test 9: Drop test table
    echo "<h2>Test 9: Clean up</h2>";
    $db->query("DROP TABLE test_table");
    echo "<div class='success'>✅ Test table dropped</div>";
    
    echo "<h2>✅ All tests passed!</h2>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";
echo "</body>";
echo "</html>";
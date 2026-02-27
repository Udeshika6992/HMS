<?php
/**
 * Test Database Connection
 * Location: /test_db.php
 */

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/functions.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Database Connection Test</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "h1 { color: #333; border-bottom: 2px solid #3498db; padding-bottom: 10px; }";
echo ".success { color: #27ae60; background: #e8f8f5; padding: 10px; border-radius: 5px; border-left: 4px solid #27ae60; margin: 10px 0; }";
echo ".error { color: #c0392b; background: #fadbd8; padding: 10px; border-radius: 5px; border-left: 4px solid #c0392b; margin: 10px 0; }";
echo ".info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 20px; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #3498db; color: white; }";
echo "tr:hover { background: #f5f5f5; }";
echo ".badge-success { background: #27ae60; color: white; padding: 3px 10px; border-radius: 3px; font-size: 12px; }";
echo ".badge-error { background: #c0392b; color: white; padding: 3px 10px; border-radius: 3px; font-size: 12px; }";
echo ".btn { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }";
echo ".btn:hover { background: #2980b9; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

echo "<h1>🔧 Database Connection Test</h1>";
echo "<p>Testing connection to database: <strong>" . DB_NAME . "</strong></p>";

try {
    // Get database instance
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<div class='success'>✅ <strong>Database connection successful!</strong></div>";
    
    // Test simple query
    $result = $db->fetchOne("SELECT 'Connection working perfectly!' as message");
    echo "<div class='success'>📊 " . $result['message'] . "</div>";
    
    // Show database info
    echo "<h2>Database Information:</h2>";
    echo "<div class='info'>";
    echo "<p><strong>Database Name:</strong> " . $db->getDatabaseName() . "</p>";
    echo "<p><strong>Host:</strong> " . $db->getHost() . "</p>";
    echo "<p><strong>Server Version:</strong> " . $db->getServerInfo() . "</p>";
    echo "<p><strong>Connection Status:</strong> <span class='badge-success'>Connected</span></p>";
    echo "</div>";
    
    // Check if tables exist
    $tables = ['users', 'doctors', 'patients', 'appointments', 'departments', 'medical_records', 'prescriptions'];
    
    echo "<h2>Table Status:</h2>";
    echo "<table>";
    echo "<tr><th>Table Name</th><th>Status</th><th>Records</th></tr>";
    
    foreach ($tables as $table) {
        try {
            $exists = $db->tableExists($table);
            $badgeClass = $exists ? 'badge-success' : 'badge-error';
            $status = $exists ? '✅ Exists' : '❌ Not found';
            
            $count = 0;
            if ($exists) {
                $count = $db->count($table);
            }
            
            echo "<tr>";
            echo "<td><strong>" . $table . "</strong></td>";
            echo "<td><span class='" . $badgeClass . "'>" . $status . "</span></td>";
            echo "<td>" . $count . " records</td>";
            echo "</tr>";
        } catch (Exception $e) {
            echo "<tr>";
            echo "<td><strong>" . $table . "</strong></td>";
            echo "<td colspan='2'><span class='badge-error'>Error: " . $e->getMessage() . "</span></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // Insert sample data if tables are empty
    if ($db->tableExists('users') && $db->count('users') == 0) {
        echo "<h2>Inserting Sample Data...</h2>";
        
        // Insert departments
        $db->query("INSERT INTO departments (department_name, description, floor_number) VALUES
            ('General Medicine', 'Primary care and general consultations', '1'),
            ('Pediatrics', 'Child healthcare', '1'),
            ('Cardiology', 'Heart care', '2'),
            ('Orthopedics', 'Bone and joint care', '2'),
            ('Dermatology', 'Skin care', '3')");
        
        // Insert users
        $db->query("INSERT INTO users (username, email, password_hash, full_name, role) VALUES
            ('admin', 'admin@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin'),
            ('dr_smith', 'dr.smith@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. John Smith', 'doctor'),
            ('dr_jones', 'dr.jones@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Sarah Jones', 'doctor'),
            ('patient1', 'john.doe@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'patient'),
            ('patient2', 'jane.smith@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'patient')");
        
        echo "<div class='success'>✅ Sample data inserted successfully!</div>";
    }
    
    // Show PHP Info
    echo "<h2>PHP Configuration:</h2>";
    echo "<div class='info'>";
    echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
    echo "<p><strong>PDO Drivers:</strong> " . implode(', ', PDO::getAvailableDrivers()) . "</p>";
    echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
    echo "<p><strong>Script Path:</strong> " . __FILE__ . "</p>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div class='error'>❌ <strong>Connection failed:</strong> " . $e->getMessage() . "</div>";
    
    echo "<h2>Troubleshooting Tips:</h2>";
    echo "<ul>";
    echo "<li>Make sure MySQL is running in XAMPP</li>";
    echo "<li>Check if database '<strong>hms_db</strong>' exists</li>";
    echo "<li>Verify database credentials in config/config.php</li>";
    echo "</ul>";
}

echo "<div style='text-align: center;'>";
echo "<a href='" . base_url('') . "' class='btn'>← Back to Home</a>";
echo "</div>";

echo "</div>";
echo "</body>";
echo "</html>";
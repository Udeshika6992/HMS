<?php
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/functions.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>HMS Data Verification</title>";
echo "<style>";
echo "body { font-family: Arial; background: #f5f5f5; padding: 20px; }";
echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }";
echo "h1 { color: #2c3e50; border-bottom: 2px solid #3498db; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th { background: #3498db; color: white; padding: 10px; }";
echo "td { padding: 8px; border-bottom: 1px solid #ddd; }";
echo ".success { color: #27ae60; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h1>📊 HMS Data Verification</h1>";

try {
    $db = Database::getInstance();
    
    // Show users
    $users = $db->fetchAll("SELECT id, username, email, full_name, role FROM users");
    echo "<h2>Users Table (" . count($users) . " records)</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Role</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['full_name'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Show departments
    $depts = $db->fetchAll("SELECT * FROM departments");
    echo "<h2>Departments Table (" . count($depts) . " records)</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Department Name</th><th>Floor</th></tr>";
    foreach ($depts as $dept) {
        echo "<tr>";
        echo "<td>" . $dept['id'] . "</td>";
        echo "<td>" . $dept['department_name'] . "</td>";
        echo "<td>" . $dept['floor_number'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p class='success'>✅ Database is fully set up with " . count($users) . " users and " . count($depts) . " departments!</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='" . base_url('') . "'>← Back to Home</a></p>";
echo "</div>";
echo "</body>";
echo "</html>";
<?php
/**
 * CONVERT ALL PASSWORDS TO PLAIN TEXT
 * ⚠️ WARNING: This is EXTREMELY DANGEROUS! Only for local testing!
 * This will make all passwords visible in the database!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set red warning styling
echo "<!DOCTYPE html>";
echo "<html><head><title>Password Conversion</title>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    .warning { background: #ffcccc; color: #990000; padding: 20px; border: 3px solid red; margin: 20px 0; }
    .danger { background: #ff0000; color: white; padding: 10px; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th { background: #333; color: white; padding: 10px; }
    td { padding: 8px; border: 1px solid #ddd; }
    .btn-danger { background: red; color: white; padding: 15px 30px; font-size: 18px; border: none; cursor: pointer; }
    .btn-danger:hover { background: #cc0000; }
    .btn-primary { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
</style>";
echo "</head><body>";

echo "<h1 class='danger'>⚠️ DANGER: PLAIN TEXT PASSWORD CONVERSION ⚠️</h1>";

echo "<div class='warning'>";
echo "<h2>⚠️ WARNING ⚠️</h2>";
echo "<p><strong>This script will convert ALL passwords to PLAIN TEXT!</strong></p>";
echo "<ul>";
echo "<li>All users' passwords will be VISIBLE in the database</li>";
echo "<li>If your database is hacked, ALL passwords are exposed</li>";
echo "<li>Users who reuse passwords elsewhere are at risk</li>";
echo "<li>This violates every security best practice</li>";
echo "</ul>";
echo "<p><strong>Only use this on LOCAL development machines!</strong></p>";
echo "</div>";

require_once 'config/config.php';
require_once 'config/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if conversion requested
    if (isset($_POST['convert'])) {
        $password_to_set = $_POST['password'] ?? 'password123';
        
        echo "<div class='warning'>";
        echo "<h3>Converting passwords to: <strong>$password_to_set</strong></h3>";
        echo "</div>";
        
        // Get all users before conversion
        $users = $db->query("SELECT id, username, email, password_hash FROM users")->fetchAll();
        
        echo "<h3>Users BEFORE conversion:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password Hash</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td><small>" . substr($user['password_hash'], 0, 30) . "...</small></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Convert ALL passwords to plain text
        $sql = "UPDATE users SET password_hash = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$password_to_set]);
        
        $count = $stmt->rowCount();
        
        echo "<div style='background: #ff9999; padding: 20px; margin: 20px 0;'>";
        echo "<h3 style='color: #990000;'>✅ CONVERSION COMPLETE!</h3>";
        echo "<p><strong>$count users</strong> now have plain text password: <code>$password_to_set</code></p>";
        echo "</div>";
        
        // Show users after conversion
        $users_after = $db->query("SELECT id, username, email, password_hash FROM users")->fetchAll();
        
        echo "<h3>Users AFTER conversion (passwords now visible!):</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th style='background: #ffcccc;'>Plain Password</th></tr>";
        
        foreach ($users_after as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td style='background: #ffffcc; font-weight: bold;'>" . $user['password_hash'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div class='warning'>";
        echo "<p><strong>⚠️ ALL PASSWORDS ARE NOW VISIBLE IN THE DATABASE! ⚠️</strong></p>";
        echo "<p>If you look in phpMyAdmin, you can see everyone's password.</p>";
        echo "</div>";
        
    } else {
        // Show current users
        $users = $db->query("SELECT id, username, email, password_hash FROM users")->fetchAll();
        
        echo "<h3>Current users in database:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Current Password Hash</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td><small>" . substr($user['password_hash'], 0, 30) . "...</small></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Conversion form
        echo "<div class='warning'>";
        echo "<h3>Convert all passwords to plain text:</h3>";
        echo "<form method='POST'>";
        echo "<p>Set all passwords to: <input type='text' name='password' value='password123' required></p>";
        echo "<p><button type='submit' name='convert' class='btn-danger'>⚠️ CONVERT ALL PASSWORDS TO PLAIN TEXT ⚠️</button></p>";
        echo "</form>";
        echo "</div>";
    }
    
    // Navigation
    echo "<p><a href='" . BASE_URL . "' class='btn-primary'>Go to Homepage</a></p>";
    
} catch (PDOException $e) {
    echo "<div class='warning'>";
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body></html>";
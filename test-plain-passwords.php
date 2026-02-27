<?php
/**
 * Test Plain Text Passwords
 * This shows all passwords in plain text (INSECURE!)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'config/Database.php';

echo "<h1 style='color:red;'>🔓 PLAIN TEXT PASSWORDS TEST</h1>";
echo "<p style='color:red;'><strong>⚠️ ALL PASSWORDS ARE VISIBLE BELOW! ⚠️</strong></p>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Get all users with their plain text passwords
    $users = $db->query("SELECT id, username, email, password_hash as plain_password, role FROM users")->fetchAll();
    
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th style='background:#ffcccc;'>PLAIN PASSWORD</th><th>Role</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td style='background:#ffff00; font-weight:bold;'>" . $user['plain_password'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p>✅ All passwords are now stored in PLAIN TEXT and visible above!</p>";
    echo "<p>Try logging in with any of these passwords.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
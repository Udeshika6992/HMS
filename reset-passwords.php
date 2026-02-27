<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔐 Safe Password Reset</h1>";

require_once 'config/config.php';
require_once 'config/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // The password you want all users to have
    $plain_password = 'password123';
    
    // Hash it properly (THIS IS THE SAFE WAY)
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    
    echo "<p>Plain password: <strong>$plain_password</strong></p>";
    echo "<p>Hashed password (stored in DB): <strong>$hashed_password</strong></p>";
    echo "<hr>";
    
    // Update all users with this hash
    $sql = "UPDATE users SET password_hash = :hash";
    $stmt = $db->prepare($sql);
    $stmt->execute(['hash' => $hashed_password]);
    
    echo "<p style='color:green; font-weight:bold;'>✅ All users now have password: '$plain_password'</p>";
    
    // Verify it works
    $test_user = $db->query("SELECT email, password_hash FROM users LIMIT 1")->fetch();
    if (password_verify($plain_password, $test_user['password_hash'])) {
        echo "<p style='color:green;'>✅ Password verification works!</p>";
    }
    
    // Show users
    $users = $db->query("SELECT id, username, email, role FROM users")->fetchAll();
    echo "<h2>Current Users:</h2>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p><a href='" . BASE_URL . "login'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
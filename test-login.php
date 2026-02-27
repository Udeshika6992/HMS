<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/UserModel.php';

echo "<h1>Login Test</h1>";

try {
    $db = Database::getInstance();
    echo "✅ Database connected<br>";
    
    $userModel = new UserModel();
    echo "✅ UserModel loaded<br>";
    
    // Test finding a user
    $user = $userModel->findByEmail('admin@hospital.com');
    
    if ($user) {
        echo "✅ Test user found: " . $user['full_name'] . "<br>";
        echo "Password hash: " . $user['password_hash'] . "<br>";
        
        // Test password verification
        $password = 'password123';
        if (password_verify($password, $user['password_hash'])) {
            echo "✅ Password verification works!<br>";
        } else {
            echo "❌ Password verification failed<br>";
        }
    } else {
        echo "❌ Test user not found. Please run seed.sql<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
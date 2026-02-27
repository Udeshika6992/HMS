<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔐 Login System Test</h1>";

// Load everything needed
require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'models/UserModel.php';
require_once 'controllers/AuthController.php';

echo "<h2>Step 1: Database Connection</h2>";
$db = Database::getInstance();
echo "✅ Database connected<br>";

echo "<h2>Step 2: UserModel Test</h2>";
$userModel = new UserModel();
$admin = $userModel->findByEmail('admin@hospital.com');

if ($admin) {
    echo "✅ Admin user found:<br>";
    echo "• ID: " . $admin['id'] . "<br>";
    echo "• Name: " . $admin['full_name'] . "<br>";
    echo "• Email: " . $admin['email'] . "<br>";
    echo "• Role: " . $admin['role'] . "<br>";
    
    // Test password
    $testPassword = 'password123';
    if (password_verify($testPassword, $admin['password_hash'])) {
        echo "✅ Password 'password123' is CORRECT!<br>";
    } else {
        echo "❌ Password is incorrect<br>";
    }
} else {
    echo "❌ Admin user not found!<br>";
}

echo "<h2>Step 3: AuthController Test</h2>";
try {
    $auth = new AuthController();
    echo "✅ AuthController loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<h2>Step 4: Login Form Location</h2>";
echo "Login page should be at: <a href='" . BASE_URL . "login' target='_blank'>" . BASE_URL . "login</a><br>";
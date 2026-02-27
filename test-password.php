<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Model.php';
require_once 'models/UserModel.php';

echo "<h1>🔐 Password Hash Test</h1>";

$userModel = new UserModel();

// Get all users
$users = $userModel->all();

echo "<h2>Users in Database:</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Email</th><th>Stored Hash</th><th>Test Password 'password123'</th></tr>";

foreach ($users as $user) {
    $testPassword = 'password123';
    $hash = $user['password_hash'];
    
    // Test if password matches
    $matches = password_verify($testPassword, $hash) ? '✅ MATCHES' : '❌ DOES NOT MATCH';
    
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td><small>" . substr($hash, 0, 30) . "...</small></td>";
    echo "<td><strong>$matches</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Test Registration:</h2>";
echo "<form method='POST'>";
echo "<input type='text' name='email' placeholder='Email'><br>";
echo "<input type='password' name='password' placeholder='Password'><br>";
echo "<button type='submit' name='test_register'>Test Register</button>";
echo "</form>";

if (isset($_POST['test_register'])) {
    $testData = [
        'username' => 'test_' . time(),
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'full_name' => 'Test User',
        'role' => 'patient'
    ];
    
    $userId = $userModel->createUser($testData);
    
    if ($userId) {
        echo "<p style='color:green'>✅ Test user created with ID: $userId</p>";
        
        // Show the hash that was created
        $newUser = $userModel->find($userId);
        echo "<p>Hash created: " . $newUser['password_hash'] . "</p>";
        
        // Test login with same password
        $loginTest = password_verify($_POST['password'], $newUser['password_hash']);
        echo "<p>Login test with same password: " . ($loginTest ? '✅ WORKS' : '❌ FAILS') . "</p>";
    }
}
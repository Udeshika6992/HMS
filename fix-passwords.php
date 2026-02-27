<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'core/Model.php';
require_once 'models/UserModel.php';

echo "<h1>🔧 Password Fix Tool</h1>";

$userModel = new UserModel();
$users = $userModel->all();

echo "<h2>Current Password Status:</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Email</th><th>Current Hash</th><th>Status</th><th>Action</th></tr>";

foreach ($users as $user) {
    $testPassword = 'password123';
    $hash = $user['password_hash'];
    $works = password_verify($testPassword, $hash);
    
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td><small>" . substr($hash, 0, 30) . "...</small></td>";
    echo "<td style='color: " . ($works ? 'green' : 'red') . "'>";
    echo ($works ? '✅ WORKS' : '❌ FAILS');
    echo "</td>";
    echo "<td>";
    
    if (!$works) {
        // Create form to fix this user
        echo "<form method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='user_id' value='" . $user['id'] . "'>";
        echo "<input type='hidden' name='email' value='" . $user['email'] . "'>";
        echo "<button type='submit' name='fix_user' class='btn-warning'>Fix Password</button>";
        echo "</form>";
    }
    
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// Handle fix requests
if (isset($_POST['fix_user'])) {
    $userId = $_POST['user_id'];
    $email = $_POST['email'];
    $newPassword = 'password123';
    
    // Create new hash
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update database
    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $stmt = $userModel->db->getConnection()->prepare($sql);
    $result = $stmt->execute([$newHash, $userId]);
    
    if ($result) {
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0;'>";
        echo "✅ Password fixed for $email! New hash created.<br>";
        echo "New hash: " . $newHash;
        echo "</div>";
        
        // Verify it works
        $test = password_verify($newPassword, $newHash);
        echo "<div style='background: #cce5ff; padding: 10px;'>";
        echo "Verification test: " . ($test ? '✅ PASSED' : '❌ FAILED');
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px;'>❌ Failed to update password</div>";
    }
    
    echo "<p><a href='?'>Refresh page</a></p>";
}

// Option to fix all at once
echo "<h2>Bulk Fix Options:</h2>";
echo "<form method='POST'>";
echo "<button type='submit' name='fix_all' class='btn-primary' style='padding:10px;'>";
echo "Fix ALL Passwords to 'password123'";
echo "</button>";
echo "</form>";

if (isset($_POST['fix_all'])) {
    $newHash = password_hash('password123', PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password_hash = ?";
    $stmt = $userModel->db->getConnection()->prepare($sql);
    $result = $stmt->execute([$newHash]);
    
    if ($result) {
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0;'>";
        echo "✅ ALL passwords have been reset to 'password123'!";
        echo "</div>";
        echo "<p><a href='?'>Refresh to see changes</a></p>";
    }
}

// Option to register a new test user
echo "<h2>Register New Test User:</h2>";
echo "<form method='POST'>";
echo "<input type='text' name='new_email' placeholder='Email' required><br>";
echo "<input type='text' name='new_password' value='password123' required><br>";
echo "<button type='submit' name='register_test'>Register Test User</button>";
echo "</form>";

if (isset($_POST['register_test'])) {
    $testData = [
        'username' => 'test_' . time(),
        'email' => $_POST['new_email'],
        'password' => $_POST['new_password'],
        'full_name' => 'Test User',
        'role' => 'patient'
    ];
    
    $newId = $userModel->createUser($testData);
    
    if ($newId) {
        echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0;'>";
        echo "✅ New user created with ID: $newId<br>";
        
        // Show the hash
        $newUser = $userModel->find($newId);
        echo "Hash created: " . $newUser['password_hash'] . "<br>";
        
        // Test login
        $loginTest = password_verify($_POST['new_password'], $newUser['password_hash']);
        echo "Login test: " . ($loginTest ? '✅ WORKS' : '❌ FAILS');
        echo "</div>";
    }
}
?>

<style>
    .btn-warning { background: #ffc107; color: #000; padding: 5px 10px; border: none; cursor: pointer; }
    .btn-primary { background: #007bff; color: #fff; padding: 10px 20px; border: none; cursor: pointer; }
    table { border-collapse: collapse; width: 100%; }
    th { background: #f2f2f2; }
    td, th { padding: 8px; text-align: left; }
</style>
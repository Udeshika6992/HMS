<?php
require_once 'config/config.php';
require_once 'config/Database.php';

echo "<h1>✅ Password Verification</h1>";

$db = Database::getInstance()->getConnection();
$users = $db->query("SELECT id, email, password_hash FROM users")->fetchAll();

$test_password = 'password123';
$correct_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Email</th><th>Hash Status</th><th>Login Test</th></tr>";

foreach ($users as $user) {
    $hash = $user['password_hash'];
    $hash_correct = ($hash == $correct_hash) ? '✅ Correct hash' : '❌ Different hash';
    $login_works = password_verify($test_password, $hash) ? '✅ WORKS' : '❌ FAILS';
    
    echo "<tr>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>$hash_correct</td>";
    echo "<td><strong>$login_works</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<p>All users should now show <strong>✅ WORKS</strong></p>";
echo "<p><a href='" . BASE_URL . "login'>Try Login</a></p>";
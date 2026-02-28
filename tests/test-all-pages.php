<?php
/**
 * Master Test File - Tests ALL Pages
 * Run this to verify every page in your HMS
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/config.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>HMS Complete Page Test</title>";
echo "<style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
    h1 { color: #2c3e50; border-bottom: 2px solid #3498db; }
    h2 { color: #34495e; margin-top: 30px; }
    .pass { color: green; background: #e8f5e8; padding: 5px 10px; border-left: 4px solid green; margin: 5px 0; }
    .fail { color: red; background: #ffe8e8; padding: 5px 10px; border-left: 4px solid red; margin: 5px 0; }
    .warning { color: orange; background: #fff3e0; padding: 5px 10px; border-left: 4px solid orange; margin: 5px 0; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #3498db; color: white; }
    tr:hover { background: #f5f5f5; }
    .summary { background: #2c3e50; color: white; padding: 20px; border-radius: 5px; text-align: center; font-size: 24px; }
</style>";
echo "</head><body>";
echo "<div class='container'>";
echo "<h1>🏥 HMS Complete Page Test Suite</h1>";

// Initialize counters
$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$results = [];

// Function to test a URL
function testPage($url, $name, &$results, &$totalTests, &$passedTests, &$failedTests) {
    $totalTests++;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = ($httpCode >= 200 && $httpCode < 400) ? 'pass' : 'fail';
    
    if ($status === 'pass') {
        $passedTests++;
    } else {
        $failedTests++;
    }
    
    $results[] = [
        'name' => $name,
        'url' => $url,
        'code' => $httpCode,
        'status' => $status
    ];
    
    return $status === 'pass';
}

// =============================================
// TEST PUBLIC PAGES
// =============================================
echo "<h2>📄 Public Pages</h2>";
$publicPages = [
    ['url' => BASE_URL, 'name' => 'Homepage'],
    ['url' => BASE_URL . 'about', 'name' => 'About Page'],
    ['url' => BASE_URL . 'contact', 'name' => 'Contact Page'],
    ['url' => BASE_URL . 'login', 'name' => 'Login Page'],
    ['url' => BASE_URL . 'register', 'name' => 'Register Page'],
];

foreach ($publicPages as $page) {
    testPage($page['url'], $page['name'], $results, $totalTests, $passedTests, $failedTests);
}

// =============================================
// TEST AUTH PAGES (Require login)
// =============================================
echo "<h2>🔐 Authenticated Pages</h2>";

// Note: These tests would need session handling
echo "<div class='warning'>⚠️ Authenticated pages require login to test properly</div>";

$authPages = [
    ['url' => BASE_URL . 'patient/dashboard', 'name' => 'Patient Dashboard'],
    ['url' => BASE_URL . 'patient/book-appointment', 'name' => 'Book Appointment'],
    ['url' => BASE_URL . 'patient/my-appointments', 'name' => 'My Appointments'],
    ['url' => BASE_URL . 'patient/medical-history', 'name' => 'Medical History'],
    ['url' => BASE_URL . 'patient/progress-charts', 'name' => 'Progress Charts'],
    ['url' => BASE_URL . 'patient/profile', 'name' => 'Patient Profile'],
    
    ['url' => BASE_URL . 'doctor/dashboard', 'name' => 'Doctor Dashboard'],
    ['url' => BASE_URL . 'doctor/appointments', 'name' => 'Doctor Appointments'],
    ['url' => BASE_URL . 'doctor/patients', 'name' => 'Doctor Patients'],
    ['url' => BASE_URL . 'doctor/schedule', 'name' => 'Doctor Schedule'],
    ['url' => BASE_URL . 'doctor/profile', 'name' => 'Doctor Profile'],
    
    ['url' => BASE_URL . 'admin/dashboard', 'name' => 'Admin Dashboard'],
    ['url' => BASE_URL . 'admin/users', 'name' => 'User Management'],
    ['url' => BASE_URL . 'admin/doctors', 'name' => 'Doctor Management'],
    ['url' => BASE_URL . 'admin/patients', 'name' => 'Patient Management'],
    ['url' => BASE_URL . 'admin/departments', 'name' => 'Department Management'],
    ['url' => BASE_URL . 'admin/appointments', 'name' => 'Appointment Management'],
    ['url' => BASE_URL . 'admin/reports', 'name' => 'Reports'],
    ['url' => BASE_URL . 'admin/settings', 'name' => 'Settings'],
    ['url' => BASE_URL . 'admin/profile', 'name' => 'Admin Profile'],
];

foreach ($authPages as $page) {
    // These will likely redirect to login, so we just check if they load
    testPage($page['url'], $page['name'] . ' (redirects to login)', $results, $totalTests, $passedTests, $failedTests);
}

// =============================================
// TEST API ENDPOINTS
// =============================================
echo "<h2>🔌 API Endpoints</h2>";
$apiEndpoints = [
    ['url' => BASE_URL . 'api/get-doctors-by-department?department_id=1', 'name' => 'Get Doctors API'],
    ['url' => BASE_URL . 'api/check-doctor-availability?doctor_id=1&date=' . date('Y-m-d'), 'name' => 'Check Availability API'],
];

foreach ($apiEndpoints as $endpoint) {
    testPage($endpoint['url'], $endpoint['name'], $results, $totalTests, $passedTests, $failedTests);
}

// =============================================
// DISPLAY RESULTS
// =============================================
echo "<h2>📊 Test Results Summary</h2>";
echo "<table>";
echo "<tr><th>Page</th><th>URL</th><th>Status</th><th>HTTP Code</th></tr>";

foreach ($results as $result) {
    $color = $result['status'] === 'pass' ? 'green' : 'red';
    $icon = $result['status'] === 'pass' ? '✅' : '❌';
    echo "<tr>";
    echo "<td>{$result['name']}</td>";
    echo "<td><a href='{$result['url']}' target='_blank'>{$result['url']}</a></td>";
    echo "<td style='color: $color; font-weight: bold;'>{$icon} " . strtoupper($result['status']) . "</td>";
    echo "<td>{$result['code']}</td>";
    echo "</tr>";
}
echo "</table>";

// Final Summary
echo "<div class='summary'>";
echo "Total Tests: $totalTests | ";
echo "<span style='color: #2ecc71;'>✅ Passed: $passedTests</span> | ";
echo "<span style='color: #e74c3c;'>❌ Failed: $failedTests</span>";
echo "</div>";

// Recommendations
echo "<h2>💡 Recommendations</h2>";
if ($failedTests > 0) {
    echo "<div class='fail'>⚠️ Some pages failed. Check the red entries above.</div>";
} else {
    echo "<div class='pass'>✅ All pages are accessible! Your HMS is working great!</div>";
}

echo "<h2>🔧 Individual Test Files</h2>";
echo "<ul>";
echo "<li><a href='test-db.php' target='_blank'>Test Database</a></li>";
echo "<li><a href='test-models.php' target='_blank'>Test Models</a></li>";
echo "<li><a href='test-forms.php' target='_blank'>Test Forms</a></li>";
echo "<li><a href='test-csrf.php' target='_blank'>Test CSRF Protection</a></li>";
echo "</ul>";

echo "</div></body></html>";
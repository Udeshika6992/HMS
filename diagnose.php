<?php
echo "<h1>🔍 HMS Diagnostic Tool</h1>";

echo "<h2>1. File Checks:</h2>";
$files = [
    '.htaccess',
    'index.php',
    'config/config.php',
    'core/Router.php',
    'controllers/HomeController.php',
    'views/home/index.php'
];

echo "<ul>";
foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    $color = $exists ? 'green' : 'red';
    echo "<li style='color: $color;'>$file: " . ($exists ? '✅ Found' : '❌ Missing') . "</li>";
}
echo "</ul>";

echo "<h2>2. Apache Modules:</h2>";
$modules = apache_get_modules();
echo "<ul>";
echo "<li>mod_rewrite: " . (in_array('mod_rewrite', $modules) ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "<li>mod_headers: " . (in_array('mod_headers', $modules) ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "</ul>";

echo "<h2>3. .htaccess Content:</h2>";
if (file_exists(__DIR__ . '/.htaccess')) {
    echo "<pre style='background: #f4f4f4; padding: 10px;'>";
    echo htmlspecialchars(file_get_contents(__DIR__ . '/.htaccess'));
    echo "</pre>";
} else {
    echo "<p style='color: red;'>.htaccess file not found!</p>";
}

echo "<h2>4. Base URL Check:</h2>";
$baseUrl = '/HMS/';
$requestUri = $_SERVER['REQUEST_URI'];
echo "<p>Your base URL is set to: <strong>$baseUrl</strong></p>";
echo "<p>Current request: <strong>$requestUri</strong></p>";
echo "<p>After removing base URL: <strong>" . str_replace($baseUrl, '', $requestUri) . "</strong></p>";

echo "<h2>5. Recommended Actions:</h2>";
echo "<ul>";
if (!in_array('mod_rewrite', $modules)) {
    echo "<li style='color: red;'>⚠️ Enable mod_rewrite in XAMPP Apache config</li>";
}
if (!file_exists(__DIR__ . '/.htaccess')) {
    echo "<li style='color: red;'>⚠️ Create .htaccess file in root folder</li>";
}
if (!file_exists(__DIR__ . '/index.php')) {
    echo "<li style='color: red;'>⚠️ index.php is missing from root folder</li>";
}
echo "</ul>";
<?php
echo "<h1>🔍 Router Test</h1>";

require_once 'config/config.php';
require_once 'core/Router.php';

// Test if Router class exists
if (class_exists('Router')) {
    echo "✅ Router class exists<br>";
    
    // Create router instance
    $router = new Router();
    echo "✅ Router instance created<br>";
    
    // Add test route
    $router->add('GET', 'test', 'TestController', 'index');
    echo "✅ Test route added<br>";
    
    // Show all routes
    echo "<h2>Available Routes:</h2>";
    echo "<ul>";
    echo "<li>GET /login</li>";
    echo "<li>GET /register</li>";
    echo "<li>GET /about</li>";
    echo "<li>GET /contact</li>";
    echo "</ul>";
    
} else {
    echo "❌ Router class not found!<br>";
}

echo "<p><a href='" . BASE_URL . "'>Go to Homepage</a></p>";
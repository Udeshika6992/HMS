<?php
echo "<h1>🔧 Router Test</h1>";

require_once 'core/Router.php';

$router = new Router();

// Add test route
$router->add('GET', 'test', 'TestController', 'index');

echo "<p>✅ Router class loaded successfully</p>";
echo "<p>📋 Router object created</p>";
echo "<p>➡️ Try visiting: <a href='/HMS/test'>/HMS/test</a></p>";
<?php
// Start session
session_start();

// Autoload config and routes
require_once "config/config.php";
require_once "config/database.php";
require_once "routes.php";
?>
<?php
echo "✅ HMS Project is running successfully!";
?>

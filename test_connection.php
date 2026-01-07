<?php
require_once __DIR__ . '/app/models/Database.php';

$db = Database::getInstance()->connect();

if ($db) {
    echo "✅ Database connected successfully!";
} else {
    echo "❌ Connection failed!";
}
?>

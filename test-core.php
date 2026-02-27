<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Core Classes Test</h1>";

// Test loading order
echo "<h2>Testing Core Classes:</h2>";

// Load config
require_once 'config/config.php';
echo "✅ Config loaded<br>";

// Load Database
require_once 'config/Database.php';
echo "✅ Database class loaded<br>";

// Load Model
require_once 'core/Model.php';
echo "✅ Model class loaded<br>";

// Check if Model class exists
if (class_exists('Model')) {
    echo "✅ Model class exists<br>";
} else {
    echo "❌ Model class does NOT exist<br>";
}

// Now load UserModel
require_once 'models/UserModel.php';
echo "✅ UserModel file loaded<br>";

if (class_exists('UserModel')) {
    echo "✅ UserModel class exists<br>";
    
    // Try to create instance
    try {
        $userModel = new UserModel();
        echo "✅ UserModel instance created<br>";
    } catch (Exception $e) {
        echo "❌ Error creating UserModel: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ UserModel class does NOT exist<br>";
}
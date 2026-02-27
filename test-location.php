<?php
echo "Current file: " . __FILE__ . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Project path: " . __DIR__ . "<br>";
echo "Is index.php in same folder? " . (file_exists(__DIR__ . '/index.php') ? 'YES ✅' : 'NO ❌');
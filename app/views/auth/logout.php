<?php
/**
 * -----------------------------------------------------------
 * logout.php
 * -----------------------------------------------------------
 * Ends the user session and redirects to the login page.
 * 
 * 🧠 OOP & Design Pattern Concepts:
 * - Follows MVC structure (this is the View layer).
 * - Uses session management safely.
 * - Works with AuthController (Controller layer).
 * -----------------------------------------------------------
 */

require_once '../../controllers/AuthController.php';

// ✅ Ensure session is active before destroying
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Destroy session safely
$_SESSION = [];
session_unset();
session_destroy();

// ✅ Redirect user to login page
header("Location: ../auth/login.php");
exit;
?>

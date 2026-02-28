<?php
/**
 * Authentication Middleware
 * Checks if user is logged in
 */

class AuthMiddleware {
    
    public function handle() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to access this page';
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
        
        return true;
    }
}
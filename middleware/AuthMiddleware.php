<?php
/**
 * Authentication Middleware
 * Checks if user is logged in
 * Location: /middleware/AuthMiddleware.php
 */

class AuthMiddleware {
    
    /**
     * Handle middleware
     * @return bool
     */
    public function handle() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirectToLogin();
            return false;
        }
        
        return true;
    }

    /**
     * Redirect to login page
     */
    private function redirectToLogin() {
        $_SESSION['flash_message'] = 'Please login to access this page';
        $_SESSION['flash_type'] = 'error';
        
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user role
     */
    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
}
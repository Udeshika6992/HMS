<?php
/**
 * Admin Middleware
 * Checks if user has admin role
 * Location: /middleware/AdminMiddleware.php
 */

class AdminMiddleware {
    
    /**
     * Handle middleware
     * @return bool
     */
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectToLogin();
            return false;
        }
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirectToDashboard();
            return false;
        }
        
        return true;
    }

    private function redirectToLogin() {
        $_SESSION['flash_message'] = 'Please login to access this page';
        $_SESSION['flash_type'] = 'error';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }

    private function redirectToDashboard() {
        $role = $_SESSION['user_role'] ?? '';
        
        switch ($role) {
            case 'doctor':
                header('Location: ' . BASE_URL . 'doctor/dashboard');
                break;
            case 'patient':
                header('Location: ' . BASE_URL . 'patient/dashboard');
                break;
            default:
                header('Location: ' . BASE_URL);
        }
        exit();
    }
}
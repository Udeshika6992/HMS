<?php
/**
 * Patient Middleware
 * Checks if user has patient role
 * Location: /middleware/PatientMiddleware.php
 */

class PatientMiddleware {
    
    /**
     * Handle middleware
     * @return bool
     */
    public function handle() {
        // First check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            $this->redirectToLogin();
            return false;
        }
        
        // Check if user has patient role
        if ($_SESSION['user_role'] !== 'patient') {
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
            case 'admin':
                header('Location: ' . BASE_URL . 'admin/dashboard');
                break;
            case 'doctor':
                header('Location: ' . BASE_URL . 'doctor/dashboard');
                break;
            default:
                header('Location: ' . BASE_URL);
        }
        exit();
    }
}
<?php
/**
 * Doctor Middleware
 * Checks if user has doctor role
 * Location: /middleware/DoctorMiddleware.php
 */

class DoctorMiddleware {
    
    /**
     * Handle middleware
     * @return bool
     */
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectToLogin();
            return false;
        }
        
        if ($_SESSION['user_role'] !== 'doctor') {
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
            case 'patient':
                header('Location: ' . BASE_URL . 'patient/dashboard');
                break;
            default:
                header('Location: ' . BASE_URL);
        }
        exit();
    }
}
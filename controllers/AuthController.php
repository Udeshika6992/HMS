<?php
/**
 * Authentication Controller - PLAIN TEXT PASSWORD VERSION
 * WARNING: This uses direct password comparison - INSECURE!
 * Only for local testing!
 */

class AuthController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        // Load UserModel
        require_once 'models/UserModel.php';
        $this->userModel = new UserModel();
    }
    
    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
        }
        
        $data = [
            'title' => 'Login - Hospital Management System'
        ];
        
        $this->render('auth/login', $data);
    }
    
    /**
     * Process login form - DIRECT COMPARISON (INSECURE!)
     */
    public function doLogin() {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        try {
            // Get form data
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate input
            if (empty($login) || empty($password)) {
                $_SESSION['error'] = 'Please enter both email/username and password';
                header('Location: ' . BASE_URL . 'login');
                exit();
            }
            
            // Find user by email or username
            $user = $this->userModel->findByEmailOrUsername($login);
            
            if (!$user) {
                error_log("Login failed: User not found - $login");
                $_SESSION['error'] = 'Invalid login credentials';
                header('Location: ' . BASE_URL . 'login');
                exit();
            }
            
            // Check if user is active
            if (!isset($user['is_active']) || $user['is_active'] != 1) {
                $_SESSION['error'] = 'Your account is deactivated. Please contact administrator.';
                header('Location: ' . BASE_URL . 'login');
                exit();
            }
            
            // ⚠️ DIRECT STRING COMPARISON - NO PASSWORD_VERIFY!
            // Compare plain text passwords directly
            if ($password === $user['password_hash']) {
                // Password is correct (plain text match)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['success'] = 'Login successful! Welcome back, ' . $user['full_name'];
                
                // Update last login time
                if (method_exists($this->userModel, 'updateLastLogin')) {
                    $this->userModel->updateLastLogin($user['id']);
                }
                
                // Log successful login
                error_log("Login successful: " . $user['email'] . " as " . $user['role']);
                
                // Redirect to appropriate dashboard
                $this->redirectToDashboard();
                
            } else {
                // Password is incorrect
                error_log("Login failed: Password mismatch for user - $login");
                error_log("Stored password: " . $user['password_hash']);
                error_log("Submitted password: " . $password);
                $_SESSION['error'] = 'Invalid login credentials';
                header('Location: ' . BASE_URL . 'login');
                exit();
            }
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred. Please try again.';
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }
    
    /**
     * Show registration form
     */
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard();
        }
        
        $data = [
            'title' => 'Register - Hospital Management System'
        ];
        
        $this->render('auth/register', $data);
    }
    
    /**
     * Process registration form
     */
    public function doRegister() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . 'register');
                exit();
            }
            
            // Get form data
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'role' => 'patient' // Default role for registration
            ];
            
            // Validate input
            $errors = [];
            
            // Check required fields
            if (empty($data['username'])) {
                $errors[] = 'Username is required';
            }
            if (empty($data['email'])) {
                $errors[] = 'Email is required';
            }
            if (empty($data['password'])) {
                $errors[] = 'Password is required';
            }
            if (empty($data['full_name'])) {
                $errors[] = 'Full name is required';
            }
            
            // Check if username exists
            if (!empty($data['username']) && $this->userModel->findByUsername($data['username'])) {
                $errors[] = 'Username already taken';
            }
            
            // Check if email exists
            if (!empty($data['email']) && $this->userModel->findByEmail($data['email'])) {
                $errors[] = 'Email already registered';
            }
            
            // Password validation
            if (strlen($data['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
            
            if ($data['password'] !== $data['confirm_password']) {
                $errors[] = 'Passwords do not match';
            }
            
            // If there are errors, redirect back with error messages
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                header('Location: ' . BASE_URL . 'register');
                exit();
            }
            
            // Create user (plain text password will be stored)
            $userId = $this->userModel->createUser($data);
            
            if ($userId) {
                $_SESSION['success'] = 'Registration successful! Please login with your credentials.';
                error_log("New user registered: " . $data['email']);
                header('Location: ' . BASE_URL . 'login');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                header('Location: ' . BASE_URL . 'register');
                exit();
            }
            
        } catch (Exception $e) {
            error_log("Registration Error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred. Please try again.';
            header('Location: ' . BASE_URL . 'register');
            exit();
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        // Log the logout
        if (isset($_SESSION['user_email'])) {
            error_log("User logged out: " . $_SESSION['user_email']);
        }
        
        // Clear session
        $_SESSION = [];
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        // Set success message and redirect
        $_SESSION['success'] = 'You have been logged out successfully';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
    
    /**
     * Show forgot password form
     */
    public function forgotPassword() {
        $data = [
            'title' => 'Forgot Password - Hospital Management System'
        ];
        
        $this->render('auth/forgot-password', $data);
    }
    
    /**
     * Process forgot password
     */
    public function doForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'forgot-password');
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            $_SESSION['error'] = 'Please enter your email address';
            header('Location: ' . BASE_URL . 'forgot-password');
            exit();
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user) {
            // In a real application, you would send an email here
            // For now, we'll just show the password (INSECURE - for testing only!)
            $_SESSION['info'] = 'Your password is: <strong>' . $user['password_hash'] . '</strong>';
        } else {
            // Don't reveal if email exists or not for security
            $_SESSION['info'] = 'If your email exists in our system, you will receive reset instructions.';
        }
        
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
    
    /**
     * Show reset password form
     */
    public function resetPassword($token) {
        $data = [
            'title' => 'Reset Password - Hospital Management System',
            'token' => $token
        ];
        
        $this->render('auth/reset-password', $data);
    }
    
    /**
     * Process reset password
     */
    public function doResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
        
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $token = $_POST['token'] ?? '';
        
        if ($password !== $confirm) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: ' . BASE_URL . 'reset-password/' . $token);
            exit();
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            header('Location: ' . BASE_URL . 'reset-password/' . $token);
            exit();
        }
        
        // In a real application, you would validate token and update password
        // For now, we'll just show success message
        $_SESSION['success'] = 'Password reset successful! Please login with your new password.';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
    
    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard() {
        $role = $_SESSION['user_role'] ?? '';
        
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . 'admin/dashboard');
                break;
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
    
    /**
     * Check if user is logged in (helper method)
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID (helper method)
     */
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role (helper method)
     */
    protected function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
}
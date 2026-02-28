<?php
/**
 * Authentication Controller - PLAIN TEXT PASSWORDS
 */

class AuthController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        require_once 'models/UserModel.php';
        $this->userModel = new UserModel();
    }
    
    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        $data = ['title' => 'Login'];
        $this->render('auth/login', $data, 'default');
    }
    
    /**
     * Process login - DIRECT COMPARISON (NO HASHING)
     */
    public function doLogin() {
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }
        
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate
        if (empty($login) || empty($password)) {
            $_SESSION['error'] = 'Please enter both email/username and password';
            $this->redirect('login');
            return;
        }
        
        // Find user
        $user = $this->userModel->findByEmailOrUsername($login);
        
        if (!$user) {
            $_SESSION['error'] = 'Invalid login credentials';
            $this->redirect('login');
            return;
        }
        
        // DIRECT COMPARISON - NO PASSWORD VERIFY
        if ($password === $user['password_hash']) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['success'] = 'Login successful!';
            
            // Update last login
            $this->userModel->updateLastLogin($user['id']);
            
            // Redirect to role-based dashboard
            $this->redirectToDashboard();
            
        } else {
            $_SESSION['error'] = 'Invalid login credentials';
            $this->redirect('login');
        }
    }
    
    /**
     * Show registration form
     */
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        $data = ['title' => 'Register'];
        $this->render('auth/register', $data, 'default');
    }
    
    /**
     * Process registration - PLAIN TEXT STORAGE
     */
    public function doRegister() {
        if (!$this->isPost()) {
            $this->redirect('register');
            return;
        }
        
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'role' => 'patient'
        ];
        
        // Validate
        $errors = [];
        
        if ($this->userModel->findByUsername($data['username'])) {
            $errors[] = 'Username already taken';
        }
        
        if ($this->userModel->findByEmail($data['email'])) {
            $errors[] = 'Email already registered';
        }
        
        if (strlen($data['password']) < 4) {
            $errors[] = 'Password must be at least 4 characters';
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Passwords do not match';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('register');
            return;
        }
        
        // Create user (plain text password)
        $userId = $this->userModel->createUser($data);
        
        if ($userId) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            $this->redirect('login');
        } else {
            $_SESSION['error'] = 'Registration failed';
            $this->redirect('register');
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
        $_SESSION['success'] = 'Logged out successfully';
        $this->redirect('');
    }
    
    /**
     * Show forgot password form
     */
    public function forgotPassword() {
        $data = ['title' => 'Forgot Password'];
        $this->render('auth/forgot-password', $data, 'default');
    }
    
    /**
     * Process forgot password - SIMPLE METHOD
     */
    public function doForgotPassword() {
        if (!$this->isPost()) {
            $this->redirect('forgot-password');
            return;
        }
        
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            $_SESSION['error'] = 'Please enter your email';
            $this->redirect('forgot-password');
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user) {
            // For demo: show password directly (INSECURE - for testing only)
            $_SESSION['info'] = 'Your password is: <strong>' . $user['password_hash'] . '</strong>';
        } else {
            $_SESSION['info'] = 'If your email exists, you will receive reset instructions.';
        }
        
        $this->redirect('login');
    }
    
    /**
     * Reset password page
     */
    public function resetPassword($token) {
        $data = [
            'title' => 'Reset Password',
            'token' => $token
        ];
        $this->render('auth/reset-password', $data, 'default');
    }
    
    /**
     * Process reset password - SIMPLE METHOD
     */
    public function doResetPassword() {
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }
        
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $token = $_POST['token'] ?? '';
        
        if ($password !== $confirm) {
            $_SESSION['error'] = 'Passwords do not match';
            $this->redirect('reset-password/' . $token);
            return;
        }
        
        if (strlen($password) < 4) {
            $_SESSION['error'] = 'Password too short';
            $this->redirect('reset-password/' . $token);
            return;
        }
        
        // For demo - just show success
        $_SESSION['success'] = 'Password reset successful! Please login.';
        $this->redirect('login');
    }

    /**
     * Redirect to role-based dashboard
     */
    private function redirectToDashboard() {
        $role = $_SESSION['user_role'] ?? '';
        
        switch ($role) {
            case 'admin':
                $this->redirect('admin/dashboard');
                break;
            case 'doctor':
                $this->redirect('doctor/dashboard');
                break;
            case 'patient':
                $this->redirect('patient/dashboard');
                break;
            default:
                $this->redirect('');
        }
    }
}
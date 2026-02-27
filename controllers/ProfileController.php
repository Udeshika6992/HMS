<?php
/**
 * Profile Controller - PLAIN TEXT VERSION
 * WARNING: This handles plain text passwords - INSECURE!
 */

class ProfileController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to access profile';
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
        
        require_once 'models/UserModel.php';
        $this->userModel = new UserModel();
    }
    
    /**
     * Show profile page
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];
        
        // Use appropriate layout based on role
        $layout = $_SESSION['user_role'] . '-layout';
        $this->render('profile/index', $data, $layout);
    }
    
    /**
     * Update profile
     */
    public function update() {
        if (!$this->isPost()) {
            $this->redirect('profile');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        $data = [
            'full_name' => $_POST['full_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];
        
        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                $data['profile_image'] = $filename;
            }
        }
        
        $result = $this->userModel->updateProfile($userId, $data);
        
        if ($result) {
            $_SESSION['success'] = 'Profile updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }
        
        $this->redirect('profile');
    }
    
    /**
     * Change password - PLAIN TEXT VERSION
     */
    public function changePassword() {
        if (!$this->isPost()) {
            $this->redirect('profile');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        // Get user
        $user = $this->userModel->find($userId);
        
        // ⚠️ Direct comparison - NO password verification!
        if ($current != $user['password_hash']) {
            $_SESSION['error'] = 'Current password is incorrect';
            $this->redirect('profile');
            return;
        }
        
        if ($new !== $confirm) {
            $_SESSION['error'] = 'New passwords do not match';
            $this->redirect('profile');
            return;
        }
        
        if (strlen($new) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('profile');
            return;
        }
        
        // Update password with plain text
        $result = $this->userModel->updatePassword($userId, $new);
        
        if ($result) {
            $_SESSION['success'] = 'Password changed successfully';
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }
        
        $this->redirect('profile');
    }
    
    /**
     * Check if POST request
     */
    private function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Redirect to URL
     */
    private function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }
}
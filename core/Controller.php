<?php
/**
 * Base Controller Class
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Load a model
     */
    protected function model($model) {
        $modelClass = $model . 'Model';
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        return null;
    }
    
    /**
     * Load a view
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        }
    }
    
    /**
     * Render a view with layout
     */
    protected function render($view, $data = [], $layout = 'default') {
        ob_start();
        $this->view($view, $data);
        $content = ob_get_clean();
        
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit();
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Get POST data
     */
    protected function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET data
     */
    protected function get($key = null, $default = null) {
        if ($key === null) return $_GET;
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Get current user ID
     */
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    protected function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser() {
        $userId = $this->getCurrentUserId();
        if (!$userId) return null;
        
        $userModel = $this->model('User');
        return $userModel->find($userId);
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCsrf() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token - FIXED VERSION with null checking
     */
    protected function validateCsrf($token) {
        // Check if token exists in session
        if (!isset($_SESSION['csrf_token'])) {
            error_log("CSRF Error: No token in session");
            return false;
        }
        
        // Check if token is null or empty
        if ($token === null || $token === '') {
            error_log("CSRF Error: Token is null or empty");
            return false;
        }
        
        // Convert to strings to ensure type safety
        $sessionToken = (string)$_SESSION['csrf_token'];
        $userToken = (string)$token;
        
        // Compare using hash_equals
        return hash_equals($sessionToken, $userToken);
    }
    
    /**
     * Get CSRF field HTML
     */
    protected function csrfField() {
        $token = $this->generateCsrf();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Validate request data
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $rulesList = explode('|', $rule);
            
            foreach ($rulesList as $singleRule) {
                if (strpos($singleRule, ':') !== false) {
                    list($ruleName, $parameter) = explode(':', $singleRule, 2);
                } else {
                    $ruleName = $singleRule;
                    $parameter = null;
                }
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value) && $value !== '0') {
                            $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                        }
                        break;
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = 'Invalid email format';
                        }
                        break;
                    case 'min':
                        if (strlen($value) < (int)$parameter) {
                            $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$parameter} characters";
                        }
                        break;
                    case 'max':
                        if (strlen($value) > (int)$parameter) {
                            $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$parameter} characters";
                        }
                        break;
                    case 'matches':
                        if ($value !== ($data[$parameter] ?? null)) {
                            $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must match " . str_replace('_', ' ', $parameter);
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Get pagination data
     */
    protected function getPaginationData($total, $page = 1, $perPage = 10) {
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $totalPages = ceil($total / $perPage);
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        
        return [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'offset' => $offset,
            'has_previous' => $page > 1,
            'has_next' => $page < $totalPages,
            'previous_page' => $page - 1,
            'next_page' => $page + 1
        ];
    }
    
    /**
     * Upload file
     */
    protected function uploadFile($file, $destination, $options = []) {
        $defaultOptions = [
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            'max_size' => 5 * 1024 * 1024 // 5MB
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'No file uploaded'];
        }
        
        if ($file['size'] > $options['max_size']) {
            return ['success' => false, 'error' => 'File too large'];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $options['allowed_types'])) {
            return ['success' => false, 'error' => 'File type not allowed'];
        }
        
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $targetPath = rtrim($destination, '/') . '/' . $filename;
        
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'error' => 'Failed to upload file'];
    }
    
    /**
     * Log activity
     */
    protected function logActivity($action, $table = null, $recordId = null, $oldData = null, $newData = null) {
        try {
            $userId = $this->getCurrentUserId();
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            
            $data = [
                'user_id' => $userId,
                'action' => $action,
                'table_name' => $table,
                'record_id' => $recordId,
                'old_data' => $oldData ? json_encode($oldData) : null,
                'new_data' => $newData ? json_encode($newData) : null,
                'ip_address' => $ip,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ];
            
            return $this->db->insert('activity_logs', $data);
        } catch (Exception $e) {
            error_log("Activity Log Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Debug function
     */
    protected function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}
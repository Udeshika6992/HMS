<?php
/**
 * Base Controller Class
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function model($model) {
        $modelClass = $model . 'Model';
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        return null;
    }
    
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        }
    }
    
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
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit();
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    protected function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    protected function setFlash($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}
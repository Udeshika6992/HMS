<?php
/**
 * View Class
 * Handles view rendering and template management
 * Location: /core/View.php
 */

class View {
    
    protected $data = [];
    protected $viewPath;
    protected $layout;
    
    /**
     * Constructor
     */
    public function __construct($viewPath = null, $data = []) {
        $this->viewPath = $viewPath;
        $this->data = $data;
    }
    
    /**
     * Set view data
     */
    public function setData($data) {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
    
    /**
     * Set layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }
    
    /**
     * Render view
     */
    public function render($viewPath = null, $data = []) {
        if ($viewPath) {
            $this->viewPath = $viewPath;
        }
        
        if (!empty($data)) {
            $this->setData($data);
        }
        
        // Extract data to variables
        extract($this->data);
        
        // Get view content
        $viewFile = __DIR__ . '/../views/' . $this->viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$viewFile}");
        }
        
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        
        // Render with layout if specified
        if ($this->layout) {
            $layoutFile = __DIR__ . '/../views/layouts/' . $this->layout . '.php';
            
            if (file_exists($layoutFile)) {
                include $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }
    
    /**
     * Render partial view (no layout)
     */
    public function renderPartial($viewPath, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$viewFile}");
        }
        
        include $viewFile;
    }
    
    /**
     * Escape output
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Include a partial view
     */
    public function partial($partialPath, $data = []) {
        extract($data);
        
        $partialFile = __DIR__ . '/../views/' . $partialPath . '.php';
        
        if (file_exists($partialFile)) {
            include $partialFile;
        }
    }
    
    /**
     * Get asset URL
     */
    public function asset($path) {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }
    
    /**
     * Generate URL
     */
    public function url($path) {
        return BASE_URL . ltrim($path, '/');
    }
    
    /**
     * Include CSRF token field
     */
    public function csrfField() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
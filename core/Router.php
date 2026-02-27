<?php
/**
 * ROUTER CLASS
 * Handles all URL routing and dispatching
 * Location: /core/Router.php
 */

class Router
{
    /**
     * Array to store all routes
     * @var array
     */
    private $routes = [];

    /**
     * Current route parameters
     * @var array
     */
    private $params = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = [];
        $this->params = [];
    }

    /**
     * Add a route to the router
     * 
     * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string $route URL pattern
     * @param string $controller Controller class name
     * @param string $action Controller method name
     * @param array $middleware Optional middleware classes
     * @return void
     */
    public function add($method, $route, $controller, $action, $middleware = [])
    {
        // Convert route to regex pattern
        // Replace {parameter} with regex capture group
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-zA-Z0-9\-_]+)', $route);
        $route = '/^' . $route . '$/';
        
        // Store route information
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $route,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch the current URL to the appropriate controller
     * 
     * @param string $url The URL to dispatch
     * @return void
     */
    public function dispatch($url)
    {
        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Clean URL
        $url = $this->removeQueryString($url);
        
        // Check each route for a match
        foreach ($this->routes as $route) {
            // Check if HTTP method matches
            if ($route['method'] !== $method && $route['method'] !== 'ANY') {
                continue;
            }
            
            // Check if pattern matches URL
            if (preg_match($route['pattern'], $url, $matches)) {
                // Extract named parameters
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                
                // Run middleware
                if (!$this->runMiddleware($route['middleware'])) {
                    return;
                }
                
                // Dispatch to controller
                $this->callController($route['controller'], $route['action'], $params);
                return;
            }
        }
        
        // No route found - 404
        $this->handleNotFound();
    }

    /**
     * Run middleware before controller
     * 
     * @param array $middleware List of middleware classes
     * @return bool
     */
    private function runMiddleware($middleware)
    {
        foreach ($middleware as $middlewareClass) {
            if (class_exists($middlewareClass)) {
                $mw = new $middlewareClass();
                if (method_exists($mw, 'handle')) {
                    if (!$mw->handle()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Call the appropriate controller and action
     * 
     * @param string $controller Controller class name
     * @param string $action Controller method name
     * @param array $params Parameters to pass
     * @return void
     */
    private function callController($controller, $action, $params = [])
    {
        // Check if controller class exists
        if (class_exists($controller)) {
            $controllerObject = new $controller();
            if (method_exists($controllerObject, $action)) {
                call_user_func_array([$controllerObject, $action], $params);
                return;
            }
        }
        
        // Try with namespace (for PSR-4 structure)
        $controllerWithNS = 'Controllers\\' . $controller;
        if (class_exists($controllerWithNS)) {
            $controllerObject = new $controllerWithNS();
            if (method_exists($controllerObject, $action)) {
                call_user_func_array([$controllerObject, $action], $params);
                return;
            }
        }
        
        // Controller or action not found - 500 error
        $this->handleServerError("Controller {$controller} or action {$action} not found");
    }

    /**
     * Handle 404 Not Found error - NO DEPENDENCY ON ErrorController
     * 
     * @return void
     */
    private function handleNotFound()
    {
        http_response_code(404);
        
        // Try to include and use ErrorController if it exists
        $errorControllerFile = __DIR__ . '/../controllers/ErrorController.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            if (class_exists('ErrorController')) {
                $controller = new ErrorController();
                if (method_exists($controller, 'notFound')) {
                    $controller->notFound();
                    return;
                }
            }
        }
        
        // Fallback to built-in error page
        $this->renderErrorPage(404, 'Page Not Found', 'The page you are looking for could not be found.');
    }

    /**
     * Handle 403 Forbidden error - NO DEPENDENCY ON ErrorController
     * 
     * @return void
     */
    private function handleForbidden()
    {
        http_response_code(403);
        
        // Try to include and use ErrorController if it exists
        $errorControllerFile = __DIR__ . '/../controllers/ErrorController.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            if (class_exists('ErrorController')) {
                $controller = new ErrorController();
                if (method_exists($controller, 'forbidden')) {
                    $controller->forbidden();
                    return;
                }
            }
        }
        
        // Fallback to built-in error page
        $this->renderErrorPage(403, 'Access Denied', 'You do not have permission to access this page.');
    }

    /**
     * Handle 500 Server Error - NO DEPENDENCY ON ErrorController
     * 
     * @param string $message Error message
     * @return void
     */
    private function handleServerError($message = '')
    {
        http_response_code(500);
        
        // Log the error
        error_log("500 Server Error: " . $message);
        
        // Try to include and use ErrorController if it exists
        $errorControllerFile = __DIR__ . '/../controllers/ErrorController.php';
        if (file_exists($errorControllerFile)) {
            require_once $errorControllerFile;
            if (class_exists('ErrorController')) {
                $controller = new ErrorController();
                if (method_exists($controller, 'serverError')) {
                    $controller->serverError();
                    return;
                }
            }
        }
        
        // Fallback to built-in error page
        $this->renderErrorPage(500, 'Server Error', 'An internal server error occurred. Please try again later.');
    }

    /**
     * Render a simple error page - BUILT-IN FALLBACK
     * 
     * @param int $code HTTP status code
     * @param string $title Error title
     * @param string $message Error message
     * @return void
     */
    private function renderErrorPage($code, $title, $message = '')
    {
        // Check if custom error view exists
        $viewFile = __DIR__ . '/../views/errors/' . $code . '.php';
        if (file_exists($viewFile)) {
            // Extract variables for the view
            $title = $title;
            $message = $message;
            $code = $code;
            require_once $viewFile;
            return;
        }
        
        // Get base URL
        $baseUrl = $this->getBaseUrl();
        
        // Fallback HTML error page
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<title>' . $code . ' - ' . $title . '</title>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<style>';
        echo 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px; }';
        echo '.error-container { text-align: center; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }';
        echo 'h1 { font-size: 72px; color: #e74c3c; margin: 0; line-height: 1; }';
        echo 'h2 { color: #333; margin: 10px 0 20px; }';
        echo 'p { color: #666; line-height: 1.6; margin-bottom: 30px; }';
        echo 'a { display: inline-block; padding: 12px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: 500; transition: background 0.3s; }';
        echo 'a:hover { background: #2980b9; }';
        echo '.error-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px; text-align: left; font-size: 14px; color: #666; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<div class="error-container">';
        echo '<h1>' . $code . '</h1>';
        echo '<h2>' . $title . '</h2>';
        if ($message) {
            echo '<p>' . htmlspecialchars($message) . '</p>';
        }
        echo '<a href="' . $baseUrl . '">Go to Homepage</a>';
        if ($code === 500 && defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo '<div class="error-details">';
            echo '<strong>Error Details:</strong> ' . htmlspecialchars($message);
            echo '</div>';
        }
        echo '</div>';
        echo '</body>';
        echo '</html>';
        exit;
    }

    /**
     * Remove query string from URL
     * 
     * @param string $url The URL to clean
     * @return string Cleaned URL
     */
    private function removeQueryString($url)
    {
        if ($url !== '') {
            $parts = explode('?', $url, 2);
            $url = $parts[0];
        }
        return trim($url, '/');
    }

    /**
     * Get base URL
     * 
     * @return string
     */
    private function getBaseUrl()
    {
        // Try to get from defined constant
        if (defined('BASE_URL')) {
            return BASE_URL;
        }
        
        // Try to determine from server
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = dirname($scriptName);
        return rtrim($scriptDir, '/') . '/';
    }

    /**
     * Get all registered routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get current route parameters
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
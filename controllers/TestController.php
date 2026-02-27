<?php
/**
 * Test Controller
 * For testing routing
 * Location: /controllers/TestController.php
 */

class TestController extends Controller {
    
    public function index() {
        // Use the controller's method to get BASE_URL safely
        $baseUrl = $this->getBaseUrl();
        
        echo "<!DOCTYPE html>";
        echo "<html>";
        echo "<head>";
        echo "<title>Router Test</title>";
        echo "<style>";
        echo "body { font-family: Arial; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }";
        echo ".container { background: white; padding: 40px; border-radius: 20px; max-width: 600px; }";
        echo "h1 { color: #27ae60; }";
        echo ".btn { background: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; }";
        echo ".info { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-top: 20px; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<div class='container'>";
        echo "<h1>✅ Router Test Successful!</h1>";
        echo "<p>If you see this page, your routing system is working!</p>";
        
        echo "<div class='info'>";
        echo "<p><strong>Base URL:</strong> " . $baseUrl . "</p>";
        echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
        echo "<p><strong>Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
        echo "</div>";
        
        echo "<a href='" . $baseUrl . "' class='btn'>Go to Homepage</a>";
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }
}
<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../core/Model.php';
require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../controllers/AuthController.php';

class AuthControllerTest extends TestCase
{
    private $authController;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the session
        $_SESSION = [];
        
        // Mock POST data
        $_POST = [];
        
        $this->authController = new AuthController();
    }
    
    /** @test */
    public function login_page_loads()
    {
        ob_start();
        $this->authController->login();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Login', $output);
    }
    
    /** @test */
    public function successful_login_redirects_to_dashboard()
    {
        // Mock POST data
        $_POST['login'] = 'admin@hospital.com';
        $_POST['password'] = 'password123';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        // Expect redirect
        $this->expectException(Exception::class);
        
        ob_start();
        $this->authController->doLogin();
        ob_end_clean();
        
        $this->assertEquals('admin', $_SESSION['user_role']);
    }
}
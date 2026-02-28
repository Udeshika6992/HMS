<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../core/Model.php';
require_once __DIR__ . '/../../../models/UserModel.php';

class UserModelTest extends TestCase
{
    private $userModel;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
    }
    
    /** @test */
    public function it_can_find_user_by_email()
    {
        // Arrange
        $email = 'admin@hospital.com';
        
        // Act
        $user = $this->userModel->findByEmail($email);
        
        // Assert
        $this->assertIsArray($user);
        $this->assertEquals($email, $user['email']);
    }
    
    /** @test */
    public function it_returns_false_for_nonexistent_email()
    {
        $user = $this->userModel->findByEmail('nonexistent@email.com');
        $this->assertFalse($user);
    }
    
    /** @test */
    public function it_can_create_a_new_user()
    {
        $data = [
            'username' => 'testuser_' . time(),
            'email' => 'test_' . time() . '@test.com',
            'password' => 'password123',
            'full_name' => 'Test User',
            'role' => 'patient'
        ];
        
        $userId = $this->userModel->createUser($data);
        
        $this->assertIsNumeric($userId);
        $this->assertGreaterThan(0, $userId);
        
        // Clean up
        $this->userModel->delete($userId);
    }
    
    /** @test */
    public function it_verifies_password_correctly()
    {
        $user = $this->userModel->findByEmail('admin@hospital.com');
        
        $this->assertTrue(
            $this->userModel->verifyPassword($user['id'], 'password123')
        );
        
        $this->assertFalse(
            $this->userModel->verifyPassword($user['id'], 'wrongpassword')
        );
    }
}
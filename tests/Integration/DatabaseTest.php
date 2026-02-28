<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/Database.php';

class DatabaseTest extends TestCase
{
    private $db;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->db = Database::getInstance();
    }
    
    /** @test */
    public function it_can_connect_to_database()
    {
        $connection = $this->db->getConnection();
        $this->assertNotNull($connection);
    }
    
    /** @test */
    public function it_can_query_database()
    {
        $result = $this->db->fetchOne("SELECT 1 as test");
        $this->assertEquals(1, $result['test']);
    }
    
    /** @test */
    public function it_can_check_table_exists()
    {
        $this->assertTrue($this->db->tableExists('users'));
        $this->assertFalse($this->db->tableExists('nonexistent_table'));
    }
}
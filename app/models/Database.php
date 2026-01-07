<?php
/**
 * -----------------------------------------------------------
 * Database.php (Model Layer)
 * -----------------------------------------------------------
 * This class manages the database connection using PDO.
 * It follows the Singleton Design Pattern to ensure that
 * only one connection instance exists throughout the system.
 *
 * It automatically loads credentials from /config/database.php
 * -----------------------------------------------------------
 */

require_once __DIR__ . '/../../config/database.php';

class Database {
    // Static instance for Singleton pattern
    private static $instance = null;
    private $conn;

    // Load database credentials from config
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;

    /**
     * ✅ Private constructor — creates PDO connection once
     */
    private function __construct() {
        try {
            // Data Source Name (DSN)
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            
            // Create PDO instance
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Set PDO attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Display connection error clearly
            die("❌ Database Connection Failed: " . $e->getMessage());
        }
    }

    /**
     * ✅ Singleton access method
     * Ensures one shared instance across all models
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * ✅ Get active PDO connection
     */
    public function connect() {
        return $this->conn;
    }

    /**
     * ❌ Prevent object cloning (Singleton rule)
     */
    private function __clone() {}

    /**
     * ❌ Prevent unserialization (Singleton rule)
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}
?>

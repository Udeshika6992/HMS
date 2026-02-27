<?php
/**
 * Database Singleton Class
 */

class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $user;
    private $pass;
    private $name;
    
    /**
     * Private constructor
     */
    private function __construct() {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->name = DB_NAME;
        $this->connect();
    }
    
    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute a query with parameters
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            error_log("Query Error: " . $e->getMessage() . " SQL: " . $sql);
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    
    /**
     * Fetch a single record
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Fetch all records
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Fetch paginated results - ADD THIS METHOD
     */
    public function fetchPaginated($sql, $params = [], $page = 1, $perPage = 10) {
        try {
            $page = max(1, (int)$page);
            $perPage = max(1, (int)$perPage);
            $offset = ($page - 1) * $perPage;
            
            // Get total count (remove ORDER BY and LIMIT)
            $countSql = preg_replace('/ORDER BY .*$/i', '', $sql);
            $countSql = preg_replace('/LIMIT .*$/i', '', $countSql);
            $countSql = "SELECT COUNT(*) as total FROM (" . $countSql . ") as count_table";
            
            $stmt = $this->connection->prepare($countSql);
            $stmt->execute($params);
            $total = $stmt->fetch()['total'] ?? 0;
            
            // Add pagination to original query
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => (int)$total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
        } catch (PDOException $e) {
            error_log("fetchPaginated Error: " . $e->getMessage());
            return [
                'data' => [],
                'total' => 0,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => 0
            ];
        }
    }
    
    /**
     * Insert a record
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_values($data));
            return $this->connection->lastInsertId();
        } catch(PDOException $e) {
            error_log("Insert Error: " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }
    
    /**
     * Update a record
     */
    public function update($table, $data, $where, $whereParams = []) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            error_log("Update Error: " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }
    
    /**
     * Delete a record
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if record exists
     */
    public function exists($table, $where, $params = []) {
        $sql = "SELECT 1 FROM {$table} WHERE {$where} LIMIT 1";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("Exists Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Count records
     */
    public function count($table, $where = '1', $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$where}";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch(PDOException $e) {
            error_log("Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->connection->rollBack();
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Check if table exists
     */
    public function tableExists($table) {
        try {
            $sql = "SHOW TABLES LIKE ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$table]);
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("TableExists Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get database name
     */
    public function getDatabaseName() {
        return $this->name;
    }
    
    /**
     * Get database host
     */
    public function getHost() {
        return $this->host;
    }
    
    /**
     * Get server version
     */
    public function getServerInfo() {
        try {
            return $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION);
        } catch(PDOException $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Check if connected
     */
    public function isConnected() {
        return $this->connection !== null;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialize
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
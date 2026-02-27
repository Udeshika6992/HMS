<?php
/**
 * Base Model Class
 * All models should extend this class
 * Location: /core/Model.php
 */

// Ensure Database class is loaded
if (!class_exists('Database')) {
    require_once __DIR__ . '/../config/Database.php';
}

class Model {
    
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
        
        if (empty($this->table)) {
            $this->table = $this->getTableNameFromClass();
        }
    }
    
    /**
     * Get table name from class name
     */
    private function getTableNameFromClass() {
        $className = get_class($this);
        $className = str_replace('Model', '', $className);
        $className = preg_replace('/(?<!^)[A-Z]/', '_$0', $className);
        return strtolower($className) . 's';
    }
    
    /**
     * Find record by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Get all records
     */
    public function all($orderBy = 'created_at', $direction = 'DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Create new record
     */
    public function create($data) {
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable) || empty($this->fillable)) {
                $filteredData[$key] = $value;
            }
        }
        
        if (!isset($filteredData['created_at'])) {
            $filteredData['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($filteredData['updated_at'])) {
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->insert($this->table, $filteredData);
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable) || empty($this->fillable)) {
                $filteredData[$key] = $value;
            }
        }
        
        $filteredData['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update(
            $this->table,
            $filteredData,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }
    
    /**
     * Get count of records
     */
    public function count($where = '1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
    
    /**
     * Check if record exists
     */
    public function exists($where, $params = []) {
        return $this->db->exists($this->table, $where, $params);
    }
    
    /**
     * Paginate results
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'created_at', $direction = 'DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        return $this->db->fetchPaginated($sql, [], $page, $perPage);
    }
    
    /**
     * Get recent records
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql);
    }
}
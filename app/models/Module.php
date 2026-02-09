<?php
/**
 * Module Model
 * Handles module-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class Module {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new module
     */
    public function create($data) {
        $sql = "INSERT INTO modules (course_id, title, description, order_number) 
                VALUES (:course_id, :title, :description, :order_number)";
        return $this->db->insert($sql, $data);
    }
    
    /**
     * Find module by ID
     */
    public function findById($id) {
        $sql = "SELECT m.*, c.title as course_title 
                FROM modules m 
                INNER JOIN courses c ON m.course_id = c.id 
                WHERE m.id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get modules by course
     */
    public function getByCourse($courseId) {
        $sql = "SELECT * FROM modules WHERE course_id = :course_id ORDER BY order_number ASC";
        return $this->db->fetchAll($sql, ['course_id' => $courseId]);
    }
    
    /**
     * Update module
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE modules SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete module
     */
    public function delete($id) {
        $sql = "DELETE FROM modules WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Reorder modules
     */
    public function reorder($courseId, $moduleIds) {
        $this->db->beginTransaction();
        
        try {
            $order = 1;
            foreach ($moduleIds as $moduleId) {
                $sql = "UPDATE modules SET order_number = :order_number WHERE id = :id AND course_id = :course_id";
                $this->db->execute($sql, [
                    'order_number' => $order,
                    'id' => $moduleId,
                    'course_id' => $courseId
                ]);
                $order++;
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}

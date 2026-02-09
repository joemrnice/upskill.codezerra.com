<?php
/**
 * Resource Model
 * Handles resource-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class Resource {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new resource
     */
    public function create($data) {
        $sql = "INSERT INTO resources (module_id, title, type, file_path, file_size, order_number) 
                VALUES (:module_id, :title, :type, :file_path, :file_size, :order_number)";
        return $this->db->insert($sql, $data);
    }
    
    /**
     * Find resource by ID
     */
    public function findById($id) {
        $sql = "SELECT r.*, m.title as module_title, m.course_id 
                FROM resources r 
                INNER JOIN modules m ON r.module_id = m.id 
                WHERE r.id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get resources by module
     */
    public function getByModule($moduleId) {
        $sql = "SELECT * FROM resources WHERE module_id = :module_id ORDER BY order_number ASC";
        return $this->db->fetchAll($sql, ['module_id' => $moduleId]);
    }
    
    /**
     * Update resource
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
        
        $sql = "UPDATE resources SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete resource
     */
    public function delete($id) {
        $sql = "DELETE FROM resources WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Mark resource as completed for user
     */
    public function markCompleted($userId, $resourceId) {
        // Check if already exists
        $sql = "SELECT id FROM progress_tracking WHERE user_id = :user_id AND resource_id = :resource_id";
        $existing = $this->db->fetch($sql, ['user_id' => $userId, 'resource_id' => $resourceId]);
        
        if ($existing) {
            $sql = "UPDATE progress_tracking SET completed = 1, completed_at = NOW() 
                    WHERE user_id = :user_id AND resource_id = :resource_id";
            return $this->db->execute($sql, ['user_id' => $userId, 'resource_id' => $resourceId]);
        } else {
            $sql = "INSERT INTO progress_tracking (user_id, resource_id, completed, completed_at) 
                    VALUES (:user_id, :resource_id, 1, NOW())";
            return $this->db->insert($sql, ['user_id' => $userId, 'resource_id' => $resourceId]);
        }
    }
    
    /**
     * Check if resource is completed by user
     */
    public function isCompleted($userId, $resourceId) {
        $sql = "SELECT completed FROM progress_tracking 
                WHERE user_id = :user_id AND resource_id = :resource_id";
        $result = $this->db->fetch($sql, ['user_id' => $userId, 'resource_id' => $resourceId]);
        
        return $result ? (bool)$result['completed'] : false;
    }
    
    /**
     * Get user progress for all resources in a course
     */
    public function getUserCourseProgress($userId, $courseId) {
        $sql = "SELECT r.id, r.title, r.type, pt.completed 
                FROM resources r 
                INNER JOIN modules m ON r.module_id = m.id 
                LEFT JOIN progress_tracking pt ON r.id = pt.resource_id AND pt.user_id = :user_id 
                WHERE m.course_id = :course_id 
                ORDER BY m.order_number, r.order_number";
        
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Reorder resources
     */
    public function reorder($moduleId, $resourceIds) {
        $this->db->beginTransaction();
        
        try {
            $order = 1;
            foreach ($resourceIds as $resourceId) {
                $sql = "UPDATE resources SET order_number = :order_number WHERE id = :id AND module_id = :module_id";
                $this->db->execute($sql, [
                    'order_number' => $order,
                    'id' => $resourceId,
                    'module_id' => $moduleId
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

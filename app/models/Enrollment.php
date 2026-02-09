<?php
/**
 * Enrollment Model
 * Handles enrollment-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class Enrollment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Enroll user in a course
     */
    public function enroll($userId, $courseId) {
        // Check if already enrolled
        if ($this->isEnrolled($userId, $courseId)) {
            return false;
        }
        
        $sql = "INSERT INTO enrollments (user_id, course_id, enrollment_date, progress, status) 
                VALUES (:user_id, :course_id, NOW(), 0, 'in_progress')";
        
        return $this->db->insert($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolled($userId, $courseId) {
        $sql = "SELECT COUNT(*) as count FROM enrollments WHERE user_id = :user_id AND course_id = :course_id";
        $result = $this->db->fetch($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
        return $result['count'] > 0;
    }
    
    /**
     * Get user enrollments
     */
    public function getUserEnrollments($userId) {
        $sql = "SELECT e.*, c.title, c.description, c.thumbnail, c.difficulty_level, c.instructor_name 
                FROM enrollments e 
                INNER JOIN courses c ON e.course_id = c.id 
                WHERE e.user_id = :user_id 
                ORDER BY e.enrollment_date DESC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Get enrollment by ID
     */
    public function findById($id) {
        $sql = "SELECT e.*, c.title as course_title, u.name as user_name 
                FROM enrollments e 
                INNER JOIN courses c ON e.course_id = c.id 
                INNER JOIN users u ON e.user_id = u.id 
                WHERE e.id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get enrollment by user and course
     */
    public function getEnrollment($userId, $courseId) {
        $sql = "SELECT * FROM enrollments WHERE user_id = :user_id AND course_id = :course_id";
        return $this->db->fetch($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Update progress
     */
    public function updateProgress($userId, $courseId, $progress) {
        $sql = "UPDATE enrollments SET progress = :progress WHERE user_id = :user_id AND course_id = :course_id";
        return $this->db->execute($sql, [
            'progress' => $progress,
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Mark course as completed
     */
    public function markCompleted($userId, $courseId) {
        $sql = "UPDATE enrollments SET status = 'completed', progress = 100, completed_at = NOW() 
                WHERE user_id = :user_id AND course_id = :course_id";
        return $this->db->execute($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Get all enrollments for admin
     */
    public function getAll($page = 1, $perPage = 20, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        $sql = "SELECT e.*, c.title as course_title, u.name as user_name, u.email as user_email 
                FROM enrollments e 
                INNER JOIN courses c ON e.course_id = c.id 
                INNER JOIN users u ON e.user_id = u.id 
                WHERE 1=1";
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['course_id'])) {
            $sql .= " AND e.course_id = :course_id";
            $params['course_id'] = $filters['course_id'];
        }
        
        $sql .= " ORDER BY e.enrollment_date DESC LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total enrollment count
     */
    public function getTotalCount($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['course_id'])) {
            $sql .= " AND course_id = :course_id";
            $params['course_id'] = $filters['course_id'];
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    /**
     * Delete enrollment
     */
    public function delete($id) {
        $sql = "DELETE FROM enrollments WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Get enrollment statistics
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_enrollments,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count,
                AVG(progress) as average_progress,
                SUM(CASE WHEN DATE(enrollment_date) = CURDATE() THEN 1 ELSE 0 END) as new_today
                FROM enrollments";
        return $this->db->fetch($sql);
    }
    
    /**
     * Calculate user progress in a course based on completed resources
     */
    public function calculateProgress($userId, $courseId) {
        // Get total resources in course
        $sql = "SELECT COUNT(r.id) as total 
                FROM resources r 
                INNER JOIN modules m ON r.module_id = m.id 
                WHERE m.course_id = :course_id";
        $totalResult = $this->db->fetch($sql, ['course_id' => $courseId]);
        $total = $totalResult['total'];
        
        if ($total == 0) {
            return 0;
        }
        
        // Get completed resources
        $sql = "SELECT COUNT(pt.id) as completed 
                FROM progress_tracking pt 
                INNER JOIN resources r ON pt.resource_id = r.id 
                INNER JOIN modules m ON r.module_id = m.id 
                WHERE m.course_id = :course_id AND pt.user_id = :user_id AND pt.completed = 1";
        $completedResult = $this->db->fetch($sql, [
            'course_id' => $courseId,
            'user_id' => $userId
        ]);
        $completed = $completedResult['completed'];
        
        $progress = ($completed / $total) * 100;
        
        // Update enrollment progress
        $this->updateProgress($userId, $courseId, $progress);
        
        // Mark as completed if 100%
        if ($progress >= 100) {
            $this->markCompleted($userId, $courseId);
        }
        
        return $progress;
    }
}

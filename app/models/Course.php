<?php
/**
 * Course Model
 * Handles course-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class Course {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new course
     */
    public function create($data) {
        $sql = "INSERT INTO courses (title, description, category, difficulty_level, thumbnail, 
                duration, instructor_name, prerequisites, status, created_by) 
                VALUES (:title, :description, :category, :difficulty_level, :thumbnail, 
                :duration, :instructor_name, :prerequisites, :status, :created_by)";
        
        return $this->db->insert($sql, $data);
    }
    
    /**
     * Find course by ID
     */
    public function findById($id) {
        $sql = "SELECT c.*, u.name as creator_name 
                FROM courses c 
                LEFT JOIN users u ON c.created_by = u.id 
                WHERE c.id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get all published courses
     */
    public function getAllPublished($page = 1, $perPage = 12, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        $sql = "SELECT c.*, COUNT(DISTINCT e.id) as enrollment_count 
                FROM courses c 
                LEFT JOIN enrollments e ON c.id = e.course_id 
                WHERE c.status = 'published'";
        
        // Apply filters
        if (!empty($filters['search'])) {
            $sql .= " AND (c.title LIKE :search OR c.description LIKE :search OR c.instructor_name LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        if (!empty($filters['category'])) {
            $sql .= " AND c.category = :category";
            $params['category'] = $filters['category'];
        }
        
        if (!empty($filters['difficulty'])) {
            $sql .= " AND c.difficulty_level = :difficulty";
            $params['difficulty'] = $filters['difficulty'];
        }
        
        $sql .= " GROUP BY c.id";
        
        // Apply sorting
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $sql .= " ORDER BY c.created_at DESC";
                    break;
                case 'popular':
                    $sql .= " ORDER BY enrollment_count DESC";
                    break;
                case 'title':
                    $sql .= " ORDER BY c.title ASC";
                    break;
                default:
                    $sql .= " ORDER BY c.created_at DESC";
            }
        } else {
            $sql .= " ORDER BY c.created_at DESC";
        }
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get all courses (including drafts) for admin
     */
    public function getAll($page = 1, $perPage = 20, $search = '') {
        $offset = ($page - 1) * $perPage;
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        $sql = "SELECT c.*, u.name as creator_name, COUNT(DISTINCT e.id) as enrollment_count 
                FROM courses c 
                LEFT JOIN users u ON c.created_by = u.id 
                LEFT JOIN enrollments e ON c.id = e.course_id 
                WHERE 1=1";
        
        if (!empty($search)) {
            $sql .= " AND (c.title LIKE :search OR c.category LIKE :search OR c.instructor_name LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        $sql .= " GROUP BY c.id ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total course count
     */
    public function getTotalCount($search = '', $published = false) {
        $sql = "SELECT COUNT(*) as total FROM courses WHERE 1=1";
        $params = [];
        
        if ($published) {
            $sql .= " AND status = 'published'";
        }
        
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR category LIKE :search OR instructor_name LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    /**
     * Update course
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
        
        $sql = "UPDATE courses SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete course
     */
    public function delete($id) {
        $sql = "DELETE FROM courses WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Get course modules
     */
    public function getModules($courseId) {
        $sql = "SELECT * FROM modules WHERE course_id = :course_id ORDER BY order_number ASC";
        return $this->db->fetchAll($sql, ['course_id' => $courseId]);
    }
    
    /**
     * Get course with modules and resources
     */
    public function getCourseWithContent($courseId) {
        $course = $this->findById($courseId);
        
        if (!$course) {
            return null;
        }
        
        // Get modules
        $modules = $this->getModules($courseId);
        
        // Get resources for each module
        foreach ($modules as &$module) {
            $sql = "SELECT * FROM resources WHERE module_id = :module_id ORDER BY order_number ASC";
            $module['resources'] = $this->db->fetchAll($sql, ['module_id' => $module['id']]);
        }
        
        $course['modules'] = $modules;
        return $course;
    }
    
    /**
     * Get distinct categories
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM courses WHERE status = 'published' ORDER BY category ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get course statistics
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_courses,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_count,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_count,
                (SELECT COUNT(*) FROM enrollments) as total_enrollments
                FROM courses";
        return $this->db->fetch($sql);
    }
    
    /**
     * Get popular courses
     */
    public function getPopular($limit = 5) {
        $sql = "SELECT c.*, COUNT(e.id) as enrollment_count 
                FROM courses c 
                LEFT JOIN enrollments e ON c.id = e.course_id 
                WHERE c.status = 'published' 
                GROUP BY c.id 
                ORDER BY enrollment_count DESC 
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    /**
     * Get recent courses
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
}

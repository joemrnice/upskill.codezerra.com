<?php
/**
 * Dashboard Controller
 * Handles user dashboard
 */

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Database.php';

class DashboardController {
    private $courseModel;
    private $enrollmentModel;
    private $db;
    
    public function __construct() {
        // Check authentication
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to access the dashboard.');
            redirect(base_url('public/auth/login.php'));
        }
    }
    
    /**
     * Show user dashboard
     */
    public function index() {
        $userId = Session::getUserId();
        
        try {
            // Lazy load models and database connection
            $this->courseModel = new Course();
            $this->enrollmentModel = new Enrollment();
            $this->db = Database::getInstance();
        
            // Get user's enrolled courses with progress
            $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
            
            // Get available courses (not enrolled)
            $availableCourses = $this->getAvailableCourses($userId);
            
            // Get recent activity
            $recentActivity = $this->getRecentActivity($userId);
            
            // Get user certificates
            $certificates = $this->getUserCertificates($userId);
            
            // Get quick stats
            $stats = [
                'enrolled' => count($enrolledCourses),
                'completed' => count(array_filter($enrolledCourses, function($course) {
                    return $course['status'] === 'completed';
                })),
                'certificates' => count($certificates)
            ];
            
            view('user/dashboard', [
                'enrolledCourses' => $enrolledCourses,
                'availableCourses' => $availableCourses,
                'recentActivity' => $recentActivity,
                'certificates' => $certificates,
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            // Handle database errors gracefully
            error_log("DashboardController Error: " . $e->getMessage());
            
            view('user/dashboard', [
                'enrolledCourses' => [],
                'availableCourses' => [],
                'recentActivity' => [],
                'certificates' => [],
                'stats' => [
                    'enrolled' => 0,
                    'completed' => 0,
                    'certificates' => 0
                ],
                'db_error' => true,
                'error_message' => 'Unable to load dashboard data. Please ensure the database is configured properly.'
            ]);
        }
    }
    
    /**
     * Get courses user is not enrolled in
     */
    private function getAvailableCourses($userId) {
        $sql = "SELECT c.*, COUNT(e.id) as enrollment_count 
                FROM courses c 
                LEFT JOIN enrollments e ON c.id = e.course_id 
                WHERE c.status = 'published' 
                AND c.id NOT IN (
                    SELECT course_id FROM enrollments WHERE user_id = :user_id
                )
                GROUP BY c.id 
                ORDER BY enrollment_count DESC, c.created_at DESC 
                LIMIT 6";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Get recent activity for user
     */
    private function getRecentActivity($userId) {
        $sql = "SELECT 
                    'enrollment' as type,
                    c.title as course_title,
                    e.enrollment_date as date,
                    NULL as score
                FROM enrollments e
                INNER JOIN courses c ON e.course_id = c.id
                WHERE e.user_id = :user_id
                
                UNION ALL
                
                SELECT 
                    'completion' as type,
                    c.title as course_title,
                    e.completed_at as date,
                    NULL as score
                FROM enrollments e
                INNER JOIN courses c ON e.course_id = c.id
                WHERE e.user_id = :user_id AND e.status = 'completed' AND e.completed_at IS NOT NULL
                
                UNION ALL
                
                SELECT 
                    'assessment' as type,
                    c.title as course_title,
                    ua.submitted_at as date,
                    ua.percentage as score
                FROM user_assessments ua
                INNER JOIN assessments a ON ua.assessment_id = a.id
                INNER JOIN courses c ON a.course_id = c.id
                WHERE ua.user_id = :user_id
                
                ORDER BY date DESC
                LIMIT 10";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    /**
     * Get user certificates
     */
    private function getUserCertificates($userId) {
        $sql = "SELECT cert.*, c.title as course_title, c.category 
                FROM certificates cert
                INNER JOIN courses c ON cert.course_id = c.id
                WHERE cert.user_id = :user_id
                ORDER BY cert.issued_date DESC";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}

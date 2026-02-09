<?php
/**
 * Home Controller
 * Handles landing page
 */

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Enrollment.php';

class HomeController {
    private $courseModel;
    private $userModel;
    private $enrollmentModel;
    
    public function __construct() {
        // Models will be instantiated lazily when needed
    }
    
    /**
     * Show landing page with statistics
     */
    public function index() {
        try {
            // Lazy load models
            $this->courseModel = new Course();
            $this->userModel = new User();
            $this->enrollmentModel = new Enrollment();
            
            // Get real statistics from database
            $courseStats = $this->courseModel->getStats();
            $userStats = $this->userModel->getStats();
            $enrollmentStats = $this->enrollmentModel->getStats();
            
            // Calculate completion rate
            $completionRate = 0;
            if ($enrollmentStats['total_enrollments'] > 0) {
                $completionRate = round(($enrollmentStats['completed_count'] / $enrollmentStats['total_enrollments']) * 100);
            }
            
            // Get popular/recent courses
            $popularCourses = $this->courseModel->getPopular(6);
            
            // Prepare statistics
            $stats = [
                'courses_offered' => $courseStats['published_count'],
                'students_enrolled' => $userStats['total_users'],
                'completion_rate' => $completionRate,
                'certificates_issued' => $enrollmentStats['completed_count']
            ];
            
            view('home/index', [
                'stats' => $stats,
                'courses' => $popularCourses
            ]);
            
        } catch (Exception $e) {
            // If database not set up, show landing page with default values
            error_log("HomeController Error: " . $e->getMessage());
            
            view('home/index', [
                'stats' => [
                    'courses_offered' => 0,
                    'students_enrolled' => 0,
                    'completion_rate' => 0,
                    'certificates_issued' => 0
                ],
                'courses' => [],
                'db_error' => true
            ]);
        }
    }
}

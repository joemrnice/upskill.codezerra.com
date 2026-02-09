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
        $this->courseModel = new Course();
        $this->userModel = new User();
        $this->enrollmentModel = new Enrollment();
    }
    
    /**
     * Show landing page with statistics
     */
    public function index() {
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
    }
}

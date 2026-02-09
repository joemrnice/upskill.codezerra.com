<?php
/**
 * Admin Controller
 * Handles admin dashboard and main admin operations
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Assessment.php';

class AdminController {
    private $userModel;
    private $courseModel;
    private $enrollmentModel;
    private $assessmentModel;
    
    public function __construct() {
        // Check if user is admin
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->assessmentModel = new Assessment();
    }
    
    /**
     * Display admin dashboard
     */
    public function dashboard() {
        // Get statistics
        $userStats = $this->userModel->getStats();
        $courseStats = $this->courseModel->getStats();
        $enrollmentStats = $this->enrollmentModel->getStats();
        
        // Get recent enrollments
        $recentEnrollments = $this->enrollmentModel->getAll(1, 5);
        
        // Get popular courses
        $popularCourses = $this->courseModel->getPopular(5);
        
        // Get recent courses
        $recentCourses = $this->courseModel->getRecent(5);
        
        $pageTitle = 'Admin Dashboard - ' . SITE_NAME;
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}

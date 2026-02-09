<?php
/**
 * Admin Enrollment Controller
 * Handles enrollment management operations
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Course.php';

class AdminEnrollmentController {
    private $enrollmentModel;
    private $courseModel;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
    }
    
    /**
     * Display all enrollments
     */
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [
            'status' => $_GET['status'] ?? '',
            'course_id' => $_GET['course_id'] ?? ''
        ];
        
        $enrollments = $this->enrollmentModel->getAll($page, 20, $filters);
        $totalEnrollments = $this->enrollmentModel->getTotalCount($filters);
        $totalPages = ceil($totalEnrollments / 20);
        
        // Get all courses for filter dropdown
        $courses = $this->courseModel->getAll(1, 1000);
        
        $pageTitle = 'Manage Enrollments - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/enrollments/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Delete enrollment
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/enrollments');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/enrollments');
            exit;
        }
        
        $enrollment = $this->enrollmentModel->findById($id);
        
        if (!$enrollment) {
            Session::setFlash('error', 'Enrollment not found.');
            header('Location: /admin/enrollments');
            exit;
        }
        
        if ($this->enrollmentModel->delete($id)) {
            Session::setFlash('success', 'Enrollment deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete enrollment.');
        }
        
        header('Location: /admin/enrollments');
        exit;
    }
}

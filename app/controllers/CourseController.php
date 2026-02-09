<?php
/**
 * Course Controller
 * Handles course catalog and enrollment
 */

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../helpers/Session.php';

class CourseController {
    private $courseModel;
    private $enrollmentModel;
    
    public function __construct() {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
    }
    
    /**
     * Show course catalog with search/filter/sort
     */
    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'difficulty' => $_GET['difficulty'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        
        $courses = $this->courseModel->getAllPublished($page, $perPage, $filters);
        $totalCourses = $this->courseModel->getTotalCount('', true);
        $totalPages = ceil($totalCourses / $perPage);
        
        // Get categories for filter
        $categories = $this->courseModel->getCategories();
        
        // Get user's enrolled courses if logged in
        $enrolledCourseIds = [];
        if (Session::isLoggedIn()) {
            $userId = Session::get('user_id');
            $enrollments = $this->enrollmentModel->getUserEnrollments($userId);
            $enrolledCourseIds = array_column($enrollments, 'course_id');
        }
        
        view('courses/index', [
            'pageTitle' => 'Course Catalog - ' . SITE_NAME,
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $filters,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'enrolledCourseIds' => $enrolledCourseIds
        ]);
    }
    
    /**
     * Show course details
     */
    public function show() {
        if (!isset($_GET['id'])) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        $courseId = (int)$_GET['id'];
        $course = $this->courseModel->getCourseWithContent($courseId);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        // Check if user is enrolled
        $isEnrolled = false;
        $enrollment = null;
        
        if (Session::isLoggedIn()) {
            $userId = Session::get('user_id');
            $isEnrolled = $this->enrollmentModel->isEnrolled($userId, $courseId);
            if ($isEnrolled) {
                $enrollment = $this->enrollmentModel->getEnrollment($userId, $courseId);
            }
        }
        
        view('courses/show', [
            'pageTitle' => $course['title'] . ' - ' . SITE_NAME,
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'enrollment' => $enrollment
        ]);
    }
    
    /**
     * Show enrollment confirmation page
     */
    public function enroll() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to enroll in courses.');
            redirect(base_url('login.php'));
            return;
        }
        
        if (!isset($_GET['id'])) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        $courseId = (int)$_GET['id'];
        $course = $this->courseModel->findById($courseId);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        // Check if already enrolled
        $userId = Session::get('user_id');
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            Session::setFlash('error', 'You are already enrolled in this course.');
            redirect(base_url('course.php?id=' . $courseId));
            return;
        }
        
        view('courses/enroll', [
            'pageTitle' => 'Enroll - ' . $course['title'],
            'course' => $course
        ]);
    }
    
    /**
     * Process enrollment
     */
    public function processEnroll() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to enroll in courses.');
            redirect(base_url('login.php'));
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('courses.php'));
            return;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('courses.php'));
            return;
        }
        
        $courseId = (int)($_POST['course_id'] ?? 0);
        $userId = Session::get('user_id');
        
        // Verify course exists
        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        // Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            Session::setFlash('error', 'You are already enrolled in this course.');
            redirect(base_url('course.php?id=' . $courseId));
            return;
        }
        
        // Enroll user
        $enrollmentId = $this->enrollmentModel->enroll($userId, $courseId);
        
        if ($enrollmentId) {
            Session::setFlash('success', 'Successfully enrolled in ' . $course['title'] . '!');
            redirect(base_url('learning.php?id=' . $courseId));
        } else {
            Session::setFlash('error', 'Failed to enroll. Please try again.');
            redirect(base_url('course.php?id=' . $courseId));
        }
    }
}

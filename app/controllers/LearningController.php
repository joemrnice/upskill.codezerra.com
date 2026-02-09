<?php
/**
 * Learning Controller
 * Handles learning interface and progress tracking
 */

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Resource.php';
require_once __DIR__ . '/../helpers/Session.php';

class LearningController {
    private $courseModel;
    private $enrollmentModel;
    private $resourceModel;
    
    public function __construct() {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->resourceModel = new Resource();
    }
    
    /**
     * Show learning interface
     */
    public function show() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to access courses.');
            redirect(base_url('auth/login.php'));
            return;
        }
        
        if (!isset($_GET['id'])) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        $courseId = (int)$_GET['id'];
        $userId = Session::get('user_id');
        
        // Check if enrolled
        if (!$this->enrollmentModel->isEnrolled($userId, $courseId)) {
            Session::setFlash('error', 'You must enroll in this course first.');
            redirect(base_url('course.php?id=' . $courseId));
            return;
        }
        
        // Get course with modules and resources
        $course = $this->courseModel->getCourseWithContent($courseId);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            redirect(base_url('courses.php'));
            return;
        }
        
        // Get enrollment info
        $enrollment = $this->enrollmentModel->getEnrollment($userId, $courseId);
        
        // Determine current resource
        $resourceId = isset($_GET['resource']) ? (int)$_GET['resource'] : null;
        $currentResource = null;
        
        // If no resource specified, find first resource
        if (!$resourceId && !empty($course['modules'])) {
            foreach ($course['modules'] as $module) {
                if (!empty($module['resources'])) {
                    $resourceId = $module['resources'][0]['id'];
                    break;
                }
            }
        }
        
        // Get resource details
        if ($resourceId) {
            $currentResource = $this->resourceModel->findById($resourceId);
            
            // Verify resource belongs to this course
            if (!$currentResource || $currentResource['course_id'] != $courseId) {
                $currentResource = null;
            }
        }
        
        // Get user progress for all resources
        $progress = $this->resourceModel->getUserCourseProgress($userId, $courseId);
        $completedResourceIds = [];
        foreach ($progress as $item) {
            if ($item['completed']) {
                $completedResourceIds[] = $item['id'];
            }
        }
        
        // Find next and previous resources
        $allResources = [];
        foreach ($course['modules'] as $module) {
            foreach ($module['resources'] as $resource) {
                $allResources[] = $resource;
            }
        }
        
        $currentIndex = -1;
        if ($resourceId) {
            foreach ($allResources as $index => $resource) {
                if ($resource['id'] == $resourceId) {
                    $currentIndex = $index;
                    break;
                }
            }
        }
        
        $prevResource = ($currentIndex > 0) ? $allResources[$currentIndex - 1] : null;
        $nextResource = ($currentIndex < count($allResources) - 1) ? $allResources[$currentIndex + 1] : null;
        
        view('learning/show', [
            'pageTitle' => 'Learning: ' . $course['title'],
            'course' => $course,
            'enrollment' => $enrollment,
            'currentResource' => $currentResource,
            'completedResourceIds' => $completedResourceIds,
            'prevResource' => $prevResource,
            'nextResource' => $nextResource
        ]);
    }
    
    /**
     * Mark resource as completed (AJAX endpoint)
     */
    public function markComplete() {
        header('Content-Type: application/json');
        
        if (!Session::isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['resource_id']) || !isset($input['course_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        $resourceId = (int)$input['resource_id'];
        $courseId = (int)$input['course_id'];
        $userId = Session::get('user_id');
        
        // Verify enrollment
        if (!$this->enrollmentModel->isEnrolled($userId, $courseId)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Not enrolled in this course']);
            return;
        }
        
        // Verify resource belongs to course
        $resource = $this->resourceModel->findById($resourceId);
        if (!$resource || $resource['course_id'] != $courseId) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Resource not found']);
            return;
        }
        
        // Mark as completed
        $result = $this->resourceModel->markCompleted($userId, $resourceId);
        
        if ($result) {
            // Recalculate progress
            $progress = $this->enrollmentModel->calculateProgress($userId, $courseId);
            
            echo json_encode([
                'success' => true,
                'message' => 'Resource marked as completed',
                'progress' => round($progress, 1)
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update progress']);
        }
    }
}

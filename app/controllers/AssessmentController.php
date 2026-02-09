<?php
/**
 * Assessment Controller
 * Handles assessment/quiz taking and results
 */

require_once __DIR__ . '/../models/Assessment.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../helpers/Session.php';

class AssessmentController {
    private $assessmentModel;
    private $enrollmentModel;
    
    public function __construct() {
        $this->assessmentModel = new Assessment();
        $this->enrollmentModel = new Enrollment();
    }
    
    /**
     * Display assessment/quiz
     */
    public function show() {
        // Check authentication
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to take assessments.');
            redirect(base_url('public/auth/login.php'));
        }
        
        $userId = Session::getUserId();
        $assessmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$assessmentId) {
            Session::setFlash('error', 'Invalid assessment.');
            redirect(base_url('public/index.php'));
        }
        
        // Get assessment with questions
        $assessment = $this->assessmentModel->getWithQuestions($assessmentId);
        
        if (!$assessment) {
            Session::setFlash('error', 'Assessment not found.');
            redirect(base_url('public/index.php'));
        }
        
        // Check if user is enrolled in the course
        $isEnrolled = $this->enrollmentModel->isEnrolled($userId, $assessment['course_id']);
        
        if (!$isEnrolled) {
            Session::setFlash('error', 'You must be enrolled in the course to take this assessment.');
            redirect(base_url('public/course.php?id=' . $assessment['course_id']));
        }
        
        // Check if user can retake (max 3 attempts)
        if (!$this->assessmentModel->canRetake($userId, $assessmentId, 3)) {
            Session::setFlash('error', 'You have reached the maximum number of attempts for this assessment.');
            redirect(base_url('public/learning.php?course_id=' . $assessment['course_id']));
        }
        
        view('assessments/show', [
            'pageTitle' => $assessment['title'] . ' - ' . SITE_NAME,
            'assessment' => $assessment
        ]);
    }
    
    /**
     * Process assessment submission
     */
    public function submit() {
        // Check authentication
        if (!Session::isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        
        $userId = Session::getUserId();
        $assessmentId = isset($_POST['assessment_id']) ? (int)$_POST['assessment_id'] : 0;
        $answers = $_POST['answers'] ?? [];
        
        if (!$assessmentId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid assessment']);
            exit;
        }
        
        // Get assessment
        $assessment = $this->assessmentModel->findById($assessmentId);
        
        if (!$assessment) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Assessment not found']);
            exit;
        }
        
        // Check enrollment
        $isEnrolled = $this->enrollmentModel->isEnrolled($userId, $assessment['course_id']);
        
        if (!$isEnrolled) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Not enrolled in course']);
            exit;
        }
        
        // Check if user can retake
        if (!$this->assessmentModel->canRetake($userId, $assessmentId, 3)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Maximum attempts reached']);
            exit;
        }
        
        try {
            // Submit assessment
            $result = $this->assessmentModel->submitUserAssessment($userId, $assessmentId, $answers);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Assessment submitted successfully',
                    'redirect' => base_url('public/assessment-result.php?id=' . $result['user_assessment_id'])
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to submit assessment']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Show assessment results
     */
    public function result() {
        // Check authentication
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to view results.');
            redirect(base_url('public/auth/login.php'));
        }
        
        $userId = Session::getUserId();
        $userAssessmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$userAssessmentId) {
            Session::setFlash('error', 'Invalid assessment result.');
            redirect(base_url('public/index.php'));
        }
        
        // Get user assessment results
        $userAssessment = $this->assessmentModel->getUserAssessment($userAssessmentId);
        
        if (!$userAssessment) {
            Session::setFlash('error', 'Assessment result not found.');
            redirect(base_url('public/index.php'));
        }
        
        // Verify ownership
        if ($userAssessment['user_id'] != $userId) {
            Session::setFlash('error', 'You do not have permission to view this result.');
            redirect(base_url('public/index.php'));
        }
        
        // Check if user can retake
        $canRetake = $this->assessmentModel->canRetake($userId, $userAssessment['assessment_id'], 3);
        
        view('assessments/result', [
            'pageTitle' => 'Assessment Result - ' . SITE_NAME,
            'userAssessment' => $userAssessment,
            'canRetake' => $canRetake
        ]);
    }
}

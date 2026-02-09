<?php
/**
 * Admin Assessment Controller
 * Handles CRUD operations for assessments
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/Assessment.php';
require_once __DIR__ . '/../models/Course.php';

class AdminAssessmentController {
    private $assessmentModel;
    private $courseModel;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->assessmentModel = new Assessment();
        $this->courseModel = new Course();
    }
    
    /**
     * Display all assessments
     */
    public function index() {
        $courseId = $_GET['course_id'] ?? null;
        $courses = $this->courseModel->getAll(1, 1000);
        
        $assessments = [];
        if ($courseId) {
            $assessments = $this->assessmentModel->getByCourse($courseId);
        }
        
        $pageTitle = 'Manage Assessments - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/assessments/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Show create assessment form
     */
    public function create() {
        $courseId = $_GET['course_id'] ?? null;
        $courses = $this->courseModel->getAll(1, 1000);
        
        $pageTitle = 'Create Assessment - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/assessments/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Store new assessment
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/assessments/create');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            header('Location: /admin/assessments/create');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('course_id', $_POST['course_id'] ?? '');
        $validator->required('title', $_POST['title'] ?? '');
        $validator->required('duration', $_POST['duration'] ?? '');
        $validator->required('passing_score', $_POST['passing_score'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/assessments/create');
            exit;
        }
        
        $assessmentData = [
            'course_id' => $_POST['course_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'duration' => $_POST['duration'],
            'passing_score' => $_POST['passing_score'],
            'total_points' => 0
        ];
        
        $assessmentId = $this->assessmentModel->create($assessmentData);
        
        if ($assessmentId) {
            Session::setFlash('success', 'Assessment created successfully!');
            header('Location: /admin/assessments/edit/' . $assessmentId);
        } else {
            Session::setFlash('error', 'Failed to create assessment. Please try again.');
            header('Location: /admin/assessments/create');
        }
        exit;
    }
    
    /**
     * Show edit assessment form
     */
    public function edit($id) {
        $assessment = $this->assessmentModel->getWithQuestions($id);
        
        if (!$assessment) {
            Session::setFlash('error', 'Assessment not found.');
            header('Location: /admin/assessments');
            exit;
        }
        
        $courses = $this->courseModel->getAll(1, 1000);
        
        $pageTitle = 'Edit Assessment - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/assessments/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Update assessment
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/assessments/edit/' . $id);
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            header('Location: /admin/assessments/edit/' . $id);
            exit;
        }
        
        $assessment = $this->assessmentModel->findById($id);
        
        if (!$assessment) {
            Session::setFlash('error', 'Assessment not found.');
            header('Location: /admin/assessments');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        $validator->required('duration', $_POST['duration'] ?? '');
        $validator->required('passing_score', $_POST['passing_score'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/assessments/edit/' . $id);
            exit;
        }
        
        $updateData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'duration' => $_POST['duration'],
            'passing_score' => $_POST['passing_score']
        ];
        
        if ($this->assessmentModel->update($id, $updateData)) {
            Session::setFlash('success', 'Assessment updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update assessment.');
        }
        
        header('Location: /admin/assessments/edit/' . $id);
        exit;
    }
    
    /**
     * Delete assessment
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/assessments');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/assessments');
            exit;
        }
        
        $assessment = $this->assessmentModel->findById($id);
        
        if (!$assessment) {
            Session::setFlash('error', 'Assessment not found.');
            header('Location: /admin/assessments');
            exit;
        }
        
        if ($this->assessmentModel->delete($id)) {
            Session::setFlash('success', 'Assessment deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete assessment.');
        }
        
        header('Location: /admin/assessments?course_id=' . $assessment['course_id']);
        exit;
    }
}

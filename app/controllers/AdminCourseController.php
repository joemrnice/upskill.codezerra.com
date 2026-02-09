<?php
/**
 * Admin Course Controller
 * Handles CRUD operations for courses
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/FileUpload.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Module.php';
require_once __DIR__ . '/../models/Resource.php';

class AdminCourseController {
    private $courseModel;
    private $moduleModel;
    private $resourceModel;
    private $fileUpload;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->courseModel = new Course();
        $this->moduleModel = new Module();
        $this->resourceModel = new Resource();
        $this->fileUpload = new FileUpload();
    }
    
    /**
     * Display all courses
     */
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        
        $courses = $this->courseModel->getAll($page, 20, $search);
        $totalCourses = $this->courseModel->getTotalCount($search);
        $totalPages = ceil($totalCourses / 20);
        
        $pageTitle = 'Manage Courses - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/courses/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Show create course form
     */
    public function create() {
        $pageTitle = 'Create Course - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/courses/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Store new course
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/courses/create');
            exit;
        }
        
        // Verify CSRF token
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            header('Location: /admin/courses/create');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        $validator->required('description', $_POST['description'] ?? '');
        $validator->required('category', $_POST['category'] ?? '');
        $validator->required('difficulty_level', $_POST['difficulty_level'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/create');
            exit;
        }
        
        // Handle thumbnail upload
        $thumbnailPath = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->fileUpload->upload($_FILES['thumbnail'], 'thumbnail');
            
            if ($uploadResult) {
                $thumbnailPath = $uploadResult['path'];
            } else {
                Session::setFlash('error', implode('<br>', $this->fileUpload->getErrors()));
                header('Location: /admin/courses/create');
                exit;
            }
        }
        
        // Create course
        $courseData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'],
            'difficulty_level' => $_POST['difficulty_level'],
            'thumbnail' => $thumbnailPath,
            'duration' => $_POST['duration'] ?? null,
            'instructor_name' => $_POST['instructor_name'] ?? null,
            'prerequisites' => $_POST['prerequisites'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
            'created_by' => Session::getUserId()
        ];
        
        $courseId = $this->courseModel->create($courseData);
        
        if ($courseId) {
            Session::setFlash('success', 'Course created successfully!');
            header('Location: /admin/courses/edit/' . $courseId);
        } else {
            Session::setFlash('error', 'Failed to create course. Please try again.');
            header('Location: /admin/courses/create');
        }
        exit;
    }
    
    /**
     * Show edit course form
     */
    public function edit($id) {
        $course = $this->courseModel->getCourseWithContent($id);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        $pageTitle = 'Edit Course - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/courses/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Update course
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/courses/edit/' . $id);
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            header('Location: /admin/courses/edit/' . $id);
            exit;
        }
        
        $course = $this->courseModel->findById($id);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        $validator->required('description', $_POST['description'] ?? '');
        $validator->required('category', $_POST['category'] ?? '');
        $validator->required('difficulty_level', $_POST['difficulty_level'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/edit/' . $id);
            exit;
        }
        
        $updateData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'],
            'difficulty_level' => $_POST['difficulty_level'],
            'duration' => $_POST['duration'] ?? null,
            'instructor_name' => $_POST['instructor_name'] ?? null,
            'prerequisites' => $_POST['prerequisites'] ?? null,
            'status' => $_POST['status'] ?? 'draft'
        ];
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->fileUpload->upload($_FILES['thumbnail'], 'thumbnail');
            
            if ($uploadResult) {
                // Delete old thumbnail
                if ($course['thumbnail']) {
                    $this->fileUpload->delete($course['thumbnail']);
                }
                $updateData['thumbnail'] = $uploadResult['path'];
            } else {
                Session::setFlash('error', implode('<br>', $this->fileUpload->getErrors()));
                header('Location: /admin/courses/edit/' . $id);
                exit;
            }
        }
        
        if ($this->courseModel->update($id, $updateData)) {
            Session::setFlash('success', 'Course updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update course.');
        }
        
        header('Location: /admin/courses/edit/' . $id);
        exit;
    }
    
    /**
     * Delete course
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/courses');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/courses');
            exit;
        }
        
        $course = $this->courseModel->findById($id);
        
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Delete thumbnail
        if ($course['thumbnail']) {
            $this->fileUpload->delete($course['thumbnail']);
        }
        
        if ($this->courseModel->delete($id)) {
            Session::setFlash('success', 'Course deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete course.');
        }
        
        header('Location: /admin/courses');
        exit;
    }
}

<?php
/**
 * Admin Module Controller
 * Handles module operations
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/Module.php';

class AdminModuleController {
    private $moduleModel;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->moduleModel = new Module();
    }
    
    /**
     * Store new module
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/courses');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/courses');
            exit;
        }
        
        $courseId = $_POST['course_id'] ?? null;
        
        if (!$courseId) {
            Session::setFlash('error', 'Course ID is required.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/edit/' . $courseId);
            exit;
        }
        
        // Get the next order number
        $modules = $this->moduleModel->getByCourse($courseId);
        $orderNumber = count($modules) + 1;
        
        $moduleData = [
            'course_id' => $courseId,
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'order_number' => $orderNumber
        ];
        
        $moduleId = $this->moduleModel->create($moduleData);
        
        if ($moduleId) {
            Session::setFlash('success', 'Module added successfully!');
        } else {
            Session::setFlash('error', 'Failed to add module.');
        }
        
        header('Location: /admin/courses/edit/' . $courseId);
        exit;
    }
    
    /**
     * Update module
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/courses');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/courses');
            exit;
        }
        
        $module = $this->moduleModel->findById($id);
        
        if (!$module) {
            Session::setFlash('error', 'Module not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/edit/' . $module['course_id']);
            exit;
        }
        
        $updateData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null
        ];
        
        if ($this->moduleModel->update($id, $updateData)) {
            Session::setFlash('success', 'Module updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update module.');
        }
        
        header('Location: /admin/courses/edit/' . $module['course_id']);
        exit;
    }
    
    /**
     * Delete module
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
        
        $module = $this->moduleModel->findById($id);
        
        if (!$module) {
            Session::setFlash('error', 'Module not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        $courseId = $module['course_id'];
        
        if ($this->moduleModel->delete($id)) {
            Session::setFlash('success', 'Module deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete module.');
        }
        
        header('Location: /admin/courses/edit/' . $courseId);
        exit;
    }
}

<?php
/**
 * Admin Resource Controller
 * Handles resource operations
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/FileUpload.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/Resource.php';
require_once __DIR__ . '/../models/Module.php';

class AdminResourceController {
    private $resourceModel;
    private $moduleModel;
    private $fileUpload;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->resourceModel = new Resource();
        $this->moduleModel = new Module();
        $this->fileUpload = new FileUpload();
    }
    
    /**
     * Store new resource
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
        
        $moduleId = $_POST['module_id'] ?? null;
        
        if (!$moduleId) {
            Session::setFlash('error', 'Module ID is required.');
            header('Location: /admin/courses');
            exit;
        }
        
        $module = $this->moduleModel->findById($moduleId);
        
        if (!$module) {
            Session::setFlash('error', 'Module not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        $validator->required('type', $_POST['type'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/edit/' . $module['course_id']);
            exit;
        }
        
        // Handle file upload
        $filePath = null;
        $fileSize = 0;
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $type = $_POST['type'];
            $uploadType = ($type === 'video') ? 'video' : (($type === 'document') ? 'document' : 'general');
            
            $uploadResult = $this->fileUpload->upload($_FILES['file'], $uploadType);
            
            if ($uploadResult) {
                $filePath = $uploadResult['path'];
                $fileSize = $uploadResult['size'];
            } else {
                Session::setFlash('error', implode('<br>', $this->fileUpload->getErrors()));
                header('Location: /admin/courses/edit/' . $module['course_id']);
                exit;
            }
        }
        
        // Get the next order number
        $resources = $this->resourceModel->getByModule($moduleId);
        $orderNumber = count($resources) + 1;
        
        $resourceData = [
            'module_id' => $moduleId,
            'title' => $_POST['title'],
            'type' => $_POST['type'],
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'order_number' => $orderNumber
        ];
        
        $resourceId = $this->resourceModel->create($resourceData);
        
        if ($resourceId) {
            Session::setFlash('success', 'Resource added successfully!');
        } else {
            Session::setFlash('error', 'Failed to add resource.');
        }
        
        header('Location: /admin/courses/edit/' . $module['course_id']);
        exit;
    }
    
    /**
     * Update resource
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
        
        $resource = $this->resourceModel->findById($id);
        
        if (!$resource) {
            Session::setFlash('error', 'Resource not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('title', $_POST['title'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/courses/edit/' . $resource['course_id']);
            exit;
        }
        
        $updateData = [
            'title' => $_POST['title']
        ];
        
        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $type = $resource['type'];
            $uploadType = ($type === 'video') ? 'video' : (($type === 'document') ? 'document' : 'general');
            
            $uploadResult = $this->fileUpload->upload($_FILES['file'], $uploadType);
            
            if ($uploadResult) {
                // Delete old file
                if ($resource['file_path']) {
                    $this->fileUpload->delete($resource['file_path']);
                }
                $updateData['file_path'] = $uploadResult['path'];
                $updateData['file_size'] = $uploadResult['size'];
            } else {
                Session::setFlash('error', implode('<br>', $this->fileUpload->getErrors()));
                header('Location: /admin/courses/edit/' . $resource['course_id']);
                exit;
            }
        }
        
        if ($this->resourceModel->update($id, $updateData)) {
            Session::setFlash('success', 'Resource updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update resource.');
        }
        
        header('Location: /admin/courses/edit/' . $resource['course_id']);
        exit;
    }
    
    /**
     * Delete resource
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
        
        $resource = $this->resourceModel->findById($id);
        
        if (!$resource) {
            Session::setFlash('error', 'Resource not found.');
            header('Location: /admin/courses');
            exit;
        }
        
        $courseId = $resource['course_id'];
        
        // Delete file
        if ($resource['file_path']) {
            $this->fileUpload->delete($resource['file_path']);
        }
        
        if ($this->resourceModel->delete($id)) {
            Session::setFlash('success', 'Resource deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete resource.');
        }
        
        header('Location: /admin/courses/edit/' . $courseId);
        exit;
    }
}

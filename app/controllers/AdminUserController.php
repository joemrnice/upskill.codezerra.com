<?php
/**
 * Admin User Controller
 * Handles CRUD operations for users
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/User.php';

class AdminUserController {
    private $userModel;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->userModel = new User();
    }
    
    /**
     * Display all users
     */
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        
        $users = $this->userModel->getAll($page, 20, $search);
        $totalUsers = $this->userModel->getTotalCount($search);
        $totalPages = ceil($totalUsers / 20);
        
        $pageTitle = 'Manage Users - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/users/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Show edit user form
     */
    public function edit($id) {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            header('Location: /admin/users');
            exit;
        }
        
        $pageTitle = 'Edit User - Admin';
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/layouts/admin-nav.php';
        require_once __DIR__ . '/../views/admin/users/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            header('Location: /admin/users');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('name', $_POST['name'] ?? '');
        $validator->email('email', $_POST['email'] ?? '');
        $validator->required('employee_id', $_POST['employee_id'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        // Check if email is already taken by another user
        $emailCheck = $this->userModel->findByEmail($_POST['email']);
        if ($emailCheck && $emailCheck['id'] != $id) {
            Session::setFlash('error', 'Email is already in use by another user.');
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        // Check if employee_id is already taken by another user
        $empIdCheck = $this->userModel->findByEmployeeId($_POST['employee_id']);
        if ($empIdCheck && $empIdCheck['id'] != $id) {
            Session::setFlash('error', 'Employee ID is already in use by another user.');
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        $updateData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'employee_id' => $_POST['employee_id'],
            'role' => $_POST['role'] ?? 'user',
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if ($this->userModel->update($id, $updateData)) {
            Session::setFlash('success', 'User updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update user.');
        }
        
        header('Location: /admin/users/edit/' . $id);
        exit;
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/users');
            exit;
        }
        
        // Prevent deleting yourself
        if ($id == Session::getUserId()) {
            Session::setFlash('error', 'You cannot delete your own account.');
            header('Location: /admin/users');
            exit;
        }
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            header('Location: /admin/users');
            exit;
        }
        
        if ($this->userModel->delete($id)) {
            Session::setFlash('success', 'User deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete user.');
        }
        
        header('Location: /admin/users');
        exit;
    }
}

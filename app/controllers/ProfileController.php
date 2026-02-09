<?php
/**
 * Profile Controller
 * Handles user profile management
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Assessment.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Database.php';

class ProfileController {
    private $userModel;
    private $enrollmentModel;
    private $assessmentModel;
    private $db;
    
    public function __construct() {
        $this->userModel = new User();
        $this->enrollmentModel = new Enrollment();
        $this->assessmentModel = new Assessment();
        $this->db = Database::getInstance();
        
        // Check authentication
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to access your profile.');
            redirect(base_url('public/auth/login.php'));
        }
    }
    
    /**
     * Show user profile page
     */
    public function index() {
        $userId = Session::get('user_id');
        
        // Get user data
        $user = $this->userModel->findById($userId);
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            redirect(base_url('public/index.php'));
        }
        
        // Get enrollment history
        $enrollments = $this->enrollmentModel->getUserEnrollments($userId);
        
        // Get test scores
        $sql = "SELECT ua.*, a.title as assessment_title, a.passing_score, c.title as course_title 
                FROM user_assessments ua 
                INNER JOIN assessments a ON ua.assessment_id = a.id 
                INNER JOIN courses c ON a.course_id = c.id 
                WHERE ua.user_id = :user_id 
                ORDER BY ua.submitted_at DESC 
                LIMIT 10";
        $testScores = $this->db->fetchAll($sql, ['user_id' => $userId]);
        
        view('profile/index', [
            'user' => $user,
            'enrollments' => $enrollments,
            'testScores' => $testScores
        ]);
    }
    
    /**
     * Update profile information
     */
    public function update() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/profile/index.php'));
        }
        
        $userId = Session::get('user_id');
        
        // Sanitize input
        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $employeeId = Validator::sanitize($_POST['employee_id'] ?? '');
        
        // Validate input
        $validator = new Validator([
            'name' => $name,
            'email' => $email,
            'employee_id' => $employeeId
        ]);
        
        $validator->required(['name', 'email', 'employee_id'])
                  ->email('email')
                  ->minLength('name', 2)
                  ->maxLength('name', 100)
                  ->maxLength('email', 100)
                  ->maxLength('employee_id', 50);
        
        if (!$validator->passes()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Session::setFlash('old', $_POST);
            redirect(base_url('public/profile/index.php'));
        }
        
        // Check email uniqueness (exclude current user)
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            Session::setFlash('error', 'Email address is already in use.');
            Session::setFlash('old', $_POST);
            redirect(base_url('public/profile/index.php'));
        }
        
        // Check employee ID uniqueness (exclude current user)
        $existingEmployee = $this->userModel->findByEmployeeId($employeeId);
        if ($existingEmployee && $existingEmployee['id'] != $userId) {
            Session::setFlash('error', 'Employee ID is already in use.');
            Session::setFlash('old', $_POST);
            redirect(base_url('public/profile/index.php'));
        }
        
        // Update user
        $updated = $this->userModel->update($userId, [
            'name' => $name,
            'email' => $email,
            'employee_id' => $employeeId
        ]);
        
        if ($updated) {
            // Update session data
            Session::set('user_name', $name);
            Session::set('user_email', $email);
            
            Session::setFlash('success', 'Profile updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update profile. Please try again.');
        }
        
        redirect(base_url('public/profile/index.php'));
    }
    
    /**
     * Update password
     */
    public function updatePassword() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/profile/index.php'));
        }
        
        $userId = Session::get('user_id');
        
        // Get input
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        $validator = new Validator([
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
            'confirm_password' => $confirmPassword
        ]);
        
        $validator->required(['current_password', 'new_password', 'confirm_password'])
                  ->minLength('new_password', 8)
                  ->maxLength('new_password', 100);
        
        if (!$validator->passes()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            redirect(base_url('public/profile/index.php'));
        }
        
        // Check if new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            Session::setFlash('error', 'New password and confirm password do not match.');
            redirect(base_url('public/profile/index.php'));
        }
        
        // Verify current password
        $user = $this->userModel->findById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            Session::setFlash('error', 'Current password is incorrect.');
            redirect(base_url('public/profile/index.php'));
        }
        
        // Update password
        $updated = $this->userModel->updatePassword($userId, $newPassword);
        
        if ($updated) {
            Session::setFlash('success', 'Password updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update password. Please try again.');
        }
        
        redirect(base_url('public/profile/index.php'));
    }
    
    /**
     * Show user certificates
     */
    public function certificates() {
        $userId = Session::get('user_id');
        
        // Get user certificates
        $sql = "SELECT c.*, co.title as course_title, co.description as course_description, 
                co.instructor_name, co.duration 
                FROM certificates c 
                INNER JOIN courses co ON c.course_id = co.id 
                WHERE c.user_id = :user_id 
                ORDER BY c.issued_date DESC";
        
        $certificates = $this->db->fetchAll($sql, ['user_id' => $userId]);
        
        view('profile/certificates', [
            'certificates' => $certificates
        ]);
    }
}

<?php
/**
 * Admin Question Controller
 * Handles question operations for assessments
 */

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../models/Assessment.php';

class AdminQuestionController {
    private $assessmentModel;
    
    public function __construct() {
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        
        $this->assessmentModel = new Assessment();
    }
    
    /**
     * Store new question
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/assessments');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/assessments');
            exit;
        }
        
        $assessmentId = $_POST['assessment_id'] ?? null;
        
        if (!$assessmentId) {
            Session::setFlash('error', 'Assessment ID is required.');
            header('Location: /admin/assessments');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('question_text', $_POST['question_text'] ?? '');
        $validator->required('question_type', $_POST['question_type'] ?? '');
        $validator->required('correct_answer', $_POST['correct_answer'] ?? '');
        $validator->required('points', $_POST['points'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/assessments/edit/' . $assessmentId);
            exit;
        }
        
        // Get the next order number
        $assessment = $this->assessmentModel->getWithQuestions($assessmentId);
        $orderNumber = count($assessment['questions']) + 1;
        
        // Prepare options (for multiple choice questions)
        $options = null;
        if ($_POST['question_type'] === 'multiple_choice' && isset($_POST['options'])) {
            $options = json_encode(array_values(array_filter($_POST['options'])));
        }
        
        $questionData = [
            'assessment_id' => $assessmentId,
            'question_text' => $_POST['question_text'],
            'question_type' => $_POST['question_type'],
            'options' => $options,
            'correct_answer' => $_POST['correct_answer'],
            'points' => $_POST['points'],
            'order_number' => $orderNumber
        ];
        
        $questionId = $this->assessmentModel->addQuestion($questionData);
        
        if ($questionId) {
            Session::setFlash('success', 'Question added successfully!');
        } else {
            Session::setFlash('error', 'Failed to add question.');
        }
        
        header('Location: /admin/assessments/edit/' . $assessmentId);
        exit;
    }
    
    /**
     * Update question
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/assessments');
            exit;
        }
        
        if (!Session::verifyToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid request.');
            header('Location: /admin/assessments');
            exit;
        }
        
        $assessmentId = $_POST['assessment_id'] ?? null;
        
        if (!$assessmentId) {
            Session::setFlash('error', 'Assessment ID is required.');
            header('Location: /admin/assessments');
            exit;
        }
        
        // Validate input
        $validator = new Validator();
        $validator->required('question_text', $_POST['question_text'] ?? '');
        $validator->required('question_type', $_POST['question_type'] ?? '');
        $validator->required('correct_answer', $_POST['correct_answer'] ?? '');
        $validator->required('points', $_POST['points'] ?? '');
        
        if ($validator->hasErrors()) {
            Session::setFlash('error', implode('<br>', $validator->getErrors()));
            header('Location: /admin/assessments/edit/' . $assessmentId);
            exit;
        }
        
        // Prepare options
        $options = null;
        if ($_POST['question_type'] === 'multiple_choice' && isset($_POST['options'])) {
            $options = json_encode(array_values(array_filter($_POST['options'])));
        }
        
        $updateData = [
            'assessment_id' => $assessmentId,
            'question_text' => $_POST['question_text'],
            'question_type' => $_POST['question_type'],
            'options' => $options,
            'correct_answer' => $_POST['correct_answer'],
            'points' => $_POST['points']
        ];
        
        if ($this->assessmentModel->updateQuestion($id, $updateData)) {
            Session::setFlash('success', 'Question updated successfully!');
        } else {
            Session::setFlash('error', 'Failed to update question.');
        }
        
        header('Location: /admin/assessments/edit/' . $assessmentId);
        exit;
    }
    
    /**
     * Delete question
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
        
        $assessmentId = $_POST['assessment_id'] ?? null;
        
        if (!$assessmentId) {
            Session::setFlash('error', 'Assessment ID is required.');
            header('Location: /admin/assessments');
            exit;
        }
        
        if ($this->assessmentModel->deleteQuestion($id)) {
            Session::setFlash('success', 'Question deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete question.');
        }
        
        header('Location: /admin/assessments/edit/' . $assessmentId);
        exit;
    }
}

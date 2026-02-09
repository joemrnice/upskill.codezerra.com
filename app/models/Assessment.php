<?php
/**
 * Assessment Model
 * Handles assessment-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class Assessment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new assessment
     */
    public function create($data) {
        $sql = "INSERT INTO assessments (course_id, title, description, duration, passing_score, total_points) 
                VALUES (:course_id, :title, :description, :duration, :passing_score, :total_points)";
        return $this->db->insert($sql, $data);
    }
    
    /**
     * Find assessment by ID
     */
    public function findById($id) {
        $sql = "SELECT a.*, c.title as course_title 
                FROM assessments a 
                INNER JOIN courses c ON a.course_id = c.id 
                WHERE a.id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get assessments by course
     */
    public function getByCourse($courseId) {
        $sql = "SELECT * FROM assessments WHERE course_id = :course_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['course_id' => $courseId]);
    }
    
    /**
     * Get assessment with questions
     */
    public function getWithQuestions($assessmentId) {
        $assessment = $this->findById($assessmentId);
        if (!$assessment) {
            return null;
        }
        
        $sql = "SELECT * FROM questions WHERE assessment_id = :assessment_id ORDER BY order_number ASC";
        $assessment['questions'] = $this->db->fetchAll($sql, ['assessment_id' => $assessmentId]);
        
        return $assessment;
    }
    
    /**
     * Update assessment
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE assessments SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete assessment
     */
    public function delete($id) {
        $sql = "DELETE FROM assessments WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Add question to assessment
     */
    public function addQuestion($data) {
        $sql = "INSERT INTO questions (assessment_id, question_text, question_type, options, 
                correct_answer, points, order_number) 
                VALUES (:assessment_id, :question_text, :question_type, :options, 
                :correct_answer, :points, :order_number)";
        
        $questionId = $this->db->insert($sql, $data);
        
        // Update total points
        $this->updateTotalPoints($data['assessment_id']);
        
        return $questionId;
    }
    
    /**
     * Update question
     */
    public function updateQuestion($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE questions SET " . implode(', ', $fields) . " WHERE id = :id";
        $result = $this->db->execute($sql, $params);
        
        // Update total points
        if (isset($data['assessment_id'])) {
            $this->updateTotalPoints($data['assessment_id']);
        }
        
        return $result;
    }
    
    /**
     * Delete question
     */
    public function deleteQuestion($id) {
        // Get assessment ID first
        $sql = "SELECT assessment_id FROM questions WHERE id = :id";
        $question = $this->db->fetch($sql, ['id' => $id]);
        
        $sql = "DELETE FROM questions WHERE id = :id";
        $result = $this->db->execute($sql, ['id' => $id]);
        
        // Update total points
        if ($question) {
            $this->updateTotalPoints($question['assessment_id']);
        }
        
        return $result;
    }
    
    /**
     * Update total points for assessment
     */
    private function updateTotalPoints($assessmentId) {
        $sql = "UPDATE assessments SET total_points = (
                SELECT COALESCE(SUM(points), 0) FROM questions WHERE assessment_id = :assessment_id
                ) WHERE id = :assessment_id";
        return $this->db->execute($sql, ['assessment_id' => $assessmentId]);
    }
    
    /**
     * Submit user assessment
     */
    public function submitUserAssessment($userId, $assessmentId, $answers) {
        $assessment = $this->getWithQuestions($assessmentId);
        
        if (!$assessment) {
            return false;
        }
        
        $score = 0;
        $totalPoints = $assessment['total_points'];
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Create user assessment record
            $sql = "INSERT INTO user_assessments (user_id, assessment_id, score, total_points, percentage, status, submitted_at) 
                    VALUES (:user_id, :assessment_id, 0, :total_points, 0, 'failed', NOW())";
            
            $userAssessmentId = $this->db->insert($sql, [
                'user_id' => $userId,
                'assessment_id' => $assessmentId,
                'total_points' => $totalPoints
            ]);
            
            // Process each answer
            foreach ($assessment['questions'] as $question) {
                $questionId = $question['id'];
                $userAnswer = $answers[$questionId] ?? '';
                
                // Check if answer is correct
                $isCorrect = $this->checkAnswer($question, $userAnswer);
                $pointsEarned = $isCorrect ? $question['points'] : 0;
                $score += $pointsEarned;
                
                // Save user answer
                $sql = "INSERT INTO user_answers (user_assessment_id, question_id, answer, is_correct, points_earned) 
                        VALUES (:user_assessment_id, :question_id, :answer, :is_correct, :points_earned)";
                
                $this->db->insert($sql, [
                    'user_assessment_id' => $userAssessmentId,
                    'question_id' => $questionId,
                    'answer' => is_array($userAnswer) ? json_encode($userAnswer) : $userAnswer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned
                ]);
            }
            
            // Calculate percentage and status
            $percentage = ($totalPoints > 0) ? ($score / $totalPoints) * 100 : 0;
            $status = ($percentage >= $assessment['passing_score']) ? 'passed' : 'failed';
            
            // Update user assessment with final score
            $sql = "UPDATE user_assessments 
                    SET score = :score, percentage = :percentage, status = :status, graded_at = NOW() 
                    WHERE id = :id";
            
            $this->db->execute($sql, [
                'score' => $score,
                'percentage' => $percentage,
                'status' => $status,
                'id' => $userAssessmentId
            ]);
            
            $this->db->commit();
            
            return [
                'user_assessment_id' => $userAssessmentId,
                'score' => $score,
                'total_points' => $totalPoints,
                'percentage' => $percentage,
                'status' => $status
            ];
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Check if an answer is correct
     */
    private function checkAnswer($question, $userAnswer) {
        switch ($question['question_type']) {
            case 'multiple_choice':
                return strcasecmp(trim($userAnswer), trim($question['correct_answer'])) === 0;
                
            case 'true_false':
                return strcasecmp(trim($userAnswer), trim($question['correct_answer'])) === 0;
                
            case 'short_answer':
                // For short answers, we'll do a case-insensitive comparison
                return strcasecmp(trim($userAnswer), trim($question['correct_answer'])) === 0;
                
            default:
                return false;
        }
    }
    
    /**
     * Get user assessment results
     */
    public function getUserAssessment($userAssessmentId) {
        $sql = "SELECT ua.*, a.title as assessment_title, a.passing_score, c.title as course_title 
                FROM user_assessments ua 
                INNER JOIN assessments a ON ua.assessment_id = a.id 
                INNER JOIN courses c ON a.course_id = c.id 
                WHERE ua.id = :id";
        
        $userAssessment = $this->db->fetch($sql, ['id' => $userAssessmentId]);
        
        if (!$userAssessment) {
            return null;
        }
        
        // Get answers
        $sql = "SELECT ua.*, q.question_text, q.question_type, q.options, q.correct_answer, q.points 
                FROM user_answers ua 
                INNER JOIN questions q ON ua.question_id = q.id 
                WHERE ua.user_assessment_id = :user_assessment_id 
                ORDER BY q.order_number ASC";
        
        $userAssessment['answers'] = $this->db->fetchAll($sql, ['user_assessment_id' => $userAssessmentId]);
        
        return $userAssessment;
    }
    
    /**
     * Get user's assessments for a course
     */
    public function getUserCourseAssessments($userId, $courseId) {
        $sql = "SELECT ua.*, a.title as assessment_title 
                FROM user_assessments ua 
                INNER JOIN assessments a ON ua.assessment_id = a.id 
                WHERE ua.user_id = :user_id AND a.course_id = :course_id 
                ORDER BY ua.submitted_at DESC";
        
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'course_id' => $courseId
        ]);
    }
    
    /**
     * Check if user can retake assessment
     */
    public function canRetake($userId, $assessmentId, $maxAttempts = 3) {
        $sql = "SELECT COUNT(*) as attempts FROM user_assessments 
                WHERE user_id = :user_id AND assessment_id = :assessment_id";
        
        $result = $this->db->fetch($sql, [
            'user_id' => $userId,
            'assessment_id' => $assessmentId
        ]);
        
        return $result['attempts'] < $maxAttempts;
    }
    
    /**
     * Get all user assessments for admin
     */
    public function getAllUserAssessments($page = 1, $perPage = 20, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $params = ['limit' => $perPage, 'offset' => $offset];
        
        $sql = "SELECT ua.*, u.name as user_name, a.title as assessment_title, c.title as course_title 
                FROM user_assessments ua 
                INNER JOIN users u ON ua.user_id = u.id 
                INNER JOIN assessments a ON ua.assessment_id = a.id 
                INNER JOIN courses c ON a.course_id = c.id 
                WHERE 1=1";
        
        if (!empty($filters['status'])) {
            $sql .= " AND ua.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['course_id'])) {
            $sql .= " AND c.id = :course_id";
            $params['course_id'] = $filters['course_id'];
        }
        
        $sql .= " ORDER BY ua.submitted_at DESC LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
}

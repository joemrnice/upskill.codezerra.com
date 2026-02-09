<?php
/**
 * User Model
 * Handles user-related database operations
 */

require_once __DIR__ . '/../helpers/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new user
     */
    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, employee_id, role, status) 
                VALUES (:name, :email, :password, :employee_id, :role, :status)";
        
        $params = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'employee_id' => $data['employee_id'],
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'] ?? 'active'
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }
    
    /**
     * Find user by employee ID
     */
    public function findByEmployeeId($employeeId) {
        $sql = "SELECT * FROM users WHERE employee_id = :employee_id";
        return $this->db->fetch($sql, ['employee_id' => $employeeId]);
    }
    
    /**
     * Verify user credentials
     */
    public function verifyCredentials($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        return $this->db->execute($sql, [
            'id' => $id,
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }
    
    /**
     * Get all users with pagination
     */
    public function getAll($page = 1, $perPage = 20, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE :search OR email LIKE :search OR employee_id LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total user count
     */
    public function getTotalCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE :search OR email LIKE :search OR employee_id LIKE :search)";
            $params['search'] = "%{$search}%";
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Set password reset token
     */
    public function setResetToken($email, $token, $expires) {
        $sql = "UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE email = :email";
        return $this->db->execute($sql, [
            'token' => $token,
            'expires' => $expires,
            'email' => $email
        ]);
    }
    
    /**
     * Find user by reset token
     */
    public function findByResetToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()";
        return $this->db->fetch($sql, ['token' => $token]);
    }
    
    /**
     * Clear reset token
     */
    public function clearResetToken($id) {
        $sql = "UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Update status (active/suspended)
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id, 'status' => $status]);
    }
    
    /**
     * Get user statistics
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_count,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as new_today
                FROM users";
        return $this->db->fetch($sql);
    }
}

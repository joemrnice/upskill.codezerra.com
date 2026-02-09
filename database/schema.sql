-- Professional Training Platform Database Schema
-- upskill.codezerra.com
-- Created: 2026-02-09

-- Drop tables if they exist (in reverse order of dependencies)
DROP TABLE IF EXISTS progress_tracking;
DROP TABLE IF EXISTS user_answers;
DROP TABLE IF EXISTS user_assessments;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS assessments;
DROP TABLE IF EXISTS certificates;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS resources;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    employee_id VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'suspended') DEFAULT 'active',
    reset_token VARCHAR(255) NULL,
    reset_token_expires DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_employee_id (employee_id),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Courses Table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    thumbnail VARCHAR(255) NULL,
    duration INT NOT NULL COMMENT 'Duration in hours',
    instructor_name VARCHAR(255) NOT NULL,
    prerequisites TEXT NULL,
    status ENUM('published', 'draft') DEFAULT 'draft',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_difficulty (difficulty_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modules Table
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    order_number INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course_id (course_id),
    INDEX idx_order (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Resources Table
CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    type ENUM('video', 'pdf', 'document', 'download') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT NOT NULL COMMENT 'File size in bytes',
    order_number INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    INDEX idx_module_id (module_id),
    INDEX idx_type (type),
    INDEX idx_order (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enrollments Table
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    progress DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Progress percentage',
    status ENUM('in_progress', 'completed') DEFAULT 'in_progress',
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id),
    INDEX idx_user_id (user_id),
    INDEX idx_course_id (course_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assessments Table
CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in minutes',
    passing_score INT NOT NULL DEFAULT 70 COMMENT 'Passing score percentage',
    total_points INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course_id (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions Table
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') NOT NULL,
    options JSON NULL COMMENT 'JSON array of options for multiple choice',
    correct_answer TEXT NOT NULL,
    points INT NOT NULL DEFAULT 1,
    order_number INT NOT NULL DEFAULT 0,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
    INDEX idx_assessment_id (assessment_id),
    INDEX idx_order (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Assessments Table
CREATE TABLE user_assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assessment_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    total_points INT NOT NULL,
    percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    status ENUM('passed', 'failed') NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    graded_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_assessment_id (assessment_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Answers Table
CREATE TABLE user_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_assessment_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    points_earned INT NOT NULL DEFAULT 0,
    FOREIGN KEY (user_assessment_id) REFERENCES user_assessments(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_user_assessment_id (user_assessment_id),
    INDEX idx_question_id (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Certificates Table
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    certificate_number VARCHAR(100) NOT NULL UNIQUE,
    issued_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file_path VARCHAR(500) NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_certificate (user_id, course_id),
    INDEX idx_user_id (user_id),
    INDEX idx_course_id (course_id),
    INDEX idx_certificate_number (certificate_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Progress Tracking Table
CREATE TABLE progress_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    resource_id INT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
    UNIQUE KEY unique_progress (user_id, resource_id),
    INDEX idx_user_id (user_id),
    INDEX idx_resource_id (resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Password: Admin@123 (hashed with bcrypt)
INSERT INTO users (name, email, password, employee_id, role, status) VALUES 
('Admin User', 'admin@codezerra.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EMP001', 'admin', 'active');

-- Insert sample courses
INSERT INTO courses (title, description, category, difficulty_level, duration, instructor_name, status, created_by) VALUES
('PHP Web Development Fundamentals', 'Learn the basics of PHP programming, including syntax, variables, functions, and object-oriented programming concepts.', 'Programming', 'beginner', 40, 'John Smith', 'published', 1),
('Advanced JavaScript Techniques', 'Master modern JavaScript including ES6+, async/await, promises, and advanced DOM manipulation.', 'Programming', 'advanced', 35, 'Sarah Johnson', 'published', 1),
('Database Design and SQL', 'Comprehensive course on database design, normalization, and advanced SQL queries with MySQL.', 'Database', 'intermediate', 30, 'Michael Brown', 'published', 1),
('Project Management Essentials', 'Learn project management methodologies, tools, and best practices for successful project delivery.', 'Management', 'beginner', 25, 'Emily Davis', 'published', 1);

-- Insert sample modules for PHP course
INSERT INTO modules (course_id, title, description, order_number) VALUES
(1, 'Introduction to PHP', 'Getting started with PHP, setup, and basic syntax', 1),
(1, 'Variables and Data Types', 'Understanding PHP variables, data types, and operators', 2),
(1, 'Control Structures', 'Conditional statements, loops, and flow control', 3),
(1, 'Functions and Arrays', 'Creating functions and working with arrays', 4),
(1, 'Object-Oriented PHP', 'Classes, objects, inheritance, and interfaces', 5);

-- Insert sample modules for JavaScript course
INSERT INTO modules (course_id, title, description, order_number) VALUES
(2, 'Modern JavaScript Syntax', 'ES6+ features, arrow functions, and destructuring', 1),
(2, 'Asynchronous JavaScript', 'Promises, async/await, and event loop', 2),
(2, 'DOM Manipulation', 'Working with the Document Object Model', 3),
(2, 'JavaScript Design Patterns', 'Common patterns and best practices', 4);

-- Insert sample assessment for PHP course
INSERT INTO assessments (course_id, title, description, duration, passing_score, total_points) VALUES
(1, 'PHP Fundamentals Quiz', 'Test your understanding of PHP basics', 30, 70, 100);

-- Insert sample questions
INSERT INTO questions (assessment_id, question_text, question_type, options, correct_answer, points, order_number) VALUES
(1, 'What does PHP stand for?', 'multiple_choice', '["Personal Home Page", "Hypertext Preprocessor", "Private Hypertext Processor", "Pre Hypertext Program"]', 'Hypertext Preprocessor', 10, 1),
(1, 'PHP is a server-side scripting language.', 'true_false', NULL, 'true', 10, 2),
(1, 'Which symbol is used to declare a variable in PHP?', 'multiple_choice', '["@", "#", "$", "%"]', '$', 10, 3),
(1, 'What is the correct way to end a PHP statement?', 'multiple_choice', '[".", ";", ":", "!"]', ';', 10, 4);

-- Update assessment total points
UPDATE assessments SET total_points = (SELECT SUM(points) FROM questions WHERE assessment_id = 1) WHERE id = 1;

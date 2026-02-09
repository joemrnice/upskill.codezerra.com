<?php
/**
 * Application Configuration
 * upskill.codezerra.com
 */

return [
    // Site Settings
    'site_name' => 'Upskill Training Platform',
    'site_url' => getenv('SITE_URL') ?: 'https://upskill.codezerra.com',
    'admin_email' => 'admin@codezerra.com',
    
    // Security
    'session_lifetime' => 7200, // 2 hours in seconds
    'remember_me_lifetime' => 2592000, // 30 days in seconds
    'password_min_length' => 8,
    'max_login_attempts' => 5,
    'lockout_time' => 900, // 15 minutes in seconds
    
    // File Upload Settings
    'upload_max_size' => 104857600, // 100MB in bytes
    'allowed_video_types' => ['video/mp4', 'video/webm', 'video/ogg'],
    'allowed_document_types' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
    'allowed_image_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    'allowed_archive_types' => ['application/zip', 'application/x-zip-compressed'],
    
    // Pagination
    'items_per_page' => 12,
    'admin_items_per_page' => 20,
    
    // Email Configuration (using environment variables)
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_username' => getenv('SMTP_USERNAME') ?: '',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: '',
    'smtp_encryption' => 'tls',
    
    // Paths
    'upload_path' => __DIR__ . '/../public/uploads/',
    'course_thumbnail_path' => 'courses/',
    'resource_path' => 'resources/',
    'certificate_path' => 'certificates/',
    
    // Assessment Settings
    'default_passing_score' => 70,
    'allow_retake' => true,
    'max_retake_attempts' => 3,
    
    // Certificate Settings
    'certificate_prefix' => 'CERT',
    'enable_certificates' => true,
];

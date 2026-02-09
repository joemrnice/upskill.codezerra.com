<?php
/**
 * Example Configuration File
 * Copy this file to config/config.php and update with your settings
 */

return [
    // Site Settings
    'site_name' => 'Upskill Training Platform',
    'site_url' => 'https://upskill.codezerra.com',
    'admin_email' => 'admin@codezerra.com',
    
    // Database Configuration (or use environment variables)
    // Set these in your web server environment or .env file
    
    // Security
    'session_lifetime' => 7200, // 2 hours
    'password_min_length' => 8,
    
    // File Upload Settings (in bytes)
    'upload_max_size' => 104857600, // 100MB
    
    // Email Configuration
    // Configure these in your environment variables:
    // SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD
    
    // Paths (relative to project root)
    'upload_path' => __DIR__ . '/../public/uploads/',
];

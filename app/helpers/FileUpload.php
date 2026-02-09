<?php
/**
 * File Upload Helper Class
 * Handles secure file uploads
 */

class FileUpload {
    private $config;
    private $errors = [];
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->config = require __DIR__ . '/../../config/config.php';
    }
    
    /**
     * Upload a file
     */
    public function upload($file, $type = 'general', $allowedTypes = null, $maxSize = null) {
        // Check if file was uploaded
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors[] = 'No file was uploaded.';
            return false;
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return false;
        }
        
        // Determine allowed types
        if ($allowedTypes === null) {
            $allowedTypes = $this->getAllowedTypes($type);
        }
        
        // Determine max size
        if ($maxSize === null) {
            $maxSize = $this->config['upload_max_size'];
        }
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            $this->errors[] = 'Invalid file type. Allowed types: ' . implode(', ', $this->getExtensions($allowedTypes));
            return false;
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            $this->errors[] = 'File size exceeds maximum allowed size of ' . $this->formatBytes($maxSize) . '.';
            return false;
        }
        
        // Generate unique filename
        $extension = $this->getExtension($file['name']);
        $filename = $this->generateUniqueFilename($extension);
        
        // Determine upload path
        $uploadPath = $this->getUploadPath($type);
        $fullPath = $uploadPath . $filename;
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->errors[] = 'Failed to move uploaded file.';
            return false;
        }
        
        // Set proper permissions
        chmod($fullPath, 0644);
        
        return [
            'filename' => $filename,
            'path' => $type . '/' . $filename,
            'size' => $file['size'],
            'type' => $mimeType,
            'original_name' => $file['name']
        ];
    }
    
    /**
     * Upload multiple files
     */
    public function uploadMultiple($files, $type = 'general') {
        $uploaded = [];
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            
            $result = $this->upload($file, $type);
            if ($result) {
                $uploaded[] = $result;
            }
        }
        
        return $uploaded;
    }
    
    /**
     * Delete a file
     */
    public function delete($path) {
        $fullPath = $this->config['upload_path'] . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    /**
     * Get allowed types based on category
     */
    private function getAllowedTypes($type) {
        switch ($type) {
            case 'video':
                return $this->config['allowed_video_types'];
            case 'document':
            case 'pdf':
                return $this->config['allowed_document_types'];
            case 'image':
            case 'thumbnail':
                return $this->config['allowed_image_types'];
            case 'archive':
                return $this->config['allowed_archive_types'];
            default:
                return array_merge(
                    $this->config['allowed_image_types'],
                    $this->config['allowed_document_types']
                );
        }
    }
    
    /**
     * Get upload path based on type
     */
    private function getUploadPath($type) {
        $basePath = $this->config['upload_path'];
        
        switch ($type) {
            case 'thumbnail':
            case 'image':
                return $basePath . $this->config['course_thumbnail_path'];
            case 'video':
            case 'document':
            case 'pdf':
            case 'archive':
                return $basePath . $this->config['resource_path'];
            case 'certificate':
                return $basePath . $this->config['certificate_path'];
            default:
                return $basePath;
        }
    }
    
    /**
     * Generate unique filename
     */
    private function generateUniqueFilename($extension) {
        return uniqid() . '_' . time() . '.' . $extension;
    }
    
    /**
     * Get file extension
     */
    private function getExtension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
    
    /**
     * Get extensions from MIME types
     */
    private function getExtensions($mimeTypes) {
        $extensions = [];
        $mimeMap = [
            'image/jpeg' => 'jpg/jpeg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/ogg' => 'ogg',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/zip' => 'zip',
        ];
        
        foreach ($mimeTypes as $mime) {
            if (isset($mimeMap[$mime])) {
                $extensions[] = $mimeMap[$mime];
            }
        }
        
        return $extensions;
    }
    
    /**
     * Get upload error message
     */
    private function getUploadErrorMessage($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File is too large.';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension.';
            default:
                return 'Unknown upload error.';
        }
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Get errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Check if upload was successful
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
}

<?php
/**
 * Utility Functions for Validation, Sanitization, Image Upload, and Flash Messages
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize user input
 * @param string|null $data
 * @param bool $isHtml Set to true for rich text fields to retain HTML while stripping scripts
 * @return string
 */
function sanitizeInput($data, $isHtml = false) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    
    if ($isHtml) {
        // Remove malicious script tags and event handlers to prevent XSS in rich text
        $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
        $data = preg_replace('/on\w+\s*=\s*"[^"]*"/i', '', $data);
        $data = preg_replace('/on\w+\s*=\s*\'[^\']*\'/i', '', $data);
        return $data;
    } else {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Validate email address format
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate uploaded image attributes
 * @param array $file The $_FILES['name'] element
 * @param string|null $error Output variable for the error message
 * @return bool
 */
function validateImage($file, &$error = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return true; // No file uploaded is not a failure (optional upload)
        }
        $error = "File upload failed with error code: " . $file['error'];
        return false;
    }

    // Check file size (limit to 2MB)
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $error = "File size exceeds the 2MB limit.";
        return false;
    }

    // Check extension
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
    $fileName = $file['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        $error = "Invalid file extension. Only JPG, JPEG, PNG, and WEBP are allowed.";
        return false;
    }

    // Check MIME type using mime_content_type if available
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (function_exists('mime_content_type')) {
        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $error = "Invalid file content type.";
            return false;
        }
    }

    return true;
}

/**
 * Rename and move uploaded file to target folder
 * @param array $file
 * @return string|null The relative path to the uploaded image, or null on failure
 */
function uploadImage($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = __DIR__ . '/../assets/uploads/profile_images/';
    
    // Create folder structure if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = $file['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Cryptographically secure unique name to prevent collisions and directory traversal attacks
    $newFileName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $destPath)) {
        return 'assets/uploads/profile_images/' . $newFileName;
    }

    return null;
}

/**
 * Set session flash message
 * @param string $type success | error
 * @param string $message
 */
function setFlash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Get and unset flash message
 * @param string $type
 * @return string|null
 */
function getFlash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

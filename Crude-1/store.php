<?php
/**
 * Processes Form Submission to Create a User Profile
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/controller/function.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create.php");
    exit;
}

// Sanitize regular string inputs
$name  = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');

// Sanitize rich text inputs (retaining tags but stripping scripts/event handlers)
$description = sanitizeInput($_POST['description'] ?? '', true);
$experience  = sanitizeInput($_POST['experience'] ?? '', true);
$projects    = sanitizeInput($_POST['projects'] ?? '', true);

// Validation
if (empty($name) || empty($email)) {
    setFlash('error', 'Please fill in all required fields (Name and Email).');
    header("Location: create.php");
    exit;
}

if (!validateEmail($email)) {
    setFlash('error', 'Invalid email address format.');
    header("Location: create.php");
    exit;
}

// Check if email already exists in DB
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        setFlash('error', 'A user with this email address already exists.');
        header("Location: create.php");
        exit;
    }
} catch (PDOException $e) {
    setFlash('error', 'Database validation query failed: ' . $e->getMessage());
    header("Location: create.php");
    exit;
}

// Handle Profile Image Upload
$profile_image = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $imgError = null;
    if (!validateImage($_FILES['profile_image'], $imgError)) {
        setFlash('error', $imgError);
        header("Location: create.php");
        exit;
    }
    
    $profile_image = uploadImage($_FILES['profile_image']);
    if (!$profile_image) {
        setFlash('error', 'Failed to save the uploaded image on the server.');
        header("Location: create.php");
        exit;
    }
}

// Insert into Database using PDO Prepared Statements
try {
    $sql = "INSERT INTO users (name, email, description, experience, projects, profile_image) 
            VALUES (:name, :email, :description, :experience, :projects, :profile_image)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name'          => $name,
        'email'         => $email,
        'description'   => $description,
        'experience'    => $experience,
        'projects'      => $projects,
        'profile_image' => $profile_image
    ]);
    
    setFlash('success', 'User profile created successfully.');
    header("Location: dashboard.php");
    exit;
} catch (PDOException $e) {
    // Delete the uploaded file if database insertion failed to avoid orphaned files
    if ($profile_image && file_exists(__DIR__ . '/' . $profile_image)) {
        unlink(__DIR__ . '/' . $profile_image);
    }
    
    setFlash('error', 'Failed to store user profile: ' . $e->getMessage());
    header("Location: create.php");
    exit;
}

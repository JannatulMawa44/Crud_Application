<?php
/**
 * Processes Form Submission to Update a User Profile
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/controller/function.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

// Retrieve ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Invalid user profile modification request.');
    header("Location: dashboard.php");
    exit;
}

// Fetch existing user record to check existence and retrieve image path
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        setFlash('error', 'User profile not found.');
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    setFlash('error', 'Database operation failed: ' . $e->getMessage());
    header("Location: edit.php?id=" . $id);
    exit;
}

// Sanitize inputs
$name  = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');

// Sanitize rich text inputs
$description = sanitizeInput($_POST['description'] ?? '', true);
$experience  = sanitizeInput($_POST['experience'] ?? '', true);
$projects    = sanitizeInput($_POST['projects'] ?? '', true);

// Validation
if (empty($name) || empty($email)) {
    setFlash('error', 'Please fill in all required fields (Name and Email).');
    header("Location: edit.php?id=" . $id);
    exit;
}

if (!validateEmail($email)) {
    setFlash('error', 'Invalid email address format.');
    header("Location: edit.php?id=" . $id);
    exit;
}

// Check if email already exists for another user (collision prevention)
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
    $stmt->execute(['email' => $email, 'id' => $id]);
    if ($stmt->fetch()) {
        setFlash('error', 'This email address is already in use by another profile.');
        header("Location: edit.php?id=" . $id);
        exit;
    }
} catch (PDOException $e) {
    setFlash('error', 'Database validation query failed: ' . $e->getMessage());
    header("Location: edit.php?id=" . $id);
    exit;
}

// Handle Profile Image Upload
$profile_image = $user['profile_image']; // Default to current image path
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $imgError = null;
    if (!validateImage($_FILES['profile_image'], $imgError)) {
        setFlash('error', $imgError);
        header("Location: edit.php?id=" . $id);
        exit;
    }
    
    // Upload new image
    $new_image_path = uploadImage($_FILES['profile_image']);
    if ($new_image_path) {
        // Delete old image file if it exists and is not a default asset
        if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])) {
            unlink(__DIR__ . '/' . $user['profile_image']);
        }
        $profile_image = $new_image_path;
    } else {
        setFlash('error', 'Failed to save the uploaded image on the server.');
        header("Location: edit.php?id=" . $id);
        exit;
    }
}

// Update Database using PDO Prepared Statements
try {
    $sql = "UPDATE users 
            SET name = :name, email = :email, description = :description, 
                experience = :experience, projects = :projects, profile_image = :profile_image 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name'          => $name,
        'email'         => $email,
        'description'   => $description,
        'experience'    => $experience,
        'projects'      => $projects,
        'profile_image' => $profile_image,
        'id'            => $id
    ]);
    
    setFlash('success', 'User profile updated successfully.');
    header("Location: dashboard.php");
    exit;
} catch (PDOException $e) {
    // If update failed and we uploaded a new image, delete the new image to clean up
    if ($profile_image !== $user['profile_image'] && file_exists(__DIR__ . '/' . $profile_image)) {
        unlink(__DIR__ . '/' . $profile_image);
    }
    
    setFlash('error', 'Failed to update user profile: ' . $e->getMessage());
    header("Location: edit.php?id=" . $id);
    exit;
}

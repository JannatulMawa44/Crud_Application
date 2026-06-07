<?php
/**
 * Deletes a User Profile and Associated Uploaded Files
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/controller/function.php';

// Validate ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Invalid user profile deletion request.');
    header("Location: dashboard.php");
    exit;
}

// Fetch user data to locate profile image path
try {
    $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        setFlash('error', 'User profile not found.');
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    setFlash('error', 'Database lookup failed: ' . $e->getMessage());
    header("Location: dashboard.php");
    exit;
}

// Delete from Database
try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    // Delete profile image file from storage if it exists
    if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])) {
        unlink(__DIR__ . '/' . $user['profile_image']);
    }
    
    setFlash('success', 'User profile deleted successfully.');
} catch (PDOException $e) {
    setFlash('error', 'Failed to delete user profile: ' . $e->getMessage());
}

header("Location: dashboard.php");
exit;

<?php
/**
 * Edit User Profile Form
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/controller/function.php';

// Validate ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlash('error', 'Invalid user profile request.');
    header("Location: dashboard.php");
    exit;
}

// Fetch user data
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
    setFlash('error', 'Database lookup failure: ' . $e->getMessage());
    header("Location: dashboard.php");
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                            <i class="fa-solid fa-user-pen text-info me-2"></i>Edit User Profile
                        </h4>
                        <p class="text-muted small mb-0 mt-1">Modify information for <?= htmlspecialchars($user['name']); ?>.</p>
                    </div>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="update.php" method="POST" enctype="multipart/form-data">
                    <!-- Hidden ID Field -->
                    <input type="hidden" name="id" value="<?= $user['id']; ?>">
                    
                    <div class="row g-4">
                        <!-- Left Side: Basic Info -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Profile Photo Upload and Current Photo -->
                        <div class="col-md-5 text-center border-start-md ps-md-4">
                            <label class="form-label d-block fw-semibold text-secondary mb-3">Profile Photo</label>
                            <div class="d-inline-block position-relative mb-3">
                                <?php 
                                $imgUrl = !empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image']) 
                                    ? htmlspecialchars($user['profile_image']) 
                                    : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80';
                                ?>
                                <img id="image-preview-placeholder" src="<?= $imgUrl; ?>" alt="Avatar Preview" class="avatar-large">
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            </div>
                            <div class="text-muted small">Upload a new photo to replace the current one. Max size: 2MB.</div>
                        </div>

                        <!-- Rich Text Details (Summernote) -->
                        <div class="col-12 border-top pt-4">
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-secondary">Professional Description</label>
                                <textarea class="summernote" id="description" name="description"><?= $user['description']; ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="experience" class="form-label fw-semibold text-secondary">Work Experience</label>
                                <textarea class="summernote" id="experience" name="experience"><?= $user['experience']; ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="projects" class="form-label fw-semibold text-secondary">Projects Portfolio</label>
                                <textarea class="summernote" id="projects" name="projects"><?= $user['projects']; ?></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="col-12 border-top pt-3 text-end">
                            <a href="profile.php?id=<?= $user['id']; ?>" class="btn btn-light rounded-pill px-4 me-2">Cancel</a>
                            <button type="submit" class="btn btn-info text-dark rounded-pill px-5 fw-semibold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<?php
/**
 * Create User Profile Form
 */
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                            <i class="fa-solid fa-user-plus text-info me-2"></i>Create User Profile
                        </h4>
                        <p class="text-muted small mb-0 mt-1">Register a new profile with rich-text experience details and an avatar.</p>
                    </div>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="store.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Left Side: Basic Info -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="name" name="name" placeholder="John Doe" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" placeholder="johndoe@example.com" required>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Profile Photo Upload -->
                        <div class="col-md-5 text-center border-start-md ps-md-4">
                            <label class="form-label d-block fw-semibold text-secondary mb-3">Profile Photo</label>
                            <div class="d-inline-block position-relative mb-3">
                                <img id="image-preview-placeholder" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80" alt="Avatar Preview" class="avatar-large">
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            </div>
                            <div class="text-muted small">Max file size: 2MB. Format: JPG, JPEG, PNG, WEBP.</div>
                        </div>

                        <!-- Rich Text Details (Summernote) -->
                        <div class="col-12 border-top pt-4">
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-secondary">Professional Description</label>
                                <textarea class="summernote" id="description" name="description"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="experience" class="form-label fw-semibold text-secondary">Work Experience</label>
                                <textarea class="summernote" id="experience" name="experience"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="projects" class="form-label fw-semibold text-secondary">Projects Portfolio</label>
                                <textarea class="summernote" id="projects" name="projects"></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="col-12 border-top pt-3 text-end">
                            <button type="reset" class="btn btn-light rounded-pill px-4 me-2">Reset</button>
                            <button type="submit" class="btn btn-info text-dark rounded-pill px-5 fw-semibold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Profile
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

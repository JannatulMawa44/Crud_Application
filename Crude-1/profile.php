<?php
/**
 * View Single User Profile
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/controller/function.php';

// Validate ID from URL
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

<div class="row g-4">
    <!-- Left Column: User Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <div class="card-body">
                <div class="mb-4">
                    <?php 
                    $imgUrl = !empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image']) 
                        ? htmlspecialchars($user['profile_image']) 
                        : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80';
                    ?>
                    <img src="<?= $imgUrl; ?>" alt="<?= htmlspecialchars($user['name']); ?>'s Avatar" class="avatar-large mb-3">
                    <h3 class="fw-bold tracking-tight mb-1" style="font-family: 'Outfit', sans-serif;"><?= htmlspecialchars($user['name']); ?></h3>
                    <p class="text-secondary small mb-3">
                        <i class="fa-regular fa-envelope me-1"></i><?= htmlspecialchars($user['email']); ?>
                    </p>
                    <span class="badge bg-light text-secondary border py-2 px-3 rounded-pill">
                        <i class="fa-regular fa-calendar-check me-1"></i>Joined <?= date('M d, Y', strtotime($user['created_at'])); ?>
                    </span>
                </div>
                
                <hr class="my-4">
                
                <div class="d-grid gap-2">
                    <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-outline-primary rounded-pill">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                    </a>
                    <a href="delete.php?id=<?= $user['id']; ?>" class="btn btn-outline-danger rounded-pill btn-delete-user" data-name="<?= htmlspecialchars($user['name']); ?>">
                        <i class="fa-solid fa-trash-can me-1"></i> Delete Profile
                    </a>
                    <a href="dashboard.php" class="btn btn-light rounded-pill mt-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Rich Text Details -->
    <div class="col-lg-8">
        <!-- Professional Description Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-address-card text-info me-2"></i>Professional Description
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (empty($user['description'])): ?>
                    <p class="text-muted italic mb-0">No description provided yet.</p>
                <?php else: ?>
                    <div class="rich-text-content">
                        <?= $user['description']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Experience Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-briefcase text-info me-2"></i>Work Experience
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (empty($user['experience'])): ?>
                    <p class="text-muted italic mb-0">No experience details provided yet.</p>
                <?php else: ?>
                    <div class="rich-text-content">
                        <?= $user['experience']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-diagram-project text-info me-2"></i>Projects Portfolio
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (empty($user['projects'])): ?>
                    <p class="text-muted italic mb-0">No project details provided yet.</p>
                <?php else: ?>
                    <div class="rich-text-content">
                        <?= $user['projects']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

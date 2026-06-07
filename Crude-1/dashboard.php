<?php
/**
 * Dashboard View - List All User Profiles
 */
require_once __DIR__ . '/controller/db.php';
require_once __DIR__ . '/includes/header.php';

// Fetch all profiles ordered by latest registration
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    setFlash('error', 'Database query failure: ' . $e->getMessage());
    $users = [];
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Profiles Dashboard</h2>
            <p class="text-muted mb-0">Manage registered users, review profiles, and perform CRUD operations.</p>
        </div>
        <a href="create.php" class="btn btn-info text-dark rounded-pill px-4 fw-semibold d-inline-flex align-items-center shadow-sm">
            <i class="fa-solid fa-user-plus me-2"></i> Add Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php if (empty($users)): ?>
            <!-- Beautiful Empty State -->
            <div class="card text-center py-5 border-0 shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fa-regular fa-user fs-1"></i>
                        </span>
                    </div>
                    <h4 class="fw-semibold text-dark">No Profiles Found</h4>
                    <p class="text-muted col-md-6 mx-auto mb-4">There are currently no registered profiles in the system. Get started by adding a new profile.</p>
                    <a href="create.php" class="btn btn-info text-dark rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Create First Profile
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- User Profiles Table -->
            <div class="table-responsive bg-white rounded-3 shadow-sm">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="width: 80px;">Avatar</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Created Date</th>
                            <th scope="col" class="text-end" style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="text-center">
                                    <?php 
                                    $imgUrl = !empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image']) 
                                        ? htmlspecialchars($user['profile_image']) 
                                        : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80'; // fallback
                                    ?>
                                    <img src="<?= $imgUrl; ?>" alt="<?= htmlspecialchars($user['name']); ?>'s Avatar" class="avatar-thumb">
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($user['name']); ?></div>
                                </td>
                                <td>
                                    <span class="text-secondary"><?= htmlspecialchars($user['email']); ?></span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        <?= date('d M Y, h:i A', strtotime($user['created_at'])); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="profile.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-outline-success rounded-pill px-3" title="View Profile">
                                            <i class="fa-solid fa-eye me-1"></i> View
                                        </a>
                                        <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Edit Profile">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                        </a>
                                        <a href="delete.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-delete-user" data-name="<?= htmlspecialchars($user['name']); ?>" title="Delete Profile">
                                            <i class="fa-solid fa-trash-can me-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<?php
/**
 * Shared Header Template
 */
require_once __DIR__ . '/../controller/function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management System</title>
    <!-- Google Fonts (Outfit & Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 (For premium icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Summernote Lite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Custom Style Sheet -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Premium Glassmorphic Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <i class="fa-solid fa-user-gear text-info me-2 fs-4"></i>
                <span class="fw-bold tracking-tight" style="font-family: 'Outfit', sans-serif;">ProfileHub</span>
            </a>
            <button class="navbar-brand-toggler navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill" href="dashboard.php">
                            <i class="fa-solid fa-chart-simple me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 btn btn-info text-dark rounded-pill fw-semibold d-inline-flex align-items-center" href="create.php">
                            <i class="fa-solid fa-user-plus me-1 text-dark"></i> Add Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-5">
        
        <!-- Flash Message Container -->
        <div class="row">
            <div class="col-12">
                <?php
                $flashSuccess = getFlash('success');
                $flashError = getFlash('error');
                ?>
                <?php if ($flashSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                            <div>
                                <strong class="d-block text-success-emphasis">Success!</strong>
                                <span class="text-success-emphasis"><?= htmlspecialchars($flashSuccess); ?></span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($flashError): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-exclamation fs-4 me-3 text-danger"></i>
                            <div>
                                <strong class="d-block text-danger-emphasis">Error!</strong>
                                <span class="text-danger-emphasis"><?= htmlspecialchars($flashError); ?></span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

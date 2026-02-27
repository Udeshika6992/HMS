<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- =============================================
         BOOTSTRAP CSS (CDN)
         ============================================= -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- =============================================
         FONT AWESOME (CDN)
         ============================================= -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- =============================================
         GOOGLE FONTS
         ============================================= -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- =============================================
         MAIN CUSTOM CSS (YOUR STYLES)
         ============================================= -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    
    <!-- =============================================
         ROLE-SPECIFIC CSS (Load based on user role)
         ============================================= -->
    <?php 
    $role = $_SESSION['user_role'] ?? '';
    if ($role == 'admin'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
    <?php elseif ($role == 'doctor'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/doctor.css">
    <?php elseif ($role == 'patient'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/patient.css">
    <?php endif; ?>
    
    <!-- =============================================
         PAGE-SPECIFIC CSS (Optional)
         ============================================= -->
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- =============================================
         DATATABLES CSS (if needed)
         ============================================= -->
    <?php if (isset($include_datatables) && $include_datatables): ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <?php endif; ?>
    
    <style>
        /* Inline styles if needed */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- =============================================
         NAVIGATION BAR
         ============================================= -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-hospital me-2"></i><?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- User is logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . $_SESSION['user_role']; ?>/dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" id="notificationList">
                                <li><a class="dropdown-item text-center" href="#">No notifications</a></li>
                            </ul>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL . $_SESSION['user_role']; ?>/profile">
                                    <i class="fas fa-user me-2"></i>My Profile
                                </a></li>
                                <?php if ($_SESSION['user_role'] == 'patient'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>patient/my-appointments">
                                        <i class="fas fa-calendar-check me-2"></i>My Appointments
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>patient/medical-history">
                                        <i class="fas fa-notes-medical me-2"></i>Medical History
                                    </a></li>
                                <?php elseif ($_SESSION['user_role'] == 'doctor'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>doctor/patients">
                                        <i class="fas fa-users me-2"></i>My Patients
                                    </a></li>
                                <?php elseif ($_SESSION['user_role'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/users">
                                        <i class="fas fa-users-cog me-2"></i>User Management
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Guest Navigation -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light ms-2" href="<?php echo BASE_URL; ?>login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light ms-2" href="<?php echo BASE_URL; ?>register">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- =============================================
         FLASH MESSAGES
         ============================================= -->
    <div class="flash-message">
        <?php
        if (isset($_SESSION['flash_message'])) {
            $type = $_SESSION['flash_type'] ?? 'info';
            $message = $_SESSION['flash_message'];
            $alertClass = $type == 'success' ? 'alert-success' : ($type == 'error' ? 'alert-danger' : 'alert-info');
            echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>";
            echo $message;
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
            echo "</div>";
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        }
        ?>
    </div>

    <!-- =============================================
         MAIN CONTENT STARTS HERE
         ============================================= -->
    <main>
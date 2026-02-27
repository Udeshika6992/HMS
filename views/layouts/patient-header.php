<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - Patient - ' . APP_NAME : 'Patient - ' . APP_NAME; ?></title>
    
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    
    <!-- Patient Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/patient.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>patient/dashboard">
                <i class="fas fa-hospital me-2"></i><?php echo APP_NAME; ?> - Patient Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/dashboard">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/book-appointment">
                            <i class="fas fa-calendar-plus me-1"></i>Book Appointment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/my-appointments">
                            <i class="fas fa-calendar-check me-1"></i>My Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/medical-history">
                            <i class="fas fa-notes-medical me-1"></i>Medical History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/progress-charts">
                            <i class="fas fa-chart-line me-1"></i>Progress
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Patient'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>patient/profile">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <div class="flash-message">
            <?php
            if (isset($_SESSION['flash_message'])) {
                $type = $_SESSION['flash_type'] ?? 'info';
                $message = $_SESSION['flash_message'];
                $alertClass = $type == 'success' ? 'alert-success' : ($type == 'error' ? 'alert-danger' : 'alert-info');
                echo "<div class='alert $alertClass alert-dismissible fade show'>";
                echo $message;
                echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
                echo "</div>";
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_type']);
            }
            ?>
        </div>
    </div>

    <main class="container-fluid mt-3">
<?php
// Include view helpers
require_once __DIR__ . '/../helpers.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - Doctor - ' . APP_NAME : 'Doctor - ' . APP_NAME; ?></title>
    
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- FullCalendar (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    
    <!-- Doctor Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/doctor.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>doctor/dashboard">
                <i class="fas fa-hospital me-2"></i><?php echo APP_NAME; ?> - Doctor Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>doctor/dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>doctor/appointments">
                            <i class="fas fa-calendar-check me-1"></i>Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>doctor/patients">
                            <i class="fas fa-users me-1"></i>My Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>doctor/schedule">
                            <i class="fas fa-clock me-1"></i>Schedule
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            Dr. <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Doctor'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>doctor/profile">
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
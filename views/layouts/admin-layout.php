<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Bootstrap CSS (CDN - Most Reliable) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
    
    <style>
        /* Emergency inline styles in case external CSS fails */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fe;
        }
        .page-header {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
            padding: 3rem 0 6rem;
            margin-bottom: -3rem;
            color: white;
        }
        .mt--6 {
            margin-top: -3rem !important;
        }
        .card-stats {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0 2rem 0 rgba(136,152,170,.15);
        }
        .icon-shape {
            width: 3.5rem;
            height: 3.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .bg-gradient-primary { background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%); }
        .bg-gradient-success { background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%); }
        .bg-gradient-info { background: linear-gradient(87deg, #11cdef 0, #1171ef 100%); }
        .bg-gradient-warning { background: linear-gradient(87deg, #fb6340 0, #fbb140 100%); }
        .text-white { color: #fff !important; }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>admin/dashboard">
                <i class="fas fa-hospital me-2"></i><?php echo APP_NAME; ?> - Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/profile">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/settings">
                                <i class="fas fa-cog me-2"></i>Settings
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

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/users">
                                <i class="fas fa-users me-2"></i>User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="<?php echo BASE_URL; ?>admin/doctors">
                                <i class="fas fa-user-md me-2"></i>Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/patients">
                                <i class="fas fa-procedures me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/departments">
                                <i class="fas fa-building me-2"></i>Departments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/appointments">
                                <i class="fas fa-calendar-check me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/reports">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/settings">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show mt-3">
                        <?php 
                        echo $_SESSION['flash_message'];
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['flash_type']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
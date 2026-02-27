<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Admin Dashboard</h1>
        <p class="lead">Welcome back, <?php echo $_SESSION['user_name']; ?>!</p>
    </div>
</section>

<!-- Statistics Cards -->
<section class="container mb-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-users text-primary"></i>
                <h3><?php echo $stats['total_users']; ?></h3>
                <p class="text-muted">Total Users</p>
                <small>
                    <span class="text-info"><?php echo $stats['total_doctors']; ?> Doctors</span> | 
                    <span class="text-success"><?php echo $stats['total_patients']; ?> Patients</span>
                </small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-calendar-check text-warning"></i>
                <h3><?php echo $stats['total_appointments']; ?></h3>
                <p class="text-muted">Total Appointments</p>
                <small>Today: <?php echo $stats['today_appointments']; ?></small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-building text-success"></i>
                <h3><?php echo $stats['total_departments']; ?></h3>
                <p class="text-muted">Departments</p>
                <small>Active Departments</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-chart-line text-danger"></i>
                <h3><?php echo date('Y'); ?></h3>
                <p class="text-muted">Current Year</p>
                <small><?php echo date('F j, Y'); ?></small>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="container mb-5">
    <div class="row">
        <!-- Recent Users -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Recent Users</h5>
                    <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-light btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_users'])): ?>
                        <p class="text-muted text-center py-4">No recent users</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($stats['recent_users'] as $user): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $user['full_name']; ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-1"></i><?php echo $user['email']; ?>
                                            </small>
                                            <br>
                                            <span class="badge bg-<?php 
                                                echo $user['role'] == 'admin' ? 'danger' : 
                                                    ($user['role'] == 'doctor' ? 'info' : 'success'); 
                                            ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Recent Appointments</h5>
                    <a href="<?php echo BASE_URL; ?>admin/appointments" class="btn btn-light btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['recent_appointments'])): ?>
                        <p class="text-muted text-center py-4">No recent appointments</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($stats['recent_appointments'], 0, 5) as $apt): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $apt['patient_name']; ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user-md me-1"></i>Dr. <?php echo $apt['doctor_name']; ?>
                                            </small>
                                            <br>
                                            <small>
                                                <i class="fas fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?>
                                                <i class="fas fa-clock ms-2 me-1"></i><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?php 
                                            echo $apt['status'] == 'completed' ? 'success' : 
                                                ($apt['status'] == 'pending' ? 'warning' : 
                                                ($apt['status'] == 'confirmed' ? 'info' : 'secondary')); 
                                        ?>">
                                            <?php echo ucfirst($apt['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>admin/users/create" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <br>Add User
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>admin/departments/create" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <br>Add Department
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <br>View Reports
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>admin/settings" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-cog fa-2x mb-2"></i>
                                <br>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
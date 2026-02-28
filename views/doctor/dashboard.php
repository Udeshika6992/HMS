<?php
/**
 * Doctor Dashboard
 * Complete professional dashboard for doctors
 */

/* @var array $doctor */
/* @var array $stats */
/* @var array $today_appointments */
/* @var array $upcoming_appointments */
/* @var array $recent_patients */
/* @var array $monthly_stats */
?>

<div class="container-fluid">
    <!-- Page Header with Doctor Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-4 fw-bold mb-2">Welcome, Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? $_SESSION['user_name']); ?>!</h1>
                            <p class="lead mb-0">
                                <i class="fas fa-stethoscope me-2"></i>
                                <?php echo htmlspecialchars($doctor['specialization'] ?? 'General Physician'); ?> 
                                <?php if (!empty($doctor['department_name'])): ?>
                                    - <?php echo htmlspecialchars($doctor['department_name']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="availability-toggle d-inline-block bg-white bg-opacity-25 rounded-pill px-4 py-2">
                                <span class="me-2 fw-semibold">Status:</span>
                                <span class="badge bg-<?php echo ($doctor['is_available'] ?? 1) ? 'success' : 'danger'; ?> fs-6 px-3 py-2">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                    <?php echo ($doctor['is_available'] ?? 1) ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Today's Appointments</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $stats['today_appointments'] ?? 0; ?></h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                <?php echo ($stats['today_appointments'] ?? 0) > 0 ? '+12%' : 'No appointments'; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $stats['pending_appointments'] ?? 0; ?></h3>
                            <small class="text-warning">
                                <i class="fas fa-hourglass-half me-1"></i>
                                Awaiting confirmation
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Completed</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $stats['completed_appointments'] ?? 0; ?></h3>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                This month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Patients</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $stats['total_patients'] ?? 0; ?></h3>
                            <small class="text-info">
                                <i class="fas fa-user-plus me-1"></i>
                                Lifetime patients
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Today's Schedule -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Today's Schedule (<?php echo date('M d, Y'); ?>)
                        </h5>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <?php echo count($today_appointments ?? []); ?> appointments
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($today_appointments)): ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-calendar-check fa-4x text-muted opacity-50"></i>
                            </div>
                            <h6 class="text-muted">No appointments scheduled for today</h6>
                            <p class="text-muted small">Enjoy your day!</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($today_appointments as $appointment): ?>
                                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-<?php 
                                                    echo $appointment['status'] == 'confirmed' ? 'success' : 
                                                        ($appointment['status'] == 'pending' ? 'warning' : 'secondary'); 
                                                ?> me-2">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                                <span class="text-muted small">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                                </span>
                                            </div>
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($appointment['patient_name']); ?></h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="fas fa-phone-alt me-2"></i>
                                                <?php echo htmlspecialchars($appointment['patient_phone'] ?? 'N/A'); ?>
                                                <?php if (!empty($appointment['symptoms'])): ?>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-notes-medical me-1"></i>
                                                    <?php echo substr(htmlspecialchars($appointment['symptoms']), 0, 30); ?>...
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <a href="<?php echo BASE_URL; ?>doctor/view-appointment/<?php echo $appointment['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary rounded-circle"
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments?date=<?php echo date('Y-m-d'); ?>" 
                               class="btn btn-link text-decoration-none">
                                View Full Schedule <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            Upcoming Appointments
                        </h5>
                        <span class="badge bg-info rounded-pill px-3 py-2">
                            <?php echo count($upcoming_appointments ?? []); ?> upcoming
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($upcoming_appointments)): ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-calendar fa-4x text-muted opacity-50"></i>
                            </div>
                            <h6 class="text-muted">No upcoming appointments</h6>
                            <p class="text-muted small">Check back later</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($upcoming_appointments, 0, 5) as $appointment): ?>
                                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($appointment['patient_name']); ?></h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="fas fa-calendar me-2"></i>
                                                <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-<?php 
                                            echo $appointment['status'] == 'confirmed' ? 'success' : 'warning'; 
                                        ?> rounded-pill px-3 py-2">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments" class="btn btn-link text-decoration-none">
                                View All Appointments <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row g-4 mt-2">
        <!-- Recent Patients -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-friends text-success me-2"></i>
                            Recent Patients
                        </h5>
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <?php echo count($recent_patients ?? []); ?> recent
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (empty($recent_patients)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted">No patients yet</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_patients as $patient): ?>
                                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $patient['profile_image'] ?? 'default-avatar.png'; ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #e9ecef;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($patient['full_name']); ?></h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                Last visit: <?php echo date('M d, Y', strtotime($patient['last_visit'] ?? 'now')); ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                <?php echo $patient['visit_count'] ?? 0; ?> visits
                                            </div>
                                        </div>
                                        <a href="<?php echo BASE_URL; ?>doctor/view-patient/<?php echo $patient['id']; ?>" 
                                           class="btn btn-sm btn-outline-success rounded-circle"
                                           data-bs-toggle="tooltip" title="View Patient">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>doctor/patients" class="btn btn-link text-decoration-none">
                                View All Patients <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics Chart -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-bar text-warning me-2"></i>
                            Monthly Statistics - <?php echo date('Y'); ?>
                        </h5>
                        <select class="form-select form-select-sm w-auto" id="chartPeriod">
                            <option value="6">Last 6 months</option>
                            <option value="12" selected>This year</option>
                            <option value="24">Last 2 years</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="monthlyChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt text-secondary me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments?date=<?php echo date('Y-m-d'); ?>" 
                               class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <span>Today's Schedule</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/patients" 
                               class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span>My Patients</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/schedule" 
                               class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <span>My Schedule</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/profile" 
                               class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center">
                                <i class="fas fa-user-md fa-2x mb-2"></i>
                                <span>My Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Monthly Statistics Chart
    var monthlyData = <?php echo json_encode($monthly_stats ?? []); ?>;
    
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var totalData = new Array(12).fill(0);
    var completedData = new Array(12).fill(0);
    
    monthlyData.forEach(function(item) {
        if (item.month >= 1 && item.month <= 12) {
            totalData[item.month - 1] = item.total || 0;
            completedData[item.month - 1] = item.completed || 0;
        }
    });
    
    var ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Total Appointments',
                    data: totalData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Completed',
                    data: completedData,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart period change handler
    $('#chartPeriod').change(function() {
        // In a real app, you'd reload the chart data via AJAX
        // For now, just show a message
        console.log('Period changed to: ' + $(this).val());
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<!-- Additional CSS -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}

.list-group-item {
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.btn-outline-primary, .btn-outline-success, .btn-outline-info, .btn-outline-secondary {
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.15);
}

.btn-outline-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.15);
}

.btn-outline-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(13, 202, 240, 0.15);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(108, 117, 125, 0.15);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
    }
    
    .stat-icon i {
        font-size: 1.5rem;
    }
}
</style>
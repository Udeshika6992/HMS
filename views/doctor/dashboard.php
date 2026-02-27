<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4">Welcome, Dr. <?php echo $doctor['full_name']; ?>!</h1>
                <p class="lead"><?php echo $doctor['specialization']; ?> - <?php echo $doctor['department_name']; ?></p>
            </div>
            <div class="col-md-4 text-end">
                <div class="availability-toggle">
                    <span class="me-2">Status:</span>
                    <span class="badge bg-<?php echo $doctor['is_available'] ? 'success' : 'danger'; ?> fs-6">
                        <?php echo $doctor['is_available'] ? 'Available' : 'Unavailable'; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="container mb-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-calendar-check text-primary"></i>
                <h3><?php echo $stats['today_appointments']; ?></h3>
                <p class="text-muted">Today's Appointments</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-clock text-warning"></i>
                <h3><?php echo $stats['pending_appointments']; ?></h3>
                <p class="text-muted">Pending</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-check-circle text-success"></i>
                <h3><?php echo $stats['completed_appointments']; ?></h3>
                <p class="text-muted">Completed</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-users text-info"></i>
                <h3><?php echo $stats['total_patients']; ?></h3>
                <p class="text-muted">Total Patients</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="container mb-5">
    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Today's Schedule (<?php echo date('M d, Y'); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($today_appointments)): ?>
                        <p class="text-muted text-center py-4">No appointments scheduled for today</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($today_appointments as $appointment): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-<?php 
                                                echo $appointment['status'] == 'confirmed' ? 'success' : 
                                                    ($appointment['status'] == 'pending' ? 'warning' : 'secondary'); 
                                            ?> mb-2">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                            <h6 class="mb-1"><?php echo $appointment['patient_name']; ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-clock me-1"></i> <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                                <i class="fas fa-phone ms-3 me-1"></i> <?php echo $appointment['patient_phone']; ?>
                                            </p>
                                            <?php if ($appointment['symptoms']): ?>
                                                <small class="text-muted"><?php echo substr($appointment['symptoms'], 0, 50); ?>...</small>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>doctor/view-appointment/<?php echo $appointment['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments?date=<?php echo date('Y-m-d'); ?>" class="btn btn-link">
                                View Full Schedule
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($upcoming_appointments)): ?>
                        <p class="text-muted text-center py-4">No upcoming appointments</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($upcoming_appointments, 0, 5) as $appointment): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $appointment['patient_name']; ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                <i class="fas fa-clock ms-3 me-1"></i> <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                            </p>
                                            <span class="badge bg-<?php echo $appointment['status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments" class="btn btn-link">View All</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-friends me-2"></i>Recent Patients</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_patients)): ?>
                        <p class="text-muted text-center py-4">No patients yet</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_patients as $patient): ?>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo UPLOAD_URL . 'profiles/' . ($patient['profile_image'] ?? 'default-avatar.png'); ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0"><?php echo $patient['full_name']; ?></h6>
                                            <small class="text-muted">
                                                Last visit: <?php echo date('M d, Y', strtotime($patient['last_visit'])); ?>
                                                (<?php echo $patient['visit_count']; ?> visits)
                                            </small>
                                        </div>
                                        <a href="<?php echo BASE_URL; ?>doctor/view-patient/<?php echo $patient['id']; ?>" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>doctor/patients" class="btn btn-link">View All Patients</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics Chart -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Monthly Statistics - <?php echo date('Y'); ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/appointments?date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <br>Today's Schedule
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/patients" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>My Patients
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/schedule" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <br>My Schedule
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?php echo BASE_URL; ?>doctor/profile" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-user-md fa-2x mb-2"></i>
                                <br>My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Monthly Statistics Chart
    var monthlyData = <?php echo json_encode($monthly_stats); ?>;
    
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var totalData = new Array(12).fill(0);
    var completedData = new Array(12).fill(0);
    
    monthlyData.forEach(function(item) {
        totalData[item.month - 1] = item.total;
        completedData[item.month - 1] = item.completed;
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
                    borderWidth: 1
                },
                {
                    label: 'Completed',
                    data: completedData,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
});
</script>
<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4">Welcome, <?php echo $patient['full_name']; ?>!</h1>
                <p class="lead">Your health journey at a glance</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?php echo BASE_URL; ?>patient/book-appointment" class="btn btn-light btn-lg">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Cards -->
<section class="container mb-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-calendar-check"></i>
                <h3><?php echo $stats['total_appointments']; ?></h3>
                <p class="text-muted">Total Appointments</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-clock"></i>
                <h3><?php echo $stats['upcoming_appointments']; ?></h3>
                <p class="text-muted">Upcoming</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-file-medical"></i>
                <h3><?php echo $stats['total_medical_records']; ?></h3>
                <p class="text-muted">Medical Records</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <i class="fas fa-calendar-day"></i>
                <h3><?php echo $stats['last_visit'] != 'No visits yet' ? date('M d, Y', strtotime($stats['last_visit'])) : 'No visits'; ?></h3>
                <p class="text-muted">Last Visit</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="container mb-5">
    <div class="row">
        <!-- Upcoming Appointments -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($upcoming_appointments)): ?>
                        <p class="text-muted text-center py-4">No upcoming appointments</p>
                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>patient/book-appointment" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Book Now
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Dr. <?php echo $appointment['doctor_name']; ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                <i class="fas fa-clock ms-3 me-1"></i> <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                            </p>
                                            <span class="badge bg-<?php echo $appointment['status'] == 'confirmed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        </div>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>patient/view-appointment/<?php echo $appointment['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>patient/my-appointments" class="btn btn-link">View All</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Vitals -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Recent Vitals</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_vitals)): ?>
                        <p class="text-muted text-center py-4">No vitals recorded yet</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>BP</th>
                                        <th>Heart Rate</th>
                                        <th>Weight</th>
                                        <th>BMI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_vitals as $vital): ?>
                                        <tr>
                                            <td><?php echo date('M d', strtotime($vital['record_date'])); ?></td>
                                            <td><?php echo $vital['blood_pressure_systolic'] ? $vital['blood_pressure_systolic'] . '/' . $vital['blood_pressure_diastolic'] : '-'; ?></td>
                                            <td><?php echo $vital['heart_rate'] ?: '-'; ?></td>
                                            <td><?php echo $vital['weight'] ? $vital['weight'] . ' kg' : '-'; ?></td>
                                            <td><?php echo $vital['bmi'] ? number_format($vital['bmi'], 1) : '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Prescriptions -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-prescription me-2"></i>Recent Prescriptions</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_prescriptions)): ?>
                        <p class="text-muted text-center py-4">No prescriptions yet</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($recent_prescriptions, 0, 5) as $prescription): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $prescription['medicine_name']; ?></h6>
                                            <p class="mb-1 small">
                                                <span class="badge bg-light text-dark"><?php echo $prescription['dosage']; ?></span>
                                                <span class="badge bg-light text-dark"><?php echo $prescription['frequency']; ?></span>
                                            </p>
                                            <small class="text-muted">Dr. <?php echo $prescription['doctor_name']; ?> - <?php echo date('M d, Y', strtotime($prescription['created_at'])); ?></small>
                                        </div>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>patient/view-prescription/<?php echo $prescription['id']; ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>patient/prescriptions" class="btn btn-link">View All</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>patient/book-appointment" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                                <br>Book Appointment
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>patient/medical-history" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-notes-medical fa-2x mb-2"></i>
                                <br>Medical History
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>patient/progress-charts" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <br>Progress Charts
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>patient/profile" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-user-circle fa-2x mb-2"></i>
                                <br>My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Health Tips Section -->
<section class="container mb-5">
    <div class="card bg-light">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>Health Tips</h4>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i> Drink plenty of water
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i> Exercise regularly
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i> Get enough sleep
                </div>
                <div class="col-md-3 mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i> Take medications on time
                </div>
            </div>
        </div>
    </div>
</section>
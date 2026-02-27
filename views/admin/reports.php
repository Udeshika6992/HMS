<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Reports</h1>
        <p class="lead">Generate and view system reports</p>
    </div>
</section>

<!-- Report Filters -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Report Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="appointments" <?php echo $report_type == 'appointments' ? 'selected' : ''; ?>>Appointments Report</option>
                        <option value="patients" <?php echo $report_type == 'patients' ? 'selected' : ''; ?>>Patients Report</option>
                        <option value="doctors" <?php echo $report_type == 'doctors' ? 'selected' : ''; ?>>Doctors Report</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-sync-alt"></i> Generate
                    </button>
                    <a href="<?php echo BASE_URL; ?>admin/export-report/<?php echo $report_type; ?>?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Export
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Report Results -->
<section class="container mb-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <?php echo ucfirst($report_type); ?> Report 
                (<?php echo date('M d, Y', strtotime($start_date)); ?> - <?php echo date('M d, Y', strtotime($end_date)); ?>)
            </h5>
        </div>
        <div class="card-body">
            <?php if ($report_type == 'appointments'): ?>
                <!-- Appointments Report -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">No appointments found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $apt): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($apt['patient_name']); ?></td>
                                        <td>Dr. <?php echo htmlspecialchars($apt['doctor_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $apt['status'] == 'completed' ? 'success' : 
                                                    ($apt['status'] == 'pending' ? 'warning' : 
                                                    ($apt['status'] == 'confirmed' ? 'info' : 'secondary')); 
                                            ?>">
                                                <?php echo ucfirst($apt['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($report_type == 'patients'): ?>
                <!-- Patients Report -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Registered Date</th>
                                <th>Patient Name</th>
                                <th>Contact</th>
                                <th>Blood Group</th>
                                <th>Total Appointments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($patients)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No patients found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($patient['phone'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($patient['blood_group'] ?? 'N/A'); ?></td>
                                        <td><?php echo $patient['total_appointments'] ?? 0; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($report_type == 'doctors'): ?>
                <!-- Doctors Report -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Department</th>
                                <th>Appointments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($doctors)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No doctors found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($doctors as $doctor): ?>
                                    <tr>
                                        <td>Dr. <?php echo htmlspecialchars($doctor['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($doctor['specialization'] ?? 'General'); ?></td>
                                        <td><?php echo htmlspecialchars($doctor['department_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo $doctor['appointment_count'] ?? 0; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $doctor['is_available'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $doctor['is_available'] ? 'Available' : 'Unavailable'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
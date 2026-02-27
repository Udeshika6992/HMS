<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Patient Details</h1>
        <p class="lead">View complete patient information</p>
    </div>
</section>

<!-- Patient Information -->
<section class="container mb-5">
    <div class="row">
        <!-- Patient Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $patient['profile_image'] ?? 'default-avatar.png'; ?>" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4><?php echo htmlspecialchars($patient['full_name']); ?></h4>
                    <p class="text-muted">Patient ID: P00<?php echo $patient['id']; ?></p>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><i class="fas fa-calendar-alt text-primary me-2"></i> DOB: <?php echo date('M d, Y', strtotime($patient['date_of_birth'] ?? '2000-01-01')); ?></p>
                        <p><i class="fas fa-venus-mars text-primary me-2"></i> Gender: <?php echo $patient['gender'] ?? 'Not specified'; ?></p>
                        <p><i class="fas fa-tint text-danger me-2"></i> Blood Group: <?php echo $patient['blood_group'] ?? 'N/A'; ?></p>
                        <p><i class="fas fa-phone text-primary me-2"></i> Phone: <?php echo $patient['phone'] ?? 'N/A'; ?></p>
                        <p><i class="fas fa-envelope text-primary me-2"></i> Email: <?php echo $patient['email']; ?></p>
                        <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Address: <?php echo $patient['address'] ?? 'N/A'; ?></p>
                        <p><i class="fas fa-calendar-check text-primary me-2"></i> Registered: <?php echo date('M d, Y', strtotime($patient['created_at'])); ?></p>
                    </div>
                    
                    <hr>
                    
                    <h6>Emergency Contact</h6>
                    <p class="mb-1"><strong><?php echo $patient['emergency_contact_name'] ?? 'Not provided'; ?></strong></p>
                    <p><?php echo $patient['emergency_contact_phone'] ?? ''; ?> (<?php echo $patient['emergency_contact_relation'] ?? ''; ?>)</p>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#history" data-bs-toggle="tab">
                                Medical History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#appointments" data-bs-toggle="tab">
                                Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#prescriptions" data-bs-toggle="tab">
                                Prescriptions
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Medical History Tab -->
                        <div class="tab-pane active" id="history">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Doctor</th>
                                            <th>Diagnosis</th>
                                            <th>Treatment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($medical_history)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No medical records found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($medical_history as $record): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($record['record_date'])); ?></td>
                                                    <td>Dr. <?php echo $record['doctor_name']; ?></td>
                                                    <td><?php echo $record['diagnosis']; ?></td>
                                                    <td><?php echo $record['treatment_plan']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Appointments Tab -->
                        <div class="tab-pane" id="appointments">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Doctor</th>
                                            <th>Time</th>
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
                                                    <td>Dr. <?php echo $apt['doctor_name']; ?></td>
                                                    <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
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
                        </div>

                        <!-- Prescriptions Tab -->
                        <div class="tab-pane" id="prescriptions">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Doctor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($prescriptions)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No prescriptions found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($prescriptions as $pres): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($pres['created_at'])); ?></td>
                                                    <td><?php echo $pres['medicine_name']; ?></td>
                                                    <td><?php echo $pres['dosage']; ?></td>
                                                    <td>Dr. <?php echo $pres['doctor_name']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
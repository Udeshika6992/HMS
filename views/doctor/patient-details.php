<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Patient Details</h1>
        <p class="lead">Complete patient information and medical history</p>
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
                    </div>
                    
                    <hr>
                    
                    <h6>Emergency Contact</h6>
                    <p class="mb-1"><strong><?php echo $patient['emergency_contact_name'] ?? 'Not provided'; ?></strong></p>
                    <p><?php echo $patient['emergency_contact_phone'] ?? ''; ?> (<?php echo $patient['emergency_contact_relation'] ?? ''; ?>)</p>
                    
                    <hr>
                    
                    <h6>Medical Information</h6>
                    <p><strong>Allergies:</strong> <?php echo $patient['allergies'] ?? 'None'; ?></p>
                    <p><strong>Chronic Conditions:</strong> <?php echo $patient['chronic_conditions'] ?? 'None'; ?></p>
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
                                <i class="fas fa-notes-medical me-2"></i>Medical History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#appointments" data-bs-toggle="tab">
                                <i class="fas fa-calendar-check me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#prescriptions" data-bs-toggle="tab">
                                <i class="fas fa-prescription me-2"></i>Prescriptions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#vitals" data-bs-toggle="tab">
                                <i class="fas fa-heartbeat me-2"></i>Vitals
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
                                            <th>Visit Type</th>
                                            <th>Diagnosis</th>
                                            <th>Treatment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($medical_history)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">No medical records found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($medical_history as $record): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($record['record_date'])); ?></td>
                                                    <td><?php echo ucfirst($record['visit_type'] ?? 'Regular'); ?></td>
                                                    <td><?php echo $record['diagnosis']; ?></td>
                                                    <td><?php echo $record['treatment_plan']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" onclick="viewRecord(<?php echo $record['id']; ?>)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" onclick="addMedicalRecord(<?php echo $patient['id']; ?>)">
                                    <i class="fas fa-plus"></i> Add Medical Record
                                </button>
                            </div>
                        </div>

                        <!-- Appointments Tab -->
                        <div class="tab-pane" id="appointments">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Symptoms</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($appointments)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">No appointments found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($appointments as $apt): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
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
                                                    <td><?php echo substr($apt['symptoms'] ?? '', 0, 50) . '...'; ?></td>
                                                    <td>
                                                        <a href="<?php echo BASE_URL; ?>doctor/view-appointment/<?php echo $apt['id']; ?>" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
                                            <th>Frequency</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($prescriptions)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">No prescriptions found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($prescriptions as $pres): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($pres['created_at'])); ?></td>
                                                    <td><?php echo $pres['medicine_name']; ?></td>
                                                    <td><?php echo $pres['dosage']; ?></td>
                                                    <td><?php echo $pres['frequency']; ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $pres['is_active'] ? 'success' : 'secondary'; ?>">
                                                            <?php echo $pres['is_active'] ? 'Active' : 'Completed'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" onclick="printPrescription(<?php echo $pres['id']; ?>)">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" onclick="addPrescription(<?php echo $patient['id']; ?>)">
                                    <i class="fas fa-plus"></i> Add Prescription
                                </button>
                            </div>
                        </div>

                        <!-- Vitals Tab -->
                        <div class="tab-pane" id="vitals">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>BP</th>
                                            <th>Heart Rate</th>
                                            <th>Weight</th>
                                            <th>BMI</th>
                                            <th>Blood Sugar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($vitals)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">No vitals recorded</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($vitals as $vital): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($vital['record_date'])); ?></td>
                                                    <td><?php echo $vital['blood_pressure_systolic'] ? $vital['blood_pressure_systolic'] . '/' . $vital['blood_pressure_diastolic'] : '-'; ?></td>
                                                    <td><?php echo $vital['heart_rate'] ?? '-'; ?></td>
                                                    <td><?php echo $vital['weight'] ? $vital['weight'] . ' kg' : '-'; ?></td>
                                                    <td><?php echo $vital['bmi'] ?? '-'; ?></td>
                                                    <td><?php echo $vital['blood_sugar_fasting'] ?? '-'; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" onclick="addVitals(<?php echo $patient['id']; ?>)">
                                    <i class="fas fa-plus"></i> Add Vitals
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function addMedicalRecord(patientId) {
    window.location.href = '<?php echo BASE_URL; ?>doctor/add-medical-record/' + patientId;
}

function addPrescription(patientId) {
    window.location.href = '<?php echo BASE_URL; ?>doctor/add-prescription/' + patientId;
}

function addVitals(patientId) {
    window.location.href = '<?php echo BASE_URL; ?>doctor/add-vitals/' + patientId;
}

function viewRecord(recordId) {
    window.location.href = '<?php echo BASE_URL; ?>doctor/view-medical-record/' + recordId;
}

function printPrescription(prescriptionId) {
    window.open('<?php echo BASE_URL; ?>doctor/print-prescription/' + prescriptionId, '_blank');
}
</script>
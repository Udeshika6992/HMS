<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Appointment Details</h1>
        <p class="lead">View complete appointment information</p>
    </div>
</section>

<!-- Appointment Details -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Appointment #<?php echo $appointment['appointment_number']; ?></h5>
                    <span class="badge bg-<?php 
                        echo $appointment['status'] == 'completed' ? 'success' : 
                            ($appointment['status'] == 'pending' ? 'warning' : 
                            ($appointment['status'] == 'confirmed' ? 'info' : 'secondary')); 
                    ?> fs-6">
                        <?php echo ucfirst($appointment['status']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Patient Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Patient Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $appointment['profile_image'] ?? 'default-avatar.png'; ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($appointment['patient_name']); ?></h5>
                                            <p class="text-muted mb-0">ID: P00<?php echo $appointment['patient_id']; ?></p>
                                        </div>
                                    </div>
                                    
                                    <table class="table table-sm">
                                        <tr>
                                            <th><i class="fas fa-calendar-alt"></i> DOB:</th>
                                            <td><?php echo date('M d, Y', strtotime($appointment['date_of_birth'] ?? '2000-01-01')); ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-tint"></i> Blood Group:</th>
                                            <td><span class="badge bg-danger"><?php echo $appointment['blood_group'] ?? 'N/A'; ?></span></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-phone"></i> Phone:</th>
                                            <td><?php echo $appointment['patient_phone'] ?? 'N/A'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-envelope"></i> Email:</th>
                                            <td><?php echo $appointment['patient_email']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctor Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $appointment['doctor_profile'] ?? 'default-avatar.png'; ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <h5 class="mb-1">Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></h5>
                                            <p class="text-muted mb-0"><?php echo $appointment['specialization']; ?></p>
                                        </div>
                                    </div>
                                    
                                    <table class="table table-sm">
                                        <tr>
                                            <th><i class="fas fa-building"></i> Department:</th>
                                            <td><?php echo $appointment['department_name'] ?? 'N/A'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-phone"></i> Contact:</th>
                                            <td><?php echo $appointment['doctor_phone'] ?? 'N/A'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-envelope"></i> Email:</th>
                                            <td><?php echo $appointment['doctor_email']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details -->
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Appointment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Date:</strong><br><?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Time:</strong><br><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Booked On:</strong><br><?php echo date('M d, Y', strtotime($appointment['created_at'])); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Status:</strong><br>
                                                <span class="badge bg-<?php 
                                                    echo $appointment['status'] == 'completed' ? 'success' : 
                                                        ($appointment['status'] == 'pending' ? 'warning' : 
                                                        ($appointment['status'] == 'confirmed' ? 'info' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <h6>Symptoms:</h6>
                                    <p><?php echo nl2br(htmlspecialchars($appointment['symptoms'] ?? 'No symptoms provided')); ?></p>
                                    
                                    <?php if ($appointment['diagnosis']): ?>
                                        <h6 class="mt-3">Diagnosis:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($appointment['diagnosis'])); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($appointment['notes']): ?>
                                        <h6 class="mt-3">Doctor's Notes:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($appointment['notes'])); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($appointment['cancellation_reason']): ?>
                                        <div class="alert alert-danger mt-3">
                                            <h6>Cancellation Reason:</h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($appointment['cancellation_reason'])); ?></p>
                                            <small>Cancelled on: <?php echo date('M d, Y h:i A', strtotime($appointment['updated_at'])); ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?php echo BASE_URL; ?>admin/appointments" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Appointments
                        </a>
                        <?php if ($appointment['status'] != 'cancelled' && $appointment['status'] != 'completed'): ?>
                            <button class="btn btn-danger" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)">
                                <i class="fas fa-times"></i> Cancel Appointment
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>admin/cancel-appointment/<?php echo $appointment['id']; ?>">
                <div class="modal-body">
                    <p>Are you sure you want to cancel this appointment?</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for cancellation</label>
                        <textarea class="form-control" id="cancellation_reason" 
                                  name="cancellation_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelAppointment(id) {
    $('#cancelModal').modal('show');
}
</script>
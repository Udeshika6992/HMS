<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Appointment Details</h1>
        <p class="lead">View your appointment information</p>
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
                        <!-- Doctor Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctor Information</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $appointment['doctor_profile'] ?? 'default-avatar.png'; ?>" 
                                         class="rounded-circle mb-3" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <h5>Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></h5>
                                    <p class="text-muted"><?php echo $appointment['specialization']; ?></p>
                                    
                                    <hr>
                                    
                                    <div class="text-start">
                                        <p><i class="fas fa-building text-primary me-2"></i> Department: <?php echo $appointment['department_name'] ?? 'N/A'; ?></p>
                                        <p><i class="fas fa-phone text-primary me-2"></i> Contact: <?php echo $appointment['doctor_phone'] ?? 'N/A'; ?></p>
                                        <p><i class="fas fa-envelope text-primary me-2"></i> Email: <?php echo $appointment['doctor_email']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Appointment Details</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th><i class="fas fa-calendar"></i> Date:</th>
                                            <td><?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-clock"></i> Time:</th>
                                            <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-tag"></i> Status:</th>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $appointment['status'] == 'completed' ? 'success' : 
                                                        ($appointment['status'] == 'pending' ? 'warning' : 
                                                        ($appointment['status'] == 'confirmed' ? 'info' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($appointment['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-calendar-plus"></i> Booked On:</th>
                                            <td><?php echo date('M d, Y', strtotime($appointment['created_at'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Symptoms -->
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Symptoms / Reason for Visit</h6>
                                </div>
                                <div class="card-body">
                                    <p><?php echo nl2br(htmlspecialchars($appointment['symptoms'] ?? 'No symptoms provided')); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor's Notes (if available) -->
                        <?php if (!empty($appointment['notes']) || !empty($appointment['diagnosis'])): ?>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Doctor's Notes</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($appointment['diagnosis'])): ?>
                                        <h6>Diagnosis:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($appointment['diagnosis'])); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($appointment['notes'])): ?>
                                        <h6 class="mt-3">Notes:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($appointment['notes'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Cancellation Information (if cancelled) -->
                        <?php if ($appointment['status'] == 'cancelled' && !empty($appointment['cancellation_reason'])): ?>
                        <div class="col-12 mb-4">
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Appointment Cancelled</h6>
                                <p><strong>Reason:</strong> <?php echo nl2br(htmlspecialchars($appointment['cancellation_reason'])); ?></p>
                                <small>Cancelled on: <?php echo date('M d, Y h:i A', strtotime($appointment['updated_at'])); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-end mt-4">
                        <a href="<?php echo BASE_URL; ?>patient/my-appointments" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Appointments
                        </a>
                        
                        <?php if ($appointment['status'] == 'pending' || $appointment['status'] == 'confirmed'): ?>
                            <?php if (strtotime($appointment['appointment_date']) > time()): ?>
                                <button class="btn btn-danger" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)">
                                    <i class="fas fa-times"></i> Cancel Appointment
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if ($appointment['status'] == 'completed'): ?>
                            <button class="btn btn-warning" onclick="addFeedback(<?php echo $appointment['id']; ?>)">
                                <i class="fas fa-star"></i> Give Feedback
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
            <form method="POST" action="<?php echo BASE_URL; ?>patient/cancel-appointment/<?php echo $appointment['id']; ?>">
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

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Give Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>patient/add-feedback/<?php echo $appointment['id']; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-stars mb-2">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="rating" value="5">
                    </div>
                    <div class="mb-3">
                        <label for="feedback_text" class="form-label">Your Feedback</label>
                        <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" required></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_anonymous" name="is_anonymous" value="1">
                        <label class="form-check-label" for="is_anonymous">Submit anonymously</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelAppointment(id) {
    $('#cancelModal').modal('show');
}

function addFeedback(id) {
    $('#feedbackModal').modal('show');
}

// Star rating functionality
document.querySelectorAll('.rating-stars i').forEach(star => {
    star.addEventListener('mouseenter', function() {
        const rating = this.dataset.rating;
        highlightStars(rating);
    });
    
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('rating').value = rating;
        highlightStars(rating, true);
    });
});

function highlightStars(rating, permanent = false) {
    document.querySelectorAll('.rating-stars i').forEach((star, index) => {
        if (index < rating) {
            star.className = 'fas fa-star text-warning';
        } else {
            star.className = 'far fa-star';
        }
    });
}

document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
    const currentRating = document.getElementById('rating').value;
    highlightStars(currentRating, true);
});
</script>

<style>
.rating-stars i {
    font-size: 24px;
    cursor: pointer;
    margin: 0 2px;
    transition: color 0.2s;
}

.rating-stars i:hover {
    transform: scale(1.1);
}
</style>
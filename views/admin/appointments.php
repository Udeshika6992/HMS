<?php
/**
 * Doctor Schedule View
 * Displays doctor's working schedule and availability
 */
$baseUrl = BASE_URL;
// Suppress warnings if variables aren't set in IDE
/* @var string $BASE_URL */
/* @var array $doctor */
/* @var array $schedule */
?>



<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Appointment Management</h1>
        <p class="lead">View and manage all appointments</p>
    </div>
</section>

<!-- Filters -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="<?php echo $selected_date; ?>">
                </div>
                <div class="col-md-3">
                    <label for="doctor_id" class="form-label">Doctor</label>
                    <select class="form-select" id="doctor_id" name="doctor_id">
                        <option value="">All Doctors</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor['id']; ?>" <?php echo $selected_doctor == $doctor['id'] ? 'selected' : ''; ?>>
                                Dr. <?php echo htmlspecialchars($doctor['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $selected_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $selected_status == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo $selected_status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $selected_status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Appointments Table -->
<section class="container mb-5">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No appointments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                                <tr>
                                    <td><?php echo $apt['appointment_number']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($apt['patient_name']); ?></strong>
                                    </td>
                                    <td>Dr. <?php echo htmlspecialchars($apt['doctor_name']); ?></td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?>
                                        <br>
                                        <small><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $apt['status'] == 'completed' ? 'success' : 
                                                ($apt['status'] == 'pending' ? 'warning' : 
                                                ($apt['status'] == 'confirmed' ? 'info' : 
                                                ($apt['status'] == 'cancelled' ? 'secondary' : 'danger'))); 
                                        ?>">
                                            <?php echo ucfirst($apt['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>admin/view-appointment/<?php echo $apt['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($apt['status'] != 'cancelled' && $apt['status'] != 'completed'): ?>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="cancelAppointment(<?php echo $apt['id']; ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            <form method="POST" action="" id="cancelForm">
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
    $('#cancelForm').attr('action', '<?php echo BASE_URL; ?>admin/cancel-appointment/' + id);
    $('#cancelModal').modal('show');
}
</script>
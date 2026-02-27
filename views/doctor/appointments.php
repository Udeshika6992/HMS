<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">My Appointments</h1>
        <p class="lead">Manage your patient appointments</p>
    </div>
</section>

<!-- Date Filter -->
<section class="container mb-4">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Select Date</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Appointments List -->
<section class="container mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Appointments for <?php echo date('F j, Y'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Contact</th>
                                    <th>Symptoms</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>09:00 AM</td>
                                    <td>John Doe</td>
                                    <td>077-1234567</td>
                                    <td>Chest pain</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" onclick="updateStatus(1, 'confirmed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>10:00 AM</td>
                                    <td>Jane Smith</td>
                                    <td>077-7654321</td>
                                    <td>Follow-up</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-info" onclick="startConsultation(2)">
                                            <i class="fas fa-stethoscope"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>11:00 AM</td>
                                    <td>Mike Johnson</td>
                                    <td>077-9876543</td>
                                    <td>High BP</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" onclick="updateStatus(3, 'confirmed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Appointment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="confirmed">Confirm</option>
                            <option value="completed">Complete</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(id, status) {
    $('#statusModal').modal('show');
    $('#status').val(status);
    $('form').attr('action', '<?php echo BASE_URL; ?>doctor/update-appointment-status/' + id);
}

function startConsultation(id) {
    window.location.href = '<?php echo BASE_URL; ?>doctor/consultation/' + id;
}
</script>
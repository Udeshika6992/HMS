<?php
/**
 * Doctor Appointments View
 * Displays all appointments for a specific doctor
 */
$baseUrl = BASE_URL;
/* @var string $BASE_URL */
/* @var array $doctor */
/* @var array $appointments */
?>

<!-- Page Header with Gradient -->
<div class="page-header bg-gradient-primary pb-6 pt-5 pt-md-6">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 text-white">Doctor Appointments</h1>
                <p class="text-white lead">View all appointments for Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? ''); ?></p>
            </div>
            <div class="col-lg-4 text-lg-right">
                <a href="<?php echo BASE_URL; ?>admin/doctors" class="btn btn-light btn-icon">
                    <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i></span>
                    <span class="btn-inner--text">Back to Doctors</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <!-- Doctor Summary Card -->
    <div class="row">
        <div class="col-xl-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $doctor['profile_image'] ?? 'default-avatar.png'; ?>" 
                         class="rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #5e72e4;">
                    <h4 class="mb-1">Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? 'Unknown'); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($doctor['specialization'] ?? 'General Physician'); ?></p>
                    
                    <hr>
                    
                    <div class="text-left small">
                        <p><i class="fas fa-envelope text-primary mr-2"></i> <?php echo htmlspecialchars($doctor['email'] ?? 'N/A'); ?></p>
                        <p><i class="fas fa-phone text-success mr-2"></i> <?php echo htmlspecialchars($doctor['phone'] ?? 'N/A'); ?></p>
                        <p><i class="fas fa-calendar text-info mr-2"></i> Total Appointments: <?php echo count($appointments); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo count($appointments); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-primary text-white rounded-circle">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-stats shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Pending</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?php 
                                        $pending = array_filter($appointments, function($a) { 
                                            return ($a['status'] ?? '') == 'pending'; 
                                        });
                                        echo count($pending);
                                        ?>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-warning text-white rounded-circle">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-stats shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Confirmed</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?php 
                                        $confirmed = array_filter($appointments, function($a) { 
                                            return ($a['status'] ?? '') == 'confirmed'; 
                                        });
                                        echo count($confirmed);
                                        ?>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-stats shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Completed</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        <?php 
                                        $completed = array_filter($appointments, function($a) { 
                                            return ($a['status'] ?? '') == 'completed'; 
                                        });
                                        echo count($completed);
                                        ?>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-success text-white rounded-circle">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="card shadow mt-4">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Appointment History</h3>
                        </div>
                        <div class="col text-right">
                            <button class="btn btn-sm btn-primary" onclick="exportTable()">
                                <i class="fas fa-download mr-2"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="appointmentsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Appointment #</th>
                                <th>Date & Time</th>
                                <th>Patient</th>
                                <th>Contact</th>
                                <th>Symptoms</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>No Appointments Found</h5>
                                        <p class="text-muted">This doctor has no appointments yet.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $apt): ?>
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold"><?php echo htmlspecialchars($apt['appointment_number'] ?? 'APT-' . str_pad($apt['id'], 5, '0', STR_PAD_LEFT)); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="font-weight-bold"><?php echo date('M d, Y', strtotime($apt['appointment_date'])); ?></span>
                                                    <br>
                                                    <small class="text-muted"><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo UPLOAD_URL; ?>profiles/default-avatar.png" 
                                                     class="rounded-circle mr-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;">
                                                <div>
                                                    <span class="font-weight-bold"><?php echo htmlspecialchars($apt['patient_name'] ?? 'Unknown'); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="fas fa-phone text-muted mr-1"></i>
                                                <?php echo htmlspecialchars($apt['patient_phone'] ?? 'N/A'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate" style="max-width: 150px; display: inline-block;">
                                                <?php echo htmlspecialchars(substr($apt['symptoms'] ?? '', 0, 30)) . '...'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $apt['status'] ?? 'pending';
                                            $badgeClass = '';
                                            $icon = '';
                                            
                                            switch($status) {
                                                case 'completed':
                                                    $badgeClass = 'bg-success';
                                                    $icon = 'fa-check-circle';
                                                    break;
                                                case 'confirmed':
                                                    $badgeClass = 'bg-info';
                                                    $icon = 'fa-check';
                                                    break;
                                                case 'pending':
                                                    $badgeClass = 'bg-warning';
                                                    $icon = 'fa-clock';
                                                    break;
                                                case 'cancelled':
                                                    $badgeClass = 'bg-secondary';
                                                    $icon = 'fa-times-circle';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-primary';
                                                    $icon = 'fa-calendar';
                                            }
                                            ?>
                                            <span class="badge badge-pill <?php echo $badgeClass; ?> text-white p-2">
                                                <i class="fas <?php echo $icon; ?> mr-1"></i>
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>admin/view-appointment/<?php echo $apt['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($status != 'completed' && $status != 'cancelled'): ?>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            onclick="updateStatus(<?php echo $apt['id']; ?>, 'completed')"
                                                            title="Mark Completed">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="updateStatus(<?php echo $apt['id']; ?>, 'cancelled')"
                                                            title="Cancel Appointment">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if (count($appointments) > 10): ?>
                    <div class="card-footer py-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">Update Appointment Status</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="statusForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label class="form-control-label">New Status</label>
                        <select class="form-control" name="status" id="statusSelect" required>
                            <option value="confirmed">Confirm Appointment</option>
                            <option value="completed">Mark as Completed</option>
                            <option value="cancelled">Cancel Appointment</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Notes / Reason</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Add any notes or cancellation reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.page-header {
    padding: 3rem 0 6rem;
    margin-bottom: -3rem;
}
.mt--6 {
    margin-top: -3rem !important;
}
.bg-gradient-primary { background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%); }
.icon-shape {
    width: 3rem;
    height: 3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.card-stats .icon-shape {
    width: 3.5rem;
    height: 3.5rem;
}
.table td, .table th {
    vertical-align: middle;
}
.badge-pill {
    padding: 0.5rem 1rem;
}
</style>

<script>
function updateStatus(id, defaultStatus) {
    $('#statusSelect').val(defaultStatus);
    $('#statusForm').attr('action', '<?php echo BASE_URL; ?>admin/appointments/update-status/' + id);
    $('#statusModal').modal('show');
}

function exportTable() {
    // Simple CSV export
    let csv = [];
    let rows = document.querySelectorAll('#appointmentsTable tr');
    
    rows.forEach(row => {
        let rowData = [];
        let cols = row.querySelectorAll('td, th');
        cols.forEach(col => {
            rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
        });
        csv.push(rowData.join(','));
    });
    
    let csvContent = csv.join('\n');
    let blob = new Blob([csvContent], { type: 'text/csv' });
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'doctor_appointments_<?php echo date('Y-m-d'); ?>.csv';
    a.click();
}
</script>

<!-- Include jQuery and Bootstrap JS if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
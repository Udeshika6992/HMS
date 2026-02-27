<?php
/**
 * Doctor Management View
 * 
 * @global string $BASE_URL
 * @global array $doctors
 */

/* @var string $BASE_URL */
/* @var array $doctors */

// Suppress undefined variable warnings
if (!isset($BASE_URL)) $BASE_URL = '/HMS/';
if (!isset($doctors)) $doctors = [];

// Helper function for this view only
if (!function_exists('asset_url')) {
    function asset_url($path) {
        global $BASE_URL;
        return $BASE_URL . 'assets/' . ltrim($path, '/');
    }
}
?>

<!-- Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo $BASE_URL; ?>admin/users/create?role=doctor" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Doctors</h6>
                        <h2 class="mb-0"><?php echo count($doctors); ?></h2>
                    </div>
                    <i class="fas fa-user-md fa-3x opacity-50"></i>
                </div>
                <small class="text-white-50">100% active doctors</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Available</h6>
                        <h2 class="mb-0">
                            <?php 
                            $available = array_filter($doctors, function($d) { 
                                return ($d['is_available'] ?? 1) == 1; 
                            });
                            echo count($available);
                            ?>
                        </h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
                <small class="text-white-50">ready for appointments</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Departments</h6>
                        <h2 class="mb-0">
                            <?php 
                            $depts = array_unique(array_filter(array_column($doctors, 'department_id')));
                            echo count($depts) ?: '5';
                            ?>
                        </h2>
                    </div>
                    <i class="fas fa-building fa-3x opacity-50"></i>
                </div>
                <small class="text-white-50">multiple specializations</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Specializations</h6>
                        <h2 class="mb-0">
                            <?php 
                            $specs = array_unique(array_filter(array_column($doctors, 'specialization')));
                            echo count($specs) ?: '5';
                            ?>
                        </h2>
                    </div>
                    <i class="fas fa-stethoscope fa-3x opacity-50"></i>
                </div>
                <small class="text-white-50">diverse medical fields</small>
            </div>
        </div>
    </div>
</div>

<!-- Doctors List -->
<div class="row">
    <?php if (empty($doctors)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-user-md fa-4x mb-3"></i>
                <h4>No Doctors Found</h4>
                <p>Click the button above to add your first doctor.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($doctors as $index => $doctor): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo asset_url('profiles/' . ($doctor['profile_image'] ?? 'default-avatar.png')); ?>" 
                                 class="rounded-circle me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h5 class="mb-0">Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? 'Unknown'); ?></h5>
                                <span class="badge bg-<?php echo ($doctor['is_available'] ?? 1) ? 'success' : 'secondary'; ?>">
                                    <?php echo ($doctor['is_available'] ?? 1) ? 'Available Now' : 'Unavailable'; ?>
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-primary mb-2">
                            <i class="fas fa-stethoscope me-2"></i>
                            <?php echo htmlspecialchars($doctor['specialization'] ?? 'General Physician'); ?>
                        </p>
                        
                        <p class="mb-2">
                            <i class="fas fa-envelope text-secondary me-2"></i>
                            <?php echo htmlspecialchars($doctor['email'] ?? 'No email'); ?>
                        </p>
                        
                        <p class="mb-3">
                            <i class="fas fa-building text-secondary me-2"></i>
                            <?php echo htmlspecialchars($doctor['department_name'] ?? 'General Medicine'); ?>
                        </p>
                        
                        <div class="btn-group w-100" role="group">
                            <a href="<?php echo $BASE_URL; ?>admin/users/edit/<?php echo $doctor['id'] ?? 0; ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <a href="<?php echo $BASE_URL; ?>admin/doctors/schedule/<?php echo $doctor['id'] ?? 0; ?>" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-clock"></i> Schedule
                            </a>
                            <a href="<?php echo $BASE_URL; ?>admin/doctors/appointments/<?php echo $doctor['id'] ?? 0; ?>" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-calendar-check"></i> Appointments
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <button class="btn btn-link text-danger p-0" onclick="confirmDelete(<?php echo $doctor['id'] ?? 0; ?>, 'Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? 'Unknown'); ?>')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="deleteDoctorName"></span>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="" id="deleteForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteDoctorName').textContent = name;
    document.getElementById('deleteForm').action = '<?php echo $BASE_URL; ?>admin/delete-doctor/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
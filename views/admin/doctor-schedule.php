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

<!-- Page Header with Gradient -->
<div class="page-header bg-gradient-primary pb-6 pt-5 pt-md-6">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 text-white">Doctor Schedule</h1>
                <p class="text-white lead">Manage working hours and availability for Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? ''); ?></p>
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
    <!-- Doctor Information Card -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="avatar-xl mx-auto mb-4">
                        <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $doctor['profile_image'] ?? 'default-avatar.png'; ?>" 
                             class="rounded-circle shadow-lg" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #5e72e4;">
                    </div>
                    <h3 class="mb-1">Dr. <?php echo htmlspecialchars($doctor['full_name'] ?? 'Unknown'); ?></h3>
                    <p class="text-muted mb-3"><?php echo htmlspecialchars($schedule['specialization'] ?? 'General Physician'); ?></p>
                    
                    <div class="text-left mt-4">
                        <div class="mb-3 d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-primary text-white rounded-circle mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted">Email</small>
                                <span class="font-weight-bold"><?php echo htmlspecialchars($doctor['email'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-success text-white rounded-circle mr-3">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted">Phone</small>
                                <span class="font-weight-bold"><?php echo htmlspecialchars($doctor['phone'] ?? '+94 77 123 4567'); ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-info text-white rounded-circle mr-3">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted">License Number</small>
                                <span class="font-weight-bold"><?php echo htmlspecialchars($schedule['license_number'] ?? 'LIC001'); ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-warning text-white rounded-circle mr-3">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted">Experience</small>
                                <span class="font-weight-bold"><?php echo $schedule['experience_years'] ?? 10; ?>+ years</span>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <div class="icon icon-shape icon-sm bg-gradient-danger text-white rounded-circle mr-3">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted">Qualification</small>
                                <span class="font-weight-bold"><?php echo htmlspecialchars($schedule['qualification'] ?? 'MBBS, MD'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <!-- Current Schedule Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Current Working Schedule</h3>
                            <small class="text-muted">Weekly availability and consultation hours</small>
                        </div>
                        <div class="col text-right">
                            <span class="badge badge-pill badge-<?php echo ($schedule['is_available'] ?? 1) ? 'success' : 'secondary'; ?> p-3">
                                <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                <?php echo ($schedule['is_available'] ?? 1) ? 'Currently Available' : 'Currently Unavailable'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if ($schedule): ?>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <tbody>
                                    <tr>
                                        <th class="border-0" style="width: 200px;">Available Days</th>
                                        <td class="border-0">
                                            <?php 
                                            $days = explode(',', $schedule['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri');
                                            foreach ($days as $day): 
                                                $isToday = (date('D') == $day);
                                            ?>
                                                <span class="badge badge-pill badge-<?php echo $isToday ? 'success' : 'info'; ?> p-2 mr-2">
                                                    <?php 
                                                    switch(trim($day)) {
                                                        case 'Mon': echo 'Monday'; break;
                                                        case 'Tue': echo 'Tuesday'; break;
                                                        case 'Wed': echo 'Wednesday'; break;
                                                        case 'Thu': echo 'Thursday'; break;
                                                        case 'Fri': echo 'Friday'; break;
                                                        case 'Sat': echo 'Saturday'; break;
                                                        case 'Sun': echo 'Sunday'; break;
                                                        default: echo $day;
                                                    }
                                                    ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Working Hours</th>
                                        <td>
                                            <i class="fas fa-clock text-primary mr-2"></i>
                                            <?php echo date('h:i A', strtotime($schedule['available_time_start'] ?? '09:00:00')); ?> 
                                            <i class="fas fa-arrow-right text-muted mx-2"></i> 
                                            <?php echo date('h:i A', strtotime($schedule['available_time_end'] ?? '17:00:00')); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Max Patients Per Day</th>
                                        <td>
                                            <span class="badge badge-pill badge-primary p-2">
                                                <i class="fas fa-users mr-1"></i>
                                                <?php echo $schedule['max_patients_per_day'] ?? 20; ?> patients
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Consultation Fee</th>
                                        <td>
                                            <h4 class="mb-0 text-success">LKR <?php echo number_format($schedule['consultation_fee'] ?? 0, 2); ?></h4>
                                            <small class="text-muted">Free for government hospitals</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td>
                                            <i class="fas fa-building text-info mr-2"></i>
                                            <?php echo htmlspecialchars($schedule['department_name'] ?? 'General Medicine'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bio / Description</th>
                                        <td>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($schedule['bio'] ?? 'Experienced physician dedicated to providing quality healthcare.')); ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Weekly Schedule Grid -->
                        <hr class="my-4">
                        <h4 class="mb-3">Weekly Schedule Overview</h4>
                        <div class="row">
                            <?php 
                            $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $availableDays = explode(',', $schedule['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri');
                            ?>
                            <?php foreach ($weekDays as $day): ?>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card <?php echo in_array($day, $availableDays) ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?> text-white">
                                        <div class="card-body text-center py-3">
                                            <h6 class="mb-0 text-uppercase font-weight-bold">
                                                <?php 
                                                switch($day) {
                                                    case 'Mon': echo 'MON'; break;
                                                    case 'Tue': echo 'TUE'; break;
                                                    case 'Wed': echo 'WED'; break;
                                                    case 'Thu': echo 'THU'; break;
                                                    case 'Fri': echo 'FRI'; break;
                                                    case 'Sat': echo 'SAT'; break;
                                                    case 'Sun': echo 'SUN'; break;
                                                }
                                                ?>
                                            </h6>
                                            <?php if (in_array($day, $availableDays)): ?>
                                                <small>Available</small>
                                                <div class="mt-2">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            <?php else: ?>
                                                <small>Not Available</small>
                                                <div class="mt-2">
                                                    <i class="fas fa-times-circle"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h4>No Schedule Information</h4>
                            <p class="text-muted">This doctor's schedule has not been set up yet.</p>
                            <button class="btn btn-primary mt-3" onclick="editSchedule()">
                                <i class="fas fa-edit"></i> Set Up Schedule
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-block btn-primary" onclick="editSchedule()">
                                <i class="fas fa-edit mr-2"></i> Edit Schedule
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-block btn-success" onclick="toggleAvailability()">
                                <i class="fas fa-toggle-on mr-2"></i> 
                                <?php echo ($schedule['is_available'] ?? 1) ? 'Mark Unavailable' : 'Mark Available'; ?>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo BASE_URL; ?>admin/doctors/appointments/<?php echo $doctor['id']; ?>" class="btn btn-block btn-info">
                                <i class="fas fa-calendar-check mr-2"></i> View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">Edit Doctor Schedule</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>admin/doctors/update-schedule/<?php echo $doctor['id']; ?>">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label class="form-control-label">Available Days</label>
                        <div class="row">
                            <?php 
                            $daysList = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $selectedDays = explode(',', $schedule['available_days'] ?? 'Mon,Tue,Wed,Thu,Fri');
                            ?>
                            <?php foreach ($daysList as $day): ?>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" type="checkbox" 
                                               name="available_days[]" value="<?php echo $day; ?>" 
                                               id="day_<?php echo $day; ?>"
                                               <?php echo in_array($day, $selectedDays) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="day_<?php echo $day; ?>">
                                            <?php 
                                            switch($day) {
                                                case 'Mon': echo 'Monday'; break;
                                                case 'Tue': echo 'Tuesday'; break;
                                                case 'Wed': echo 'Wednesday'; break;
                                                case 'Thu': echo 'Thursday'; break;
                                                case 'Fri': echo 'Friday'; break;
                                                case 'Sat': echo 'Saturday'; break;
                                                case 'Sun': echo 'Sunday'; break;
                                            }
                                            ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Start Time</label>
                                <input type="time" class="form-control" name="available_time_start" 
                                       value="<?php echo substr($schedule['available_time_start'] ?? '09:00:00', 0, 5); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">End Time</label>
                                <input type="time" class="form-control" name="available_time_end" 
                                       value="<?php echo substr($schedule['available_time_end'] ?? '17:00:00', 0, 5); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Max Patients Per Day</label>
                                <input type="number" class="form-control" name="max_patients_per_day" 
                                       value="<?php echo $schedule['max_patients_per_day'] ?? 20; ?>" min="1" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Consultation Fee (LKR)</label>
                                <input type="number" class="form-control" name="consultation_fee" 
                                       value="<?php echo $schedule['consultation_fee'] ?? 0; ?>" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Bio / Description</label>
                        <textarea class="form-control" name="bio" rows="3"><?php echo htmlspecialchars($schedule['bio'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
.bg-gradient-success { background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%); }
.bg-gradient-secondary { background: linear-gradient(87deg, #f7fafc 0, #f7f8fc 100%); }
.icon-shape {
    width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.avatar-xl {
    width: 120px;
    height: 120px;
}
</style>

<script>
function editSchedule() {
    $('#editScheduleModal').modal('show');
}

function toggleAvailability() {
    if (confirm('Toggle doctor availability status?')) {
        // AJAX call to toggle availability
        $.post('<?php echo BASE_URL; ?>admin/doctors/toggle-availability/<?php echo $doctor['id']; ?>', {
            csrf_token: '<?php echo $_SESSION['csrf_token'] ?? ''; ?>'
        }, function(response) {
            location.reload();
        });
    }
}
</script>
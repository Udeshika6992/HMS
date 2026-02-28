<?php
/**
 * System Settings View
 */
/* @var array $settings */
?>

<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">System Settings</h1>
        <p class="lead">Configure hospital management system settings</p>
    </div>
</section>

<!-- Settings Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <!-- Show error message if any -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Show success message if any -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php 
                            echo $_SESSION['success']; 
                            unset($_SESSION['success']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>admin/settings">
                        <!-- CRITICAL: Add CSRF token field -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <div class="mb-3">
                            <label for="hospital_name" class="form-label">Hospital Name</label>
                            <input type="text" class="form-control" id="hospital_name" name="hospital_name" 
                                   value="<?php echo htmlspecialchars($settings['hospital_name'] ?? 'Deltota Divisional Hospital'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($settings['address'] ?? 'Main Street, Deltota'); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($settings['phone'] ?? '081-1234567'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($settings['email'] ?? 'info@deltotahospital.lk'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="working_hours" class="form-label">Working Hours</label>
                            <input type="text" class="form-control" id="working_hours" name="working_hours" 
                                   value="<?php echo htmlspecialchars($settings['working_hours'] ?? 'Mon-Fri: 8:00 AM - 8:00 PM, Sat: 8:00 AM - 2:00 PM'); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_duration" class="form-label">Appointment Duration (minutes)</label>
                                <input type="number" class="form-control" id="appointment_duration" name="appointment_duration" 
                                       value="<?php echo htmlspecialchars($settings['appointment_duration'] ?? '30'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_appointments_per_day" class="form-label">Max Appointments Per Day</label>
                                <input type="number" class="form-control" id="max_appointments_per_day" name="max_appointments_per_day" 
                                       value="<?php echo htmlspecialchars($settings['max_appointments_per_day'] ?? '20'); ?>">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">System Configuration</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="Asia/Colombo" <?php echo ($settings['timezone'] ?? '') == 'Asia/Colombo' ? 'selected' : ''; ?>>Asia/Colombo</option>
                                    <option value="Asia/Kolkata" <?php echo ($settings['timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : ''; ?>>Asia/Kolkata</option>
                                    <option value="UTC" <?php echo ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select" id="date_format" name="date_format">
                                    <option value="Y-m-d" <?php echo ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                    <option value="d/m/Y" <?php echo ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                    <option value="m/d/Y" <?php echo ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
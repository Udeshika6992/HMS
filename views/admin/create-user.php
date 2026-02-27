<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Create New User</h1>
        <p class="lead">Add a new user to the system</p>
    </div>
</section>

<!-- Create User Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['form_errors'])) {
                        echo '<div class="alert alert-danger">';
                        foreach ($_SESSION['form_errors'] as $error) {
                            echo '<p class="mb-0">' . implode(', ', (array)$error) . '</p>';
                        }
                        echo '</div>';
                        unset($_SESSION['form_errors']);
                    }
                    
                    $formData = $_SESSION['form_data'] ?? [];
                    unset($_SESSION['form_data']);
                    ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>admin/users/create">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" <?php echo ($formData['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="doctor" <?php echo ($formData['role'] ?? '') == 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                    <option value="patient" <?php echo ($formData['role'] ?? '') == 'patient' ? 'selected' : ''; ?>>Patient</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($formData['address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Doctor-specific fields -->
                        <div id="doctorFields" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Doctor Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Qualification</label>
                                    <input type="text" class="form-control" id="qualification" name="qualification">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="experience_years" class="form-label">Experience (Years)</label>
                                    <input type="number" class="form-control" id="experience_years" name="experience_years">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>"><?php echo $dept['department_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Patient-specific fields -->
                        <div id="patientFields" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Patient Information</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="form-select" id="blood_group" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('role').addEventListener('change', function() {
    var role = this.value;
    document.getElementById('doctorFields').style.display = role === 'doctor' ? 'block' : 'none';
    document.getElementById('patientFields').style.display = role === 'patient' ? 'block' : 'none';
});
</script>
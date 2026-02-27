<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">Edit User</h1>
        <p class="lead">Edit user information</p>
    </div>
</section>

<!-- Edit User Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit User: <?php echo htmlspecialchars($user['username']); ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $user['id']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <?php if ($user['role'] === 'doctor' && $details): ?>
                            <hr>
                            <h5 class="mb-3">Doctor Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization" 
                                           value="<?php echo htmlspecialchars($details['specialization'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Qualification</label>
                                    <input type="text" class="form-control" id="qualification" name="qualification" 
                                           value="<?php echo htmlspecialchars($details['qualification'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="experience_years" class="form-label">Experience (Years)</label>
                                    <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                           value="<?php echo htmlspecialchars($details['experience_years'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number" 
                                           value="<?php echo htmlspecialchars($details['license_number'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo ($details['department_id'] ?? '') == $dept['id'] ? 'selected' : ''; ?>>
                                                <?php echo $dept['department_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($user['role'] === 'patient' && $details): ?>
                            <hr>
                            <h5 class="mb-3">Patient Information</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="<?php echo htmlspecialchars($details['date_of_birth'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($details['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($details['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($details['gender'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="form-select" id="blood_group" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" <?php echo ($details['blood_group'] ?? '') == 'A+' ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo ($details['blood_group'] ?? '') == 'A-' ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo ($details['blood_group'] ?? '') == 'B+' ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo ($details['blood_group'] ?? '') == 'B-' ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo ($details['blood_group'] ?? '') == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo ($details['blood_group'] ?? '') == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo ($details['blood_group'] ?? '') == 'O+' ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo ($details['blood_group'] ?? '') == 'O-' ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="<?php echo htmlspecialchars($details['emergency_contact_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                                    <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="<?php echo htmlspecialchars($details['emergency_contact_phone'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control" id="allergies" name="allergies" rows="2"><?php echo htmlspecialchars($details['allergies'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="chronic_conditions" class="form-label">Chronic Conditions</label>
                                <textarea class="form-control" id="chronic_conditions" name="chronic_conditions" rows="2"><?php echo htmlspecialchars($details['chronic_conditions'] ?? ''); ?></textarea>
                            </div>
                        <?php endif; ?>

                        <div class="text-end mt-4">
                            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
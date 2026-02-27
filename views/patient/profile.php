<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">My Profile</h1>
        <p class="lead">Manage your personal information</p>
    </div>
</section>

<!-- Profile Content -->
<section class="container mb-5">
    <div class="row">
        <!-- Profile Picture Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-image-container mb-3">
                        <img src="<?= UPLOAD_URL; ?>profiles/<?= $patient['profile_image'] ?? 'default-avatar.png'; ?>" 
                             class="rounded-circle img-fluid" 
                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #3498db;"
                             id="profilePreview">
                        <label for="profile_image" class="profile-image-upload">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <h4><?= htmlspecialchars($patient['full_name'] ?? 'User'); ?></h4>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i><?= $patient['email'] ?? 'No email'; ?><br>
                        <i class="fas fa-phone me-2"></i><?= $patient['phone'] ?? 'Not provided'; ?>
                    </p>
                    <span class="badge bg-primary">Patient</span>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#personal" data-bs-toggle="tab">
                                <i class="fas fa-user me-2"></i>Personal Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#medical" data-bs-toggle="tab">
                                <i class="fas fa-notes-medical me-2"></i>Medical Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#emergency" data-bs-toggle="tab">
                                <i class="fas fa-ambulance me-2"></i>Emergency Contact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#security" data-bs-toggle="tab">
                                <i class="fas fa-lock me-2"></i>Security
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane active" id="personal">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            
                            <form method="POST" action="<?= BASE_URL; ?>patient/update-profile" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?= htmlspecialchars($patient['full_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" 
                                               value="<?= $patient['email'] ?? ''; ?>" readonly disabled>
                                        <small class="text-muted">Email cannot be changed</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?= htmlspecialchars($patient['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" 
                                               accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-muted">Max size: 2MB. Formats: JPG, PNG</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($patient['address'] ?? ''); ?></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Personal Info
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Medical Information Tab -->
                        <div class="tab-pane" id="medical">
                            <form method="POST" action="<?= BASE_URL; ?>patient/update-medical-info">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                               value="<?= $patient['date_of_birth'] ?? ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">-- Select Gender --</option>
                                            <option value="Male" <?= ($patient['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?= ($patient['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                            <option value="Other" <?= ($patient['gender'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="blood_group" class="form-label">Blood Group</label>
                                        <select class="form-select" id="blood_group" name="blood_group">
                                            <option value="">-- Select Blood Group --</option>
                                            <?php 
                                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                            foreach ($bloodGroups as $bg):
                                            ?>
                                                <option value="<?= $bg; ?>" <?= ($patient['blood_group'] ?? '') == $bg ? 'selected' : ''; ?>>
                                                    <?= $bg; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="allergies" class="form-label">Allergies</label>
                                    <textarea class="form-control" id="allergies" name="allergies" rows="2" 
                                              placeholder="List any allergies (e.g., Penicillin, Dust, Seafood)"><?= htmlspecialchars($patient['allergies'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="chronic_conditions" class="form-label">Chronic Conditions</label>
                                    <textarea class="form-control" id="chronic_conditions" name="chronic_conditions" rows="2" 
                                              placeholder="List any chronic conditions (e.g., Diabetes, Hypertension)"><?= htmlspecialchars($patient['chronic_conditions'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="current_medications" class="form-label">Current Medications</label>
                                    <textarea class="form-control" id="current_medications" name="current_medications" rows="2" 
                                              placeholder="List any current medications"><?= htmlspecialchars($patient['current_medications'] ?? ''); ?></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Medical Info
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Emergency Contact Tab -->
                        <div class="tab-pane" id="emergency">
                            <form method="POST" action="<?= BASE_URL; ?>patient/update-emergency-contact">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                               value="<?= htmlspecialchars($patient['emergency_contact_name'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                                        <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                               value="<?= htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="emergency_contact_relation" class="form-label">Relationship</label>
                                    <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation" 
                                           value="<?= htmlspecialchars($patient['emergency_contact_relation'] ?? ''); ?>"
                                           placeholder="e.g., Spouse, Parent, Sibling">
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    This contact will be notified in case of emergency.
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Emergency Contact
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane" id="security">
                            <form method="POST" action="<?= BASE_URL; ?>patient/change-password">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Changing your password will log you out from all devices.
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.profile-image-container {
    position: relative;
    display: inline-block;
}

.profile-image-upload {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: #3498db;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s;
}

.profile-image-upload:hover {
    background: #2980b9;
}

#profile_image {
    display: none;
}

.nav-tabs .nav-link {
    color: white !important;
    opacity: 0.8;
}

.nav-tabs .nav-link.active {
    opacity: 1;
    font-weight: bold;
}

.tab-content {
    padding-top: 20px;
}
</style>

<script>
// Preview image before upload
document.getElementById('profile_image').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(alert) {
        alert.style.transition = 'opacity 1s';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 1000);
    });
}, 5000);
</script>
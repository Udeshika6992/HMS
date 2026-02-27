<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-4">My Profile</h1>
        <p class="lead">Manage your account information</p>
    </div>
</section>

<!-- Profile Content -->
<section class="container mb-5">
    <div class="row">
        <!-- Profile Picture Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?php echo UPLOAD_URL; ?>profiles/<?php echo $user['profile_image'] ?? 'default-avatar.png'; ?>" 
                         class="rounded-circle img-fluid mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?><br>
                        <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?>
                    </p>
                    <span class="badge bg-danger">Administrator</span>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#profile" data-bs-toggle="tab">
                                <i class="fas fa-user me-2"></i>Profile
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
                        <!-- Profile Tab -->
                        <div class="tab-pane active" id="profile">
                            <form method="POST" action="<?php echo BASE_URL; ?>admin/update-profile" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>" readonly disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" 
                                               accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane" id="security">
                            <form method="POST" action="<?php echo BASE_URL; ?>admin/change-password">
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
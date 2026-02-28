<?php
/**
 * Create User View
 * 
 * @global string $BASE_URL
 * @global array $departments
 * @global array $formData
 */

// Suppress IDE warnings - these variables are passed from controller
$baseUrl = BASE_URL ?? '/HMS/';
$depts = $departments ?? [];
$form_data = $formData ?? [];

// Helper function for this view
if (!function_exists('asset_url')) {
    function asset_url($path) {
        global $baseUrl;
        return $baseUrl . 'assets/' . ltrim($path, '/');
    }
}
?>

<!-- Page Header -->
<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; margin-bottom: 40px;">
    <div class="container">
        <h1 class="display-4">Create New User</h1>
        <p class="lead">Add a new user to the system</p>
    </div>
</section>

<!-- Create User Form -->
<section class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Display form errors if any
                    if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])): 
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($_SESSION['form_errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars(is_array($error) ? implode(', ', $error) : $error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php 
                        unset($_SESSION['form_errors']);
                    endif; 
                    ?>

                    <form method="POST" action="<?php echo $baseUrl; ?>admin/users/create" id="createUserForm">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <!-- Username and Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" 
                                           placeholder="Enter username" required>
                                </div>
                                <small class="text-muted">Minimum 3 characters, letters and numbers only</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" 
                                           placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirm password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($form_data['full_name'] ?? ''); ?>" 
                                       placeholder="Enter full name" required>
                            </div>
                        </div>

                        <!-- Phone and Role -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>" 
                                           placeholder="Enter phone number">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label fw-bold">User Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">-- Select User Role --</option>
                                        <option value="admin" <?php echo ($form_data['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                        <option value="doctor" <?php echo ($form_data['role'] ?? '') == 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                        <option value="patient" <?php echo ($form_data['role'] ?? '') == 'patient' ? 'selected' : ''; ?>>Patient</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" 
                                      placeholder="Enter address"><?php echo htmlspecialchars($form_data['address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Doctor-specific fields -->
                        <div id="doctorFields" style="display: none;" class="mt-4 p-3 bg-light rounded">
                            <h5 class="mb-3 text-primary"><i class="fas fa-user-md me-2"></i>Doctor Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization" 
                                           value="<?php echo htmlspecialchars($form_data['specialization'] ?? ''); ?>" 
                                           placeholder="e.g., Cardiologist">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Qualification</label>
                                    <input type="text" class="form-control" id="qualification" name="qualification" 
                                           value="<?php echo htmlspecialchars($form_data['qualification'] ?? ''); ?>" 
                                           placeholder="e.g., MBBS, MD">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="experience_years" class="form-label">Experience (Years)</label>
                                    <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                           value="<?php echo htmlspecialchars($form_data['experience_years'] ?? ''); ?>" 
                                           min="0" max="50" placeholder="Years">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number" 
                                           value="<?php echo htmlspecialchars($form_data['license_number'] ?? ''); ?>" 
                                           placeholder="Medical license #">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">-- Select Department --</option>
                                        <?php foreach ($depts as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" 
                                                <?php echo ($form_data['department_id'] ?? '') == $dept['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept['department_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Patient-specific fields -->
                        <div id="patientFields" style="display: none;" class="mt-4 p-3 bg-light rounded">
                            <h5 class="mb-3 text-success"><i class="fas fa-user me-2"></i>Patient Information</h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="<?php echo htmlspecialchars($form_data['date_of_birth'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">-- Select Gender --</option>
                                        <option value="Male" <?php echo ($form_data['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($form_data['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($form_data['gender'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="form-select" id="blood_group" name="blood_group">
                                        <option value="">-- Select Blood Group --</option>
                                        <?php 
                                        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                        foreach ($bloodGroups as $bg): 
                                        ?>
                                            <option value="<?php echo $bg; ?>" 
                                                <?php echo ($form_data['blood_group'] ?? '') == $bg ? 'selected' : ''; ?>>
                                                <?php echo $bg; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo $baseUrl; ?>admin/users" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const doctorFields = document.getElementById('doctorFields');
    const patientFields = document.getElementById('patientFields');
    const form = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Role-based field visibility
    function toggleRoleFields() {
        const role = roleSelect.value;
        doctorFields.style.display = role === 'doctor' ? 'block' : 'none';
        patientFields.style.display = role === 'patient' ? 'block' : 'none';
        
        // Update required attributes
        const doctorInputs = doctorFields.querySelectorAll('input, select');
        const patientInputs = patientFields.querySelectorAll('input, select');
        
        doctorInputs.forEach(input => {
            input.required = role === 'doctor';
        });
        
        patientInputs.forEach(input => {
            input.required = role === 'patient';
        });
    }

    roleSelect.addEventListener('change', toggleRoleFields);
    
    // Password visibility toggle
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = event.currentTarget.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessage = '';

        // Password validation
        if (password.value.length < 6) {
            isValid = false;
            errorMessage += '• Password must be at least 6 characters\n';
            password.classList.add('is-invalid');
        } else {
            password.classList.remove('is-invalid');
        }

        // Confirm password validation
        if (password.value !== confirmPassword.value) {
            isValid = false;
            errorMessage += '• Passwords do not match\n';
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.classList.remove('is-invalid');
        }

        // Username validation
        const username = document.getElementById('username');
        if (username.value.length < 3) {
            isValid = false;
            errorMessage += '• Username must be at least 3 characters\n';
            username.classList.add('is-invalid');
        } else {
            username.classList.remove('is-invalid');
        }

        // Email validation
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            isValid = false;
            errorMessage += '• Please enter a valid email address\n';
            email.classList.add('is-invalid');
        } else {
            email.classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fix the following errors:\n' + errorMessage);
            return false;
        }

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
        return true;
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
});
</script>

<!-- Custom Styles -->
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 40px;
}

.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.5rem;
}

.card-body {
    padding: 2rem;
}

.form-control, .form-select {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

.input-group-text {
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-right: none;
    border-radius: 8px 0 0 8px;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 8px 8px 0;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.rounded {
    border-radius: 10px !important;
}
</style>
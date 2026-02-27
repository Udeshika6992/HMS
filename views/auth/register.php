<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .register-container {
            max-width: 600px;
            width: 100%;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-header h2 {
            margin: 10px 0 0;
            font-weight: 600;
        }
        .register-body {
            padding: 40px;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            font-size: 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 50px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-top: 20px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .error-feedback {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-user-plus fa-3x"></i>
                <h2>Create Account</h2>
                <p>Join our hospital management system</p>
            </div>
            <div class="register-body">
                <?php 
                if (isset($_SESSION['flash_message'])) {
                    $type = $_SESSION['flash_type'] ?? 'info';
                    $message = $_SESSION['flash_message'];
                    $alertClass = $type === 'success' ? 'alert-success' : ($type === 'error' ? 'alert-danger' : 'alert-info');
                    echo "<div class='alert $alertClass'>$message</div>";
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                }

                $formData = $_SESSION['form_data'] ?? [];
                $errors = $_SESSION['form_errors'] ?? [];
                ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>do-register">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control <?php echo isset($errors['full_name']) ? 'is-invalid' : ''; ?>" 
                                   name="full_name" value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>" required>
                            <?php if (isset($errors['full_name'])): ?>
                                <div class="error-feedback"><?php echo $errors['full_name']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                   name="username" value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="error-feedback"><?php echo $errors['username']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                               name="email" value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="error-feedback"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                   name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="error-feedback"><?php echo $errors['password']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                   name="confirm_password" required>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="error-feedback"><?php echo $errors['confirm_password']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phone" 
                               value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($formData['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </form>
                
                <div class="login-link">
                    Already have an account? <a href="<?php echo BASE_URL; ?>login">Login here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Clear form data after display
unset($_SESSION['form_errors']);
unset($_SESSION['form_data']);
?>
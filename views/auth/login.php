<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 400px;
            width: 90%;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h2 {
            margin: 10px 0 0;
            font-weight: 600;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }
        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding-left: 45px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
            outline: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 50px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        .demo-credentials {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 14px;
        }
        .demo-credentials p {
            margin: 5px 0;
            color: #666;
        }
        .demo-credentials strong {
            color: #333;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link a:hover {
            color: #333;
        }
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-hospital fa-3x"></i>
                <h2>Welcome Back</h2>
                <p>Please login to your account</p>
            </div>
            <div class="login-body">
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?>">
                        <?php 
                        echo $_SESSION['flash_message'];
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['flash_type']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Login Form -->
                <form method="POST" action="<?php echo BASE_URL; ?>do-login" id="loginForm">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               class="form-control" 
                               name="login" 
                               placeholder="Email or Username" 
                               required 
                               autofocus>
                    </div>
                    
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               placeholder="Password" 
                               required>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="<?php echo BASE_URL; ?>forgot-password" class="text-decoration-none">
                            Forgot Password?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn-login" id="submitBtn">
                        <span class="spinner" style="display: none;"></span>
                        <span class="btn-text">Login</span>
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="text-center mt-4">
                    Don't have an account? 
                    <a href="<?php echo BASE_URL; ?>register" class="text-decoration-none fw-bold">
                        Register here
                    </a>
                </div>
                
                <!-- Demo Credentials -->
                <div class="demo-credentials">
                    <p class="mb-2"><strong>Demo Credentials:</strong></p>
                    <p><i class="fas fa-user-circle text-primary me-2"></i>Admin: admin@hospital.com / password123</p>
                    <p><i class="fas fa-user-md text-success me-2"></i>Doctor: dr.smith@hospital.com / password123</p>
                    <p><i class="fas fa-user text-info me-2"></i>Patient: john.doe@email.com / password123</p>
                </div>
                
                <!-- Back to Home -->
                <div class="back-link">
                    <a href="<?php echo BASE_URL; ?>">
                        <i class="fas fa-arrow-left me-1"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true);
                $('.spinner').show();
                $('.btn-text').text('Logging in...');
            });
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>
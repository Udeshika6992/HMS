<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .forgot-container {
            max-width: 400px;
            width: 90%;
        }
        .forgot-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .forgot-header h2 {
            margin: 10px 0 0;
            font-weight: 600;
        }
        .forgot-body {
            padding: 40px 30px;
        }
        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding-left: 45px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 10;
            font-size: 18px;
        }
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 50px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <i class="fas fa-lock fa-3x"></i>
                <h2>Forgot Password?</h2>
                <p>Enter your email to reset password</p>
            </div>
            <div class="forgot-body">
                <?php
                if (isset($_SESSION['flash_message'])) {
                    $type = $_SESSION['flash_type'] ?? 'info';
                    $message = $_SESSION['flash_message'];
                    $alertClass = $type === 'success' ? 'alert-success' : ($type === 'error' ? 'alert-danger' : 'alert-info');
                    echo "<div class='alert $alertClass'>$message</div>";
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                }
                ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>do-forgot-password">
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-control" name="email" 
                               placeholder="Enter your email" required>
                    </div>
                    
                    <button type="submit" class="btn-reset">
                        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="<?php echo BASE_URL; ?>login">Back to Login</a>
                </div>
                
                <div class="text-center mt-3">
                    <a href="<?php echo BASE_URL; ?>" class="text-muted">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
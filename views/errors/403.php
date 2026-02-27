<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            margin: 20px;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #f5576c;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .error-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }
        .error-message {
            color: #666;
            margin-bottom: 30px;
            font-size: 18px;
        }
        .lock-icon {
            font-size: 80px;
            color: #f5576c;
            margin-bottom: 20px;
        }
        .btn-home {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-block;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
            color: white;
        }
        .btn-home i {
            margin-right: 10px;
        }
        .help-text {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>
        <div class="error-message">
            <p>Sorry, you don't have permission to access this page.</p>
            <p class="mt-2">If you believe this is a mistake, please contact your system administrator.</p>
        </div>
        <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/HMS/'; ?>" class="btn-home">
            <i class="fas fa-home"></i> Go to Homepage
        </a>
        <div class="help-text">
            <i class="fas fa-shield-alt"></i> This area is restricted to authorized personnel only
        </div>
    </div>
</body>
</html>
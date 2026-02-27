<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
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
            color: #5e72e4;
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
        .gear-icon {
            font-size: 80px;
            color: #5e72e4;
            margin-bottom: 20px;
            animation: spin 4s infinite linear;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .btn-home {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-block;
            margin: 0 10px;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(94, 114, 228, 0.4);
            color: white;
        }
        .btn-refresh {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-block;
            margin: 0 10px;
        }
        .btn-refresh:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(127, 140, 141, 0.4);
            color: white;
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
        <div class="gear-icon">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="error-code">500</div>
        <div class="error-title">Server Error</div>
        <div class="error-message">
            <p>Something went wrong on our servers.</p>
            <p class="mt-2">We're working to fix the issue. Please try again later.</p>
        </div>
        <div>
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/HMS/'; ?>" class="btn-home">
                <i class="fas fa-home"></i> Homepage
            </a>
            <a href="javascript:location.reload()" class="btn-refresh">
                <i class="fas fa-sync-alt"></i> Try Again
            </a>
        </div>
        <div class="help-text">
            <i class="fas fa-tools"></i> Our technical team has been notified
        </div>
    </div>
</body>
</html>
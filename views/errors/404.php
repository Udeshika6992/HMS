<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
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
            color: #764ba2;
            line-height: 1;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-back {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-back:hover {
            background: #7f8c8d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-title">Page Not Found</div>
        <p class="mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="<?php echo BASE_URL; ?>" class="btn-home">
            <i class="fas fa-home"></i> Go to Homepage
        </a>
        <a href="javascript:history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : APP_NAME; ?></title>
    
    <!-- Bootstrap CSS (CDN) - for print -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print">
    
    <style>
        @media print {
            body {
                font-family: 'Times New Roman', serif;
                color: black;
                background: white;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <?php echo $content; ?>
    
    <div class="no-print text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
    
    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        window.onload = function() {
            if (window.location.search.includes('autoprint=true')) {
                window.print();
            }
        }
    </script>
</body>
</html>
<?php
// Basic pure HTML output test - no database or includes required
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orient Yacht - Basic HTML Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        header {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin: 0;
        }
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .content {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Orient Yacht</h1>
        <p>Basic HTML Test</p>
    </header>
    
    <div class="content">
        <h2>Welcome to Orient Yacht</h2>
        <p>This is a basic HTML test page to verify if the server can properly serve HTML content.</p>
        <p>If you can see this page, it means that the web server is working correctly, but there might be issues with PHP or database connections.</p>
        
        <h3>Next Steps:</h3>
        <ol>
            <li>Check PHP configuration</li>
            <li>Verify database connection settings</li>
            <li>Check error logs for more detailed information</li>
            <li>Ensure all required files exist in the correct locations</li>
        </ol>
        
        <p>Current Time: <?= date('Y-m-d H:i:s') ?></p>
        <p>PHP Version: <?= phpversion() ?></p>
        
        <a href="index.php" class="btn">Go to Homepage</a>
    </div>
    
    <footer>
        <p>&copy; <?= date('Y') ?> Orient Yacht. All Rights Reserved.</p>
    </footer>
</body>
</html> 
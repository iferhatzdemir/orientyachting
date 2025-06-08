<?php
/**
 * Error page template for Orient Yachting
 * 
 * PHP version 7.3
 */

// Get HTTP status code
$status_code = http_response_code();

// Get error messages - use defaults if not set
$error_title = isset($error_title) ? $error_title : 'Bir hata oluştu';
$error_message = isset($error_message) ? $error_message : 'İşleminiz sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.';

// Define image based on error type
$error_image = "assets/img/error.jpg";  // Default error image

// Different message for 404
if ($status_code == 404) {
    $error_title = 'Sayfa bulunamadı';
    $error_message = 'Aradığınız sayfa bulunamadı. Lütfen ana sayfaya dönün.';
    $error_image = "assets/img/404.jpg";  // 404 specific image
}

// Get site URL for links
$site_url = isset($siteurl) ? $siteurl : (
    ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . 
    ($_SERVER['HTTP_HOST'] ?? 'localhost') . 
    '/orientyachting/'
);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($error_title); ?> - Orient Yachting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="<?php echo $site_url; ?>assets/css/master.css">
    <link rel="icon" type="image/x-icon" href="<?php echo $site_url; ?>favicon.ico">
    <style>
        .error-container {
            text-align: center;
            padding: 50px 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .error-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .error-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: #0d3d63;
        }
        .error-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #555;
        }
        .btn-home {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0d3d63;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-home:hover {
            background-color: #0a2e4a;
            color: #fff;
        }
        .footer-note {
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <img src="<?php echo $site_url . $error_image; ?>" alt="<?php echo htmlspecialchars($error_title); ?>" class="error-image">
        <h1 class="error-title"><?php echo htmlspecialchars($error_title); ?></h1>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <a href="<?php echo $site_url; ?>" class="btn-home">Ana Sayfaya Dön</a>
        <p class="footer-note">Bu hata kaydedildi ve teknik ekibimiz tarafından incelenecektir.</p>
    </div>
</body>
</html> 
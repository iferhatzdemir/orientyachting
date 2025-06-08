<?php
@session_start();
@ob_start();

// Daha detaylı hata mesajları için error reporting ayarları

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Hata işleme fonksiyonu
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    $error_message = "Hata [$errno]: $errstr - $errfile:$errline";
    error_log($error_message);
    
    // Kritik hataları göster
    if ($errno == E_ERROR || $errno == E_PARSE || $errno == E_CORE_ERROR || 
        $errno == E_COMPILE_ERROR || $errno == E_USER_ERROR) {
        ob_clean();
        include("include/hata.php"); // Özel hata sayfası
        exit(1);
    }
    
    return true; // Diğer hata işleyicilere devam et
}

// Hata işleyici kaydı
set_error_handler("custom_error_handler");

// Exception handler
function exception_handler($exception) {
    $error_message = "Exception: " . $exception->getMessage() . " in " . 
                    $exception->getFile() . " on line " . $exception->getLine();
    error_log($error_message);
    
    ob_clean();
    include("include/hata.php"); // Özel hata sayfası
    exit(1);
}

// Exception handler kaydı
set_exception_handler('exception_handler');

// Include error handling helper functions if they exist
if (file_exists("helpers/error_helper.php")) {
    include_once("helpers/error_helper.php");
}

// Set the timezone
date_default_timezone_set('Europe/Istanbul');

define("DATA","data/");
define("SAYFA","include/");
define("SINIF","classes/");

try {
    include_once(DATA."baglanti.php");
    define("SITE",$siteurl);
    include_once(SAYFA."seo.php");
} catch (Exception $e) {
    error_log("Kritik hata: " . $e->getMessage());
    
    if (!defined("SITE")) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $site_url = $protocol . $_SERVER['HTTP_HOST'] . "/";
        define("SITE", $site_url);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Orient Yachting / Home</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <script src="<?=SITE?>assets/js/preloader.js"></script>
    <link rel="stylesheet" href="<?=SITE?>assets/css/master.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="<?=SITE?>assets/css/preloader.css">
    <link rel="stylesheet" href="<?=SITE?>assets/css/custom.css">
    <link rel="stylesheet" href="<?=SITE?>assets/css/brand.css">

    <!--[if lt IE 9 ]>
<script src="/assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->
</head>

<body class="page">
    <!-- Loader end-->

    <?php	
    try {
        include_once(DATA."ust.php"); 
    } catch (Exception $e) {
        error_log("Header include error: " . $e->getMessage());
        echo '<div class="alert alert-danger">Üst menü yüklenemedi. Lütfen daha sonra tekrar deneyiniz.</div>';
    }
    ?>
    <div class="l-theme animated-css" data-header="sticky" data-header-top="200" id="main-content">
    <?php
    try {
        if(isset($_GET["sayfa"]) && !empty($_GET["sayfa"]))
        {
            $sayfa = $_GET["sayfa"].".php";
            if(file_exists(SAYFA.$sayfa))
            {
                if($sayfa == "service-detail.php" && (!isset($_GET["seflink"]) || empty($_GET["seflink"]))) {
                    error_log("ERROR: service-detail.php requested but no seflink parameter provided");
                    header("Location: " . SITE . "services");
                    exit;
                }
                include_once(SAYFA.$sayfa);
            }
            else
            {
                include_once(SAYFA."404.php");
            }
        }
        else
        {
            include_once(SAYFA."home.php");
        }
    } catch (Exception $e) {
        error_log("Content load error: " . $e->getMessage());
        echo '<div class="container mt-5 mb-5">';
        echo '<div class="alert alert-danger">Sayfa yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.</div>';
        echo '</div>';
    }
    
    try {
        include_once(DATA."footer.php");
    } catch (Exception $e) {
        error_log("Footer include error: " . $e->getMessage());
    }
    ?>
    </div>
    <!-- end layout-theme-->
    <!-- ++++++++++++-->
    <!-- MAIN SCRIPTS-->
    <!-- ++++++++++++-->
    <script>
        var site_url = "<?=SITE?>";
    </script>
    <script src="<?=SITE?>assets/libs/jquery-3.3.1.min.js"></script>
    <script src="<?=SITE?>assets/libs/jquery-migrate-1.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="<?=SITE?>assets/plugins/popever/popper.min.js"></script>
    <script src="<?=SITE?>assets/libs/bootstrap-4.1.3/js/bootstrap.min.js"></script>
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <!---->
    <!-- Color scheme-->
    <script src="<?=SITE?>assets/plugins/switcher/js/dmss.js"></script>
    <!-- Select customization & Color scheme-->
    <script src="<?=SITE?>assets/libs/bootstrap-select.min.js"></script>
    <!-- Pop-up window-->
    <script src="<?=SITE?>assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Headers scripts-->
    <script src="<?=SITE?>assets/plugins/headers/slidebar.js"></script>
    <script src="<?=SITE?>assets/plugins/headers/header.js"></script>
    <!-- Mail scripts-->
    <script src="<?=SITE?>assets/plugins/jqBootstrapValidation.js"></script>
    <script src="<?=SITE?>assets/plugins/contact_me.js"></script>
    <!-- Progress numbers-->
    <script src="<?=SITE?>assets/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js"></script>
    <script src="<?=SITE?>assets/plugins/rendro-easy-pie-chart/jquery.waypoints.min.js"></script>
    <!-- Animations-->
    <script src="<?=SITE?>assets/plugins/scrollreveal/scrollreveal.min.js"></script>
    <!-- Scale images-->
    <script src="<?=SITE?>assets/plugins/ofi.min.js"></script>
    <!-- Sliders-->
    <script src="<?=SITE?>assets/plugins/slick/slick.js"></script>
    <!-- Slider number-->
    <script src="<?=SITE?>assets/plugins/noUiSlider/wNumb.js"></script>
    <script src="<?=SITE?>assets/plugins/noUiSlider/nouislider.min.js"></script>
    <!-- User customization-->
    <script src="<?=SITE?>assets/js/custom.js"></script>
</body>

</html>

<style>
/* Luxury Preloader Styles */
#yacht-preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #12263f 0%, #1e4c7a 100%);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease, visibility 0.5s ease;
}

.preloader-content {
    text-align: center;
    max-width: 90%;
}

.luxury-yacht {
    margin-bottom: 30px;
}

.yacht-svg {
    width: 280px;
    height: 140px;
}

/* SVG Animations */
.yacht-hull, .yacht-cabin, .yacht-deck-detail, .yacht-window, .yacht-flag {
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
    animation: drawYacht 3s ease forwards;
}

.yacht-cabin {
    animation-delay: 0.5s;
}

.yacht-deck-detail {
    animation-delay: 1s;
}

.yacht-window {
    animation-delay: 1.5s;
}

.yacht-flag {
    animation-delay: 2s;
}

@keyframes drawYacht {
    to {
        stroke-dashoffset: 0;
    }
}

/* Wave Animations */
.wave {
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
    animation: drawWave 2s ease forwards;
}

.wave1 {
    animation-delay: 1.5s;
}

.wave2 {
    animation-delay: 1.8s;
}

.wave3 {
    animation-delay: 2.1s;
}

@keyframes drawWave {
    to {
        stroke-dashoffset: 0;
    }
}

/* Bobbing Animation */
.luxury-yacht {
    animation: bobYacht 4s ease-in-out infinite;
}

@keyframes bobYacht {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}

/* Logo and Loading Bar */
.loading-text {
    margin-bottom: 20px;
}

.loading-text img {
    filter: drop-shadow(0 5px 15px rgba(255, 255, 255, 0.2));
    animation: pulseLogo 2s ease-in-out infinite;
}

@keyframes pulseLogo {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.loading-bar {
    width: 280px;
    height: 4px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    margin: 0 auto;
    overflow: hidden;
    position: relative;
}

.loading-progress {
    height: 100%;
    width: 0;
    background: linear-gradient(to right, #c8a97e, #e2d2b8);
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 2px;
    animation: progress 3.5s ease-in-out forwards;
}

@keyframes progress {
    0% {
        width: 0;
    }
    100% {
        width: 100%;
    }
}

/* Hide Preloader When Page Loaded */
body.loaded #yacht-preloader {
    opacity: 0;
    visibility: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide preloader after content loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 1000);
    });
    
    // If page takes too long to load, hide preloader anyway
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 6000);
});
</script>
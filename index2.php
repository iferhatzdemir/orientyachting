<?php
// Silent error handling for production - errors will be logged but not displayed
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_log("--- Page load at " . date('Y-m-d H:i:s') . " ---");

// Session management
@session_start();
@ob_start();

// Define paths
define("DATA","data/");
define("SAYFA","include/");
define("SINIF","admin/class/");

// Include language helper
require_once __DIR__ . '/helpers/language_helper.php';

// Default language if not set
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'tr';
}

// Language change through URL
if (isset($_GET['lang'])) {
    set_language($_GET['lang']);
}

// Default site variables - fallback if database connection fails
$default_siteurl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/orient/";
$default_sitebaslik = "Orient Yacht";
$default_sitetelefon = "+90 XXX XXX XX XX";
$default_sitemail = "info@orientyacht.com";

// Try to include database connection
$db_included = false;
if(file_exists(DATA."baglanti.php")) {
    try {
        include_once(DATA."baglanti.php");
        $db_included = true;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
    }
}

// Use defaults if database inclusion failed
if (!$db_included || !isset($siteurl)) {
    $siteurl = $default_siteurl;
    $sitebaslik = $default_sitebaslik;
    $sitetelefon = $default_sitetelefon;
    $sitemail = $default_sitemail;
}

// Define site URL based on current domain
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' || $_SERVER['SERVER_PORT']==443){
   $protocol = "https://"; 
}
else{
    $protocol = "http://";
}
$sunucu=$protocol.$_SERVER['SERVER_NAME'];
define("SITE",$sunucu."/orient/");

// Make sure VT object exists
if (!isset($VT)) {
    // Log the fact that VT is missing
    error_log("VT object missing, creating emergency replacement");
    
    // Create emergency VT class if it doesn't exist
    if (!class_exists('VT')) {
        class EmergencyVT {
            public function VeriGetir($tablo, $where = "", $whereArray = array(), $orderBy = "", $limit = "") {
                error_log("Emergency VT: VeriGetir called for $tablo");
                return false;
            }
            
            public function SorguCalistir($query, $params = array()) {
                error_log("Emergency VT: SorguCalistir called");
                return false;
            }
        }
    }
    
    // Create VT instance
    $VT = new EmergencyVT();
}

// Include SEO file with error handling
if(file_exists(SAYFA."seo.php")) {
    try {
        include_once(SAYFA."seo.php");
    } catch (Exception $e) {
        error_log("SEO file error: " . $e->getMessage());
    }
}

// Define fallback translation function if it doesn't exist
if (!function_exists('t')) {
    function t($key, $params = []) {
        return isset($params['fallback']) ? $params['fallback'] : $key;
    }
}

// Define fallback getMultilingualContent function if it doesn't exist
if (!function_exists('getMultilingualContent')) {
    function getMultilingualContent($table, $id) {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?= isset($meta_title) ? $meta_title : $sitebaslik ?></title>
	<meta content="<?= isset($meta_description) ? $meta_description : 'Orient Yacht Charter' ?>" name="description">
	<meta content="<?= isset($meta_keywords) ? $meta_keywords : 'yacht, charter, rental' ?>" name="keywords">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="telephone=no" name="format-detection">
	<meta name="HandheldFriendly" content="true">
    <?php 
    // Canonical URL belirleme
    $canonical_url = SITE;
    if (isset($_GET['sayfa'])) {
        $canonical_url .= $_GET['sayfa'];
        if (isset($_GET['seflink'])) {
             $canonical_url .= '/' . $_GET['seflink'];
        }
    } else {
        $canonical_url .= 'anasayfa'; // Varsayılan anasayfa
    }
    // Open Graph ve Twitter için ana resmi belirle (yat detayda $anaResim, diğerlerinde varsayılan)
    $og_image = isset($anaResim) ? $anaResim : SITE . 'assets/img/og-default.jpg'; // Varsayılan OG resmi yolu
    ?>
    <link rel="canonical" href="<?= $canonical_url ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= isset($yat) ? 'product' : 'website' ?>">
    <meta property="og:url" content="<?= $canonical_url ?>">
    <meta property="og:title" content="<?= isset($meta_title) ? $meta_title : $sitebaslik ?>">
    <meta property="og:description" content="<?= isset($meta_description) ? $meta_description : 'Orient Yacht Charter' ?>">
    <meta property="og:image" content="<?= $og_image ?>">
    <meta property="og:site_name" content="<?= $sitebaslik ?>" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $canonical_url ?>">
    <meta property="twitter:title" content="<?= isset($meta_title) ? $meta_title : $sitebaslik ?>">
    <meta property="twitter:description" content="<?= isset($meta_description) ? $meta_description : 'Orient Yacht Charter' ?>">
    <meta property="twitter:image" content="<?= $og_image ?>">
    
	<link rel="stylesheet" href="<?=SITE?>assets/css/master.css">
	<link rel="stylesheet" href="<?=SITE?>assets/css/preloader.css">
	<link rel="stylesheet" href="<?=SITE?>assets/css/custom-nav.css">
	<link rel="icon" type="image/x-icon" href="<?=SITE?>favicon.ico">
	<!-- Preloader script should load early -->
	<script src="<?=SITE?>assets/js/preloader.js"></script>
	
	<!-- Custom styles for language selector -->
	<style>
	.lang-nav-item {
		display: flex;
		align-items: center;
		margin: 0 10px;
	}
	.lang-nav-item img {
		width: 24px;
		height: 16px;
		margin-right: 5px;
	}
	
	/* Fallback styles for when assets fail to load */
	body {
	    font-family: Arial, sans-serif;
	    line-height: 1.6;
	    color: #333;
	    background-color: #fff;
	}
	.container {
	    max-width: 1170px;
	    margin: 0 auto;
	    padding: 0 15px;
	}
	.row {
	    display: flex;
	    flex-wrap: wrap;
	}
	</style>
	
	<!--[if lt IE 9 ]>
<script src="<?=SITE?>assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->
    <!-- JSON-LD Script (Included pages can add to this) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?= $sitebaslik ?>",
      "url": "<?= SITE ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= SITE ?>arama?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
</head>

<body class="page">
	<!-- Loader-->
	<div id="yacht-preloader">
		<div class="preloader-content">
			<div class="luxury-yacht">
				<svg class="yacht-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" width="200" height="100">
					<!-- Hull -->
					<path class="yacht-hull" d="M150,250 C250,280 550,280 650,250 L680,280 C550,320 250,320 120,280 Z" fill="none" stroke="#ffffff" stroke-width="4"/>
					<!-- Cabin -->
					<path class="yacht-cabin" d="M300,250 L300,200 L500,200 L500,250" fill="none" stroke="#ffffff" stroke-width="3"/>
					<!-- Deck Details -->
					<path class="yacht-deck-detail" d="M200,250 L260,250" fill="none" stroke="#ffffff" stroke-width="2"/>
					<path class="yacht-deck-detail" d="M540,250 L600,250" fill="none" stroke="#ffffff" stroke-width="2"/>
					<!-- Windows -->
					<path class="yacht-window" d="M330,225 L350,225" fill="none" stroke="#ffffff" stroke-width="2"/>
					<path class="yacht-window" d="M380,225 L400,225" fill="none" stroke="#ffffff" stroke-width="2"/>
					<path class="yacht-window" d="M430,225 L450,225" fill="none" stroke="#ffffff" stroke-width="2"/>
					<!-- Flagpole -->
					<path class="yacht-flag" d="M600,250 L600,180 L650,200 L600,220" fill="none" stroke="#ffffff" stroke-width="2"/>
					<!-- Waves -->
					<path class="wave wave1" d="M50,300 Q200,270 400,300 Q600,330 750,300" fill="none" stroke="#3498db" stroke-width="3"/>
					<path class="wave wave2" d="M50,320 Q200,290 400,320 Q600,350 750,320" fill="none" stroke="#2980b9" stroke-width="2"/>
					<path class="wave wave3" d="M50,340 Q200,310 400,340 Q600,370 750,340" fill="none" stroke="#1a5276" stroke-width="2"/>
				</svg>
			</div>
			<div class="loading-text">
				<span>ORIENT YACHT</span>
			</div>
			<div class="loading-bar">
				<div class="loading-progress"></div>
			</div>
		</div>
	</div>
	<!-- Loader end-->
	<div class="l-theme animated-css" data-header="sticky" data-header-top="200" data-canvas="container">
		<?php
	// Include header with error handling
	if(file_exists(DATA."ust.php")) {
	    try {
	        include_once(DATA."ust.php");
	    } catch (Exception $e) {
	        error_log("Header error: " . $e->getMessage());
	        echo "<header style='text-align:center; padding: 20px;'><h1>{$sitebaslik}</h1></header>";
	    }
	} else {
	    echo "<header style='text-align:center; padding: 20px;'><h1>{$sitebaslik}</h1></header>";
	}
	
	// İçerik yükleme with error handling
	if(isset($_GET["sayfa"]) && !empty($_GET["sayfa"])) {
		 // Güvenlik için temizle - sadece harf, rakam, tire ve alt çizgi izin ver
		 $sayfa_param = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET["sayfa"]);
		 $sayfa = $sayfa_param.".php";
		 
		 // Include path traversal önleme
		 $sayfa = str_replace('../', '', $sayfa);
		 $sayfa = str_replace('..\\', '', $sayfa);
		 
		 if(file_exists(SAYFA.$sayfa)) {
		     try {
			     include_once(SAYFA.$sayfa);
			 } catch (Exception $e) {
			     error_log("Content error: " . $e->getMessage());
			     echo "<div class='container'><p>Sorry, there was an error loading the content. Please try again later.</p></div>";
		     }
		 } else {
		     try {
			     include_once(SAYFA."home.php");
			 } catch (Exception $e) {
			     error_log("Home error: " . $e->getMessage());
			     echo "<div class='container'><p>Sorry, there was an error loading the homepage. Please try again later.</p></div>";
		     }
		 }
	} else {
	    try {
		     include_once(SAYFA."home.php");
	    } catch (Exception $e) {
		     error_log("Home error: " . $e->getMessage());
		     echo "<div class='container'><p>Sorry, there was an error loading the homepage. Please try again later.</p></div>";
	    }
	}
	
	// Include footer with error handling
	if(file_exists(DATA."footer.php")) {
	    try {
	        include_once(DATA."footer.php");
	    } catch (Exception $e) {
	        error_log("Footer error: " . $e->getMessage());
	        echo "<footer style='text-align:center; padding: 20px; margin-top: 40px; border-top: 1px solid #eee;'><p>&copy; " . date('Y') . " {$sitebaslik}</p></footer>";
	    }
	} else {
	    echo "<footer style='text-align:center; padding: 20px; margin-top: 40px; border-top: 1px solid #eee;'><p>&copy; " . date('Y') . " {$sitebaslik}</p></footer>";
	}
	?>
	</div>
	<!-- end layout-theme-->
	<!-- ++++++++++++-->
	<!-- MAIN SCRIPTS-->
	<!-- ++++++++++++-->
	<script src="<?=SITE?>assets/libs/jquery-3.3.1.min.js"></script>
	<script src="<?=SITE?>assets/libs/jquery-migrate-1.4.1.min.js"></script>
	<!-- Bootstrap-->
	<script src="<?=SITE?>assets/plugins/popever/popper.min.js"></script>
	<script src="<?=SITE?>assets/libs/bootstrap-4.1.3/js/bootstrap.min.js"></script>
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
	<!-- Main slider-->
	<script src="<?=SITE?>assets/plugins/slider-pro/jquery.sliderPro.min.js"></script>
	<!-- Sliders-->
	<script src="<?=SITE?>assets/plugins/slick/slick.js"></script>
	<!-- Slider number-->
	<script src="<?=SITE?>assets/plugins/noUiSlider/wNumb.js"></script>
	<script src="<?=SITE?>assets/plugins/noUiSlider/nouislider.min.js"></script>
	<!-- User customization-->
	<script src="<?=SITE?>assets/js/custom.js"></script>
	<script src="<?=SITE?>assets/js/custom-lang.js"></script>
	<script src="<?=SITE?>assets/js/tr-fix.js"></script>
	
	<script>
	// Fallback for missing images
	document.addEventListener('DOMContentLoaded', function() {
	    const images = document.querySelectorAll('img');
	    images.forEach(img => {
	        img.onerror = function() {
	            if (!this.src.includes('default')) {
	                this.src = '<?=SITE?>assets/img/default-image.jpg';
	            }
	        };
	    });
	});
	</script>
</body>

</html>
<?php
// Make sure output buffer is flushed
@ob_end_flush();
?>
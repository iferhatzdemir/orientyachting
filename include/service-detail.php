<?php
if(!defined("SABIT")) define("SABIT", true);

// Enable error logging for debugging
error_log("Service detail page accessed: " . $_SERVER['REQUEST_URI']);

// Debug section - only visible when ?debug=1 is added to URL
if(isset($_GET['debug']) && $_GET['debug'] == '1') {
    echo '<div style="background: #f8f9fa; border: 1px solid #ccc; padding: 15px; margin: 15px;">';
    echo '<h3>Debug Information</h3>';
    echo '<p><strong>Request URI:</strong> ' . $_SERVER['REQUEST_URI'] . '</p>';
    echo '<p><strong>Query String:</strong> ' . $_SERVER['QUERY_STRING'] . '</p>';
    echo '<p><strong>Seflink Parameter:</strong> ' . (isset($_GET['seflink']) ? $_GET['seflink'] : 'NOT SET') . '</p>';
    echo '<p><strong>Current File:</strong> ' . __FILE__ . '</p>';
    
    // Test database connection
    echo '<h4>Database Test:</h4>';
    try {
        $testQuery = $VT->VeriGetir("services", "", array(), "LIMIT 1");
        if($testQuery !== false) {
            echo '<p style="color: green;">Database connection successful. Found records in services table.</p>';
        } else {
            echo '<p style="color: red;">No records found in services table or query failed.</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;">Database error: ' . $e->getMessage() . '</p>';
    }
    
    echo '</div>';
}

// Check if we have a service seflink
$seflink = isset($_GET['seflink']) ? $VT->filter($_GET['seflink']) : null;

error_log("Seflink parameter: " . ($seflink ? $seflink : "NULL"));

if(!$seflink) {
    error_log("ERROR: No seflink parameter provided");
    header("Location: " . SITE . "services");
    exit;
}

try {
    // Fetch the service details from the database
    $service = $VT->VeriGetir("services", "WHERE seflink=? AND durum=?", array($seflink, 1), "ORDER BY ID ASC", 1);
    
    error_log("Database query executed for seflink: " . $seflink);
    
    if($service === false) {
        // Log the error
        error_log("ERROR: Service not found with seflink: " . $seflink);
        
        // Try to fetch any service with this seflink regardless of status
        $anyService = $VT->VeriGetir("services", "WHERE seflink=?", array($seflink), "ORDER BY ID ASC", 1);
        
        if($anyService !== false) {
            error_log("NOTE: Service exists but may be inactive. ID: " . $anyService[0]["ID"] . ", Status: " . $anyService[0]["durum"]);
        } else {
            // Check if table exists and has records
            $checkTable = $VT->VeriGetir("services", "", array(), "LIMIT 1");
            if($checkTable === false) {
                error_log("ERROR: Services table may be empty or inaccessible");
            } else {
                error_log("Services table is accessible and contains records");
            }
        }
        
        // Redirect to services list
        header("Location: " . SITE . "services");
        exit;
    }
    
    error_log("Service found. ID: " . $service[0]["ID"] . ", Title: " . $service[0]["baslik"]);
    
    // Get the current language - default to English
    $currentLang = isset($_SESSION["dil"]) ? $_SESSION["dil"] : "en";
    
    // Get language-specific content if available
    $serviceTitle = $service[0]["baslik"];
    $serviceContent = $service[0]["metin"];
    $serviceDescription = $service[0]["description"];
    $serviceKeywords = $service[0]["anahtar"];
    $serviceSlogan = !empty($service[0]["slogan"]) ? $service[0]["slogan"] : "Professional Yacht Services";
    
    // Check if translation exists
    if($currentLang != "tr") {
        $serviceTranslation = $VT->VeriGetir("services_dil", "WHERE service_id=? AND lang=?", array($service[0]["ID"], $currentLang), "ORDER BY ID ASC", 1);
        
        if($serviceTranslation !== false) {
            if(!empty($serviceTranslation[0]["baslik"])) {
                $serviceTitle = $serviceTranslation[0]["baslik"];
            }
            if(!empty($serviceTranslation[0]["metin"])) {
                $serviceContent = $serviceTranslation[0]["metin"];
            }
            if(!empty($serviceTranslation[0]["slogan"])) {
                $serviceSlogan = $serviceTranslation[0]["slogan"];
            }
        }
    }
    
    // Get high-quality image for hero background
    $serviceImage = !empty($service[0]["resim"]) ? SITE."images/services/".$service[0]["resim"] : SITE."images/services/default-service.jpg";
    
    // Get site settings for header information
    $siteAyar = $VT->VeriGetir("ayarlar", "WHERE ID=?", array(1), "ORDER BY ID ASC", 1);
    if($siteAyar != false) {
        $phone = $siteAyar[0]["telefon"];
        $email = $siteAyar[0]["email"];
    }
    
    // Get related services
    $relatedServices = $VT->VeriGetir("services", "WHERE ID != ? AND durum=?", array($service[0]["ID"], 1), "ORDER BY sirano ASC LIMIT 3");
    
    // SEO meta tags
    $seo = $VT->VeriGetir("seo", "WHERE seo_url=? AND durum=?", array("services", 1), "ORDER BY ID ASC", 1);
    
    if($seo === false) {
        error_log("WARNING: SEO data not found for services");
        // Set default values
        $seo = array(array(
            "title" => "Orient Yachting - Services",
            "description" => "Premium yacht charter services",
            "keywords" => "yacht, charter, services"
        ));
    }
} catch (Exception $e) {
    error_log("CRITICAL ERROR in service-detail.php: " . $e->getMessage());
    include(SAYFA."404.php");
    exit;
}
?>

<title><?=$serviceTitle?> | Orient Yachting</title>
<meta name="description" content="<?=!empty($serviceDescription) ? $serviceDescription : substr(strip_tags($serviceContent), 0, 160)?>">
<meta name="keywords" content="<?=!empty($serviceKeywords) ? $serviceKeywords : 'yacht, charter, luxury, '.$serviceTitle?>">

<!-- Open Graph Tags -->
<meta property="og:title" content="<?=$serviceTitle?> | Orient Yachting">
<meta property="og:description" content="<?=!empty($serviceDescription) ? $serviceDescription : substr(strip_tags($serviceContent), 0, 160)?>">
<meta property="og:url" content="<?=SITE?>services/<?=$service[0]["seflink"]?>">
<meta property="og:type" content="article">
<?php if(!empty($service[0]["resim"])) { ?>
<meta property="og:image" content="<?=SITE?>images/services/<?=$service[0]["resim"]?>">
<?php } ?>

<!-- Twitter Card Tags -->
<meta name="twitter:title" content="<?=$serviceTitle?>">
<meta name="twitter:description" content="<?=substr(strip_tags($serviceContent), 0, 160)?>">
<?php if(!empty($service[0]["resim"])) { ?>
<meta name="twitter:image" content="<?=SITE?>images/services/<?=$service[0]["resim"]?>">
<meta name="twitter:card" content="summary_large_image">
<?php } else { ?>
<meta name="twitter:card" content="summary">
<?php } ?>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "<?=$serviceTitle?>",
    "description": "<?=substr(strip_tags($serviceContent), 0, 160)?>",
    "provider": {
        "@type": "Organization",
        "name": "Orient Yachting",
        "url": "<?=SITE?>"
    }
    <?php if(!empty($service[0]["resim"])) { ?>
    ,"image": "<?=SITE?>images/services/<?=$service[0]["resim"]?>"
    <?php } ?>
}
</script>

<!-- Enhanced styling with animations and effects -->
<style>
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideInFromBottom {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

@keyframes scaleIn {
    0% { transform: scale(0.95); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes floatAnimation {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.service-page {
    font-family: 'Arial', sans-serif;
    color: #333;
    line-height: 1.6;
}

.hero-banner {
    position: relative;
    background-color: #0a1f35;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 600px; /* Increased height */
    margin-top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.hero-banner:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,20,0.4) 0%, rgba(0,0,20,0.8) 100%);
    z-index: 1;
}

.hero-bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('<?=SITE?>images/pattern.png');
    background-size: cover;
    opacity: 0.1;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 0 20px;
    margin-top: 100px; /* Push content lower */
    animation: fadeIn 1.2s ease-out;
}

.service-title {
    color: #fff;
    font-size: 56px;
    font-weight: 700;
    margin: 0 0 20px;
    text-align: center;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    letter-spacing: 1px;
    opacity: 0;
    animation: slideInFromBottom 1s ease-out forwards;
    animation-delay: 0.3s;
}

.service-subtitle {
    color: #fff;
    font-size: 24px;
    margin-bottom: 40px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    opacity: 0;
    animation: slideInFromBottom 1s ease-out forwards;
    animation-delay: 0.6s;
}

.hero-line {
    width: 80px;
    height: 3px;
    background: #fff;
    margin: 25px auto 30px;
    opacity: 0;
    animation: scaleIn 1s ease-out forwards;
    animation-delay: 0.8s;
}

.breadcrumb-nav {
    position: absolute;
    bottom: 50px; /* Positioned lower */
    left: 0;
    width: 100%;
    text-align: center;
    z-index: 2;
    opacity: 0;
    animation: fadeIn 1s ease-out forwards;
    animation-delay: 1s;
}

.breadcrumb {
    display: inline-block;
    color: rgba(255,255,255,0.9);
    font-size: 15px;
    background: rgba(0,0,0,0.3);
    padding: 10px 25px;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.breadcrumb a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: #fff;
    text-decoration: underline;
}

.content-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 70px 20px 90px;
    position: relative;
}

.content-container:before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 3px;
    height: 50px;
    background: #0a1f35;
    opacity: 0.5;
}

.service-content {
    margin-bottom: 50px;
    line-height: 1.9;
    animation: fadeIn 1s ease-out;
    animation-delay: 0.5s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.service-content p {
    margin-bottom: 25px;
}

.floating-element {
    animation: floatAnimation 5s ease-in-out infinite;
}

.related-services {
    margin-top: 70px;
    border-top: 1px solid #eee;
    padding-top: 60px;
    position: relative;
}

.related-services:before {
    content: '';
    position: absolute;
    top: -3px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 5px;
    background: #0a1f35;
}

.related-title {
    font-size: 32px;
    margin-bottom: 40px;
    color: #0a1f35;
    text-align: center;
    position: relative;
    opacity: 0;
    animation: fadeIn 1s ease-out forwards;
    animation-delay: 0.3s;
}

.related-title:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: #0a1f35;
    margin: 15px auto 0;
}

.service-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

.service-item {
    flex: 0 0 calc(33.333% - 30px);
    text-align: center;
    margin-bottom: 30px;
    opacity: 0;
    animation: fadeIn 0.8s ease-out forwards;
}

.service-item:nth-child(1) { animation-delay: 0.4s; }
.service-item:nth-child(2) { animation-delay: 0.6s; }
.service-item:nth-child(3) { animation-delay: 0.8s; }

.service-link {
    display: block;
    padding: 25px 20px;
    background-color: #f8f9fa;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: all 0.4s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    position: relative;
    top: 0;
    overflow: hidden;
}

.service-link:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 0;
    background: #0a1f35;
    transition: all 0.4s ease;
    z-index: 0;
}

.service-link:hover {
    color: #fff;
    transform: translateY(-7px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.service-link:hover:before {
    height: 100%;
}

.service-link span {
    position: relative;
    z-index: 1;
}

.contact-bar {
    background: linear-gradient(135deg, #0a1f35 0%, #1e4e7a 100%);
    padding: 40px;
    text-align: center;
    border-radius: 8px;
    margin-top: 60px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
    color: #fff;
    opacity: 0;
    animation: fadeIn 1s ease-out forwards;
    animation-delay: 0.5s;
}

.contact-bar:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('<?=SITE?>images/pattern.png');
    background-size: cover;
    opacity: 0.05;
}

.contact-bar p {
    margin: 0 0 20px;
    font-size: 20px;
    position: relative;
}

.btn {
    display: inline-block;
    background-color: #fff;
    color: #0a1f35;
    padding: 14px 35px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.2);
    transition: all 0.4s ease;
    z-index: -1;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.btn:hover:before {
    left: 100%;
}

@media (max-width: 768px) {
    .hero-banner {
        height: 450px;
    }
    
    .hero-content {
        margin-top: 50px;
    }
    
    .service-title {
        font-size: 36px;
    }
    
    .service-subtitle {
        font-size: 18px;
        margin-bottom: 25px;
    }
    
    .breadcrumb-nav {
        bottom: 30px;
    }
    
    .service-item {
        flex: 0 0 100%;
    }
    
    .content-container {
        padding: 50px 15px 70px;
    }
    
    .contact-bar {
        padding: 30px 20px;
    }
    
    .contact-bar p {
        font-size: 18px;
    }
}

/* Hero Section */
.luxury-hero {
    height: 80vh;
    min-height: 600px;
    position: relative;
    overflow: hidden;
    margin-top: -120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-position: center !important;
    background-size: cover !important;
}

.hero-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('<?=SITE?>assets/img/uk-flag-pattern.png') center/cover;
    opacity: 0.15;
    mix-blend-mode: overlay;
}

.container.h-100 {
    height: 100%;
    padding-top: 120px;
    position: relative;
    z-index: 2;
}

.hero-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 20px;
    transform: translateY(40px);
}

.display-title {
    font-family: 'Playfair Display', serif;
    font-size: 72px;
    color: #fff;
    margin-bottom: 25px;
    font-weight: 700;
    letter-spacing: 1px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(20px);
    animation: titleFadeIn 1s forwards 0.5s;
    line-height: 1.2;
}

@keyframes titleFadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.title-separator {
    position: relative;
    height: 20px;
    margin: 30px 0;
}

.title-separator .diamond {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
    width: 12px;
    height: 12px;
    background: #C8A97E;
    animation: diamondSpin 4s linear infinite;
}

@keyframes diamondSpin {
    0% { transform: translate(-50%, -50%) rotate(45deg); }
    100% { transform: translate(-50%, -50%) rotate(405deg); }
}

.title-separator::before,
.title-separator::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 120px;
    height: 2px;
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.title-separator::before {
    right: 55%;
    transform-origin: right;
    animation: lineExtendLeft 1s forwards 0.8s;
}

.title-separator::after {
    left: 55%;
    transform-origin: left;
    animation: lineExtendRight 1s forwards 0.8s;
}

.hero-subtitle {
    color: #fff;
    font-size: 24px;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 2px;
    margin-top: 20px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    opacity: 0;
    animation: fadeIn 1s forwards 1s;
    text-transform: uppercase;
    font-weight: 300;
}

/* Breadcrumb Styles */
.breadcrumb-nav {
    margin-top: 40px;
}

.breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 30px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50px;
    backdrop-filter: blur(10px);
}

.breadcrumb a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
    font-weight: 500;
    font-size: 15px;
}

.breadcrumb a:hover {
    color: #C8A97E;
}

.breadcrumb span {
    color: #C8A97E;
    font-weight: 500;
}

/* Wave Animation */
.hero-waves {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    overflow: hidden;
}

.waves {
    width: 100%;
    height: 100%;
}

.wave-parallax > use {
    animation: waveMove 25s cubic-bezier(.55,.5,.45,.5) infinite;
}

.wave-parallax > use:nth-child(1) { animation-delay: -2s; }
.wave-parallax > use:nth-child(2) { animation-delay: -3s; }
.wave-parallax > use:nth-child(3) { animation-delay: -4s; }
.wave-parallax > use:nth-child(4) { animation-delay: -5s; }

@keyframes waveMove {
    0% { transform: translate3d(-90px,0,0); }
    100% { transform: translate3d(85px,0,0); }
}

@media (max-width: 768px) {
    .hero-waves {
        height: 40px;
    }
}
</style>

<div class="service-page">
    <!-- Hero Section -->
    <div class="luxury-hero" style="background: linear-gradient(rgba(11, 34, 66, 0.3), rgba(11, 34, 66, 0.5)), url('<?=$serviceImage?>') no-repeat center center/cover;">
        <div class="hero-pattern"></div>
        <div class="container h-100 d-flex align-items-center justify-content-center">
            <div class="hero-content text-center" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="display-title"><?=$serviceTitle?></h1>
                <div class="title-separator">
                    <span class="diamond"></span>
                </div>
                <p class="hero-subtitle"><?=$serviceSlogan?></p>
                
              
            </div>
        </div>
        <div class="hero-waves">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                <defs>
                    <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="wave-parallax">
                    <use href="#wave-path" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
                    <use href="#wave-path" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                    <use href="#wave-path" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                    <use href="#wave-path" x="48" y="7" fill="#fff"></use>
                </g>
            </svg>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-container">
        <div class="service-content">
            <?=stripslashes($serviceContent)?>
        </div>
        
        <!-- Contact Section -->
        <div class="contact-bar">
            <p>For more information about our <?=$serviceTitle?> service</p>
            <a href="<?=SITE?>contact" class="btn">Contact Us</a>
        </div>
        
        <!-- Related Services -->
        <?php if($relatedServices !== false && count($relatedServices) > 0) { ?>
        <div class="related-services">
            <h2 class="related-title">Other Services</h2>
            
            <div class="service-list">
                <?php
                $delay = 0;
                foreach($relatedServices as $related) {
                    $delay += 0.2;
                    // Translation check
                    $relatedTitle = $related["baslik"];
                    
                    if($currentLang != "tr") {
                        $relatedTranslation = $VT->VeriGetir("services_dil", "WHERE service_id=? AND lang=?", array($related["ID"], $currentLang), "ORDER BY ID ASC", 1);
                        if($relatedTranslation !== false && !empty($relatedTranslation[0]["baslik"])) {
                            $relatedTitle = $relatedTranslation[0]["baslik"];
                        }
                    }
                ?>
                <div class="service-item" style="animation-delay: <?=$delay?>s;">
                    <a href="<?=SITE?>services/<?=$related["seflink"]?>" class="service-link">
                        <span><?=stripslashes($relatedTitle)?></span>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parallax effect on scroll
    window.addEventListener('scroll', function() {
        let scrollPosition = window.pageYOffset;
        let heroBanner = document.querySelector('.hero-banner');
        if (heroBanner) {
            heroBanner.style.backgroundPosition = 'center ' + (scrollPosition * 0.4) + 'px';
        }
    });
});
</script> 
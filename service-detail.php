<?php
// Include necessary files and initialize database connection
require_once 'VT.php';
$VT = new VT();

// Define a function to load a template
function loadTemplate($templatePath) {
    if (file_exists($templatePath)) {
        return file_get_contents($templatePath);
    }
    return false;
}

// Define a function to replace template placeholders with actual data
function renderTemplate($template, $data) {
    foreach ($data as $key => $value) {
        $template = str_replace("{{ $key }}", $value, $template);
    }
    return $template;
}

// Check if we have a service ID
$serviceID = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$serviceID) {
    // Redirect to services list page if no ID is provided
    header("Location: services.php");
    exit;
}

// Fetch the service details from the database
$service = $VT->VeriGetir("services", "WHERE ID=? AND durum=?", array($serviceID, 1), "ORDER BY ID ASC", 1);

if ($service === false) {
    // Service not found or not active
    header("Location: services.php");
    exit;
}

// Get the current language (default to English if not set)
$currentLang = isset($_GET['lang']) ? $VT->filter($_GET['lang']) : 'en';

// Get language-specific content if available
$serviceTranslation = $VT->VeriGetir("services_dil", "WHERE service_id=? AND lang=?", array($serviceID, $currentLang), "ORDER BY ID ASC", 1);

// Prepare service data
$serviceTitle = $service[0]["baslik"];
$serviceContent = $service[0]["aciklama"];

// If translation exists, use it
if ($serviceTranslation !== false) {
    if (!empty($serviceTranslation[0]["baslik"])) {
        $serviceTitle = $serviceTranslation[0]["baslik"];
    }
    if (!empty($serviceTranslation[0]["aciklama"])) {
        $serviceContent = $serviceTranslation[0]["aciklama"];
    }
}

// Get related services
$relatedServices = $VT->VeriGetir("services", "WHERE ID != ? AND durum=?", array($serviceID, 1), "ORDER BY sirano ASC LIMIT 4");

// Prepare data for related services
$relatedServicesHTML = '';
if ($relatedServices !== false) {
    foreach ($relatedServices as $relatedService) {
        $serviceImage = !empty($relatedService["resim"]) 
            ? "images/services/" . $relatedService["resim"] 
            : "assets/img/service-placeholder.jpg";
        
        $relatedServicesHTML .= '
        <div class="related-service-item">
            <div class="card">
                <img src="'.$serviceImage.'" class="card-img-top" alt="'.$relatedService["baslik"].'">
                <div class="card-body">
                    <h5 class="card-title">'.$relatedService["baslik"].'</h5>
                    <p class="card-text">'.substr(strip_tags($relatedService["aciklama"]), 0, 100).'...</p>
                    <a href="service-detail.php?id='.$relatedService["ID"].'" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>';
    }
}

// Prepare gallery/features HTML
$galleryHTML = '';
if (!empty($service[0]["resim"])) {
    $galleryHTML .= '
    <div class="col-lg-6 pb-3">
        <div class="pr-3">
            <img class="img-fluid" src="images/services/'.$service[0]["resim"].'" alt="'.$serviceTitle.'"/>
        </div>
    </div>';
}

// Add icon if available
if (!empty($service[0]["icon"])) {
    $galleryHTML .= '
    <div class="col-lg-6 pb-3">
        <div class="service-icon">
            <i class="'.$service[0]["icon"].' fa-5x"></i>
        </div>
    </div>';
}

// Prepare data for template placeholders
$templateData = array(
    'site_title' => 'Orient Yachting',
    'page_title' => $serviceTitle,
    'meta_description' => substr(strip_tags($serviceContent), 0, 160),
    'meta_keywords' => 'yacht, services, charter, '.$serviceTitle,
    'contact_phone' => '+90 555 123 4567',
    'contact_email' => 'info@orientyachting.com',
    'social_media_links' => '
        <li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i class="ic fab fa-twitter"></i></a></li>
        <li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i class="ic fab fa-facebook-f"></i></a></li>
        <li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i class="ic fab fa-instagram"></i></a></li>',
    'navigation_menu' => '
        <ul class="yamm main-menu navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#">Our Fleet</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="yachts.php">Boats Listing</a>
                    <a class="dropdown-item" href="yacht-details.php">Boat Details</a>
                </div>
            </li>
            <li class="nav-item active"><a class="nav-link" href="services.php">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>',
    'service_image_url' => !empty($service[0]["resim"]) ? "images/services/".$service[0]["resim"] : "assets/img/service-placeholder.jpg",
    'service_title' => $serviceTitle,
    'service_date_iso' => date("Y-m-d"),
    'service_date_formatted' => date("F j, Y"),
    'service_meta_info' => '',
    'social_share_buttons' => '
        <div class="b-post-soc__item"><a class="b-post-soc__link" href="#" target="_blank"><i class="ic fab fa-twitter"></i></a></div>
        <div class="b-post-soc__item"><a class="b-post-soc__link" href="#" target="_blank"><i class="ic fab fa-facebook-f"></i></a></div>
        <div class="b-post-soc__item"><a class="b-post-soc__link" href="#" target="_blank"><i class="ic fab fa-pinterest-p"></i></a></div>',
    'service_content' => $serviceContent,
    'service_features_gallery' => $galleryHTML,
    'service_additional_info' => '',
    'related_services' => $relatedServicesHTML,
    'footer_content' => '
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <a class="footer__logo" href="index.html">
                            <img class="img-fluid" src="assets/img/logo-light.png" alt="logo">
                        </a>
                        <div class="footer-info">
                            Orient Yachting offers premium yacht charter services with a focus on luxury and comfort. Explore the beautiful coastlines with our fleet of well-maintained vessels.
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h3 class="footer-section__title">Contact Information</h3>
                        <div class="footer-contact">
                            <div class="footer-contact__item">
                                <i class="fas fa-map-marker-alt"></i> 123 Marina Boulevard, Istanbul, Turkey
                            </div>
                            <div class="footer-contact__item">
                                <i class="fas fa-phone"></i> +90 555 123 4567
                            </div>
                            <div class="footer-contact__item">
                                <i class="fas fa-envelope"></i> info@orientyachting.com
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer-section">
                        <h3 class="footer-section__title">Newsletter</h3>
                        <form class="footer-form">
                            <input class="footer-form__input" type="email" placeholder="Your Email">
                            <button class="footer-form__btn">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-copyright">
                        &copy; 2023 Orient Yachting. All rights reserved.
                    </div>
                </div>
            </div>
        </div>',
    'footer_scripts' => '
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery-migrate.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/custom.js"></script>'
);

// Load the template
$template = loadTemplate('services.html');

if ($template === false) {
    die("Error: Template file not found!");
}

// Render the template
$renderedHTML = renderTemplate($template, $templateData);

// Output the final HTML
echo $renderedHTML;
?> 
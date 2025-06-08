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

// Get services from database
$services = $VT->VeriGetir("services", "WHERE durum=?", array(1), "ORDER BY sirano ASC");

// Get the current language (default to English if not set)
$currentLang = isset($_GET['lang']) ? $VT->filter($_GET['lang']) : 'en';

// Prepare services HTML
$servicesHTML = '';

if ($services !== false) {
    $servicesHTML .= '<div class="row">';
    
    foreach ($services as $service) {
        // Get language-specific content if available
        $serviceTitle = $service["baslik"];
        $serviceContent = $service["aciklama"];
        
        $serviceTranslation = $VT->VeriGetir("services_dil", "WHERE service_id=? AND lang=?", array($service["ID"], $currentLang), "ORDER BY ID ASC", 1);
        
        // If translation exists, use it
        if ($serviceTranslation !== false) {
            if (!empty($serviceTranslation[0]["baslik"])) {
                $serviceTitle = $serviceTranslation[0]["baslik"];
            }
            if (!empty($serviceTranslation[0]["aciklama"])) {
                $serviceContent = $serviceTranslation[0]["aciklama"];
            }
        }
        
        $serviceImage = !empty($service["resim"]) 
            ? "images/services/" . $service["resim"] 
            : "assets/img/service-placeholder.jpg";
        
        $serviceIcon = !empty($service["icon"]) 
            ? '<div class="service-icon mb-4"><i class="' . $service["icon"] . ' fa-3x"></i></div>' 
            : '';
        
        // Limit content length for preview
        $shortContent = substr(strip_tags($serviceContent), 0, 150) . '...';
        
        $servicesHTML .= '
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card service-card h-100">
                <div class="service-card-img">
                    <img src="' . $serviceImage . '" class="card-img-top" alt="' . $serviceTitle . '">
                </div>
                <div class="card-body text-center">
                    ' . $serviceIcon . '
                    <h5 class="card-title">' . $serviceTitle . '</h5>
                    <p class="card-text">' . $shortContent . '</p>
                    <a href="service-detail.php?id=' . $service["ID"] . '" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>';
    }
    
    $servicesHTML .= '</div>';
} else {
    $servicesHTML = '<div class="alert alert-info">No services available at the moment.</div>';
}

// Create a template string for the services page
$servicesTemplate = '<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>{{ site_title }} - {{ page_title }}</title>
      <meta content="{{ meta_description }}" name="description">
      <meta content="{{ meta_keywords }}" name="keywords">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta content="telephone=no" name="format-detection">
      <meta name="HandheldFriendly" content="true">
      <link rel="stylesheet" href="assets/css/master.css">
      
      <link rel="icon" type="image/x-icon" href="favicon.ico"><!--[if lt IE 9 ]>
<script src="/assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->
  </head>
  <body class="page">
      <!-- Loader-->
      <div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
      <!-- Loader end-->
    <div class="l-theme animated-css" data-header="sticky" data-header-top="200" data-canvas="container">
        
      <!-- ==========================-->
      <!-- SEARCH MODAL-->
      <!-- ==========================-->
      <div class="header-search open-search">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 offset-sm-2 col-10 offset-1">
              <div class="navbar-search">
                <form class="search-global">
                  <input class="search-global__input" type="text" placeholder="Type to search" autocomplete="off" name="s" value=""/>
                  <button class="search-global__btn"><i class="icon stroke icon-Search"></i></button>
                  <div class="search-global__note">Begin typing your search above and press return to search.</div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <button class="search-close close" type="button"><i class="fa fa-times"></i></button>
      </div>
      <!-- ==========================-->
      <!-- MOBILE MENU-->
      <!-- ==========================-->
      <div data-off-canvas="mobile-slidebar left overlay">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
          <li class="nav-item"><a class="nav-link" href="listing.html">Boats Listing</a></li>
          <li class="nav-item active"><a class="nav-link" href="services.html">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="tours.html">Tours</a></li>
          <li class="nav-item"><a class="nav-link" href="blog.html">News</a></li>
          <li class="nav-item"><a class="nav-link" href="contacts.html">Contact</a></li>
        </ul>
      </div>
      
      <!-- HEADER -->
      <header class="header header-slider">
        <div class="top-bar">
          <div class="container">
            <div class="row justify-content-between align-items-center">
              <div class="col-auto">
                <div class="top-bar__item"><i class="fas fa-phone-square"></i> Phone: {{ contact_phone }}</div>
                <div class="top-bar__item"><i class="fas fa-envelope-square"></i> Email: {{ contact_email }}</div>
              </div>
              <div class="col-auto">
                <ul class="header-soc list-unstyled">
                  {{ social_media_links }}
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="header-main">
          <div class="container">
            <div class="row align-items-center justify-content-between">
              <div class="col-auto">
                <a class="navbar-brand navbar-brand_light scroll" href="index.html"> <img class="normal-logo img-fluid" src="assets/img/logo-light.png" alt="logo" /> </a>
                <a class="navbar-brand navbar-brand_dark scroll" href="index.html"><img class="normal-logo img-fluid" src="assets/img/logo-dark.png" alt="logo" /></a>
              </div>
              <div class="col-auto d-xl-none">
                <!-- Mobile Trigger Start-->
                <button class="menu-mobile-button js-toggle-mobile-slidebar toggle-menu-button"><i class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                <!-- Mobile Trigger End-->
              </div>
              <div class="col-xl d-none d-xl-block">
                <nav class="navbar navbar-expand-lg justify-content-end" id="nav">
                  {{ navigation_menu }}
                  <span class="header-main__link btn_header_search"><i class="ic icon-magnifier"></i></span>
                  <button class="header-main__btn btn btn-secondary">Book Now</button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </header>
      <!-- end .header-->
        
      <div class="section-title-page area-bg area-bg_dark area-bg_op_60">
        <div class="area-bg__inner">
          <div class="container text-center">
            <h1 class="b-title-page">{{ page_title }}</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ page_title }}</li>
              </ol>
            </nav>
            <!-- end .breadcrumb-->
          </div>
        </div>
      </div>
      <!-- end .b-title-page-->
      
      <div class="l-main-content">
        <div class="ui-decor ui-decor_mirror ui-decor_sm-h bg-primary"></div>
        <main>
          <section class="section-services">
            <div class="container">
              <div class="row">
                <div class="col-xl-10 offset-xl-1">
                  <div class="text-center mb-5">
                    <h2 class="ui-title">Our Services</h2>
                    <p class="ui-text">We offer a comprehensive range of yacht-related services designed to ensure your experience on the water is nothing short of exceptional. Explore our service offerings and discover how we can enhance your maritime adventures.</p>
                  </div>
                </div>
              </div>
              
              <!-- Services List -->
              {{ services_list }}
              
            </div>
          </section>
        </main>
      </div>
      
      <!-- Footer -->
      <footer class="footer">
        {{ footer_content }}
      </footer>
      
      <!-- Scripts -->
      {{ footer_scripts }}
    </div>
  </body>
</html>';

// Prepare data for template placeholders
$templateData = array(
    'site_title' => 'Orient Yachting',
    'page_title' => 'Our Services',
    'meta_description' => 'Discover our comprehensive range of yacht charter services and amenities.',
    'meta_keywords' => 'yacht, services, charter, luxury, boat rentals',
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
    'services_list' => $servicesHTML,
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

// Render the template
$renderedHTML = renderTemplate($servicesTemplate, $templateData);

// Output the final HTML
echo $renderedHTML;
?> 
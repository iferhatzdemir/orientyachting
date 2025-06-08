<!-- Yacht-themed Luxury Preloader -->
<!-- Added custom styles for logo centering -->
<style>
/* Global centering styles */
.center-flex {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

.center-block {
    display: block !important;
    margin-left: auto !important;
    margin-right: auto !important;
    text-align: center !important;
}

/* Enhanced preloader centering */
.orient-preloader {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    min-height: 100vh !important;
}

.preloader-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    max-width: 100% !important;
    padding: 20px !important;
}

.logo-container {
    width: 100% !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 30px auto !important;
    position: relative !important;
}

.preloader-logo {
    max-width: 200px !important;
    height: auto !important;
    margin: 0 auto !important;
    display: block !important;
}

/* Center all section headings */
.luxury-section-heading {
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 60px !important;
}

/* Center all content sections */
.section-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
}

/* Center icons in advantage cards */
.advantage-icon-wrapper {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 0 auto !important;
}

.advantage-icon {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

/* Center content in advantage cards */
.advantage-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
}

/* Center images in gallery */
.gallery-item-col {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

/* Center modal content */
.modal-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Center form elements */
.form-group {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Center buttons */
.btn-container {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 20px auto !important;
}

/* Center text content */
.text-content {
    text-align: center !important;
    margin: 0 auto !important;
    max-width: 800px !important;
}

/* Center images */
.img-center {
    display: block !important;
    margin: 0 auto !important;
}

/* Center grid items */
.grid-item {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
}

/* Complete override of preloader logo styles to ensure centering */
.orient-preloader {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

.orient-preloader .preloader-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    max-width: 100% !important;
}

.logo-container {
    width: 100% !important;
    text-align: center !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 30px auto !important;
    position: static !important;
    opacity: 1 !important;
    animation: none !important;
}

.preloader-logo {
    max-width: 200px !important;
    height: auto !important;
    margin: 0 auto !important;
    display: block !important;
    transform: none !important;
    animation: none !important;
}

/* Ensure the following elements are properly centered as well */
.yacht-animation,
.ocean-animation,
.loading-bar {
    margin-left: auto !important;
    margin-right: auto !important;
}
</style>

<div id="orient-preloader" class="orient-preloader center-flex">
    <div class="preloader-content center-flex">
        <div class="yacht-animation center-block">
            <svg class="yacht-svg" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Yacht outline path -->
                <path class="yacht-path"
                    d="M200,200 C250,180 300,170 350,170 C400,170 450,180 500,200 L550,200 C600,200 650,220 700,250 L700,250 L500,250 C450,230 400,220 350,220 C300,220 250,230 200,250 L200,200 Z"
                    fill="none" stroke="#c8a97e" stroke-width="3" />
                <!-- Yacht mast -->
                <line class="yacht-mast" x1="400" y1="170" x2="400" y2="120" stroke="#c8a97e" stroke-width="3" />
                <!-- Yacht flag -->
                <path class="yacht-flag" d="M400,120 L430,130 L400,140" fill="none" stroke="#c8a97e" stroke-width="2" />
            </svg>
        </div>
        <div class="ocean-animation center-block">
            <div class="wave wave-1"></div>
            <div class="wave wave-2"></div>
            <div class="wave wave-3"></div>
        </div>
        <div class="logo-container center-flex">
            <img src="assets/img/logo-light.png" alt="Orient Yachting" class="preloader-logo img-center">
        </div>
        <div class="loading-bar center-block">
            <div class="loading-progress"></div>
        </div>
    </div>
</div>

<!-- FullScreen Luxury Hero Slider -->
<div class="fullscreen-hero-slider" id="hero-slider">
    <div class="slider-container">
        <?php
        $banner = $VT->VeriGetir("banner", "WHERE durum=? AND (resim IS NOT NULL OR video IS NOT NULL)", array(1), "ORDER BY sirano ASC");
        if ($banner != false) {
            foreach ($banner as $index => $item) {
                $hasImage = !empty($item["resim"]);
                $hasVideo = !empty($item["video"]);
                if (!$hasImage && !$hasVideo) continue;

                $imgPath = rtrim(SITE, "/") . "/images/banner/" . $item["resim"];
                $videoPath = rtrim(SITE, "/") . "/images/banner/" . $item["video"];
                $isActive = $index === 0 ? ' active' : '';
        ?>
        <div class="slider-slide<?php echo $isActive; ?>" data-slide-index="<?php echo $index; ?>">
            <div class="slider-media">
                <?php if ($hasImage): ?>
                <div class="slider-image-wrapper">
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars(strip_tags($item["baslik"])) ?>"
                        class="slider-image">
                </div>
                <?php elseif ($hasVideo): ?>
                <div class="slider-video-wrapper">
                    <video class="slider-video" autoplay muted loop playsinline>
                        <source src="<?= $videoPath ?>" type="video/mp4">
                        Tarayıcınız video etiketini desteklemiyor.
                    </video>
                </div>
                <?php endif; ?>
                <!-- Luxury overlay with golden accents -->
                <div class="slider-luxury-overlay">
                    <div class="overlay-pattern"></div>
                    <div class="overlay-gradient"></div>
                </div>
            </div>
            <div class="slider-content">
                <div class="slider-text-container">
                    <?php if (!empty($item["baslik"])): ?>
                    <div class="slider-tagline">
                        <span class="tagline-line"></span>
                        <h2 class="slider-subtitle"><?= stripslashes($item["baslik"]) ?></h2>
                        <span class="tagline-line"></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item["aciklama"])): ?>
                    <h1 class="slider-title"><?= stripslashes($item["aciklama"]) ?></h1>
                    <div class="golden-separator">
                        <div class="separator-diamond"></div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item["url"])): ?>
                    <div class="slider-cta">
                        <a href="<?= $item["url"] ?>" class="btn-luxury btn-luxury-white">
                            <span class="btn-text">Experience Luxury</span>
                            <span class="btn-hover"></span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            }
        }
        ?>
    </div>

    <!-- Navigation Controls with refined luxury design -->
    <div class="slider-navigation">
        <button class="slider-prev" aria-label="Previous slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
        <div class="slider-dots">
            <?php
            if ($banner != false) {
                $count = count($banner);
                for ($i = 0; $i < $count; $i++) {
                    $isActive = $i === 0 ? ' active' : '';
                    echo '<button class="slider-dot' . $isActive . '" data-slide="' . $i . '" aria-label="Go to slide ' . ($i + 1) . '"></button>';
                }
            }
            ?>
        </div>
        <button class="slider-next" aria-label="Next slide">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 6L15 12L9 18" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</div>

<!-- Add custom CSS for luxury slider styling -->
<style>
/* Luxury Hero Slider Enhancements */
.fullscreen-hero-slider {
    position: relative;
    height: 100vh;
    min-height: 700px;
    overflow: hidden;
}

.slider-container {
    height: 100%;
}

.slider-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    overflow: hidden;
}

.slider-slide.active {
    opacity: 1;
    z-index: 1;
}

.slider-media {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.slider-image-wrapper,
.slider-video-wrapper {
    width: 100%;
    height: 100%;
}

.slider-image,
.slider-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Luxury overlay enhancements */
.slider-luxury-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.overlay-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('<?=SITE?>assets/img/pattern-overlay.png');
    background-size: cover;
    opacity: 0.15;
}

.overlay-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.6) 100%);
}

.slider-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 0 100px;
    text-align: center;
    color: #fff;
}

.slider-text-container {
    max-width: 1000px;
    padding: 20px;
    transform: translateY(30px);
    opacity: 0;
    animation: fadeInUp 1s ease-out 0.5s forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slider-tagline {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.tagline-line {
    width: 40px;
    height: 1px;
    background-color: #c8a97e;
    margin: 0 15px;
}

.slider-subtitle {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin: 0;
    color: #c8a97e;
}

.slider-title {
    font-family: 'Playfair Display', serif;
    font-size: 72px;
    font-weight: 700;
    line-height: 1.2;
    margin: 0 0 30px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.golden-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 30px auto;
    width: 100%;
    max-width: 200px;
    position: relative;
}

.golden-separator:before,
.golden-separator:after {
    content: '';
    height: 1px;
    flex-grow: 1;
    background: linear-gradient(90deg, transparent, rgba(200, 169, 126, 0.6), rgba(200, 169, 126, 1));
}

.golden-separator:after {
    background: linear-gradient(90deg, rgba(200, 169, 126, 1), rgba(200, 169, 126, 0.6), transparent);
}

.separator-diamond {
    width: 10px;
    height: 10px;
    background-color: #c8a97e;
    transform: rotate(45deg);
    margin: 0 15px;
}

.btn-luxury {
    display: inline-block;
    padding: 18px 35px;
    background-color: transparent;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    border: 1px solid #c8a97e;
    border-radius: 0;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
    text-decoration: none;
}

.btn-hover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #c8a97e;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    z-index: -1;
}

.btn-luxury:hover {
    color: #0a1f35;
}

.btn-luxury:hover .btn-hover {
    transform: translateY(0);
}

/* Add white button style for better visibility on slider */
.btn-luxury-white {
    background-color: transparent;
    color: #fff;
    border-color: #fff;
    border-width: 2px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

.btn-luxury-white .btn-hover {
    background-color: #fff;
}

.btn-luxury-white:hover {
    color: #0a1f35;
    border-color: #fff;
    text-shadow: none;
}

/* Navigation Controls Styling */
.slider-navigation {
    position: absolute;
    bottom: 50px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 5;
}

.slider-prev,
.slider-next {
    background: transparent;
    border: none;
    padding: 10px;
    cursor: pointer;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.slider-prev:hover,
.slider-next:hover {
    opacity: 1;
    transform: scale(1.1);
}

.slider-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 20px;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 1px solid #c8a97e;
    background-color: transparent;
    margin: 0 5px;
    padding: 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active,
.slider-dot:hover {
    background-color: #c8a97e;
    transform: scale(1.2);
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .slider-title {
        font-size: 60px;
    }

    .slider-content {
        padding: 0 50px;
    }
}

@media (max-width: 991px) {
    .slider-title {
        font-size: 48px;
    }

    .fullscreen-hero-slider {
        min-height: 600px;
    }
}

@media (max-width: 767px) {
    .slider-title {
        font-size: 36px;
    }

    .slider-subtitle {
        font-size: 14px;
        letter-spacing: 2px;
    }

    .slider-navigation {
        bottom: 30px;
    }

    .slider-content {
        padding: 0 30px;
    }

    .fullscreen-hero-slider {
        min-height: 500px;
        height: 80vh;
    }

    .btn-luxury {
        padding: 15px 30px;
        font-size: 12px;
    }
}
</style>

<!-- end .b-services-->
<section class="advantages-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="luxury-section-heading">
                    <span class="luxury-subheading">DISCOVER EXCELLENCE</span>
                    <h2 class="luxury-heading">Our Distinguished Offerings</h2>
                    <div class="luxury-heading-decoration">
                        <span class="line"></span>
                        <span class="diamond"></span>
                        <span class="line"></span>
                    </div>
                    <p class="luxury-description">
                        Experience the epitome of maritime elegance with our bespoke services,
                        where every detail is crafted to exceed your expectations
                    </p>
                </div>
            </div>
        </div>
        <div class="row advantages-row">
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="advantage-icon-wrapper center-flex">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon center-flex">
                            <i class="flaticon-rudder-1"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">EXCEPTIONAL VOYAGES</h3>
                        <div class="advantage-separator"></div>
                        <p class="advantage-text">
                            Meticulously crafted journeys that transcend ordinary exploration, where crystal-clear
                            waters and hidden harbors become the backdrop for your unforgettable moments.
                        </p>
                        <a href="#" class="advantage-link">Explore Our Routes <i
                                class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="advantage-icon-wrapper center-flex">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon center-flex">
                            <i class="flaticon-snorkel"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">PERSONALIZED REFINEMENT</h3>
                        <div class="advantage-separator"></div>
                        <p class="advantage-text">
                            From gourmet delights to bespoke routes, every detail is tailored to your unique
                            preferences, creating an experience as distinctive as your signature.
                        </p>
                        <a href="#" class="advantage-link">Custom Experiences <i
                                class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="advantage-icon-wrapper center-flex">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon center-flex">
                            <i class="flaticon-sailor"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">ELEGANT EXCELLENCE</h3>
                        <div class="advantage-separator"></div>
                        <p class="advantage-text">
                            Our attentive crew anticipates your desires before you realize them, delivering flawless
                            elegance through the art of invisible service.
                        </p>
                        <a href="#" class="advantage-link">Meet Our Crew <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add custom CSS for advantages section -->
<style>
/* Luxury Section Headings - Global Style */
.luxury-section-heading {
    text-align: center;
    margin-bottom: 60px;
    position: relative;
}

.luxury-subheading {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 3px;
    color: #c8a97e;
    margin-bottom: 15px;
    display: block;
}

.luxury-heading {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    font-weight: 700;
    color: #0a1f35;
    margin-bottom: 20px;
    line-height: 1.2;
}

.luxury-heading-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 25px auto;
}

.luxury-heading-decoration .line {
    height: 1px;
    width: 40px;
    background: linear-gradient(90deg, rgba(200, 169, 126, 0.3), rgba(200, 169, 126, 1));
}

.luxury-heading-decoration .line:last-child {
    background: linear-gradient(90deg, rgba(200, 169, 126, 1), rgba(200, 169, 126, 0.3));
}

.luxury-heading-decoration .diamond {
    width: 8px;
    height: 8px;
    background-color: #c8a97e;
    transform: rotate(45deg);
    margin: 0 15px;
}

.luxury-description {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 400;
    color: #666;
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.8;
}

/* Advantages Section Specific Styles */
.advantages-section {
    padding: 100px 0;
    background-color: #f9f9f9;
    position: relative;
    overflow: hidden;
}

.advantages-section:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(200, 169, 126, 0.3), transparent);
}

.advantages-row {
    position: relative;
    z-index: 2;
}

.advantage-card {
    background-color: #fff;
    padding: 50px 30px;
    border-radius: 5px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.4s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.advantage-card:before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #0a1f35, #c8a97e);
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.4s ease;
}

.advantage-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.advantage-card:hover:before {
    transform: scaleX(1);
    transform-origin: left;
}

/* Enhanced Icon Styling for Perfect Alignment */
.advantage-icon-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.advantage-icon-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    width: 100%;
    height: 100%;
    background-color: rgba(200, 169, 126, 0.1);
    border-radius: 50%;
    transform: scale(0.8);
    transition: all 0.4s ease;
}

.advantage-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: #c8a97e;
    font-size: 40px;
    transition: all 0.4s ease;
    text-align: center;
    z-index: 2;
}

.advantage-icon i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    margin: 0;
    padding: 0;
    display: block;
    line-height: 1;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.advantage-card:hover .advantage-icon-bg {
    transform: scale(1);
    background-color: #0a1f35;
}

.advantage-card:hover .advantage-icon {
    color: #c8a97e;
}

.advantage-content {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.advantage-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: #0a1f35;
    margin-bottom: 20px;
    letter-spacing: 1px;
}

.advantage-separator {
    width: 30px;
    height: 2px;
    background-color: #c8a97e;
    margin: 0 auto 20px;
    transition: all 0.4s ease;
}

.advantage-card:hover .advantage-separator {
    width: 50px;
}

.advantage-text {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    color: #666;
    line-height: 1.7;
    margin-bottom: 25px;
}

.advantage-link {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #0a1f35;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
}

.advantage-link:after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 1px;
    background-color: #c8a97e;
    transition: all 0.3s ease;
}

.advantage-link i {
    margin-left: 5px;
    transition: all 0.3s ease;
}

.advantage-link:hover {
    color: #c8a97e;
}

.advantage-link:hover:after {
    width: 100%;
}

.advantage-link:hover i {
    transform: translateX(5px);
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .advantages-section {
        padding: 80px 0;
    }

    .luxury-heading {
        font-size: 36px;
    }

    .advantage-card {
        padding: 40px 20px;
        margin-bottom: 30px;
    }
}

@media (max-width: 767px) {
    .advantages-section {
        padding: 60px 0;
    }

    .luxury-heading {
        font-size: 30px;
    }

    .luxury-subheading {
        font-size: 12px;
    }

    .luxury-description {
        font-size: 14px;
    }
}
</style>

<section class="section-goods premium-fleet-section">
    <div class="section-default section-goods__inner bg-dark">
        <div class="ui-decor ui-decor_mirror ui-decor_center"></div>
        <div class="container">
            <div class="luxury-section-heading light-theme">
                <span class="luxury-subheading">MARITIME EXCELLENCE</span>
                <h2 class="luxury-heading">Exclusive Yacht Collection</h2>
                <div class="luxury-heading-decoration">
                    <span class="line"></span>
                    <span class="diamond"></span>
                    <span class="line"></span>
                </div>
                <p class="luxury-description">
                    Discover our curated selection of prestigious vessels, each embodying the perfect balance of
                    performance, comfort, and sophisticated design for the ultimate maritime experience.
                </p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="luxury-yacht-grid">
            <div class="row">
                <?php
                $yachtList = $VT->VeriGetir("yachts", "WHERE durum=?", array(1), "ORDER BY sirano ASC limit 8");
                if ($yachtList != false) {
                    foreach ($yachtList as $yacht) {
                        $imagePath = SITE . "images/yachts/" . $yacht["resim"];
                ?>
                <div class="col-xl-3 col-md-6">
                    <div class="luxury-yacht-card" data-aos="fade-up">
                        <div class="yacht-card-image">
                            <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>">
                                <img src="<?=$imagePath?>" alt="<?=stripslashes($yacht["baslik"])?>" />
                                <div class="yacht-overlay">
                                    <span class="explore-btn">Explore</span>
                                </div>
                            </a>
                            <div class="yacht-price">
                                <span>$<?=number_format($yacht["price_per_day"], 0)?></span>
                                <small>Per Day</small>
                            </div>
                        </div>
                        <div class="yacht-card-content">
                            <h3 class="yacht-title">
                                <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>"><?=stripslashes($yacht["baslik"])?></a>
                            </h3>
                            <div class="yacht-meta">
                                <div class="yacht-meta-item">
                                    <i class="fas fa-ruler-horizontal"></i>
                                    <span><?=stripslashes($yacht["length_m"])?> Meters</span>
                                </div>
                                <div class="yacht-meta-item">
                                    <i class="fas fa-bed"></i>
                                    <span><?=stripslashes($yacht["cabin_count"])?> Cabins</span>
                                </div>
                                <div class="yacht-meta-item">
                                    <i class="fas fa-users"></i>
                                    <span><?=stripslashes($yacht["capacity"])?> Guests</span>
                                </div>
                                <div class="yacht-meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span><?=stripslashes($yacht["crew"])?> Crew</span>
                                </div>
                            </div>
                            <div class="yacht-features">
                                <?php
                                    // Yat özelliklerini çek
                                    $features = $VT->VeriGetir("yacht_features_pivot", 
                                        "INNER JOIN yacht_features ON yacht_features_pivot.feature_id = yacht_features.ID 
                                         WHERE yacht_features_pivot.yacht_id=? AND yacht_features.durum=?", 
                                        array($yacht["ID"], 1), "ORDER BY yacht_features.baslik ASC", 5);
                                    
                                    if($features != false) {
                                        foreach($features as $index => $feature) {
                                        if($index <= 2) {
                                            echo '<span class="yacht-feature-tag">' . stripslashes($feature["baslik"]) . '</span>';
                                        } elseif($index == 3 && count($features) > 3) {
                                            echo '<span class="yacht-feature-more">+' . (count($features) - 3) . '</span>';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                            </div>
                            <div class="yacht-action">
                                <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>" class="btn-luxury-small">
                                    View Details <i class="fas fa-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    </div><br>
                </div> <br>
                <?php
                    }
                }
                ?>
            </div>
            <div class="text-center mt-5">
                <a href="<?=SITE?>yacht-listing" class="btn-luxury btn-luxury-primary">
                    <span class="btn-text">View Our Complete Fleet</span>
                    <span class="btn-hover"></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Add custom CSS for yacht collection section -->
<style>
/* Yacht Collection Section Styling */
.premium-fleet-section {
    position: relative;
    padding-bottom: 100px;
}

.premium-fleet-section .section-default {
    padding: 100px 0 70px;
}

.bg-dark {
    background-color: #0a1f35;
}

/* Light theme for section headings on dark backgrounds */
.luxury-section-heading.light-theme .luxury-subheading,
.luxury-section-heading.light-theme .luxury-heading,
.luxury-section-heading.light-theme .luxury-description {
    color: #fff;
}

.luxury-section-heading.light-theme .luxury-heading {
    color: #fff;
}

.luxury-section-heading.light-theme .luxury-description {
    color: rgba(255, 255, 255, 0.7);
}

.luxury-section-heading.light-theme .luxury-heading-decoration .diamond {
    background-color: #c8a97e;
}

.luxury-section-heading.light-theme .luxury-heading-decoration .line {
    background: linear-gradient(90deg, rgba(200, 169, 126, 0.3), rgba(200, 169, 126, 1));
}

.luxury-section-heading.light-theme .luxury-heading-decoration .line:last-child {
    background: linear-gradient(90deg, rgba(200, 169, 126, 1), rgba(200, 169, 126, 0.3));
}

/* Yacht Card Styling */
.luxury-yacht-grid {
    padding-top: 70px;
}

.luxury-yacht-card {
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
    transition: all 0.4s ease;
    position: relative;
}

.luxury-yacht-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.yacht-card-image {
    position: relative;
    overflow: hidden;
}

.yacht-card-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: all 0.7s ease;
}

.luxury-yacht-card:hover .yacht-card-image img {
    transform: scale(1.08);
}

.yacht-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(10, 31, 53, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.luxury-yacht-card:hover .yacht-overlay {
    opacity: 1;
}

.explore-btn {
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 12px 30px;
    border: 2px solid #c8a97e;
    background-color: rgba(200, 169, 126, 0.2);
    transition: all 0.3s ease;
    transform: translateY(20px);
    opacity: 0;
}

.luxury-yacht-card:hover .explore-btn {
    transform: translateY(0);
    opacity: 1;
}

.explore-btn:hover {
    background-color: #c8a97e;
    border-color: #c8a97e;
}

.yacht-price {
    position: absolute;
    top: 20px;
    right: 0;
    background: linear-gradient(135deg, #0a1f35, rgba(10, 31, 53, 0.8));
    color: #fff;
    padding: 10px 15px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-left: 3px solid #c8a97e;
}

.yacht-price small {
    font-size: 12px;
    opacity: 0.7;
    margin-top: 2px;
}

.yacht-card-content {
    padding: 25px 20px;
}

.yacht-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.3;
}

.yacht-title a {
    color: #0a1f35;
    text-decoration: none;
    transition: all 0.3s ease;
}

.yacht-title a:hover {
    color: #c8a97e;
}

.yacht-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(200, 169, 126, 0.1);
}

.yacht-meta-item {
    display: flex;
    align-items: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    color: #666;
    padding: 8px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 6px;
    transition: all 0.3s ease;
}

.yacht-meta-item:hover {
    background: rgba(200, 169, 126, 0.1);
    transform: translateY(-2px);
}

.yacht-meta-item i {
    color: #c8a97e;
    margin-right: 10px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.yacht-meta-item span {
    font-weight: 500;
}

@media (max-width: 1200px) {
    .yacht-meta {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .yacht-meta {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .yacht-meta-item {
        font-size: 12px;
    }
}

.yacht-features {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.yacht-feature-tag {
    background-color: rgba(200, 169, 126, 0.1);
    color: #0a1f35;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    padding: 5px 10px;
    margin-right: 8px;
    margin-bottom: 8px;
    border-radius: 3px;
    border: 1px solid rgba(200, 169, 126, 0.2);
    transition: all 0.3s ease;
}

.yacht-feature-tag:hover {
    background-color: #c8a97e;
    color: #fff;
    border-color: #c8a97e;
}

.yacht-feature-more {
    background-color: rgba(10, 31, 53, 0.1);
    color: #0a1f35;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    padding: 5px 10px;
    margin-right: 8px;
    margin-bottom: 8px;
    border-radius: 3px;
    border: 1px solid rgba(10, 31, 53, 0.1);
}

.yacht-action {
    text-align: right;
}

.btn-luxury-small {
    display: inline-block;
    padding: 10px 20px;
    background-color: transparent;
    color: #0a1f35;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 1px solid #c8a97e;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
    text-decoration: none;
}

.btn-luxury-small:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #c8a97e;
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.3s ease;
    z-index: -1;
}

.btn-luxury-small:hover {
    color: #fff;
}

.btn-luxury-small:hover:before {
    transform: scaleX(1);
    transform-origin: left;
}

.btn-luxury-small i {
    margin-left: 5px;
    transition: all 0.3s ease;
}

.btn-luxury-small:hover i {
    transform: translateX(5px);
}

/* Luxury Button Primary Style */
.btn-luxury {
    display: inline-block;
    padding: 15px 35px;
    background-color: transparent;
    color: #fffff;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    border: 1px solid #c8a97e;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
    text-decoration: none;
}

.btn-luxury .btn-hover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #c8a97e;
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    z-index: -1;
}

.btn-luxury:hover {
    color: #fff;
}

.btn-luxury:hover .btn-hover {
    transform: scaleX(1);
    transform-origin: left;
}

.btn-luxury-primary {
    background-color: #0a1f35;
    color: #fff;
    border-color: #0a1f35;
}

.btn-luxury-primary .btn-hover {
    background-color: #c8a97e;
}

.btn-luxury-primary:hover {
    border-color: #c8a97e;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .premium-fleet-section .section-default {
        padding: 80px 0 50px;
    }

    .luxury-yacht-grid {
        padding-top: 50px;
    }
}

@media (max-width: 991px) {
    .premium-fleet-section {
        padding-bottom: 70px;
    }

    .yacht-card-image img {
        height: 220px;
    }
}

@media (max-width: 767px) {
    .premium-fleet-section .section-default {
        padding: 60px 0 30px;
    }

    .premium-fleet-section {
        padding-bottom: 50px;
    }

    .yacht-title {
        font-size: 18px;
    }

    .yacht-meta-item {
        font-size: 12px;
    }
}
</style>

<!-- Essential Charter Information Section -->
<section class="essential-info-section">
    <div class="container">
        <div class="luxury-section-heading">
            <span class="luxury-subheading">CHARTER KNOWLEDGE</span>
            <h2 class="luxury-heading">Essential Information</h2>
            <div class="luxury-heading-decoration">
                <span class="line"></span>
                <span class="diamond"></span>
                <span class="line"></span>
            </div>
            <p class="luxury-description">
                Everything you need to know about your luxury yacht experience, carefully curated to ensure your voyage
                exceeds all expectations
            </p>
        </div>

        <div class="row luxury-info-cards">
            <!-- Booking Process -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="info-title">Booking Process</h4>
                    <ul class="info-list">
                        <li><span class="bullet-accent"></span>Inquiry submission</li>
                        <li><span class="bullet-accent"></span>Customized itinerary</li>
                        <li><span class="bullet-accent"></span>30% reservation deposit</li>
                        <li><span class="bullet-accent"></span>Final payment 30 days prior</li>
                        <li><span class="bullet-accent"></span>Welcome package delivery</li>
                    </ul>
                    <a href="#" class="info-link">Learn More <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>

            <!-- Popular Destinations -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h4 class="info-title">Popular Destinations</h4>
                    <ul class="info-list">
                        <li><span class="bullet-accent"></span>Turkish Riviera</li>
                        <li><span class="bullet-accent"></span>Greek Islands</li>
                        <li><span class="bullet-accent"></span>Adriatic Coast</li>
                        <li><span class="bullet-accent"></span>French Riviera</li>
                        <li><span class="bullet-accent"></span>Amalfi Coast, Italy</li>
                    </ul>
                    <a href="#" class="info-link">Explore Destinations <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>

            <!-- Premium Services -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h4 class="info-title">Premium Services</h4>
                    <ul class="info-list">
                        <li><span class="bullet-accent"></span>Professional crew</li>
                        <li><span class="bullet-accent"></span>Gourmet catering</li>
                        <li><span class="bullet-accent"></span>Water sports equipment</li>
                        <li><span class="bullet-accent"></span>Private event planning</li>
                        <li><span class="bullet-accent"></span>Concierge assistance</li>
                    </ul>
                    <a href="#" class="info-link">View Services <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>

            <!-- Requirements -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="info-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="info-title">Requirements</h4>
                    <ul class="info-list">
                        <li><span class="bullet-accent"></span>Valid passport/ID</li>
                        <li><span class="bullet-accent"></span>Charter agreement</li>
                        <li><span class="bullet-accent"></span>Insurance coverage</li>
                        <li><span class="bullet-accent"></span>Health declarations</li>
                        <li><span class="bullet-accent"></span>Special requests in advance</li>
                    </ul>
                    <a href="#" class="info-link">Full Requirements <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Charter Policy Highlights -->
        <div class="charter-policy">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="policy-content">
                        <span class="luxury-subheading">OUR PROMISE</span>
                        <h3 class="policy-title">Charter Policy</h3>
                        <div class="policy-divider"></div>
                        <p class="policy-text">We pride ourselves on transparent and client-friendly policies to ensure
                            your yachting experience exceeds expectations.</p>

                        <div class="policy-features">
                            <div class="policy-feature">
                                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                                <div class="feature-text">
                                    <h5>Flexible Cancellation</h5>
                                    <p>Full refund available up to 60 days before departure</p>
                                </div>
                            </div>

                            <div class="policy-feature">
                                <div class="feature-icon"><i class="fas fa-user-clock"></i></div>
                                <div class="feature-text">
                                    <h5>24/7 Support</h5>
                                    <p>Round-the-clock assistance before and during your charter</p>
                                </div>
                            </div>

                            <div class="policy-feature">
                                <div class="feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                <div class="feature-text">
                                    <h5>Satisfaction Guarantee</h5>
                                    <p>Our commitment to excellence and your complete satisfaction</p>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="btn-luxury">
                            <span class="btn-text">Complete Policy Details</span>
                            <span class="btn-hover"></span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="policy-image">
                        <img src="assets/img/yacht-captain-hand-on-yacht-steering-wheel-4SBVP5K.jpg"
                            alt="Luxury Yacht Charter Policy" class="img-fluid">
                        <div class="experience-badge">
                            <div class="badge-content">
                                <span class="years">15</span>
                                <span class="text">Years of<br>Excellence</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-progress luxury-deal-section">
    <div class="container">
        <div class="luxury-deal-wrapper">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div class="deal-image-wrapper">
                        <img src="assets/img/deal-weak.jpg" alt="Exclusive Yacht Deal" class="deal-image">
                        <div class="deal-badge">LIMITED OFFER</div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div class="deal-content">
                        <h5 class="deal-subtitle">Premium Selection</h5>
                        <h3 class="deal-title">DayDream Yacht Experience <span class="deal-price">$800 / Hour</span>
                        </h3>
                        <div class="deal-divider"></div>
                        <div class="deal-details">
                            <div class="deal-spec">
                                <span class="spec-label">Builder/Model:</span>
                                <span class="spec-value">French Waves</span>
                            </div>
                            <div class="deal-spec">
                                <span class="spec-label">Type/Year:</span>
                                <span class="spec-value">Luxury Yacht 2021</span>
                            </div>
                            <div class="deal-spec">
                                <span class="spec-label">Length:</span>
                                <span class="spec-value">105 FT / 32 M</span>
                            </div>
                            <div class="deal-spec">
                                <span class="spec-label">Capacity:</span>
                                <span class="spec-value">Up to 20 Guests</span>
                            </div>
                            <div class="deal-spec">
                                <span class="spec-label">Crew:</span>
                                <span class="spec-value">6 Professional Staff</span>
                            </div>
                        </div>
                        <div class="deal-cta">
                            <a href="#" class="btn btn-primary deal-btn">Reserve Now</a>
                            <div class="deal-contact">
                                <i class="fas fa-phone-alt"></i>
                                <div class="contact-info">
                                    <span>For Priority Booking</span>
                                    <strong>+1 755 302 8549</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Counters -->
        <div class="row luxury-counters">
            <!-- Counter #1 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="luxury-counter">
                    <div class="counter-icon">
                        <i class="flaticon-sailor"></i>
                    </div>
                    <div class="counter-number" data-count="240">
                        <span class="counter-value">240</span>
                        <span class="counter-plus">+</span>
                    </div>
                    <div class="counter-label">Destinations Worldwide</div>
                </div>
            </div>
            <!-- Counter #2 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="luxury-counter">
                    <div class="counter-icon">
                        <i class="flaticon-snorkel"></i>
                    </div>
                    <div class="counter-number" data-count="980">
                        <span class="counter-value">980</span>
                        <span class="counter-plus">+</span>
                    </div>
                    <div class="counter-label">Satisfied Clients</div>
                </div>
            </div>
            <!-- Counter #3 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="luxury-counter">
                    <div class="counter-icon">
                        <i class="flaticon-island-1"></i>
                    </div>
                    <div class="counter-number" data-count="175">
                        <span class="counter-value">175</span>
                        <span class="counter-plus">+</span>
                    </div>
                    <div class="counter-label">Premium Yachts</div>
                </div>
            </div>
            <!-- Counter #4 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="luxury-counter">
                    <div class="counter-icon">
                        <i class="flaticon-chef-hat"></i>
                    </div>
                    <div class="counter-number" data-count="630">
                        <span class="counter-value">630</span>
                        <span class="counter-plus">+</span>
                    </div>
                    <div class="counter-label">Gourmet Experiences</div>
                </div>
            </div>
        </div>
        <!-- End of Counters -->
    </div>
</section>
<section class="section-goods-offers">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="text-left offers-left">
                <h2 class="ui-title">Premium Boat<br>
                    Rental Services</h2> <img src="assets/img/decore02.png" alt="photo">
                <div class="offers-left-text">
                    <p>Eorem ipsum dolor amet consectetur sed adipisicing elit sed eiusmod tempor et dolore magna
                        aliqua minim veniam, quis nostrud exercitation aliquip ex ea consequat duis aute irure
                        dolorin.</p>
                </div>

                <a class="btn btn-primary" href="#">view more</a>

            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-8">
            <div class="b-offers-slider ui-slider_arr-prim js-slider"
                data-slick="{&quot;slidesToShow&quot;: 3, &quot;slidesToScroll&quot;: 1, &quot;dots&quot;: false, &quot;arrows&quot;: true, &quot;autoplay&quot;: true,   &quot;responsive&quot;: [{&quot;breakpoint&quot;: 992, &quot;settings&quot;: {&quot;slidesToShow&quot;: 1, &quot;slidesToScroll&quot;: 1}}]}">
                <div class="b-offers-nevica">
                    <div class="b-offers-nevica-photo"> <img src="assets/img/offers001.jpg" alt="photo"> </div>
                    <h6>Water Sports Boat</h6>
                    <div class="decore01"></div>
                    <p>Adipisicing eiusmod tempor incidids labore dolore magna aliqa ust enim ad minim veniams
                        quis nostrs sed citation ullam coy laboris nisit.</p>
                </div>
                <!-- end .b-offers-->
                <div class="b-offers-nevica">
                    <div class="b-offers-nevica-photo"> <img src="assets/img/offers002.jpg" alt="photo"> </div>
                    <h6>Family Gathering</h6>
                    <div class="decore01"></div>
                    <p>Adipisicing eiusmod tempor incidids labore dolore magna aliqa ust enim ad minim veniams
                        quis nostrs sed citation ullam coy laboris nisit.</p>
                </div>
                <!-- end .b-offers-->
                <div class="b-offers-nevica">
                    <div class="b-offers-nevica-photo"> <img src="assets/img/offers003.jpg" alt="photo"> </div>
                    <h6>Corporate Events</h6>
                    <div class="decore01"></div>
                    <p>Adipisicing eiusmod tempor incidids labore dolore magna aliqa ust enim ad minim veniams
                        quis nostrs sed citation ullam coy laboris nisit.</p>
                </div>
                <!-- end .b-offers-->
                <div class="b-offers-nevica">
                    <div class="b-offers-nevica-photo"> <img src="assets/img/offers004.jpg" alt="photo"> </div>
                    <h6>Celebrations events</h6>
                    <div class="decore01"></div>
                    <p>Adipisicing eiusmod tempor incidids labore dolore magna aliqa ust enim ad minim veniams
                        quis nostrs sed citation ullam coy laboris nisit.</p>
                </div>
                <!-- end .b-offers-->
            </div>
        </div>
    </div>
</section>
<section class="section-video section-default section-goods__inner bg-dark ">

    <div class="ui-decor ui-decor_mirror ui-decor_center"></div>


    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-10">
                <div class="video-info">
                    <p><img src="assets/img/decore02.png" alt="decore">Give us a call or drop an email, We
                        endeavor to answer within 24 hours</p>
                    <h4>We've Exclusive Boats With Charter Offers</h4>
                    <h5>LET'S PLAN YOUR NEXT TOUR!</h5>
                    <ul>
                        <li><i class="fas fa-phone-square"></i> Call Us Today: +1 755 302 8549</li>
                        <li><i class="fas fa-envelope-square"></i> Email: <a
                                href="mailto:name@rmy-domain.com">support@my-domain.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-2 col-lg-2">
                <a class="video-btn venobox ternary-video-btn-style vbox-item popup-youtube" data-vbtype="video"
                    data-autoplay="true" href="https://www.youtube.com/watch?v=JAIvWg4iQHo"><i class="fa fa-play"></i>
                    <div class="pulsing-bg"></div>
                    <span>Watch A Tour</span>
                </a>


            </div>
        </div>
    </div>
</section>

<!-- Video Modal for Gallery Videos -->
<div class="video-modal">
    <div class="video-modal-container">
        <div class="video-modal-close">&times;</div>
        <video controls class="video-player">
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<!-- Manual Lightbox for Images -->
<div class="manual-lightbox">
    <div class="lightbox-container">
        <div class="lightbox-close">&times;</div>
        <img class="lightbox-image" src="" alt="">
        <div class="lightbox-caption"></div>
        <button class="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="lightbox-next"><i class="fas fa-chevron-right"></i></button>
    </div>
</div>

<!-- Add CSS for gallery section to fix visibility issues -->
<style>
/* Gallery Section Styling */
.section-gallery {
    padding: 80px 0;
    background-color: #f9f9f9;
    position: relative;
}

.gallery-with-spacing {
    margin-top: 60px;
}

.gallery-row {
    margin-bottom: 30px;
}

.gallery-item-col {
    padding: 10px;
}

.ui-gallery__img {
    display: block;
    overflow: hidden;
    position: relative;
    border-radius: 5px;
    height: 300px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    background-color: #eee;
    transition: all 0.3s ease;
}

.ui-gallery__img:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.ui-gallery__img img.img-scale {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
    display: block;
}

.ui-gallery__img:hover img.img-scale {
    transform: scale(1.08);
}

.gallery-item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(10, 31, 53, 0.2);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ui-gallery__img:hover .gallery-item-overlay {
    opacity: 1;
}

.gallery-frame {
    position: absolute;
    top: 10px;
    left: 10px;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    pointer-events: none;
    stroke: #c8a97e;
    stroke-width: 2px;
    fill: none;
}

.frame-line {
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    transition: stroke-dashoffset 0.6s ease;
}

.ui-gallery__img:hover .frame-line {
    stroke-dashoffset: 0;
}

.gallery-zoom {
    background-color: #c8a97e;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    transform: scale(0);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.ui-gallery__img:hover .gallery-zoom {
    transform: scale(1);
}

/* Video thumbnail styling */
.video-thumbnail-container {
    background-color: #0a1f35;
    position: relative;
    overflow: hidden;
}

.video-thumbnail {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-poster {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c8a97e;
    font-size: 30px;
    background-color: #0a1f35;
}

.default-video-bg {
    background-color: #102a43;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background-color: rgba(200, 169, 126, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    transition: all 0.3s ease;
}

.video-thumbnail-container:hover .play-button {
    background-color: #c8a97e;
    transform: translate(-50%, -50%) scale(1.1);
}

/* Lightbox and Modal Styling */
.manual-lightbox,
.video-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.manual-lightbox.active,
.video-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
}

.lightbox-container,
.video-modal-container {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.lightbox-close,
.video-modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    color: #fff;
    font-size: 30px;
    cursor: pointer;
    z-index: 1;
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    display: block;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
}

.video-player {
    max-width: 100%;
    max-height: 80vh;
    display: block;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
}

.lightbox-caption {
    color: #fff;
    text-align: center;
    padding: 15px 0;
    font-family: 'Montserrat', sans-serif;
}

.lightbox-prev,
.lightbox-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: #fff;
    border: none;
    width: 50px;
    height: 50px;
    font-size: 20px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.lightbox-prev {
    left: -80px;
}

.lightbox-next {
    right: -80px;
}

.lightbox-prev:hover,
.lightbox-next:hover {
    background-color: rgba(200, 169, 126, 0.8);
}

@media (max-width: 991px) {
    .ui-gallery__img {
        height: 250px;
    }

    .gallery-row {
        margin-bottom: 20px;
    }
}

@media (max-width: 767px) {
    .section-gallery {
        padding: 60px 0;
    }

    .ui-gallery__img {
        height: 200px;
    }

    .gallery-item-col {
        padding: 5px;
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
    }
}
</style>

<!-- Add JavaScript for gallery functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Video gallery functionality
    const videoThumbnails = document.querySelectorAll('.video-thumbnail-container');
    const videoModal = document.querySelector('.video-modal');
    const videoPlayer = document.querySelector('.video-player');
    const videoModalClose = document.querySelector('.video-modal-close');

    videoThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const videoUrl = this.getAttribute('data-video');
            videoPlayer.querySelector('source').setAttribute('src', videoUrl);
            videoPlayer.load();
            videoModal.classList.add('active');
        });
    });

    videoModalClose.addEventListener('click', function() {
        videoModal.classList.remove('active');
        videoPlayer.pause();
    });

    // Manual lightbox functionality for images
    const galleryImages = document.querySelectorAll('.js-zoom-gallery__item');
    const lightbox = document.querySelector('.manual-lightbox');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxCaption = document.querySelector('.lightbox-caption');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    let currentImageIndex = 0;

    galleryImages.forEach((image, index) => {
        image.addEventListener('click', function(e) {
            e.preventDefault();
            currentImageIndex = index;
            openLightbox(this.getAttribute('href'), this.querySelector('img').getAttribute(
                'alt'));
        });
    });

    function openLightbox(src, caption) {
        lightboxImage.setAttribute('src', src);
        lightboxCaption.textContent = caption;
        lightbox.classList.add('active');
    }

    lightboxClose.addEventListener('click', function() {
        lightbox.classList.remove('active');
    });

    lightboxPrev.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        const prevImage = galleryImages[currentImageIndex];
        openLightbox(prevImage.getAttribute('href'), prevImage.querySelector('img').getAttribute(
        'alt'));
    });

    lightboxNext.addEventListener('click', function() {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        const nextImage = galleryImages[currentImageIndex];
        openLightbox(nextImage.getAttribute('href'), nextImage.querySelector('img').getAttribute(
        'alt'));
    });

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            lightbox.classList.remove('active');
            videoModal.classList.remove('active');
            videoPlayer.pause();
        }
    });
});
</script>

<section class="section-form">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="text-left">
                    <h2 class="ui-title">Booking Form</h2>
                    <p>Dolore magna aliqua enim ad minim veniam, quis nostrudreprehenderits
                        <br> dolore fugiat nulla pariatur lorem ipsum dolor sit amet.
                    </p> <img src="assets/img/decore03.png" alt="photo">
                    <form action="#">

                        <div class="row row-form-b">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Subject">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="6" placeholder="Message"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="b-main-filter__btn btn btn-secondary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="text-left title-padding-m-top">
                    <h2 class="ui-title">Boat Rental FAQ's</h2>
                    <p>Dolore magna aliqua enim ad minim veniam, quis nostrudreprehenderits
                        <br> dolore fugiat nulla pariatur lorem ipsum dolor sit amet.
                    </p> <img src="assets/img/decore03.png" alt="photo">
                </div>

                <div class="ui-accordion accordion" id="accordion-1">
                    <div class="card">
                        <div class="card-header" id="heading1">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse"
                                    data-target="#collapse1" aria-expanded="true" aria-controls="collapse1"><span
                                        class="ui-accordion__number">01</span>How to book a yacht/boat from
                                    Nevica?<i class="ic fas fa-chevron-down"></i></button>
                            </h3>
                        </div>
                        <div class="collapse show" id="collapse1" data-aria-labelledby="heading1"
                            data-parent="#accordion-1">
                            <div class="card-body">Quis nostrud exercitate laboridy aliquip duis irure sed dolor
                                ipsum excpture fugiat estan veniam, quis nostrud exercitation ullamco laboris nisi
                                ut aliquip ex velit esse cillum dolore eu fugiat nulla pariatur.</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading2">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse"
                                    data-target="#collapse2" aria-expanded="false" aria-controls="collapse2"><span
                                        class="ui-accordion__number">02</span>What are the safety precautions
                                    maintained by you?<i class="ic fas fa-chevron-down"></i></button>
                            </h3>
                        </div>
                        <div class="collapse" id="collapse2" data-aria-labelledby="heading2" data-parent="#accordion-1">
                            <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life
                                accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat
                                skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf
                                moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes
                                anderson</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading3">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse"
                                    data-target="#collapse3" aria-expanded="false" aria-controls="collapse3"><span
                                        class="ui-accordion__number">03</span>What if the weather gets
                                    unfavourable for boating?<i class="ic fas fa-chevron-down"></i></button>
                            </h3>
                        </div>
                        <div class="collapse" id="collapse3" data-aria-labelledby="heading3" data-parent="#accordion-1">
                            <div class="card-bodyFood">truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon
                                tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda
                                shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson Anim
                                pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson
                                ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading4">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse"
                                    data-target="#collapse4" aria-expanded="false" aria-controls="collapse4"><span
                                        class="ui-accordion__number">04</span>Can I bring my own food or drinking
                                    water?<i class="ic fas fa-chevron-down"></i></button>
                            </h3>
                        </div>
                        <div class="collapse" id="collapse4" data-aria-labelledby="heading4" data-parent="#accordion-1">
                            <div class="card-body">Nliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
                                brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,
                                sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch
                                et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson</div>
                        </div>
                    </div>

                </div>
                <!-- end .accordion-->


            </div>
        </div>
    </div>
</section>




<style>
.luxury-partners {
    padding: 100px 0;
    background: linear-gradient(135deg, #0a1f35 0%, #1c3f5f 100%);
    position: relative;
    overflow: hidden;
}

.wave-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    background-size: cover;
    background-position: center;
    opacity: 0.1;
    pointer-events: none;
}

.luxury-section-heading {
    margin-bottom: 60px;
}

.luxury-subheading {
    display: block;
    color: #c8a97e;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 3px;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.luxury-heading {
    color: #ffffff;
    font-size: 42px;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 20px;
    font-family: 'Playfair Display', serif;
}

.luxury-heading-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.line {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
}

.diamond {
    width: 8px;
    height: 8px;
    background: #c8a97e;
    transform: rotate(45deg);
}

.brand-two__single {
    padding: 20px;
}

.logo-frame {
    position: relative;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    padding: 30px;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease;
    overflow: hidden;
    border: 1px solid rgba(200, 169, 126, 0.1);
}

.logo-frame:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 1px solid rgba(200, 169, 126, 0.1);
    border-radius: 8px;
    transition: all 0.4s ease;
}

.logo-frame img {
    max-width: 100%;
    max-height: 60px;
    transition: all 0.4s ease;
    filter: grayscale(100%) brightness(200%);
    opacity: 0.7;
    object-fit: contain;
}

.logo-frame:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-5px);
    border-color: rgba(200, 169, 126, 0.3);
}

.logo-frame:hover:before {
    border-color: rgba(200, 169, 126, 0.3);
}

.logo-frame:hover img {
    filter: grayscale(0%) brightness(100%);
    opacity: 1;
    transform: scale(1.05);
}

.partner-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 15px;
    background: linear-gradient(to top, rgba(10, 31, 53, 0.9), transparent);
    transform: translateY(100%);
    transition: all 0.4s ease;
    text-align: center;
}

.logo-frame:hover .partner-overlay {
    transform: translateY(0);
}

.partner-name {
    color: #fff;
    font-family: 'Playfair Display', serif;
    font-size: 13px;
    font-style: italic;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Owl Carousel Özel Stilleri */
.brand-two__carousel .owl-stage {
    display: flex;
    align-items: center;
}

.brand-two__carousel .owl-item {
    transition: all 0.4s ease;
}

.brand-two__carousel .owl-item.active {
    opacity: 1;
}

.brand-two__carousel .owl-item:not(.active) {
    opacity: 0.5;
}

@media (max-width: 991px) {
    .luxury-partners {
        padding: 80px 0;
    }
    
    .luxury-heading {
        font-size: 36px;
    }
    
    .logo-frame {
        min-height: 100px;
        padding: 20px;
    }
    
    .logo-frame img {
        max-height: 50px;
    }
}

@media (max-width: 767px) {
    .luxury-partners {
        padding: 60px 0;
    }
    
    .luxury-heading {
        font-size: 30px;
    }
    
    .logo-frame {
        min-height: 90px;
        padding: 15px;
    }
    
    .logo-frame img {
        max-height: 40px;
    }
    
    .partner-overlay {
        padding: 10px;
    }
    
    .partner-name {
        font-size: 12px;
    }
}
</style>

<script>
$(document).ready(function(){
    var $carousel = $('.brand-two__carousel');
    
    // Carousel'ı başlat
    $carousel.owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplaySpeed: 1000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 2,
                margin: 15
            },
            576: {
                items: 3,
                margin: 20
            },
            768: {
                items: 4,
                margin: 25
            },
            992: {
                items: 5,
                margin: 30
            }
        }
    });
    
    // Performans optimizasyonu için lazy loading
    $carousel.find('img').each(function() {
        $(this).attr('loading', 'lazy');
    });
    
    // Touch cihazlar için swipe desteği
    $carousel.on('touchstart', function() {
        $(this).trigger('stop.owl.autoplay');
    });
    
    $carousel.on('touchend', function() {
        $(this).trigger('play.owl.autoplay');
    });
});
</script>

<section class="luxury-partners-showcase">
    <div class="partners-wave-overlay"></div>
    <div class="container">
        <div class="luxury-section-heading text-center">
            <span class="luxury-subheading">DISTINGUISHED COLLABORATIONS</span>
            <h2 class="luxury-heading">Our Esteemed Partners</h2>
            <div class="luxury-heading-decoration">
                <span class="line"></span>
                <span class="diamond"></span>
                <span class="line"></span>
            </div>
        </div>
        
        <div class="partners-slider-container">
            <div class="partners-slider owl-carousel">
                <?php
                try {
                    $partners = $VT->VeriGetir("isortaklarimiz", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                    
                    if($partners != false && is_array($partners) && count($partners) > 0) {
                        foreach($partners as $partner) {
                            if(!empty($partner["resim"])) {
                                $logoPath = SITE . "images/isortaklarimiz/" . $partner["resim"];
                                // Windows uyumlu yol oluştur
                                $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . parse_url($logoPath, PHP_URL_PATH));
                                ?>
                                <div class="partner-item">
                                    <div class="partner-card">
                                        <div class="partner-logo-frame">
                                            <?php if(file_exists($absolutePath)): ?>
                                                <img src="<?=$logoPath?>" alt="<?=stripslashes($partner["baslik"])?>" loading="lazy" />
                                            <?php else: ?>
                                                <div class="logo-error">
                                                    Logo not found: <?=basename($logoPath)?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="partner-hover-effect"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        echo '<div class="no-partners">No partners found in database</div>';
                    }
                } catch (Exception $e) {
                    error_log("Partner section error: " . $e->getMessage());
                    echo '<div class="error-message">Unable to load partners</div>';
                }
                ?>
            </div>
            <div class="slider-controls">
                <button class="slider-nav prev"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-nav next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<style>
.luxury-partners-showcase {
    padding: 100px 0;
    background: linear-gradient(135deg, #0a1f35 0%, #1c3f5f 100%);
    position: relative;
    overflow: hidden;
}

.partners-wave-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.03" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,208C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    background-size: cover;
    background-position: center;
    opacity: 0.5;
    pointer-events: none;
}

.partners-slider-container {
    position: relative;
    padding: 0 50px;
    margin-top: 50px;
}

.partner-item {
    padding: 15px;
}

.partner-card {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(200, 169, 126, 0.2);
    border-radius: 12px;
    padding: 30px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.partner-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(200, 169, 126, 0.4);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.partner-logo-frame {
    position: relative;
    height: 120px; /* Yükseklik artırıldı */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 15px;
}

.partner-logo-frame img {
    max-width: 100%;
    max-height: 100px; /* Yükseklik artırıldı */
    width: auto;
    height: auto;
    object-fit: contain;
    opacity: 1; /* Opaklık 1'e ayarlandı */
    transition: all 0.4s ease;
}

.logo-error {
    background: rgba(200, 169, 126, 0.1);
    border-radius: 8px;
    padding: 15px;
    font-style: italic;
    color: #c8a97e;
    font-size: 12px;
    text-align: center;
    width: 100%;
}

/* Debug için eklenen stil */
.debug-info {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(200, 169, 126, 0.3);
    border-radius: 4px;
    padding: 10px;
    margin: 10px 0;
    font-family: monospace;
    font-size: 12px;
    color: #c8a97e;
    word-break: break-all;
}

.partner-card:hover .partner-logo-frame img {
    opacity: 1;
    transform: scale(1.05);
}

.partner-hover-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at center, rgba(200, 169, 126, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: all 0.4s ease;
}

.partner-card:hover .partner-hover-effect {
    opacity: 1;
}

.slider-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    pointer-events: none;
    z-index: 10;
}

.slider-nav {
    position: absolute;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(200, 169, 126, 0.1);
    border: 1px solid rgba(200, 169, 126, 0.3);
    color: #c8a97e;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    pointer-events: auto;
}

.slider-nav:hover {
    background: #c8a97e;
    color: #fff;
}

.slider-nav.prev {
    left: -25px;
}

.slider-nav.next {
    right: -25px;
}

.luxury-section-heading {
    text-align: center;
    margin-bottom: 60px;
}

.luxury-subheading {
    display: block;
    color: #c8a97e;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 3px;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.luxury-heading {
    color: #ffffff;
    font-size: 42px;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 20px;
    font-family: 'Playfair Display', serif;
}

.luxury-heading-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.line {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
}

.diamond {
    width: 8px;
    height: 8px;
    background: #c8a97e;
    transform: rotate(45deg);
}

.no-partners, .error-message, .logo-error {
    color: #c8a97e;
    text-align: center;
    padding: 20px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
}

.error-message {
    color: #e74c3c;
}

.logo-error {
    background: rgba(200, 169, 126, 0.1);
    border-radius: 8px;
    padding: 15px;
    font-style: italic;
}

@media (max-width: 991px) {
    .luxury-partners-showcase {
        padding: 70px 0;
    }
    
    .partner-card {
        padding: 20px;
    }
    
    .partner-logo-frame {
        height: 80px;
    }
    
    .luxury-heading {
        font-size: 36px;
    }
}

@media (max-width: 767px) {
    .luxury-partners-showcase {
        padding: 50px 0;
    }
    
    .partners-slider-container {
        padding: 0 30px;
    }
    
    .partner-logo-frame {
        height: 60px;
    }
    
    .slider-nav {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .luxury-heading {
        font-size: 30px;
    }
}
</style>

<script>
$(document).ready(function(){
    var $slider = $('.partners-slider');
    
    // Debug için slider içeriğini kontrol et
    console.log('Slider items count:', $slider.children().length);
    
    if($slider.length > 0 && $slider.children().length > 0) {
        $slider.owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            navElement: 'button',
            navText: [
                '<i class="fas fa-chevron-left"></i>',
                '<i class="fas fa-chevron-right"></i>'
            ],
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplaySpeed: 1000,
            autoplayHoverPause: true,
            smartSpeed: 800,
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 15
                },
                576: {
                    items: 2,
                    margin: 20
                },
                768: {
                    items: 3,
                    margin: 25
                },
                1200: {
                    items: 4,
                    margin: 30
                }
            }
        });
        
        // Resimlerin yüklenme durumunu kontrol et
        $slider.find('img').on('load', function() {
            console.log('Image loaded:', this.src);
        }).on('error', function() {
            console.log('Image failed to load:', this.src);
            $(this).closest('.partner-logo-frame').html('<div class="logo-error">Image failed to load</div>');
        });
    }
});
</script>

<!-- Partners Section -->


<style>
/* Luxury Partners Section */
.luxury-partners {
    padding: 100px 0;
    background: linear-gradient(135deg, #0a1f35 0%, #1c3f5f 100%);
    position: relative;
    overflow: hidden;
}

.partners-bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CiAgPHBhdGggZD0iTTAgMGg2MHY2MEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zMCAzMG0tMjAgMGEyMCwyMCAwIDEsMCA0MCwwYTIwLDIwIDAgMSwwIC00MCwwIiBzdHJva2U9InJnYmEoMjAwLDE2OSwxMjYsMC4xKSIgZmlsbD0ibm9uZSIvPgo8L3N2Zz4=');
    opacity: 0.05;
}

.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.partner-item {
    perspective: 1000px;
}

.partner-card {
    position: relative;
    width: 100%;
    height: 200px;
    cursor: pointer;
    transform-style: preserve-3d;
    transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.partner-item:hover .partner-card {
    transform: rotateY(180deg);
}

.partner-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
}

.partner-front,
.partner-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 30px;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(200, 169, 126, 0.1);
    border-radius: 15px;
    overflow: hidden;
}

.partner-front {
    transform: rotateY(0deg);
}

.partner-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, rgba(200, 169, 126, 0.1), rgba(10, 31, 53, 0.95));
    text-align: center;
}

.partner-logo {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
}

.partner-front:hover .partner-logo {
    opacity: 1;
    transform: scale(1.05);
}

.partner-placeholder {
    font-size: 40px;
    color: rgba(200, 169, 126, 0.3);
}

.partner-name {
    color: #c8a97e;
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    margin-bottom: 15px;
    font-weight: 600;
}

.partner-description {
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}

.partner-link {
    color: #c8a97e;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border: 1px solid rgba(200, 169, 126, 0.3);
    border-radius: 25px;
    transition: all 0.3s ease;
}

.partner-link:hover {
    background: #c8a97e;
    color: #0a1f35;
    border-color: #c8a97e;
}

.partner-link i {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.partner-link:hover i {
    transform: translateX(5px);
}

/* Shine Effect */
.partner-front::before {
    content: '';
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: skewX(-25deg);
    transition: all 0.75s ease;
}

.partner-card:hover .partner-front::before {
    animation: shine 1.5s infinite;
}

@keyframes shine {
    100% {
        left: 125%;
    }
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .partners-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 991px) {
    .luxury-partners {
        padding: 80px 0;
    }
    
    .partner-card {
        height: 180px;
    }
}

@media (max-width: 767px) {
    .luxury-partners {
        padding: 60px 0;
    }
    
    .partners-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }
    
    .partner-card {
        height: 160px;
    }
    
    .partner-name {
        font-size: 18px;
    }
}

/* Touch Device Support */
@media (hover: none) {
    .partner-card {
        transform-style: flat;
    }
    
    .partner-back {
        display: none;
    }
    
    .partner-front {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .partner-logo {
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // AOS başlatma
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Touch cihazlar için özel işleme
    if ('ontouchstart' in window) {
        document.querySelectorAll('.partner-card').forEach(card => {
            card.addEventListener('click', function() {
                this.classList.toggle('touched');
            });
        });
    }
    
    // Görüntü yükleme hatası yönetimi
    document.querySelectorAll('.partner-logo').forEach(img => {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            let placeholder = document.createElement('div');
            placeholder.className = 'partner-placeholder';
            placeholder.innerHTML = '<i class="fas fa-building"></i>';
            this.parentNode.appendChild(placeholder);
        });
    });
});
</script>

<!-- Certificate Slider Section -->

<!-- Luxury Certificate Modal -->
<div class="luxury-certificate-modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <button class="modal-close">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-content">
            <img src="" alt="" class="modal-image">
            <div class="modal-info">
                <h3 class="modal-title"></h3>
                                    </div>
                                </div>
                            </div>
</div>

<style>
/* Luxury Certificate Section */
.luxury-certificates {
    padding: 100px 0;
    background: linear-gradient(to right, #f8f8f8, #ffffff);
    position: relative;
    overflow: hidden;
}

.luxury-certificates::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(200, 169, 126, 0.3), transparent);
}

/* Certificate Slider */
.certificates-slider {
    margin-top: 60px;
    padding: 20px 0;
}

.certificate-slide {
    padding: 20px;
}

.certificate-frame {
    position: relative;
    background: #ffffff;
    border-radius: 0;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.certificate-frame::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 1px solid rgba(200, 169, 126, 0.1);
    pointer-events: none;
    transition: all 0.4s ease;
}

.certificate-frame:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.certificate-frame:hover::before {
    border-color: rgba(200, 169, 126, 0.3);
}

.certificate-inner {
    position: relative;
    aspect-ratio: 3/4;
    overflow: hidden;
}

.certificate-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.certificate-frame:hover .certificate-inner img {
    transform: scale(1.05);
}

.certificate-details {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 31, 53, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.certificate-frame:hover .certificate-details {
    opacity: 1;
}

.zoom-indicator {
    width: 60px;
    height: 60px;
    background: rgba(200, 169, 126, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    transform: scale(0);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

.certificate-frame:hover .zoom-indicator {
    transform: scale(1);
}

.certificate-caption {
    padding: 20px;
    text-align: center;
    border-top: 1px solid rgba(200, 169, 126, 0.1);
}

.certificate-caption h4 {
    color: #0a1f35;
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    transition: all 0.3s ease;
}

.certificate-frame:hover .certificate-caption h4 {
    color: #c8a97e;
}

/* Owl Carousel Navigation */
.certificates-slider .owl-nav {
    margin-top: 40px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.certificates-slider .owl-nav button {
    width: 50px;
    height: 50px;
    border: 1px solid rgba(200, 169, 126, 0.3) !important;
    border-radius: 50%;
    background: transparent !important;
    color: #c8a97e !important;
    font-size: 24px !important;
    transition: all 0.3s ease;
}

.certificates-slider .owl-nav button:hover {
    background: #c8a97e !important;
    color: #fff !important;
    border-color: #c8a97e !important;
}

.certificates-slider .owl-dots {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.certificates-slider .owl-dot {
    width: 10px;
    height: 10px;
    background: rgba(200, 169, 126, 0.2) !important;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.certificates-slider .owl-dot.active {
    background: #c8a97e !important;
    transform: scale(1.2);
}

/* Modal Styles */
.luxury-certificate-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.luxury-certificate-modal.active {
    display: flex;
    opacity: 1;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 31, 53, 0.95);
}

.modal-container {
    position: relative;
    width: 90%;
    max-width: 1200px;
    margin: auto;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
    transform: translateY(20px);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.luxury-certificate-modal.active .modal-container {
    transform: translateY(0);
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: #c8a97e;
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #0a1f35;
    transform: rotate(90deg);
}

.modal-image {
    max-width: 100%;
    max-height: 70vh;
    display: block;
    margin: 0 auto;
}

.modal-caption {
    text-align: center;
    padding: 15px 0 0;
    color: #0a1f35;
    font-family: 'Playfair Display', serif;
    font-size: 18px;
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .certificate-slider-section {
        padding: 70px 0;
    }
}

@media (max-width: 767px) {
    .certificate-slider-section {
        padding: 50px 0;
    }
    
    .certificate-title {
        font-size: 16px;
    }
    
    .certificate-description {
        font-size: 13px;
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize certificate slider
    $('.certificate-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            992: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });

    // Certificate modal functionality
    $('.certificate-card').on('click', function() {
        var imgSrc = $(this).find('img').attr('src');
        var caption = $(this).find('.certificate-title').text();
        
        $('.modal-image').attr('src', imgSrc);
        $('.modal-caption').text(caption);
        $('.certificate-modal').addClass('active');
    });

    $('.modal-close, .modal-overlay').on('click', function() {
        $('.certificate-modal').removeClass('active');
    });

    // Close modal on ESC key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            $('.certificate-modal').removeClass('active');
        }
    });
});
</script>   


<!-- Certificate Lightbox -->
<div class="certificate-lightbox">
    <div class="lightbox-overlay"></div>
    <div class="lightbox-container">
        <button class="close-lightbox">
            <i class="fas fa-times"></i>
        </button>
        <img src="" alt="" class="lightbox-image">
        </div>
    </div>

<style>
.prestigious-certificates {
    padding: 120px 0;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}

.certificate-header {
    text-align: center;
    margin-bottom: 60px;
}

.elegant-subtitle {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    letter-spacing: 3px;
    color: #c8a97e;
    text-transform: uppercase;
    display: block;
    margin-bottom: 20px;
}

.prestigious-title {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #1a1a1a;
    margin-bottom: 25px;
    font-weight: 700;
}

.title-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.title-decoration .line {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
}

.title-decoration .diamond {
    color: #c8a97e;
    font-size: 12px;
}

.certificate-slider {
    margin: 0 -15px;
    padding: 20px 0;
}

.certificate-item {
    padding: 15px;
}

.certificate-frame {
    position: relative;
    background: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.certificate-frame:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.certificate-image {
    width: 100%;
    height: auto;
    display: block;
    transition: all 0.5s ease;
}

.hover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(200, 169, 126, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.certificate-frame:hover .hover-overlay {
    opacity: 1;
}

.view-button {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c8a97e;
    font-size: 24px;
    transform: scale(0);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.certificate-frame:hover .view-button {
    transform: scale(1);
}

.slider-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 50px;
    gap: 30px;
}

.nav-button {
    width: 50px;
    height: 50px;
    border: 1px solid rgba(200, 169, 126, 0.3);
    border-radius: 50%;
    background: transparent;
    color: #c8a97e;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-button:hover {
    background: #c8a97e;
    color: #fff;
    border-color: #c8a97e;
}

.custom-dots {
    display: flex;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(200, 169, 126, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    width: 24px;
    border-radius: 4px;
    background: #c8a97e;
}

/* Lightbox Styles */
.certificate-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(26, 26, 26, 0.95);
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.certificate-lightbox.active {
    display: flex;
    opacity: 1;
}

.lightbox-container {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    margin: auto;
    padding: 20px;
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    display: block;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.close-lightbox {
    position: absolute;
    top: -40px;
    right: -40px;
    width: 40px;
    height: 40px;
    background: #c8a97e;
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.close-lightbox:hover {
    background: #fff;
    color: #c8a97e;
    transform: rotate(90deg);
}

@media (max-width: 991px) {
    .prestigious-certificates {
        padding: 80px 0;
    }
    
    .prestigious-title {
        font-size: 36px;
    }
}

@media (max-width: 767px) {
    .prestigious-certificates {
        padding: 60px 0;
    }
    
    .prestigious-title {
        font-size: 28px;
    }
    
    .nav-button {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize certificate slider
    const $certificateSlider = $('.certificate-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1,
                margin: 15
            },
            576: {
                items: 2,
                margin: 20
            },
            992: {
                items: 3,
                margin: 25
            },
            1200: {
                items: 3,
                margin: 30
        }
        },
        onInitialized: function(event) {
            createCustomDots(event.item.count);
        },
        onChanged: function(event) {
            updateCustomDots(event.item.index);
        }
    });

    // Custom navigation
    $('.nav-button.prev').click(function() {
        $certificateSlider.trigger('prev.owl.carousel', [700]);
    });

    $('.nav-button.next').click(function() {
        $certificateSlider.trigger('next.owl.carousel', [700]);
    });

    function createCustomDots(count) {
        const $dots = $('.custom-dots');
        $dots.empty();
        
        for(let i = 0; i < count; i++) {
            $dots.append(`<span class="dot ${i === 0 ? 'active' : ''}"></span>`);
        }

        $('.dot').click(function() {
            const index = $(this).index();
            $certificateSlider.trigger('to.owl.carousel', [index, 700]);
        });
    }

    function updateCustomDots(index) {
        $('.dot').removeClass('active');
        $('.dot').eq(index).addClass('active');
    }

    // Lightbox functionality
    $('.certificate-frame').click(function() {
        const imgSrc = $(this).find('img').attr('src');
        $('.lightbox-image').attr('src', imgSrc);
        $('.certificate-lightbox').addClass('active');
    });

    $('.close-lightbox, .lightbox-overlay').click(function() {
        $('.certificate-lightbox').removeClass('active');
    });

    // Close lightbox on ESC key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            $('.certificate-lightbox').removeClass('active');
        }
    });

    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
});
</script>

<!-- Elegant Certificate Showcase -->
<section class="certificate-showcase">
    <div class="container">
        <div class="showcase-header" data-aos="fade-up">
            <span class="showcase-subtitle">EXCELLENCE IN STANDARDS</span>
            <h2 class="showcase-title">International Certifications</h2>
            <div class="title-accent">
                <span class="line"></span>
                <span class="icon"><i class="fas fa-award"></i></span>
                <span class="line"></span>
            </div>
        </div>

        <div class="certificate-carousel owl-carousel" data-aos="fade-up" data-aos-delay="200">
            <?php
            try {
                $certificates = $VT->VeriGetir("certificate", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                
                if($certificates != false) {
                    foreach($certificates as $cert) {
                        if(!empty($cert["resim"])) {
                            $certPath = SITE . "images/certificate/" . $cert["resim"];
                            ?>
                            <div class="certificate-item">
                                <div class="certificate-card">
                                    <div class="certificate-image">
                                        <img src="<?=$certPath?>" alt="<?=stripslashes($cert["baslik"])?>" loading="lazy" />
                                        <div class="hover-effect">
                                            <button class="view-btn" aria-label="View Certificate">
                                                <i class="fas fa-search-plus"></i>
        </button>
        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("Certificate section error: " . $e->getMessage());
            }
            ?>
        </div>

        <div class="carousel-navigation">
            <button class="nav-btn prev" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="carousel-dots"></div>
            <button class="nav-btn next" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Certificate Viewer -->
<div class="certificate-viewer">
    <div class="viewer-backdrop"></div>
    <div class="viewer-content">
        <button class="close-viewer" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
        <img src="" alt="" class="viewer-image">
    </div>
</div>

<style>
/* Certificate Showcase Section */
.certificate-showcase {
    padding: 100px 0;
    background: #ffffff;
    position: relative;
}

/* Header Styles */
.showcase-header {
    text-align: center;
    margin-bottom: 60px;
}

.showcase-subtitle {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    letter-spacing: 4px;
    color: #c8a97e;
    margin-bottom: 15px;
    font-weight: 500;
}

.showcase-title {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #1a1a1a;
    margin-bottom: 25px;
    font-weight: 700;
    line-height: 1.2;
}

.title-accent {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.title-accent .line {
    width: 40px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
}

.title-accent .icon {
    color: #c8a97e;
    font-size: 16px;
}

/* Carousel Styles */
.certificate-carousel {
    margin: 0 -15px;
    padding: 15px 0;
}

.certificate-item {
    padding: 15px;
}

.certificate-card {
    position: relative;
    background: #fff;
    border-radius: 0;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.certificate-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
}

.certificate-image {
    position: relative;
    aspect-ratio: 3/4;
    background: #f8f8f8;
}

.certificate-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.hover-effect {
    position: absolute;
    inset: 0;
    background: rgba(200, 169, 126, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.certificate-card:hover .hover-effect {
    opacity: 1;
}

.view-btn {
    width: 60px;
    height: 60px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    color: #c8a97e;
    font-size: 24px;
    cursor: pointer;
    transform: scale(0.5);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.certificate-card:hover .view-btn {
    transform: scale(1);
    opacity: 1;
}

.view-btn:hover {
    background: #fff;
    transform: scale(1.1) !important;
}

/* Navigation Styles */
.carousel-navigation {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 50px;
    gap: 30px;
}

.nav-btn {
    width: 46px;
    height: 46px;
    border: 1px solid rgba(200, 169, 126, 0.3);
    border-radius: 50%;
    background: transparent;
    color: #c8a97e;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-btn:hover {
    background: #c8a97e;
    color: #fff;
    border-color: #c8a97e;
}

.carousel-dots {
    display: flex;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(200, 169, 126, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    width: 24px;
    border-radius: 4px;
    background: #c8a97e;
}

/* Certificate Viewer */
.certificate-viewer {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.certificate-viewer.active {
    display: flex;
    opacity: 1;
}

.viewer-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(26, 26, 26, 0.95);
}

.viewer-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    margin: auto;
    z-index: 1;
}

.viewer-image {
    max-width: 100%;
    max-height: 85vh;
    display: block;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.close-viewer {
    position: absolute;
    top: -50px;
    right: -50px;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: #c8a97e;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-viewer:hover {
    background: #fff;
    color: #c8a97e;
    transform: rotate(90deg);
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .showcase-title {
        font-size: 38px;
    }
}

@media (max-width: 991px) {
    .certificate-showcase {
        padding: 80px 0;
    }
    
    .showcase-title {
        font-size: 34px;
    }
}

@media (max-width: 767px) {
    .certificate-showcase {
        padding: 60px 0;
    }
    
    .showcase-title {
        font-size: 28px;
    }
    
    .nav-btn {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .close-viewer {
        top: -60px;
        right: 50%;
        transform: translateX(50%);
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize certificate carousel
    const $carousel = $('.certificate-carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1,
                margin: 15
            },
            576: {
                items: 2,
                margin: 20
            },
            992: {
                items: 3,
                margin: 25
            }
        },
        onInitialized: function(event) {
            createDots(event.item.count);
        },
        onChanged: function(event) {
            updateDots(event.item.index);
        }
    });

    // Custom navigation
    $('.nav-btn.prev').click(function() {
        $carousel.trigger('prev.owl.carousel', [700]);
    });

    $('.nav-btn.next').click(function() {
        $carousel.trigger('next.owl.carousel', [700]);
    });

    function createDots(count) {
        const $dots = $('.carousel-dots');
        $dots.empty();
        
        for(let i = 0; i < count; i++) {
            $dots.append(`<span class="dot ${i === 0 ? 'active' : ''}"></span>`);
        }

        $('.dot').click(function() {
            const index = $(this).index();
            $carousel.trigger('to.owl.carousel', [index, 700]);
        });
    }

    function updateDots(index) {
        $('.dot').removeClass('active');
        $('.dot').eq(index).addClass('active');
    }

    // Certificate viewer functionality
    $('.certificate-card').click(function() {
        const imgSrc = $(this).find('img').attr('src');
        $('.viewer-image').attr('src', imgSrc);
        $('.certificate-viewer').addClass('active');
    });

    $('.close-viewer, .viewer-backdrop').click(function() {
        $('.certificate-viewer').removeClass('active');
    });

    // Close viewer on ESC key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            $('.certificate-viewer').removeClass('active');
        }
    });

    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
});
</script>


<style>
/* Distinguished Partners Section */
.distinguished-partners {
    padding: 120px 0;
    background: linear-gradient(to right, #0a1f35, #1a3a5f);
    position: relative;
    overflow: hidden;
}

.partners-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CiAgPHBhdGggZD0iTTAgMGg2MHY2MEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zMCAzMG0tMjAgMGEyMCwyMCAwIDEsMCA0MCwwYTIwLDIwIDAgMSwwIC00MCwwIiBzdHJva2U9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiIGZpbGw9Im5vbmUiLz4KPC9zdmc+');
    opacity: 0.5;
}

.partners-header {
    text-align: center;
    margin-bottom: 80px;
    position: relative;
}

.elegant-label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    letter-spacing: 4px;
    color: #c8a97e;
    margin-bottom: 20px;
    font-weight: 500;
}

.partners-title {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    color: #ffffff;
    margin-bottom: 30px;
    font-weight: 700;
    line-height: 1.2;
}

.title-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
}

.title-separator .line {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(200, 169, 126, 0.5), transparent);
}

.title-separator .diamond {
    width: 8px;
    height: 8px;
    background: #c8a97e;
    transform: rotate(45deg);
}

/* Partner Slider */
.partners-slider {
    margin: 0 -20px;
    padding: 20px 0;
}

.partner-slide {
    padding: 20px;
}

.partner-frame {
    position: relative;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 4px;
    overflow: hidden;
    aspect-ratio: 4/3;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.frame-inner {
    position: relative;
    width: 100%;
    height: 100%;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.03);
    transition: all 0.5s ease;
}

.frame-border {
    position: absolute;
    inset: 0;
    border: 1px solid rgba(200, 169, 126, 0.1);
    transition: all 0.5s ease;
}

.partner-frame:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.05);
}

.partner-frame:hover .frame-border {
    border-color: rgba(200, 169, 126, 0.3);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.partner-logo {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.5s ease;
}

.partner-frame:hover .partner-logo {
    opacity: 1;
    transform: scale(1.05);
}

/* Navigation */
.slider-navigation {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 60px;
    gap: 30px;
}

.nav-arrow {
    width: 50px;
    height: 50px;
    border: 1px solid rgba(200, 169, 126, 0.3);
    border-radius: 50%;
    background: transparent;
    color: #c8a97e;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-arrow:hover {
    background: #c8a97e;
    color: #fff;
    border-color: #c8a97e;
}

.slider-dots {
    display: flex;
    gap: 10px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(200, 169, 126, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    width: 24px;
    border-radius: 4px;
    background: #c8a97e;
}

@media (max-width: 1200px) {
    .partners-title {
        font-size: 42px;
}
}

@media (max-width: 991px) {
    .distinguished-partners {
        padding: 80px 0;
    }
    
    .partners-title {
        font-size: 36px;
    }
    
    .partner-frame {
        aspect-ratio: 3/2;
    }
}

@media (max-width: 767px) {
    .distinguished-partners {
        padding: 60px 0;
    }
    
    .partners-title {
        font-size: 30px;
    }
    
    .nav-arrow {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .partner-slide {
        padding: 10px;
    }
    
    .frame-inner {
        padding: 20px;
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize partners slider
    const $slider = $('.partners-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1,
                margin: 20
            },
            576: {
                items: 2,
                margin: 20
            },
            768: {
                items: 3,
                margin: 25
            },
            1200: {
                items: 4,
                margin: 30
            }
        },
        onInitialized: function(event) {
            createDots(event.item.count);
        },
        onChanged: function(event) {
            updateDots(event.item.index);
        }
    });

    // Custom navigation
    $('.nav-arrow.prev').click(function() {
        $slider.trigger('prev.owl.carousel', [700]);
    });

    $('.nav-arrow.next').click(function() {
        $slider.trigger('next.owl.carousel', [700]);
    });

    function createDots(count) {
        const $dots = $('.slider-dots');
        $dots.empty();
        
        for(let i = 0; i < count; i++) {
            $dots.append(`<span class="dot ${i === 0 ? 'active' : ''}"></span>`);
        }

        $('.dot').click(function() {
            const index = $(this).index();
            $slider.trigger('to.owl.carousel', [index, 700]);
    });
    }

    function updateDots(index) {
        $('.dot').removeClass('active');
        $('.dot').eq(index).addClass('active');
    }

    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
});
</script>

<!-- Excellence in Standards Section -->

<!-- Certificate Viewer -->


<style>
/* ... existing code ... */

/* Certificate Viewer Styles */
.cert-viewer {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cert-viewer.active {
    display: flex;
    opacity: 1;
}

.viewer-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    cursor: pointer;
}

.viewer-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    margin: auto;
    z-index: 10000;
}

.viewer-image {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 5px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
}

.close-viewer {
    position: absolute;
    top: -40px;
    right: -40px;
    width: 40px;
    height: 40px;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.close-viewer:hover {
    transform: rotate(90deg);
}

.nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-arrow:hover {
    background: rgba(255, 255, 255, 0.2);
}

.nav-arrow.prev {
    left: -80px;
}

.nav-arrow.next {
    right: -80px;
}

.cert-counter {
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
}

@media (max-width: 991px) {
    .nav-arrow {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .nav-arrow.prev {
        left: -50px;
    }
    
    .nav-arrow.next {
        right: -50px;
    }
}

@media (max-width: 767px) {
    .nav-arrow {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.15);
    }
    
    .nav-arrow.prev {
        left: 10px;
    }
    
    .nav-arrow.next {
        right: 10px;
    }
    
    .close-viewer {
        top: -50px;
        right: 0;
    }
}
</style>

<script>
$(document).ready(function(){
    // ... existing code ...

    // Certificate viewer functionality with navigation
    let currentIndex = 0;
    const $certViewer = $('.cert-viewer');
    const $viewerImage = $('.viewer-image');
    const $counter = $('.cert-counter');
    const certItems = [];

    // Collect all certificate items
    $('.cert-item').each(function() {
        certItems.push({
            src: $(this).data('src'),
            alt: $(this).find('img').attr('alt') || ''
        });
    });

    function updateViewer(index) {
        currentIndex = index;
        $viewerImage.attr('src', certItems[index].src);
        $viewerImage.attr('alt', certItems[index].alt);
        $counter.find('.current').text(index + 1);
        $counter.find('.total').text(certItems.length);
    }

    function showViewer(index) {
        updateViewer(index);
        $certViewer.addClass('active');
        // Disable page scroll
        $('body').css('overflow', 'hidden');
    }

    function hideViewer() {
        $certViewer.removeClass('active');
        // Re-enable page scroll
        $('body').css('overflow', '');
    }

    function nextCert() {
        let nextIndex = (currentIndex + 1) % certItems.length;
        updateViewer(nextIndex);
    }

    function prevCert() {
        let prevIndex = (currentIndex - 1 + certItems.length) % certItems.length;
        updateViewer(prevIndex);
    }

    // Click handlers for certificates
    $('.cert-frame').click(function() {
        const index = $(this).closest('.cert-item').index();
        showViewer(index);
    });

    // Navigation buttons
    $('.nav-arrow.next').click(function(e) {
        e.stopPropagation();
        nextCert();
    });

    $('.nav-arrow.prev').click(function(e) {
        e.stopPropagation();
        prevCert();
    });

    // Close viewer
    $('.close-viewer, .viewer-overlay').click(function() {
        hideViewer();
    });

    // Keyboard navigation
    $(document).keydown(function(e) {
        if (!$certViewer.hasClass('active')) return;
        
        switch(e.keyCode) {
            case 37: // Left arrow
                prevCert();
                break;
            case 39: // Right arrow
                nextCert();
                break;
            case 27: // ESC
                hideViewer();
                break;
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    $certViewer.on('touchstart', function(e) {
        touchStartX = e.originalEvent.touches[0].clientX;
    });

    $certViewer.on('touchmove', function(e) {
        touchEndX = e.originalEvent.touches[0].clientX;
    });

    $certViewer.on('touchend', function() {
        const swipeThreshold = 50;
        const swipeDistance = touchEndX - touchStartX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                prevCert();
            } else {
                nextCert();
            }
        }
    });

    // Preload adjacent images
    function preloadAdjacentImages(index) {
        const prevIndex = (index - 1 + certItems.length) % certItems.length;
        const nextIndex = (index + 1) % certItems.length;

        new Image().src = certItems[prevIndex].src;
        new Image().src = certItems[nextIndex].src;
    }

    // Update preload when changing images
    $viewerImage.on('load', function() {
        preloadAdjacentImages(currentIndex);
    });
});
</script>



<style>
/* Prestigious Certifications Section */
.prestigious-certifications {
    padding: 120px 0;
    background: linear-gradient(to bottom, #ffffff, #f8f9fa);
    position: relative;
}

/* Header Styles */
.section-header {
    text-align: center;
    margin-bottom: 80px;
}

.elegant-subtitle {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    letter-spacing: 3px;
    color: #c8a97e;
    text-transform: uppercase;
    margin-bottom: 20px;
    display: block;
}

.prestigious-title {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    color: #1a1a1a;
    margin-bottom: 25px;
    font-weight: 700;
    line-height: 1.2;
}

.golden-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 30px 0;
}

.golden-separator .line {
    width: 60px;
    height: 1px;
    background: linear-gradient(to right, transparent, #c8a97e, transparent);
}

.diamond-icon {
    margin: 0 15px;
    color: #c8a97e;
    font-size: 12px;
}

.section-description {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.8;
}

/* Certificate Grid */
.cert-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    padding: 20px;
}

/* Certificate Card */
.cert-card {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
}

.cert-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.cert-inner {
    position: relative;
    padding-top: 140%; /* Aspect ratio */
    background: #fff;
}

.cert-image-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.cert-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s ease;
}

.cert-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.cert-card:hover .cert-overlay {
    opacity: 1;
}

.cert-details {
    text-align: center;
    padding: 20px;
    transform: translateY(20px);
    transition: all 0.4s ease;
}

.cert-card:hover .cert-details {
    transform: translateY(0);
}

.cert-title {
    color: #fff;
    font-size: 18px;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.view-cert-btn {
    background: transparent;
    border: 2px solid #c8a97e;
    color: #c8a97e;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.view-cert-btn:hover {
    background: #c8a97e;
    color: #fff;
}

/* Loading Animation */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #f8f8f8;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    position: relative;
}

.spinner-ring {
    width: 100%;
    height: 100%;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #c8a97e;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Premium Certificate Viewer */
.premium-cert-viewer {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
        opacity: 0;
    transition: opacity 0.3s ease;
    }

.premium-cert-viewer.active {
    display: flex;
        opacity: 1;
}

.viewer-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
}

.viewer-container {
    position: relative;
    width: 90%;
    max-width: 1200px;
    margin: auto;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
    z-index: 10000;
}

.viewer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.viewer-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    color: #333;
    margin: 0;
}

.close-viewer {
    background: transparent;
    border: none;
    color: #666;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.close-viewer:hover {
    color: #c8a97e;
    transform: rotate(90deg);
}

.viewer-content {
    position: relative;
    padding: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    }
    
.image-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.viewer-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
}

.nav-button {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: #666;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 20px;
}

.nav-button:hover {
    background: #c8a97e;
    color: #fff;
}

.viewer-footer {
    padding: 20px;
    text-align: center;
    border-top: 1px solid #eee;
    }

.cert-counter {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    color: #666;
}

/* Responsive Design */
@media (max-width: 991px) {
    .prestigious-title {
        font-size: 36px;
    }
    
    .cert-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 767px) {
    .prestigious-certifications {
        padding: 80px 0;
    }
    
    .prestigious-title {
        font-size: 28px;
    }
    
    .viewer-container {
        width: 95%;
    }
    
    .nav-button {
        width: 40px;
        height: 40px;
        font-size: 16px;
        margin: 0 10px;
    }
    
    .viewer-content {
        padding: 20px;
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize lazy loading
    const lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy",
        callback_loaded: function(el) {
            $(el).closest('.cert-card').find('.loading-overlay').fadeOut();
        }
    });

    // Certificate viewer functionality
    let currentIndex = 0;
    const $viewer = $('.premium-cert-viewer');
    const $viewerImage = $('.viewer-image');
    const $viewerTitle = $('.viewer-title');
    const $counter = $('.cert-counter');
    const certItems = [];

    // Collect all certificate items
    $('.cert-card').each(function() {
        certItems.push({
            src: $(this).data('src'),
            title: $(this).data('title')
        });
    });

    function updateViewer(index) {
        currentIndex = index;
        $viewerImage.attr('src', certItems[index].src);
        $viewerTitle.text(certItems[index].title);
        $counter.find('.current').text(index + 1);
        $counter.find('.total').text(certItems.length);
    }

    function showViewer(index) {
        updateViewer(index);
        $viewer.addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function hideViewer() {
        $viewer.removeClass('active');
        $('body').css('overflow', '');
    }

    function nextCert() {
        let nextIndex = (currentIndex + 1) % certItems.length;
        updateViewer(nextIndex);
    }

    function prevCert() {
        let prevIndex = (currentIndex - 1 + certItems.length) % certItems.length;
        updateViewer(prevIndex);
    }

    // Click handlers
    $('.cert-card').click(function() {
        const index = $(this).index();
        showViewer(index);
    });

    $('.nav-button.next').click(function(e) {
        e.stopPropagation();
        nextCert();
    });

    $('.nav-button.prev').click(function(e) {
        e.stopPropagation();
        prevCert();
    });

    $('.close-viewer, .viewer-backdrop').click(function() {
        hideViewer();
    });

    // Keyboard navigation
    $(document).keydown(function(e) {
        if (!$viewer.hasClass('active')) return;

        switch(e.keyCode) {
            case 37: // Left arrow
                prevCert();
                break;
            case 39: // Right arrow
                nextCert();
                break;
            case 27: // ESC
                hideViewer();
                break;
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    $viewer.on('touchstart', function(e) {
        touchStartX = e.originalEvent.touches[0].clientX;
    });

    $viewer.on('touchmove', function(e) {
        touchEndX = e.originalEvent.touches[0].clientX;
    });

    $viewer.on('touchend', function() {
        const swipeThreshold = 50;
        const swipeDistance = touchEndX - touchStartX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                prevCert();
            } else {
                nextCert();
    }
        }
    });

    // Preload adjacent images
    function preloadAdjacentImages(index) {
        const prevIndex = (index - 1 + certItems.length) % certItems.length;
        const nextIndex = (index + 1) % certItems.length;

        new Image().src = certItems[prevIndex].src;
        new Image().src = certItems[nextIndex].src;
    }

    // Preload images when viewing
    $viewerImage.on('load', function() {
        preloadAdjacentImages(currentIndex);
    });

    // Handle window resize
    $(window).resize(function() {
        if ($viewer.hasClass('active')) {
            $viewerImage.css('max-height', (window.innerHeight * 0.7) + 'px');
        }
    });
});
</script>

<!-- Luxury Certifications Section -->
<section class="luxury-certifications" id="certificationSection">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="elegant-label">EXCELLENCE IN STANDARDS</span>
            <h2 class="luxury-title">International Certifications</h2>
            <div class="golden-divider">
                <span class="line"></span>
                <span class="diamond"><i class="fas fa-diamond"></i></span>
                <span class="line"></span>
            </div>
            <p class="elegant-description">Our commitment to excellence is validated through prestigious international certifications</p>
        </div>

        <div class="certificates-showcase" data-aos="fade-up" data-aos-delay="200">
            <?php
            try {
                $certificates = $VT->VeriGetir("certificate", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                
                if($certificates != false) {
                    echo '<div class="cert-masonry">';
                    foreach($certificates as $cert) {
                        if(!empty($cert["resim"])) {
                            $certPath = SITE . "images/certificate/" . $cert["resim"];
                            ?>
                            <div class="cert-item" data-src="<?=$certPath?>" data-title="<?=stripslashes($cert["baslik"])?>">
                                <div class="cert-frame">
                                    <div class="image-wrapper">
                                        <div class="elegant-loader">
                                            <div class="loader-ring"></div>
                                        </div>
                                        <img 
                                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                            data-src="<?=$certPath?>"
                                            alt="<?=stripslashes($cert["baslik"])?>"
                                            class="cert-image lazy"
                                        />
                                    </div>
                                    <div class="hover-layer">
                                        <div class="content">
                                            <h3 class="cert-name"><?=stripslashes($cert["baslik"])?></h3>
                                            <button class="view-button" aria-label="View Certificate">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    echo '</div>';
                }
            } catch (Exception $e) {
                error_log("Certificate section error: " . $e->getMessage());
            }
            ?>
        </div>
    </div>
</section>

<!-- Premium Partners Section -->
<section class="premium-partners">
    <div class="elegant-overlay"></div>
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="elegant-label">DISTINGUISHED COLLABORATIONS</span>
            <h2 class="luxury-title">Our Esteemed Partners</h2>
            <div class="golden-divider">
                <span class="line"></span>
                <span class="diamond"><i class="fas fa-diamond"></i></span>
                <span class="line"></span>
            </div>
        </div>

        <div class="partners-showcase" data-aos="fade-up" data-aos-delay="200">
            <?php
            try {
                $partners = $VT->VeriGetir("isortaklarimiz", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                
                if($partners != false) {
                    echo '<div class="partners-carousel owl-carousel">';
                    foreach($partners as $partner) {
                        if(!empty($partner["resim"])) {
                            $logoPath = SITE . "images/isortaklarimiz/" . $partner["resim"];
                            ?>
                            <div class="partner-item">
                                <div class="partner-frame">
                                    <img 
                                        src="<?=$logoPath?>" 
                                        alt="<?=stripslashes($partner["baslik"])?>" 
                                        class="partner-logo"
                                        loading="lazy"
                                    />
                                </div>
                            </div>
                            <?php
                        }
                    }
                    echo '</div>';
                }
            } catch (Exception $e) {
                error_log("Partners section error: " . $e->getMessage());
            }
            ?>
            
            <div class="carousel-controls">
                <button class="nav-btn prev" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="custom-dots"></div>
                <button class="nav-btn next" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Luxury Certificate Viewer -->
<div class="luxury-viewer">
    <div class="viewer-overlay"></div>
    <div class="viewer-content">
        <div class="viewer-header">
            <h3 class="viewer-title"></h3>
            <button class="close-btn" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="viewer-body">
            <button class="nav-arrow prev" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="image-container">
                <img src="" alt="" class="viewer-image">
            </div>
            <button class="nav-arrow next" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="viewer-footer">
            <div class="counter">
                <span class="current">1</span> / <span class="total">1</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Common Luxury Styles */
.elegant-label {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    letter-spacing: 3px;
    color: #c8a97e;
    text-transform: uppercase;
    display: block;
    margin-bottom: 15px;
}

.luxury-title {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #1a1a1a;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 25px;
}

.golden-divider {
        display: flex;
    align-items: center;
        justify-content: center;
    margin: 30px 0;
}

.golden-divider .line {
    width: 60px;
    height: 1px;
    background: linear-gradient(to right, transparent, #c8a97e, transparent);
}

.golden-divider .diamond {
    margin: 0 15px;
    color: #c8a97e;
    font-size: 12px;
}

.elegant-description {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    color: #666;
    line-height: 1.8;
    max-width: 600px;
    margin: 0 auto;
}

/* Certifications Section */
.luxury-certifications {
    padding: 120px 0;
    background: linear-gradient(to bottom, #fff, #f8f9fa);
    position: relative;
}

.cert-masonry {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    padding: 20px 0;
    }
    
.cert-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.cert-frame {
    position: relative;
    padding-top: 140%;
    background: #fff;
}

.image-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.cert-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s ease;
}

.hover-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s ease;
}

.cert-item:hover .hover-layer {
        opacity: 1;
    }
    
.cert-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.cert-name {
    color: #fff;
    font-size: 18px;
    margin-bottom: 15px;
    font-family: 'Montserrat', sans-serif;
}

.view-button {
    width: 50px;
    height: 50px;
    border: 2px solid #c8a97e;
    border-radius: 50%;
    background: transparent;
    color: #c8a97e;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-button:hover {
    background: #c8a97e;
    color: #fff;
}

/* Partners Section */
.premium-partners {
    padding: 100px 0;
    background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
    position: relative;
    color: #fff;
}

.premium-partners .luxury-title {
    color: #fff;
}

.partners-carousel {
    margin: 60px 0;
}

.partner-item {
        padding: 20px;
    }

.partner-frame {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 10px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 150px;
}

.partner-frame:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-5px);
}

.partner-logo {
    max-width: 100%;
    max-height: 80px;
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
    }
    
.partner-frame:hover .partner-logo {
        opacity: 1;
}

.carousel-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 40px;
    gap: 30px;
    }
    
    .nav-btn {
    width: 46px;
    height: 46px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    background: transparent;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-btn:hover {
    background: #c8a97e;
    border-color: #c8a97e;
}

/* Luxury Viewer */
.luxury-viewer {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.luxury-viewer.active {
    display: flex;
        opacity: 1;
}

.viewer-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(5px);
}

.viewer-content {
    position: relative;
    width: 90%;
    max-width: 1200px;
    margin: auto;
    z-index: 10000;
}

.viewer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.viewer-title {
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 24px;
    margin: 0;
}

.close-btn {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.close-btn:hover {
    transform: rotate(90deg);
}

.viewer-body {
    position: relative;
    display: flex;
    align-items: center;
    gap: 30px;
}

.nav-arrow {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-arrow:hover {
    background: #c8a97e;
}

.image-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.viewer-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
}

.viewer-footer {
    margin-top: 30px;
    text-align: center;
}

.counter {
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
}

/* Loading Animation */
.elegant-loader {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #f8f8f8;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

.loader-ring {
    width: 40px;
    height: 40px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #c8a97e;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 991px) {
    .luxury-title {
        font-size: 36px;
    }
    
    .cert-masonry {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 767px) {
    .luxury-certifications,
    .premium-partners {
        padding: 80px 0;
    }
    
    .luxury-title {
        font-size: 28px;
    }
    
    .nav-arrow {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .viewer-content {
        width: 95%;
    }
    
    .viewer-title {
        font-size: 20px;
    }
}
</style>

<script>
$(document).ready(function(){
    // Initialize partners carousel
    const $partnersCarousel = $('.partners-carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            }
        }
    });

    // Partners carousel navigation
    $('.carousel-controls .prev').click(function() {
        $partnersCarousel.trigger('prev.owl.carousel', [700]);
    });

    $('.carousel-controls .next').click(function() {
        $partnersCarousel.trigger('next.owl.carousel', [700]);
    });

    // Initialize lazy loading
    const lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy",
        callback_loaded: function(el) {
            $(el).closest('.cert-item').find('.elegant-loader').fadeOut();
        }
    });

    // Certificate viewer functionality
    let currentIndex = 0;
    const $viewer = $('.luxury-viewer');
    const $viewerImage = $('.viewer-image');
    const $viewerTitle = $('.viewer-title');
    const $counter = $('.counter');
    const certItems = [];

    // Collect all certificate items
    $('.cert-item').each(function() {
        certItems.push({
            src: $(this).data('src'),
            title: $(this).data('title')
        });
    });

    function updateViewer(index) {
        currentIndex = index;
        $viewerImage.attr('src', certItems[index].src);
        $viewerTitle.text(certItems[index].title);
        $counter.find('.current').text(index + 1);
        $counter.find('.total').text(certItems.length);
    }

    function showViewer(index) {
        updateViewer(index);
        $viewer.addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function hideViewer() {
        $viewer.removeClass('active');
        $('body').css('overflow', '');
    }

    function nextCert() {
        let nextIndex = (currentIndex + 1) % certItems.length;
        updateViewer(nextIndex);
    }

    function prevCert() {
        let prevIndex = (currentIndex - 1 + certItems.length) % certItems.length;
        updateViewer(prevIndex);
    }

    // Click handlers
    $('.cert-item').click(function() {
        const index = $(this).index();
        showViewer(index);
    });

    $('.nav-arrow.next').click(function(e) {
        e.stopPropagation();
        nextCert();
    });

    $('.nav-arrow.prev').click(function(e) {
        e.stopPropagation();
        prevCert();
    });

    $('.close-btn, .viewer-overlay').click(function() {
        hideViewer();
    });

    // Keyboard navigation
    $(document).keydown(function(e) {
        if (!$viewer.hasClass('active')) return;

        switch(e.keyCode) {
            case 37: // Left arrow
                prevCert();
                break;
            case 39: // Right arrow
                nextCert();
                break;
            case 27: // ESC
                hideViewer();
                break;
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    $viewer.on('touchstart', function(e) {
        touchStartX = e.originalEvent.touches[0].clientX;
    });

    $viewer.on('touchmove', function(e) {
        touchEndX = e.originalEvent.touches[0].clientX;
    });

    $viewer.on('touchend', function() {
        const swipeThreshold = 50;
        const swipeDistance = touchEndX - touchStartX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                prevCert();
            } else {
                nextCert();
            }
        }
    });

    // Preload adjacent images
    function preloadAdjacentImages(index) {
        const prevIndex = (index - 1 + certItems.length) % certItems.length;
        const nextIndex = (index + 1) % certItems.length;

        new Image().src = certItems[prevIndex].src;
        new Image().src = certItems[nextIndex].src;
    }

    // Preload images when viewing
    $viewerImage.on('load', function() {
        preloadAdjacentImages(currentIndex);
    });

    // Handle window resize
    $(window).resize(function() {
        if ($viewer.hasClass('active')) {
            $viewerImage.css('max-height', (window.innerHeight * 0.7) + 'px');
        }
    });
});
</script>
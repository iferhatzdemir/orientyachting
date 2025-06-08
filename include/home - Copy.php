<!-- Yacht-themed Luxury Preloader -->
<div id="orient-preloader" class="orient-preloader">
    <div class="preloader-content">
        <div class="yacht-animation">
            <svg class="yacht-svg" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Yacht outline path -->
                <path class="yacht-path" d="M200,200 C250,180 300,170 350,170 C400,170 450,180 500,200 L550,200 C600,200 650,220 700,250 L700,250 L500,250 C450,230 400,220 350,220 C300,220 250,230 200,250 L200,200 Z" fill="none" stroke="#c8a97e" stroke-width="3"/>
                <!-- Yacht mast -->
                <line class="yacht-mast" x1="400" y1="170" x2="400" y2="120" stroke="#c8a97e" stroke-width="3"/>
                <!-- Yacht flag -->
                <path class="yacht-flag" d="M400,120 L430,130 L400,140" fill="none" stroke="#c8a97e" stroke-width="2"/>
            </svg>
        </div>
        <div class="ocean-animation">
            <div class="wave wave-1"></div>
            <div class="wave wave-2"></div>
            <div class="wave wave-3"></div>
        </div>
        <div class="logo-container">
            <img src="assets/img/logo-light.png" alt="Orient Yachting" class="preloader-logo">
        </div>
        <div class="loading-bar">
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
            </div>
            <div class="slider-content">
                <div class="slider-text-container">
                    <?php if (!empty($item["baslik"])): ?>
                    <h2 class="slider-subtitle"><?= stripslashes($item["baslik"]) ?></h2>
                    <?php endif; ?>

                    <?php if (!empty($item["aciklama"])): ?>
                    <h1 class="slider-title"><?= stripslashes($item["aciklama"]) ?></h1>
                    <?php endif; ?>

                    <?php if (!empty($item["url"])): ?>
                    <div class="slider-cta">
                        <a href="<?= $item["url"] ?>" class="btn btn-hero">Discover Luxury</a>
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

    <!-- Navigation Controls -->
    <div class="slider-navigation">
        <button class="slider-prev" aria-label="Previous slide"><span></span></button>
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
        <button class="slider-next" aria-label="Next slide"><span></span></button>
    </div>
</div>

<!-- end .b-services-->
<section class="advantages-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-heading">Our Distinguished Offerings</h2>
                <div class="heading-separator"><span></span></div>
            </div>
        </div>
        <div class="row advantages-row">
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="advantage-icon-wrapper">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon">
                            <i class="flaticon-rudder-1"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">EXCEPTIONAL VOYAGES</h3>
                        <div class="advantage-separator">
                            <svg width="80" height="15" viewBox="0 0 80 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="separator-svg">
                                <path d="M5 7.5H25" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                                <circle cx="40" cy="7.5" r="5" fill="#c8a97e" />
                                <path d="M55 7.5H75" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="advantage-text">
                            Meticulously crafted journeys that transcend ordinary exploration, where crystal-clear
                            waters and hidden harbors become the backdrop for your unforgettable moments.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="advantage-icon-wrapper">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon">
                            <i class="flaticon-snorkel"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">PERSONALIZED REFINEMENT</h3>
                        <div class="advantage-separator">
                            <svg width="80" height="15" viewBox="0 0 80 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="separator-svg">
                                <path d="M5 7.5H25" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                                <circle cx="40" cy="7.5" r="5" fill="#c8a97e" />
                                <path d="M55 7.5H75" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="advantage-text">
                            From gourmet delights to bespoke routes, every detail is tailored to your unique
                            preferences, creating an experience as distinctive as your signature.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="advantage-icon-wrapper">
                        <div class="advantage-icon-bg"></div>
                        <div class="advantage-icon">
                            <i class="flaticon-sailor"></i>
                        </div>
                    </div>
                    <div class="advantage-content">
                        <h3 class="advantage-title">ELEGANT EXCELLENCE</h3>
                        <div class="advantage-separator">
                            <svg width="80" height="15" viewBox="0 0 80 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="separator-svg">
                                <path d="M5 7.5H25" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                                <circle cx="40" cy="7.5" r="5" fill="#c8a97e" />
                                <path d="M55 7.5H75" stroke="#c8a97e" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="advantage-text">
                            Our attentive crew anticipates your desires before you realize them, delivering flawless
                            elegance through the art of invisible service.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-goods premium-fleet-section">
    <div class="section-default section-goods__inner bg-dark">
        <div class="ui-decor ui-decor_mirror ui-decor_center"></div>
        <div class="container">
            <div class="text-center">
                <h2 class="ui-title ui-title_light">Exclusive Yacht Collection</h2>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <p>Discover our curated selection of prestigious vessels, each embodying the perfect balance of
                            performance, comfort, and sophisticated design for the ultimate maritime experience.</p>
                        <img src="assets/img/decore03.png" alt="decorative element">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="section-goods__list">
            <div class="row">
                <?php
                $yachtList = $VT->VeriGetir("yachts", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                if ($yachtList != false) {
                    foreach ($yachtList as $yacht) {
                        $imagePath = SITE . "images/yachts/" . $yacht["resim"];
                ?>
                <div class="col-xl-3 col-md-6">
                    <div class="b-goods luxury-yacht-card">
                        <a class="b-goods__img" href="<?=SITE?>yat/<?=$yacht["seflink"]?>">
                            <img class="img-scale" src="<?=$imagePath?>" alt="<?=stripslashes($yacht["baslik"])?>" />
                            <div class="yacht-card-overlay">
                                <span class="explore-btn">Explore</span>
                            </div>
                        </a>
                        <div class="b-goods__main">
                            <div class="row no-gutters">
                                <div class="col">
                                    <a class="b-goods__title"
                                        href="<?=SITE?>yat/<?=$yacht["seflink"]?>"><?=stripslashes($yacht["baslik"])?></a>
                                    <div class="b-goods__info yacht-meta">
                                        <span><?=stripslashes($yacht["length_m"])?> Meters</span> •
                                        <span><?=stripslashes($yacht["cabin_count"])?> Cabins</span> •
                                        <span><?=stripslashes($yacht["capacity"])?> Guests</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="b-goods__price text-primary">
                                        <span
                                            class="b-goods__price-number">$<?=number_format($yacht["price_per_day"], 0)?>
                                            <span class="b-goods__price-after-price">Per Day</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="b-goods-descrip_nev_wrap">
                                <div class="yacht-features">
                                    <?php
                                    // Yat özelliklerini çek
                                    $features = $VT->VeriGetir("yacht_features_pivot", 
                                        "INNER JOIN yacht_features ON yacht_features_pivot.feature_id = yacht_features.ID 
                                         WHERE yacht_features_pivot.yacht_id=? AND yacht_features.durum=?", 
                                        array($yacht["ID"], 1), "ORDER BY yacht_features.baslik ASC", 5);
                                    
                                    if($features != false) {
                                        foreach($features as $index => $feature) {
                                            echo '<span class="yacht-feature-tag">' . stripslashes($feature["baslik"]) . '</span>';
                                            if($index >= 3 && count($features) > 4) {
                                                echo '<span class="yacht-feature-more">+' . (count($features) - 4) . '</span>';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="text-right mt-3">
                                    <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>" class="btn-link">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a class="btn btn-primary view-all-boats" href="<?=SITE?>yacht-listing">View Our Complete Fleet</a>
            </div>
        </div>
    </div>
</section>

<!-- Essential Charter Information Section -->
<section class="essential-info-section">
    <div class="container">
        <div class="section-title text-center">
            <h2 class="ui-title">Essential Information</h2>
            <div class="decore01 centered-decore"></div>
            <p class="subtitle">Everything you need to know about your luxury yacht experience</p>
        </div>

        <div class="row luxury-info-cards">
            <!-- Booking Process -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="info-title">Booking Process</h4>
                    <ul class="info-list">
                        <li>Inquiry submission</li>
                        <li>Customized itinerary</li>
                        <li>30% reservation deposit</li>
                        <li>Final payment 30 days prior</li>
                        <li>Welcome package delivery</li>
                    </ul>
                    <a href="#" class="info-link">Learn More</a>
                </div>
            </div>

            <!-- Popular Destinations -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h4 class="info-title">Popular Destinations</h4>
                    <ul class="info-list">
                        <li>Turkish Riviera</li>
                        <li>Greek Islands</li>
                        <li>Adriatic Coast</li>
                        <li>French Riviera</li>
                        <li>Amalfi Coast, Italy</li>
                    </ul>
                    <a href="#" class="info-link">Explore Destinations</a>
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
                        <li>Professional crew</li>
                        <li>Gourmet catering</li>
                        <li>Water sports equipment</li>
                        <li>Private event planning</li>
                        <li>Concierge assistance</li>
                    </ul>
                    <a href="#" class="info-link">View Services</a>
                </div>
            </div>

            <!-- Requirements -->
            <div class="col-lg-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="info-title">Requirements</h4>
                    <ul class="info-list">
                        <li>Valid passport/ID</li>
                        <li>Charter agreement</li>
                        <li>Insurance coverage</li>
                        <li>Health declarations</li>
                        <li>Special requests in advance</li>
                    </ul>
                    <a href="#" class="info-link">Full Requirements</a>
                </div>
            </div>
        </div>

        <!-- Charter Policy Highlights -->
        <div class="charter-policy">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="policy-content">
                        <h3 class="policy-title">Our Charter Policy</h3>
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

                        <a href="#" class="btn btn-outline-primary policy-btn">Complete Policy Details</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="policy-image">
                        <img src="assets/img/yacht-captain-hand-on-yacht-steering-wheel-4SBVP5K.jpg"
                            alt="Luxury Yacht Charter Policy" class="img-fluid">
                        <div class="experience-badge">
                            <span class="years">15</span>
                            <span class="text">Years of<br>Excellence</span>
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
<section class="section-gallery">
    <div class="container">
        <div class="text-center">
            <div class="luxury-section-heading">
                <span class="luxury-subheading">OUR EXCLUSIVE COLLECTION</span>
            <h2 class="ui-title">Picture Gallery</h2>
                <div class="luxury-heading-decoration">
                    <span class="line"></span>
                    <span class="diamond"></span>
                    <span class="line"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <p class="luxury-description">Experience the epitome of nautical elegance through our carefully curated visual showcase, each image capturing the essence of luxury and sophistication that defines our yachting experience.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="ui-gallery js-zoom-gallery gallery-with-spacing">
        <?php
        // Get gallery images with a simplified approach
        $gallery = $VT->VeriGetir("resimler", "WHERE tablo=?", array("galeri"), "ORDER BY ID DESC LIMIT 8");
        
        if ($gallery != false && count($gallery) > 0) {
            // Define the base URL for images
            $baseURL = rtrim(SITE, "/");
            
            // Start first row
            echo '<div class="row gallery-row">';
            $count = 0;
            
            foreach ($gallery as $index => $item) {
                $filename = $item["resim"];
                if (empty($filename)) continue;
                
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $isVideo = in_array($extension, array('mp4', 'webm', 'ogg', 'mov'));
                
                // Simplified direct URL construction
                if ($isVideo) {
                    $mediaUrl = $baseURL . "/images/resimler/" . $filename;
                } else {
                    $mediaUrl = $baseURL . "/images/resimler/" . $filename;
                }
                
                // Grid layout for visual interest
                if ($count == 0) {
                    echo '<div class="col-lg-3 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="100">';
                } elseif ($count == 1) {
                    echo '<div class="col-lg-3 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="200">';
                } elseif ($count == 2) {
                    echo '<div class="col-lg-2 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="300">';
                } elseif ($count == 3) {
                    echo '<div class="col-lg-4 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="400">';
                } elseif ($count == 4) {
                    echo '</div><div class="row gallery-row">';
                    echo '<div class="col-lg-5 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="100">';
                } elseif ($count == 5) {
                    echo '<div class="col-lg-2 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="200">';
                } elseif ($count == 6) {
                    echo '<div class="col-lg-3 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="300">';
                } elseif ($count == 7) {
                    echo '<div class="col-lg-2 col-sm-6 gallery-item-col" data-aos="fade-up" data-aos-delay="400">';
                }
                
                $alt = "Luxury Yacht Gallery Image " . ($count + 1);
                
                if ($isVideo) {
                    // Video thumbnail
                    ?>
                    <div class="ui-gallery__img video-thumbnail-container" data-video="<?php echo $mediaUrl; ?>">
                        <div class="video-thumbnail">
                            <div class="video-poster default-video-bg">
                                <i class="fas fa-film"></i>
                            </div>
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- Image with hover overlay -->
                    <a class="ui-gallery__img js-zoom-gallery__item" href="<?php echo $mediaUrl; ?>">
                        <img class="img-scale" src="<?php echo $mediaUrl; ?>" alt="<?php echo $alt; ?>">
                        <div class="gallery-item-overlay">
                            <svg class="gallery-frame" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <path class="frame-line top" d="M0,0 L100,0"></path>
                                <path class="frame-line right" d="M100,0 L100,100"></path>
                                <path class="frame-line bottom" d="M100,100 L0,100"></path>
                                <path class="frame-line left" d="M0,100 L0,0"></path>
                            </svg>
                            <div class="gallery-zoom">
                                <i class="fas fa-search-plus"></i>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <?php
                $count++;
                
                // Close the final row
                if ($count == 8 || $index == count($gallery) - 1) {
                    echo '</div>';
                }
            }
                } else {
            echo '<div class="alert alert-info text-center">No gallery images available yet.</div>';
        }
        ?>
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
<section class="section-reviews area-bg area-bg_dark area-bg_op_90">
    <div class="area-bg__inner section-default">
        <div class="container text-center">
            <div class="text-center">
                <h2 class="ui-title ui-title_light">What People Says...</h2>
                <div class="row">
                    <div class="col-md-8 offset-md-2"> <img src="assets/img/decore03.png" alt="photo"> </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="b-reviews-slider js-slider"
                        data-slick="{&quot;slidesToShow&quot;: 1, &quot;slidesToScroll&quot;: 1, &quot;autoplay&quot;: true, &quot;dots&quot;: false, &quot;arrows&quot;: false}">
                        <blockquote class="b-reviews">
                            <div class="b-seller__img"><img class="img-scale" src="assets/img/avatar.jpg" alt="foto">
                            </div>
                            <div class="b-reviews__text">Exercit ullamco laboris nisiut aliquip ex ea com irure
                                dolor reprehs tempor incididunt ut labore dolore magna aliqua quis nostrud sed
                                exercitation ullamco laboris nisiut duis aute irure sit amet, consectetur
                                adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. </div>
                            <div class="b-reviews__footer">
                                <div class="b-reviews__name">Donald James</div>
                                <div class="b-reviews__category">Customer</div>
                            </div>
                        </blockquote>
                        <!-- end .b-reviews-->
                        <blockquote class="b-reviews">
                            <div class="b-seller__img"><img class="img-scale" src="assets/img/avatar.jpg" alt="foto">
                            </div>
                            <div class="b-reviews__text">Exercit ullamco laboris nisiut aliquip ex ea com irure
                                dolor reprehs tempor incididunt ut labore dolore magna aliqua quis nostrud sed
                                exercitation ullamco laboris nisiut duis aute irure sit amet, consectetur
                                adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. </div>
                            <div class="b-reviews__footer">
                                <div class="b-reviews__name">Donald James</div>
                                <div class="b-reviews__category">Customer</div>
                            </div>
                        </blockquote>
                        <!-- end .b-reviews-->
                        <blockquote class="b-reviews">
                            <div class="b-seller__img"><img class="img-scale" src="assets/img/avatar.jpg" alt="foto">
                            </div>
                            <div class="b-reviews__text">Exercit ullamco laboris nisiut aliquip ex ea com irure
                                dolor reprehs tempor incididunt ut labore dolore magna aliqua quis nostrud sed
                                exercitation ullamco laboris nisiut duis aute irure sit amet, consectetur
                                adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. </div>
                            <div class="b-reviews__footer">
                                <div class="b-reviews__name">Donald James</div>
                                <div class="b-reviews__category">Customer</div>
                            </div>
                        </blockquote>
                        <!-- end .b-reviews-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-article section-default">
    <div class="container">
        <div class="text-center">
            <h2 class="ui-title">Industry News</h2>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <p>Dolore magna aliqua enim ad minim veniam, quis nostrud exercitation aliquip duis aute irure
                        dolorin <br> reprehenderits vol dolore fugiat nulla pariatur excepteur sint occaecat
                        cupidatat.</p> <img src="assets/img/decore04.png" alt="photo">
                </div>
            </div>
        </div>
        <div class="pt-2 row">
            <div class="col-xl-4 col-md-4">
                <section class="b-post b-post-3">
                    <div class="entry-media">
                        <a href="post.html"><img class="img-scale" src="assets/img/sailing-on-vacation-EUT5FWG.jpg"
                                alt="photo" /></a>
                    </div>
                    <div class="entry-meta">
                        <time class="entry-meta__item" datetime="2019-01-31">June 15, 2020</time> <span
                            class="entry-meta__item">
                            by
                            <a class="entry-meta__link text-primary" href="blog.html">Nevica</a></span>
                    </div>
                    <div class="entry-main">
                        <div class="entry-header">
                            <h2 class="entry-title"><a href="post.html">Corporate yacht for smooth
                                    running of main events</a></h2>
                        </div>
                        <div class="entry-content">Aiusmod tempor incididunt labore dolore magna ust enim sed
                            veniams quis nostrud</div>
                    </div> <a class="btn-post" href="post.html">Read More</a>
                </section>
                <!-- end .b-post-->
            </div>
            <div class="col-xl-4 col-md-4">
                <section class="b-post b-post-3">
                    <div class="entry-media">
                        <a href="post.html"><img class="img-scale" src="assets/img/326576456534.jpg" alt="photo" /></a>
                    </div>
                    <div class="entry-meta">
                        <time class="entry-meta__item" datetime="2019-01-31">June 12, 2020</time> <span
                            class="entry-meta__item">
                            by
                            <a class="entry-meta__link text-primary" href="blog.html">Nevica</a></span>
                    </div>
                    <div class="entry-main">
                        <div class="entry-header">
                            <h2 class="entry-title"><a href="post.html">The Best staff members for
                                    your service available</a></h2>
                        </div>
                        <div class="entry-content">Aiusmod tempor incididunt labore dolore magna ust enim sed
                            veniams quis nostrud</div>
                    </div> <a class="btn-post" href="post.html">Read More</a>
                </section>
                <!-- end .b-post-->
            </div>
            <div class="col-xl-4 col-md-4">
                <section class="b-post b-post-3">
                    <div class="entry-media">
                        <a href="post.html"><img class="img-scale" src="assets/img/sailing-on-vacation-EUT5FWG34.jpg"
                                alt="photo" /></a>
                    </div>
                    <div class="entry-meta">
                        <time class="entry-meta__item" datetime="2019-01-31">June 3, 2020</time> <span
                            class="entry-meta__item">
                            by
                            <a class="entry-meta__link text-primary" href="blog.html">Nevica</a></span>
                    </div>
                    <div class="entry-main">
                        <div class="entry-header">
                            <h2 class="entry-title"><a href="post.html">Don't worry about comfort
                                    and company facilities</a></h2>
                        </div>
                        <div class="entry-content">Aiusmod tempor incididunt labore dolore magna ust enim sed
                            veniams quis nostrud</div>
                    </div> <a class="btn-post" href="post.html">Read More</a>
                </section>
                <!-- end .b-post-->
            </div>
        </div>
    </div>
</section>
<section class="section-default section-banners">
    <div class="container">
        <div class="text-center"> <img src="assets/img/banners.jpg" alt="photo"> </div>
    </div>
</section>
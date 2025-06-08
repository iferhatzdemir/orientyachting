<?php
if(!defined("SABIT")) define("SABIT", true);

// Get site settings for contact information
$contactInfo = $VT->VeriGetir("ayarlar", "WHERE ID=?", array(1), "ORDER BY ID ASC", 1);
if($contactInfo != false) {
    $phone = $contactInfo[0]["telefon"];
    $email = $contactInfo[0]["mail"];
    $address = $contactInfo[0]["adres"];
    $fax = $contactInfo[0]["fax"];
    $phone2 = $contactInfo[0]["telefon2"];
    $email2 = $contactInfo[0]["mail2"];
} else {
    // Default values if database fetch fails
    $phone = "+90 123 456 7890";
    $email = "info@orientyachting.com";
    $address = "Marina Bay, Istanbul, Turkey";
    $fax = "+90 123 456 7891";
    $phone2 = "";
    $email2 = "";
}

// Handle contact form submission
$formMessage = "";
$formSuccess = false;

if(isset($_POST["contact_submit"]) && $_POST["contact_submit"] == "1") {
    if(
        !empty($_POST["name"]) && 
        !empty($_POST["email"]) && 
        !empty($_POST["subject"]) && 
        !empty($_POST["message"])
    ) {
        $name = $VT->filter($_POST["name"]);
        $email = $VT->filter($_POST["email"]);
        $phone = isset($_POST["phone"]) ? $VT->filter($_POST["phone"]) : "";
        $subject = $VT->filter($_POST["subject"]);
        $message = $VT->filter($_POST["message"]);
        $date = date("Y-m-d H:i:s");
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formMessage = '<div class="contact-alert contact-alert-error"><i class="fas fa-exclamation-circle"></i> Please enter a valid email address.</div>';
        } else {
            // Insert to database
            $insertData = $VT->SorguCalistir("INSERT INTO iletisim", 
                "SET adsoyad=?, email=?, telefon=?, konu=?, mesaj=?, tarih=?, durum=?",
                array($name, $email, $phone, $subject, $message, $date, 1)
            );
            
            if($insertData != false) {
                // Send email notification
                $mailContent = "Name: $name\n";
                $mailContent .= "Email: $email\n";
                $mailContent .= "Phone: $phone\n";
                $mailContent .= "Subject: $subject\n\n";
                $mailContent .= "Message:\n$message";
                
                // You can add mail functionality here
                // mail($contactInfo[0]["mail"], "New Contact Form Submission", $mailContent);
                
                $formSuccess = true;
                $formMessage = '<div class="contact-alert contact-alert-success"><i class="fas fa-check-circle"></i> Your message has been sent successfully. We will get back to you as soon as possible.</div>';
            } else {
                $formMessage = '<div class="contact-alert contact-alert-error"><i class="fas fa-exclamation-circle"></i> An error occurred while sending your message. Please try again later.</div>';
            }
        }
    } else {
        $formMessage = '<div class="contact-alert contact-alert-warning"><i class="fas fa-exclamation-triangle"></i> Please fill in all required fields.</div>';
    }
}

// SEO meta tags
$seo = $VT->VeriGetir("seo", "WHERE seo_url=? AND durum=?", array("contact", 1), "ORDER BY ID ASC", 1);
if($seo === false) {
    // Set default values
    $seo = array(array(
        "title" => "Contact Us - Orient Yachting | Luxury Yacht Charter",
        "description" => "Get in touch with Orient Yachting for luxury yacht charter, management and other premium services. Experience the finest in maritime luxury.",
        "keywords" => "contact, yacht, charter, luxury yacht, management, inquiries, VIP service",
    ));
}
?>

<title><?=$seo[0]["title"]?></title>
<meta name="description" content="<?=$seo[0]["description"]?>">
<meta name="keywords" content="<?=$seo[0]["keywords"]?>">

<!-- Open Graph Tags -->
<meta property="og:title" content="<?=$seo[0]["title"]?>">
<meta property="og:description" content="<?=$seo[0]["description"]?>">
<meta property="og:url" content="<?=SITE?>contact">
<meta property="og:type" content="website">
<meta property="og:image" content="<?=SITE?>images/yacht-og-image.jpg">

<!-- Twitter Card Tags -->
<meta name="twitter:title" content="<?=$seo[0]["title"]?>">
<meta name="twitter:description" content="<?=$seo[0]["description"]?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?=SITE?>images/yacht-twitter-image.jpg">

<!-- Custom Styling -->
<style>
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideInFromLeft {
    0% { transform: translateX(-50px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

@keyframes borderPulse {
    0% { box-shadow: 0 0 0 0 rgba(200, 169, 126, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(200, 169, 126, 0); }
    100% { box-shadow: 0 0 0 0 rgba(200, 169, 126, 0); }
}

.contact-page {
    font-family: 'Montserrat', 'Arial', sans-serif;
    color: #333;
    line-height: 1.6;
}

.hero-banner {
    position: relative;
    background-color: #0a1f35;
    background-image: url('<?=SITE?>images/contact-bg.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    height: 600px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 0;
}

.hero-banner:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,20,0.3) 0%, rgba(0,0,20,0.7) 100%);
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
    opacity: 0.07;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 900px;
    padding: 0 20px;
    animation: fadeIn 1.2s ease-out;
    margin-top: 50px;
}

.page-title {
    color: #fff;
    font-size: 62px;
    font-weight: 700;
    margin: 0 0 20px;
    text-align: center;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    letter-spacing: 2px;
    opacity: 0;
    animation: slideInFromLeft 1s ease-out forwards;
    animation-delay: 0.3s;
    text-transform: uppercase;
    font-family: 'Playfair Display', serif;
}

.page-subtitle {
    color: #fff;
    font-size: 24px;
    margin-bottom: 30px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    opacity: 0;
    animation: fadeIn 1s ease-out forwards;
    animation-delay: 0.6s;
}

.hero-line {
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
    margin: 25px auto 30px;
    opacity: 0;
    animation: fadeIn 1s ease-out forwards, shimmer 3s infinite linear;
    animation-delay: 0.8s;
    background-size: 200% 100%;
}

.breadcrumb-nav {
    position: absolute;
    bottom: 50px;
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
    padding: 12px 30px;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(200, 169, 126, 0.3);
}

.breadcrumb a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: #c8a97e;
    text-decoration: none;
}

.contact-section {
    background-color: #f8f9fa;
    background-image: radial-gradient(ellipse at top, #f9f9f9, #f1f1f1);
    padding: 100px 0;
    position: relative;
}

.contact-section:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #0a1f35, #c8a97e, #0a1f35);
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-title {
    font-size: 42px;
    font-weight: 700;
    color: #1B2B44;
    margin-bottom: 50px;
    text-align: center;
    position: relative;
    font-family: 'Playfair Display', serif;
}

.section-title:after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background: #C8A97E;
    margin: 20px auto 0;
}

.contact-info-box {
    background-color: #fff;
    border-radius: 15px;
    padding: 40px 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.4s ease;
    height: 100%;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
    text-align: center;
}

.contact-info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.contact-info-icon {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #1B2B44;
    border: 2px solid #C8A97E;
    border-radius: 50%;
    margin: 0 auto 25px;
    transition: all 0.4s ease;
    background: #fff;
    position: relative;
}

.contact-info-icon:before {
    content: '';
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    border-radius: 50%;
    border: 2px solid #C8A97E;
    opacity: 0.3;
}

.contact-info-icon i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    transition: all 0.4s ease;
}

.contact-info-box:hover .contact-info-icon {
    background: #1B2B44;
    color: #C8A97E;
    transform: rotateY(180deg);
}

.contact-info-box:hover .contact-info-icon:before {
    border-color: #1B2B44;
}

.contact-info-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #1B2B44;
    font-family: 'Playfair Display', serif;
}

.contact-info-text {
    color: #666;
    font-size: 16px;
    line-height: 1.8;
    margin: 0;
}

.contact-info-text a {
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.contact-info-text a:hover {
    color: #C8A97E;
}

.contact-form-box {
    background-color: #fff;
    border-radius: 10px;
    padding: 50px 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.contact-form-box:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #0a1f35, #c8a97e);
}

.form-group {
    margin-bottom: 25px;
    position: relative;
}

.form-control {
    height: 58px;
    border: 2px solid #eaeaea;
    border-radius: 6px;
    padding: 10px 15px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
    color: #333;
    box-shadow: none;
    font-weight: 400;
    padding-top: 20px;
}

.form-control:focus {
    border-color: #c8a97e;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    background-color: #fff;
}

.form-label {
    position: absolute;
    top: 19px;
    left: 15px;
    font-size: 16px;
    color: #777;
    transition: all 0.3s ease;
    pointer-events: none;
    font-weight: 500;
}

.form-control:focus ~ .form-label,
.form-control:not(:placeholder-shown) ~ .form-label {
    top: 8px;
    left: 15px;
    font-size: 12px;
    color: #c8a97e;
    font-weight: 600;
}

textarea.form-control {
    height: 180px;
    resize: none;
    padding-top: 25px;
}

.btn-contact {
    height: 58px;
    border-radius: 6px;
    background: linear-gradient(135deg, #0a1f35, #183b5f);
    border: none;
    padding: 0 35px;
    font-size: 18px;
    font-weight: 600;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    z-index: 1;
    width: 100%;
    cursor: pointer;
}

.btn-contact:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #c8a97e, #e0c9a9);
    z-index: -1;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    transform: scaleX(0);
    transform-origin: right;
}

.btn-contact:hover:before {
    transform: scaleX(1);
    transform-origin: left;
}

.btn-contact:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    color: #0a1f35;
}

.contact-alert {
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 25px;
    font-size: 16px;
    display: flex;
    align-items: center;
}

.contact-alert i {
    margin-right: 15px;
    font-size: 20px;
}

.contact-alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.contact-alert-error {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.contact-alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border: 1px solid rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.map-container {
    position: relative;
    height: 500px;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    margin-top: 80px;
}

.map-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0);
    z-index: 1;
    pointer-events: none;
}

.map-title {
    position: absolute;
    top: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    padding: 15px 30px;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    color: #0a1f35;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    z-index: 2;
    font-family: 'Playfair Display', serif;
    border: 1px solid rgba(200, 169, 126, 0.3);
}

.map-title:before {
    content: '';
    position: absolute;
    top: 50%;
    left: -5px;
    right: -5px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #c8a97e, transparent);
    z-index: -1;
}

iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.social-links {
    text-align: center;
    margin-top: 60px;
}

.social-title {
    font-size: 24px;
    font-weight: 600;
    color: #0a1f35;
    margin-bottom: 25px;
    font-family: 'Playfair Display', serif;
}

.social-list {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.social-item {
    display: inline-block;
}

.social-link {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fff;
    color: #0a1f35;
    font-size: 22px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 2px solid transparent;
}

.social-link:hover {
    background: #0a1f35;
    color: #c8a97e;
    transform: translateY(-5px);
    border-color: #c8a97e;
    box-shadow: 0 15px 25px rgba(0,0,0,0.1);
}

.required-text {
    color: #888;
    font-size: 14px;
    margin-top: 15px;
    text-align: right;
}

.required-mark {
    color: #c8a97e;
    font-weight: 600;
}

@media (max-width: 991px) {
    .page-title {
        font-size: 48px;
    }
    
    .hero-banner {
        height: 500px;
    }
    
    .contact-form-box {
        margin-top: 50px;
    }
    
    .contact-info-icon {
        width: 100px;
        height: 100px;
        font-size: 24px;
    }
    
    .contact-info-icon:before {
        top: -6px;
        left: -6px;
        right: -6px;
        bottom: -6px;
    }
    
    .contact-info-title {
        font-size: 20px;
    }
}

@media (max-width: 767px) {
    .page-title {
        font-size: 36px;
    }
    
    .page-subtitle {
        font-size: 18px;
    }
    
    .hero-banner {
        height: 400px;
    }
    
    .contact-section {
        padding: 60px 0;
    }
    
    .section-title {
        font-size: 32px;
        margin-bottom: 40px;
    }
    
    .contact-info-icon {
        width: 90px;
        height: 90px;
        font-size: 22px;
    }
    
    .contact-info-icon:before {
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
    }
    
    .map-container {
        height: 400px;
    }
    
    .social-link {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
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

<section class="contact-page">
    <!-- Hero Banner -->
    <div class="hero-banner" style="background-image: url('<?=SITE?>images/contact-bg.jpg');">
        <div class="hero-bg-pattern"></div>
        <div class="hero-content">
            <h1 class="page-title">Contact Us</h1>
            <div class="hero-line"></div>
            <p class="page-subtitle">We're Ready to Assist You with Premium Yacht Services</p>
        </div>
        <div class="breadcrumb-nav">
            <div class="breadcrumb">
                <a href="<?=SITE?>">Home</a> / Contact Us
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

    <!-- Contact Section -->
    <div class="contact-section">
        <div class="contact-container">
            <h2 class="section-title">Get In Touch</h2>
            
            <div class="row">
                <!-- Contact Info Boxes -->
                <div class="col-lg-4">
                    <div class="contact-info-box">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="contact-info-title">Our Location</h3>
                        <p class="contact-info-text"><?=$address?></p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="contact-info-box">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3 class="contact-info-title">Phone Numbers</h3>
                        <p class="contact-info-text">
                            <a href="tel:<?=$phone?>"><?=$phone?></a>
                            <?php if(!empty($phone2)): ?>
                            <br>
                            <a href="tel:<?=$phone2?>"><?=$phone2?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="contact-info-box">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="contact-info-title">Email Address</h3>
                        <p class="contact-info-text">
                            <a href="mailto:<?=$email?>"><?=$email?></a>
                            <?php if(!empty($email2)): ?>
                            <br>
                            <a href="mailto:<?=$email2?>"><?=$email2?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-box">
                        <?=$formMessage?>
                        
                        <form id="contactForm" method="post" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name" placeholder=" " required>
                                        <label class="form-label" for="name">Full Name <span class="required-mark">*</span></label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                                        <label class="form-label" for="email">Email Address <span class="required-mark">*</span></label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder=" ">
                                        <label class="form-label" for="phone">Phone Number</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder=" " required>
                                        <label class="form-label" for="subject">Subject <span class="required-mark">*</span></label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="message" name="message" rows="6" placeholder=" " required></textarea>
                                        <label class="form-label" for="message">Your Message <span class="required-mark">*</span></label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <input type="hidden" name="contact_submit" value="1">
                                    <button type="submit" class="btn-contact">Send Message</button>
                                    <p class="required-text">Fields marked with <span class="required-mark">*</span> are required</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Map -->
                <div class="col-lg-5">
                    <div class="map-container">
                        <div class="map-overlay"></div>
                        <div class="map-title">Find Us On The Map</div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3009.8178067818188!2d28.979756414959094!3d41.02584792637255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab9bd6570f4e1%3A0xe0585a386e564cc7!2sKaraköy%2C%20Galata%20Bridge%2C%2034425%20Beyoğlu%2Fİstanbul%2C%20Turkey!5e0!3m2!1sen!2sus!4v1652866834055!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            
            <!-- Social Links -->
            <div class="social-links">
                <h3 class="social-title">Connect With Us</h3>
                <ul class="social-list">
                    <li class="social-item">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="social-item">
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li class="social-item">
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li class="social-item">
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </li>
                    <li class="social-item">
                        <a href="#" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Form Validation JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Get form fields
            const nameField = document.getElementById('name');
            const emailField = document.getElementById('email');
            const subjectField = document.getElementById('subject');
            const messageField = document.getElementById('message');
            
            // Reset any previous error styles
            [nameField, emailField, subjectField, messageField].forEach(field => {
                field.style.borderColor = '';
            });
            
            // Validate name
            if (!nameField.value.trim()) {
                nameField.style.borderColor = '#dc3545';
                isValid = false;
            }
            
            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailField.value.trim() || !emailRegex.test(emailField.value.trim())) {
                emailField.style.borderColor = '#dc3545';
                isValid = false;
            }
            
            // Validate subject
            if (!subjectField.value.trim()) {
                subjectField.style.borderColor = '#dc3545';
                isValid = false;
            }
            
            // Validate message
            if (!messageField.value.trim()) {
                messageField.style.borderColor = '#dc3545';
                isValid = false;
            }
            
            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Add input event listeners to clear error styles when user types
        const formFields = contactForm.querySelectorAll('.form-control');
        formFields.forEach(field => {
            field.addEventListener('input', function() {
                this.style.borderColor = '';
            });
        });
    }
    
    // Add animation on scroll if AOS library is available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
});
</script> 
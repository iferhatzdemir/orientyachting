<?php
/**
 * Premium Yacht Detail Page Template
 * Ultra-Modern Luxury Design
 */

// Get Yacht ID and SEO link from URL
$yacht_id = isset($_GET["id"]) ? $_GET["id"] : 0;
$yacht_seo = isset($_GET["seflink"]) ? $_GET["seflink"] : "";

// Database connection
include_once(SINIF."VT.php");
$VT = new VT();

// Get yacht data
$yacht = false;
if(!empty($yacht_seo)) {
    $yacht = $VT->VeriGetir("yachts", "WHERE seflink=?", array($yacht_seo));
} elseif($yacht_id > 0) {
    $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($yacht_id));
}

// Set yacht data
$yachtData = array(
    "name" => "Golden Odyssey",
    "location" => "Mediterranean Sea",
    "price" => "€25,000",
    "price_period" => "per week",
    "length" => "42m",
    "guests" => "12",
    "cabins" => "6",
    "crew" => "8",
    "built_year" => "2021",
    "refit_year" => "2023",
    "speed" => "22 knots",
    "builder" => "Benetti",
    "main_image" => SITE."images/yachts/default-yacht.jpg",
    "description" => "Experience unparalleled luxury aboard our prestigious yacht. This magnificent vessel blends elegant design with cutting-edge technology to create the perfect environment for your exclusive journey. Indulge in the spacious interiors featuring premium materials and bespoke furnishings, or enjoy the expansive deck areas perfect for entertaining and relaxation."
);

// If yacht data found, override defaults
if($yacht != false) {
    $yacht = $yacht[0]; // Get first row
    $yacht_id = $yacht["ID"];
    
    $yachtData["name"] = $yacht["baslik"] ?? $yachtData["name"];
    $yachtData["price"] = isset($yacht["fiyat"]) && !empty($yacht["fiyat"]) ? "€" . number_format($yacht["fiyat"]) : $yachtData["price"];
    $yachtData["location"] = $yacht["konum"] ?? $yachtData["location"];
    $yachtData["length"] = $yacht["uzunluk"] ?? $yachtData["length"];
    $yachtData["guests"] = $yacht["kapasite"] ?? $yachtData["guests"];
    $yachtData["cabins"] = $yacht["kabin"] ?? $yachtData["cabins"];
    $yachtData["crew"] = $yacht["murettebat"] ?? $yachtData["crew"];
    $yachtData["built_year"] = $yacht["yil"] ?? $yachtData["built_year"];
    $yachtData["refit_year"] = $yacht["refit"] ?? $yachtData["refit_year"];
    $yachtData["speed"] = $yacht["hiz"] . " knots" ?? $yachtData["speed"];
    $yachtData["builder"] = $yacht["tersane"] ?? $yachtData["builder"];
    $yachtData["description"] = $yacht["description"] ?? $yachtData["description"];
    
    // Get main image
    if(isset($yacht["resim"]) && !empty($yacht["resim"])) {
        $yachtData["main_image"] = SITE . "images/yachts/" . $yacht["resim"];
    }
}

// Get all yacht images
$galleryImages = array();

// Try to get images from database
if(isset($yacht_id) && $yacht_id > 0) {
    $images = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("yachts", $yacht_id));
    
    if($images) {
        foreach($images as $img) {
            // Check if file exists in images/resimler/ directory
            $resimler_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . 'images/resimler/' . $img["resim"];
            $yachts_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . 'images/yachts/' . $img["resim"];
            
            if(file_exists($resimler_path)) {
                $galleryImages[] = SITE . "images/resimler/" . $img["resim"];
            } 
            elseif(file_exists($yachts_path)) {
                $galleryImages[] = SITE . "images/yachts/" . $img["resim"];
            }
            // If image doesn't exist in either location, don't add to the gallery
        }
    }
}

// If no images found, add some default ones
if(empty($galleryImages)) {
    $galleryImages = array(
        SITE . "images/yachts/default-yacht-1.jpg",
        SITE . "images/yachts/default-yacht-2.jpg",
        SITE . "images/yachts/default-yacht-3.jpg",
        SITE . "images/yachts/default-yacht-4.jpg",
        SITE . "images/yachts/default-yacht-5.jpg"
    );
}

// Add CSS for this page
echo '<link rel="stylesheet" href="'.SITE.'css/yacht-detail.css">';
?>

<!-- Yacht Detail Hero Section -->
<section class="yacht-hero" style="background-image: url('<?php echo $yachtData["main_image"]; ?>');">
    <div class="yacht-hero-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="yacht-name fade-in"><?php echo $yachtData["name"]; ?></h1>
                    <div class="yacht-location fade-in">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo $yachtData["location"]; ?>
                    </div>
                    <div class="yacht-price fade-in">
                        <span class="yacht-price-label">From</span>
                        <?php echo $yachtData["price"]; ?> <span class="yacht-price-period"><?php echo $yachtData["price_period"]; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yacht Overview Section -->
<section class="yacht-overview-section">
    <div class="container">
        <div class="yacht-overview fade-in">
            <div class="yacht-overview-grid">
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-ruler-horizontal"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["length"]; ?></div>
                    <div class="yacht-spec-label">Length</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["guests"]; ?></div>
                    <div class="yacht-spec-label">Guests</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["cabins"]; ?></div>
                    <div class="yacht-spec-label">Cabins</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["crew"]; ?></div>
                    <div class="yacht-spec-label">Crew</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yacht Description Section -->
<section class="yacht-content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="fade-in">
                    <h2 class="section-heading">About <?php echo $yachtData["name"]; ?></h2>
                    <div class="yacht-description">
                        <?php echo $yachtData["description"]; ?>
                    </div>
                </div>
                
                <!-- Yacht Gallery -->
                <div class="yacht-gallery fade-in">
                    <h2 class="section-heading">Gallery</h2>
                    <div class="gallery-grid">
                        <?php
                        // Display gallery images
                        foreach($galleryImages as $index => $img) {
                            $featuredClass = ($index === 0) ? 'featured' : '';
                            ?>
                            <div class="gallery-item <?php echo $featuredClass; ?>">
                                <img src="<?php echo $img; ?>" alt="<?php echo $yachtData["name"]; ?> - Image <?php echo $index+1; ?>">
                                <div class="zoom-icon">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Yacht Specifications -->
                <div class="fade-in">
                    <h2 class="section-heading">Specifications</h2>
                    <table class="specs-table">
                        <tr>
                            <td>Builder</td>
                            <td><?php echo $yachtData["builder"]; ?></td>
                        </tr>
                        <tr>
                            <td>Year Built</td>
                            <td><?php echo $yachtData["built_year"]; ?></td>
                        </tr>
                        <tr>
                            <td>Refit</td>
                            <td><?php echo $yachtData["refit_year"]; ?></td>
                        </tr>
                        <tr>
                            <td>Length</td>
                            <td><?php echo $yachtData["length"]; ?></td>
                        </tr>
                        <tr>
                            <td>Guests</td>
                            <td><?php echo $yachtData["guests"]; ?> guests in <?php echo $yachtData["cabins"]; ?> cabins</td>
                        </tr>
                        <tr>
                            <td>Crew</td>
                            <td><?php echo $yachtData["crew"]; ?> professional crew members</td>
                        </tr>
                        <tr>
                            <td>Cruising Speed</td>
                            <td><?php echo $yachtData["speed"]; ?></td>
                        </tr>
                    </table>
                </div>
                
                <!-- Yacht Amenities -->
                <div class="fade-in">
                    <h2 class="section-heading">Amenities & Toys</h2>
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div class="amenity-text">High-speed WiFi</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-swimming-pool"></i>
                            </div>
                            <div class="amenity-text">Jacuzzi on deck</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-satellite-dish"></i>
                            </div>
                            <div class="amenity-text">Satellite TV</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-wind"></i>
                            </div>
                            <div class="amenity-text">Air conditioning</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="amenity-text">Gym equipment</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-water"></i>
                            </div>
                            <div class="amenity-text">Jet skis (2)</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-wine-glass-alt"></i>
                            </div>
                            <div class="amenity-text">Premier wine selection</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="amenity-text">Gourmet chef</div>
                        </div>
                        
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                <i class="fas fa-ship"></i>
                            </div>
                            <div class="amenity-text">Tender boat</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="booking-sidebar fade-in">
                    <h3 class="booking-sidebar-title">Plan Your Voyage</h3>
                    <p class="booking-sidebar-text">Interested in chartering this luxury yacht? Reach out to our team for availability and personalized service.</p>
                    
                    <form id="booking-form" class="booking-form">
                        <div class="form-group">
                            <label for="guest-name" class="form-label">Full Name</label>
                            <input type="text" id="guest-name" class="form-control" placeholder="Your Name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="guest-email" class="form-label">Email Address</label>
                            <input type="email" id="guest-email" class="form-control" placeholder="your@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="guest-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="guest-phone" class="form-control" placeholder="+1 234 567 8900">
                        </div>
                        
                        <div class="form-group">
                            <label for="start-date" class="form-label">Charter Start Date</label>
                            <input type="date" id="start-date" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="end-date" class="form-label">Charter End Date</label>
                            <input type="date" id="end-date" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="guests" class="form-label">Number of Guests</label>
                            <select id="guests" class="form-control">
                                <?php for($i = 1; $i <= $yachtData["guests"]; $i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> guest<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Additional Requests</label>
                            <textarea id="message" class="form-control" rows="4" placeholder="Special requirements or questions..."></textarea>
                        </div>
                        
                        <button type="submit" id="booking-submit" class="btn-booking">Request Availability</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Section (Full Width for Mobile) -->
<section id="booking-section" class="booking-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center fade-in">
                <h2 class="section-heading">Begin Your Luxury Journey</h2>
                <p class="booking-text">Experience the epitome of luxury and comfort aboard <?php echo $yachtData["name"]; ?>. Our professional crew is ready to ensure your journey exceeds all expectations.</p>
                <a href="#booking-form" class="btn-booking">Book Your Charter</a>
            </div>
        </div>
    </div>
</section>

<!-- Floating Booking Button (Mobile Only) -->
<div class="floating-booking-btn">
    <i class="fas fa-calendar-alt"></i>
</div>

<!-- Lightbox Gallery -->
<div class="lightbox-overlay">
    <div class="lightbox-container">
        <img src="" class="lightbox-image" alt="Yacht Gallery Image">
        <div class="lightbox-close">&times;</div>
        <div class="lightbox-nav lightbox-prev"><i class="fas fa-chevron-left"></i></div>
        <div class="lightbox-nav lightbox-next"><i class="fas fa-chevron-right"></i></div>
    </div>
</div>

<!-- Add JavaScript for the yacht detail page -->
<script src="<?php echo SITE; ?>assets/js/yacht-detail.js"></script>

<!-- Structured Data for SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "<?php echo $yachtData["name"]; ?>",
    "description": "<?php echo strip_tags($yachtData["description"]); ?>",
    "image": "<?php echo $yachtData["main_image"]; ?>",
    "offers": {
        "@type": "Offer",
        "priceCurrency": "EUR",
        "price": "<?php echo str_replace(array('€', ','), '', $yachtData["price"]); ?>",
        "availability": "https://schema.org/InStock"
    },
    "brand": {
        "@type": "Brand",
        "name": "<?php echo $yachtData["builder"]; ?>"
    }
}
</script> 
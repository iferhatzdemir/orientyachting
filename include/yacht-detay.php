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

// Default image paths for fallback
$default_yacht_images = array(
    SITE . "assets/img/ocean-background.jpg",
    SITE . "assets/img/yacht-1.jpg",
    SITE . "assets/img/yacht-2.jpg",
    SITE . "assets/img/yacht-3.jpg",
    SITE . "assets/img/yacht-4.jpg"
);

// Get location name if we have location_id
$location_name = "Mediterranean Sea";
if($yacht && isset($yacht[0]["location_id"]) && !empty($yacht[0]["location_id"])) {
    $location_data = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($yacht[0]["location_id"]));
    if($location_data) {
        $location_name = $location_data[0]["baslik"];
    }
}

// Get yacht type if we have type_id
$yacht_type = "Motor Yacht";
if($yacht && isset($yacht[0]["type_id"]) && !empty($yacht[0]["type_id"])) {
    $type_data = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($yacht[0]["type_id"]));
    if($type_data) {
        $yacht_type = $type_data[0]["baslik"];
    }
}

// Set yacht data
$yachtData = array(
    "name" => "Luxury Yacht",
    "location" => $location_name,
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
    "description" => "Experience unparalleled luxury aboard our prestigious yacht. This magnificent vessel blends elegant design with cutting-edge technology to create the perfect environment for your exclusive journey."
);

// If yacht data found, override defaults
if($yacht != false) {
    $yacht = $yacht[0]; // Get first row
    $yacht_id = $yacht["ID"];
    
    $yachtData["name"] = $yacht["baslik"] ?? $yachtData["name"];
    
    // Format prices based on database fields
    if(isset($yacht["price_per_week"]) && !empty($yacht["price_per_week"])) {
        $yachtData["price"] = "€" . number_format($yacht["price_per_week"]);
        $yachtData["price_period"] = "per week";
    } elseif(isset($yacht["price_per_day"]) && !empty($yacht["price_per_day"])) {
        $yachtData["price"] = "€" . number_format($yacht["price_per_day"]);
        $yachtData["price_period"] = "per day";
    }
    
    $yachtData["location"] = $location_name;
    $yachtData["length"] = isset($yacht["length_m"]) ? $yacht["length_m"] . "m" : $yachtData["length"];
    $yachtData["guests"] = $yacht["capacity"] ?? $yacht["guest_capacity"] ?? $yachtData["guests"];
    $yachtData["cabins"] = $yacht["cabin_count"] ?? $yachtData["cabins"];
    $yachtData["crew"] = $yacht["crew"] ?? $yachtData["crew"];
    $yachtData["built_year"] = $yacht["build_year"] ?? $yachtData["built_year"];
    $yachtData["refit_year"] = $yacht["refit_year"] ?? $yachtData["refit_year"];
    $yachtData["speed"] = isset($yacht["speed"]) ? $yacht["speed"] . " knots" : $yachtData["speed"];
    $yachtData["builder"] = $yacht["builder"] ?? $yachtData["builder"];
    $yachtData["description"] = $yacht["metin"] ?? $yachtData["description"];
    
    // Get main image
    if(isset($yacht["resim"]) && !empty($yacht["resim"])) {
        $yachtData["main_image"] = SITE . "images/yachts/" . $yacht["resim"];
    }
}

// Get all yacht images and videos
$galleryMedia = array();

// Try to get images and videos from database
if(isset($yacht_id) && $yacht_id > 0) {
    // İlk olarak, ana resmi ekleyelim
    if(isset($yacht) && !empty($yacht["resim"])) {
        $galleryMedia[] = array(
            'url' => SITE . "images/yachts/" . $yacht["resim"],
            'type' => 'image'
        );
    }
    
    // Şimdi de veritabanındaki diğer resimleri ekleyelim
    $media = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("yachts", $yacht_id));
    
    if($media) {
        foreach($media as $item) {
            // Check if it's a video file
            $filename = $item["resim"];
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $isVideo = in_array($extension, array('mp4', 'webm', 'ogg', 'mov'));
            
            // Determine URL based on file type
            $mediaUrl = '';
            $mediaType = 'image';
            
            if($isVideo) {
                $mediaType = 'video';
                // Video dosyaları videos klasöründe
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . 'images/yachts/videos/' . $filename)) {
                    $mediaUrl = SITE . "images/yachts/videos/" . $filename;
                } else {
                    $mediaUrl = SITE . "images/yachts/videos/" . $filename; // Varsayılan yol
                }
            } else {
                // Resim dosyaları images/yachts klasöründe
                $mediaUrl = SITE . "images/yachts/" . $filename;
}

            // Only add if not already in the gallery
            $isDuplicate = false;
            foreach($galleryMedia as $existingMedia) {
                if($existingMedia['url'] === $mediaUrl) {
                    $isDuplicate = true;
                    break;
                }
            }
            
            if(!$isDuplicate && !empty($mediaUrl)) {
                $galleryMedia[] = array(
                    'url' => $mediaUrl,
                    'type' => $mediaType
                );
            }
        }
    }
}

// Hiç medya yoksa, varsayılan resimler ekleyelim
if(empty($galleryMedia)) {
    $galleryMedia[] = array(
        'url' => SITE . "assets/img/ocean-background.jpg",
        'type' => 'image'
    );
}

// Add main image to gallery if it's not already included
$mainImageAdded = false;
if (!empty($yachtData["main_image"])) {
    foreach($galleryMedia as $item) {
        if($item['url'] == $yachtData["main_image"]) {
            $mainImageAdded = true;
            break;
        }
    }
    
    if(!$mainImageAdded) {
        array_unshift($galleryMedia, array(
            'url' => $yachtData["main_image"],
            'type' => 'image'
        ));
    }
}

// If no media found, add some default images
if(empty($galleryMedia)) {
    foreach($default_yacht_images as $img) {
        $galleryMedia[] = array(
            'url' => $img,
            'type' => 'image'
        );
    }
}

// Add CSS for this page
echo '<link rel="stylesheet" href="'.SITE.'css/yacht-detail.css">';
?>

<!-- Yacht Detail Hero Section -->
<div style="background-image: url(<?=SITE?>images/bg/bg.jpg);">
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
                        <div class="yacht-spec-label">LENGTH</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["guests"]; ?></div>
                        <div class="yacht-spec-label">GUESTS</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["cabins"]; ?></div>
                        <div class="yacht-spec-label">CABINS</div>
                </div>
                
                <div class="yacht-spec">
                    <div class="yacht-spec-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="yacht-spec-value"><?php echo $yachtData["crew"]; ?></div>
                        <div class="yacht-spec-label">CREW</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yacht Description Section -->
<section class="yacht-content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
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
                        // Display gallery images and videos
                        foreach($galleryMedia as $index => $media) {
                            $featuredClass = ($index === 0) ? 'featured' : '';
                            $mediaType = $media['type'];
                            $mediaUrl = $media['url'];
                            
                            if($mediaType == 'video') {
                                // Display video thumbnail with play button
                                ?>
                            <div class="gallery-item <?php echo $featuredClass; ?> video-item">
                                <div class="video-thumbnail" data-video="<?php echo $mediaUrl; ?>">
                                    <!-- Video thumbnail background with play button overlay -->
                                    <div class="video-poster" style="background-color: #0a1a2a;">
                                        <i class="fas fa-video"
                                            style="position: absolute; font-size: 32px; color: #444; top: 50%; left: 50%; transform: translate(-50%, -80%);"></i>
                                    </div>
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                            </div>
                            <?php
                            } else {
                                // Display image
                            ?>
                            <div class="gallery-item <?php echo $featuredClass; ?>">
                                <img src="<?php echo $mediaUrl; ?>"
                                    alt="<?php echo $yachtData["name"]; ?> - Image <?php echo $index+1; ?>">
                                <div class="zoom-icon">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Yacht Specifications -->
                <div class="fade-in">
                    <h2 class="section-heading">Specifications</h2>
                    <table class="specs-table">
                        <tr>
                                <td>Yacht Type</td>
                                <td><?php echo $yacht_type; ?></td>
                        </tr>
                        <tr>
                            <td>Year Built</td>
                            <td><?php echo $yachtData["built_year"]; ?></td>
                        </tr>
                            <?php if(!empty($yachtData["refit_year"])): ?>
                        <tr>
                            <td>Refit</td>
                            <td><?php echo $yachtData["refit_year"]; ?></td>
                        </tr>
                            <?php endif; ?>
                        <tr>
                            <td>Length</td>
                            <td><?php echo $yachtData["length"]; ?></td>
                        </tr>
                        <tr>
                            <td>Guests</td>
                                <td><?php echo $yachtData["guests"]; ?> guests in <?php echo $yachtData["cabins"]; ?>
                                    cabins</td>
                        </tr>
                        <tr>
                            <td>Crew</td>
                            <td><?php echo $yachtData["crew"]; ?> professional crew members</td>
                        </tr>
                            <?php if(!empty($yachtData["speed"])): ?>
                        <tr>
                            <td>Cruising Speed</td>
                            <td><?php echo $yachtData["speed"]; ?></td>
                        </tr>
                            <?php endif; ?>
                            <?php if(!empty($yachtData["builder"])): ?>
                            <tr>
                                <td>Builder</td>
                                <td><?php echo $yachtData["builder"]; ?></td>
                            </tr>
                            <?php endif; ?>
                    </table>
                </div>
                
                <!-- Yacht Amenities -->
                <div class="fade-in">
                    <h2 class="section-heading">Amenities & Toys</h2>
                    <div class="amenities-grid">
                            <?php
                        // Define default amenities in case no data is found
                        $defaultAmenities = array(
                            array("icon" => "fas fa-wifi", "text" => "High-speed WiFi"),
                            array("icon" => "fas fa-swimming-pool", "text" => "Jacuzzi on deck"),
                            array("icon" => "fas fa-satellite-dish", "text" => "Satellite TV"),
                            array("icon" => "fas fa-wind", "text" => "Air conditioning"),
                            array("icon" => "fas fa-dumbbell", "text" => "Gym equipment"),
                            array("icon" => "fas fa-water", "text" => "Jet skis"),
                            array("icon" => "fas fa-wine-glass-alt", "text" => "Premier wine selection"),
                            array("icon" => "fas fa-utensils", "text" => "Gourmet chef"),
                            array("icon" => "fas fa-ship", "text" => "Tender boat")
                        );
                        
                        // Try to get yacht features/amenities from the database
                        $features = array();
                        
                        // Check if a features table exists and the yacht has features
                        if(isset($yacht_id) && $yacht_id > 0) {
                            // Directly query the yacht_features_pivot table joining with yacht_features
                            $yacht_features = $VT->VeriGetir(
                                "yacht_features_pivot 
                                 INNER JOIN yacht_features ON yacht_features_pivot.feature_id = yacht_features.ID", 
                                "WHERE yacht_features_pivot.yacht_id=? AND yacht_features.durum=?", 
                                array($yacht_id, 1), 
                                "ORDER BY yacht_features.baslik ASC"
                            );
                            
                            // Map feature icons based on common names
                            $featureIcons = array(
                                'wifi' => 'fas fa-wifi',
                                'internet' => 'fas fa-wifi',
                                'klima' => 'fas fa-wind',
                                'air' => 'fas fa-wind',
                                'jakuzi' => 'fas fa-swimming-pool',
                                'jacuzzi' => 'fas fa-swimming-pool',
                                'havuz' => 'fas fa-swimming-pool',
                                'pool' => 'fas fa-swimming-pool',
                                'mutfak' => 'fas fa-utensils',
                                'kitchen' => 'fas fa-utensils',
                                'gym' => 'fas fa-dumbbell',
                                'spor' => 'fas fa-dumbbell',
                                'tv' => 'fas fa-tv',
                                'satellite' => 'fas fa-satellite-dish'
                            );
                            
                            if($yacht_features) {
                                foreach($yacht_features as $feature) {
                                    $featureName = stripslashes($feature["baslik"]);
                                    
                                    // Determine icon based on feature name
                                    $icon = "fas fa-check";
                                    foreach($featureIcons as $keyword => $iconClass) {
                                        if(stripos($featureName, $keyword) !== false) {
                                            $icon = $iconClass;
                                            break;
                                        }
                                    }
                                    
                                    $features[] = array(
                                        "icon" => $icon,
                                        "text" => $featureName
                                    );
                                }
                            }
                        }
                        
                        // If no features found, use default amenities
                        if(empty($features)) {
                            $features = $defaultAmenities;
                        }
                        
                        // Display amenities
                        foreach($features as $feature) {
                            ?>
                        <div class="amenity-item">
                            <div class="amenity-icon">
                                    <i class="<?php echo $feature["icon"]; ?>"></i>
                            </div>
                                <div class="amenity-text"><?php echo $feature["text"]; ?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Similar Yachts Section -->
<section class="similar-yachts-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center fade-in">
                <h2 class="section-heading">Similar Yachts You May Like</h2>
                <p class="section-subheading">Discover other exceptional vessels in our exclusive fleet</p>
            </div>
        </div>

        <div class="row similar-yachts-container">
            <?php
            // Get similar yachts based on criteria
            $currentYachtId = $yacht_id ?? 0;
            $similarYachts = array();
            
            // First try to get yachts with similar characteristics (type, location, capacity)
            if(isset($yacht["type_id"]) && !empty($yacht["type_id"])) {
                // Try to find yachts with the same type
                $similarByType = $VT->VeriGetir("yachts", 
                    "WHERE durum=? AND type_id=? AND ID!=?", 
                    array(1, $yacht["type_id"], $currentYachtId),
                    "ORDER BY RAND() LIMIT 3"
                );
                
                if($similarByType) {
                    $similarYachts = array_merge($similarYachts, $similarByType);
                }
            }
            
            // If we don't have enough similar yachts by type, try location
            if(count($similarYachts) < 3 && isset($yacht["location_id"]) && !empty($yacht["location_id"])) {
                $similarByLocation = $VT->VeriGetir("yachts", 
                    "WHERE durum=? AND location_id=? AND ID!=? AND ID NOT IN (" . implode(',', array_map(function($y) { return $y['ID']; }, $similarYachts)) . ")", 
                    array(1, $yacht["location_id"], $currentYachtId),
                    "ORDER BY RAND() LIMIT " . (3 - count($similarYachts))
                );
                
                if($similarByLocation) {
                    $similarYachts = array_merge($similarYachts, $similarByLocation);
                }
            }
            
            // If we still don't have enough yachts, get random ones
            if(count($similarYachts) < 3) {
                $excludeIds = array($currentYachtId);
                foreach($similarYachts as $sy) {
                    $excludeIds[] = $sy['ID'];
                }
                
                $randomYachts = $VT->VeriGetir("yachts", 
                    "WHERE durum=? AND ID NOT IN (" . implode(',', $excludeIds) . ")", 
                    array(1),
                    "ORDER BY RAND() LIMIT " . (3 - count($similarYachts))
                );
                
                if($randomYachts) {
                    $similarYachts = array_merge($similarYachts, $randomYachts);
                }
            }
            
            // Display similar yachts
            if(!empty($similarYachts)) {
                foreach($similarYachts as $similarYacht) {
                    // Get yacht image
                    $yachtImage = SITE . "assets/img/yacht-1.jpg"; // Default image
                    if(!empty($similarYacht["resim"])) {
                        $yachtImage = SITE . "images/yachts/" . $similarYacht["resim"];
                    }
                    
                    // Format price
                    $price = "";
                    $pricePeriod = "";
                    if(isset($similarYacht["price_per_week"]) && !empty($similarYacht["price_per_week"])) {
                        $price = "€" . number_format($similarYacht["price_per_week"]);
                        $pricePeriod = "per week";
                    } elseif(isset($similarYacht["price_per_day"]) && !empty($similarYacht["price_per_day"])) {
                        $price = "€" . number_format($similarYacht["price_per_day"]);
                        $pricePeriod = "per day";
                    }
                    
                    // Get yacht specs
                    $length = isset($similarYacht["length_m"]) ? $similarYacht["length_m"] . "m" : "";
                    $guests = $similarYacht["capacity"] ?? $similarYacht["guest_capacity"] ?? "";
                    $cabins = $similarYacht["cabin_count"] ?? "";
                    ?>
                <div class="col-md-6 col-lg-4 mb-4 fade-in">
                    <div class="similar-yacht-card">
                        <div class="similar-yacht-image">
                            <img src="<?php echo $yachtImage; ?>"
                                alt="<?php echo stripslashes($similarYacht["baslik"]); ?>">
                            <?php if(!empty($price)): ?>
                            <div class="similar-yacht-price">
                                <span><?php echo $price; ?></span>
                                <small><?php echo $pricePeriod; ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="similar-yacht-content">
                            <h3 class="similar-yacht-title"><?php echo stripslashes($similarYacht["baslik"]); ?></h3>
                            <div class="similar-yacht-specs">
                                <?php if(!empty($length)): ?>
                                <div class="similar-yacht-spec">
                                    <i class="fas fa-ruler-horizontal"></i>
                                    <span><?php echo $length; ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($guests)): ?>
                                <div class="similar-yacht-spec">
                                    <i class="fas fa-users"></i>
                                    <span><?php echo $guests; ?> Guests</span>
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($cabins)): ?>
                                <div class="similar-yacht-spec">
                                    <i class="fas fa-bed"></i>
                                    <span><?php echo $cabins; ?> Cabins</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo SITE; ?>yat/<?php echo $similarYacht["seflink"]; ?>"
                                class="btn-view-yacht">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>No similar yachts available at the moment.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Required JavaScript files -->
<script src="<?php echo SITE; ?>assets/js/yacht-detail.js"></script>
<script src="<?php echo SITE; ?>assets/js/gallery-functions.js"></script>

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
        "name": "<?php echo $yacht_type; ?>"
    }
}
</script> 
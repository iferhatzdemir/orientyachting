<?php
// Get current category from URL parameter
$kategori = $VT->filter($_GET["kategori"]);

// Get category info from yacht_categories table
$categoryInfo = $VT->VeriGetir("yacht_categories", "WHERE seflink=? AND durum=?", array($kategori, 1), "ORDER BY ID ASC", 1);

// If category doesn't exist, redirect to 404
if($categoryInfo == false) {
    $link = SITE . "404";
    echo '<meta http-equiv="refresh" content="0;url=' . $link . '">';
    exit();
}

// Store category data
$categoryData = $categoryInfo[0];
$categoryID = $categoryData["ID"];
$categoryName = stripslashes($categoryData["baslik"]);
?>

<!-- Yacht Category Listing Page - Displaying yachts by type -->
<div class="yacht-listing-hero" style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('<?=SITE?>assets/img/yacht-hero-bg.jpg') no-repeat center center/cover;">
  <div class="container">
    <div class="hero-content" data-aos="fade-up">
      <h1><?=$categoryName?> Yachts</h1>
      <p>Experience our exclusive collection of premium <?=$categoryName?> yachts available for charter</p>
    </div>
  </div>
  
  <!-- Decorative yacht silhouette -->
  <svg class="hero-yacht-silhouette" viewBox="0 0 1200 200" xmlns="http://www.w3.org/2000/svg">
    <path d="M300,180 C450,150 600,130 750,130 C900,130 1050,150 1200,180 L1200,180 L900,180 C750,160 600,150 450,150 C300,150 150,160 0,180 L300,180 Z" fill="rgba(255,255,255,0.1)"/>
  </svg>
</div>

<div class="container yacht-listing-container">
  <!-- Breadcrumbs -->
  <div class="breadcrumbs-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=SITE?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?=SITE?>yacht-listing">Yachts</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?=$categoryName?></li>
      </ol>
    </nav>
  </div>

  <div class="row">
    <!-- Yacht Listings -->
    <div class="col-12">
      <div class="yachts-header" data-aos="fade-up">
        <h2><?=$categoryName?> Yacht Collection</h2>
        <?php if(!empty($categoryData["metin"])): ?>
        <div class="category-description">
          <p><?=stripslashes($categoryData["metin"])?></p>
        </div>
        <?php endif; ?>
      </div>
      
      <div class="yacht-results">
        <?php
        // Get all active yachts of this category using the relationship table
        $yachts = $VT->VeriGetir("yachts 
                  INNER JOIN yacht_category_rel ON yachts.id = yacht_category_rel.yacht_id", 
                  "WHERE yacht_category_rel.category_id=? AND yachts.is_active=?", 
                  array($categoryID, 1), 
                  "ORDER BY yachts.id DESC");
        
        if($yachts != false) {
          echo '<div class="row yacht-cards-container">';
          
          foreach($yachts as $yacht) {
            // Get location name (from location field if it exists)
            $location_name = $yacht["location"] ?? "";
            
            // Get main image
            $image_path = "assets/img/yacht-placeholder.jpg";
            if(!empty($yacht["featured_image"])) {
              $image_path = "images/yachts/" . $yacht["featured_image"];
            }
        ?>
        <div class="col-md-6 col-xl-4 yacht-card-wrapper" data-aos="fade-up">
          <div class="yacht-card">
            <div class="yacht-card-image">
              <a href="<?=SITE?>yat/<?=$yacht["slug"]?>">
                <img src="<?=SITE?><?=$image_path?>" alt="<?=$yacht["name"]?>" loading="lazy">
              </a>
              <?php if($yacht["is_active"] == 1): ?>
              <div class="yacht-status available">Available</div>
              <?php else: ?>
              <div class="yacht-status booked">Booked</div>
              <?php endif; ?>
              
              <div class="yacht-type"><?=$categoryName?></div>
            </div>
            
            <div class="yacht-card-content">
              <h3 class="yacht-title">
                <a href="<?=SITE?>yat/<?=$yacht["slug"]?>"><?=$yacht["name"]?></a>
              </h3>
              
              <?php if(!empty($location_name)): ?>
              <div class="yacht-location">
                <i class="fas fa-map-marker-alt"></i> <?=$location_name?>
              </div>
              <?php endif; ?>
              
              <div class="yacht-specs">
                <div class="spec-item">
                  <i class="fas fa-ruler-horizontal"></i>
                  <span><?=$yacht["length"]?> m</span>
                </div>
                <div class="spec-item">
                  <i class="fas fa-users"></i>
                  <span><?=$yacht["guests"]?> <?=$yacht["guests"] > 1 ? 'guests' : 'guest'?></span>
                </div>
                <div class="spec-item">
                  <i class="fas fa-bed"></i>
                  <span><?=$yacht["cabins"]?> <?=$yacht["cabins"] > 1 ? 'cabins' : 'cabin'?></span>
                </div>
              </div>
              
              <div class="yacht-price">
                <div class="price-amount"><?=$yacht["currency"]?><?=number_format($yacht["price"], 0, '.', ',')?></div>
                <div class="price-period">per day</div>
              </div>
              
              <div class="yacht-actions">
                <a href="<?=SITE?>yat/<?=$yacht["slug"]?>" class="btn btn-outline-primary">View Details</a>
                <a href="<?=SITE?>reservation?yacht=<?=$yacht["id"]?>" class="btn btn-primary">Book Now</a>
              </div>
            </div>
          </div>
        </div>
        <?php
          }
          
          echo '</div>';
          
          // If no yachts found
          if(count($yachts) == 0) {
            echo '<div class="no-results" data-aos="fade-up">';
            echo '<img src="'.SITE.'assets/img/no-results.svg" alt="No results found">';
            echo '<h3>No '.$categoryName.' Yachts Found</h3>';
            echo '<p>Please check back later or contact our yacht specialists for personalized recommendations.</p>';
            echo '</div>';
          }
        } else {
          // If no yachts found
          echo '<div class="no-results" data-aos="fade-up">';
          echo '<img src="'.SITE.'assets/img/no-results.svg" alt="No results found">';
          echo '<h3>No '.$categoryName.' Yachts Found</h3>';
          echo '<p>Please check back later or contact our yacht specialists for personalized recommendations.</p>';
          echo '</div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<section class="cta-section" data-aos="fade-up">
  <div class="container">
    <div class="cta-content">
      <h2>Curated <?=$categoryName?> Yacht Experience</h2>
      <p>Our yacht experts can help you find the perfect <?=strtolower($categoryName)?> yacht for your specific needs and create a tailor-made maritime journey.</p>
      <a href="<?=SITE?>contact" class="btn btn-light btn-lg">Speak With A Specialist</a>
    </div>
  </div>
</section>

<!-- Use the same CSS from yacht-listing.php as they share the same design -->
<style>
  /* Hero Section */
  .yacht-listing-hero {
    height: 500px;
    display: flex;
    align-items: center;
    text-align: center;
    position: relative;
    margin-bottom: 70px;
    background-attachment: fixed;
  }
  
  .yacht-listing-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 100%);
    z-index: 1;
  }
  
  .hero-content {
    color: #fff;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    padding: 0 20px;
  }
  
  .hero-content h1 {
    font-size: 60px;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    font-family: 'Playfair Display', serif;
    letter-spacing: 1px;
  }
  
  .hero-content p {
    font-size: 20px;
    margin-bottom: 40px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    font-weight: 300;
    letter-spacing: 0.5px;
    line-height: 1.6;
  }
  
  /* Category Description */
  .category-description {
    margin-top: 20px;
    font-size: 16px;
    line-height: 1.6;
    color: #666;
    max-width: 900px;
  }
  
  /* Breadcrumbs */
  .breadcrumbs-wrapper {
    margin-bottom: 40px;
  }
  
  .breadcrumb {
    background-color: transparent;
    padding: 0;
    margin: 0;
    font-size: 15px;
  }
  
  .breadcrumb-item a {
    color: #c8a97e;
    text-decoration: none;
    transition: color 0.2s;
  }
  
  .breadcrumb-item a:hover {
    color: #bc9c6d;
  }
  
  .breadcrumb-item.active {
    color: #666;
  }
  
  .breadcrumb-item+.breadcrumb-item::before {
    color: #999;
  }
  
  /* Yacht Listing Container */
  .yacht-listing-container {
    padding-bottom: 60px;
  }
  
  /* Yacht Listings */
  .yachts-header {
    display: flex;
    flex-direction: column;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
  }
  
  .yachts-header h2 {
    font-size: 28px;
    margin: 0;
    font-weight: 600;
    color: #333;
    font-family: 'Playfair Display', serif;
  }
  
  /* Yacht Cards Container */
  .yacht-cards-container {
    margin: 0 -15px;
  }
  
  /* Yacht Card */
  .yacht-card-wrapper {
    margin-bottom: 40px;
    padding: 0 15px;
  }
  
  .yacht-card {
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    height: 100%;
    position: relative;
    border: 1px solid rgba(0,0,0,0.05);
  }
  
  .yacht-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  
  .yacht-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(to right, #c8a97e, #d4bc96);
    transform: scaleX(0);
    transform-origin: bottom right;
    transition: transform 0.4s ease;
  }
  
  .yacht-card:hover::after {
    transform: scaleX(1);
    transform-origin: bottom left;
  }
  
  .yacht-card-image {
    position: relative;
    height: 240px;
    overflow: hidden;
  }
  
  .yacht-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }
  
  .yacht-card:hover .yacht-card-image img {
    transform: scale(1.1);
  }
  
  .yacht-status {
    position: absolute;
    top: 15px;
    left: 15px;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    z-index: 2;
    letter-spacing: 1px;
  }
  
  .yacht-status.available {
    background-color: #c8a97e;
    color: #fff;
  }
  
  .yacht-status.booked {
    background-color: #e74c3c;
    color: #fff;
  }
  
  .yacht-type {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background-color: rgba(0,0,0,0.7);
    color: #fff;
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 5px;
    font-weight: 500;
    letter-spacing: 0.5px;
  }
  
  .yacht-card-content {
    padding: 25px;
  }
  
  .yacht-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 12px;
    line-height: 1.3;
    font-family: 'Playfair Display', serif;
  }
  
  .yacht-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s;
  }
  
  .yacht-title a:hover {
    color: #c8a97e;
  }
  
  .yacht-location {
    color: #666;
    font-size: 14px;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .yacht-location i {
    color: #c8a97e;
    margin-right: 8px;
    font-size: 16px;
  }
  
  .yacht-specs {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
  }
  
  .spec-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .spec-item i {
    color: #c8a97e;
    font-size: 18px;
    margin-bottom: 8px;
  }
  
  .spec-item span {
    font-size: 14px;
    color: #666;
    font-weight: 500;
  }
  
  .yacht-price {
    margin-bottom: 25px;
    text-align: center;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
  }
  
  .price-amount {
    font-size: 26px;
    font-weight: 700;
    color: #c8a97e;
    font-family: 'Playfair Display', serif;
  }
  
  .price-period {
    font-size: 13px;
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  .yacht-actions {
    display: flex;
    gap: 10px;
  }
  
  .yacht-actions .btn {
    flex: 1;
    font-size: 14px;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
  }
  
  .yacht-actions .btn-outline-primary {
    color: #c8a97e;
    border-color: #c8a97e;
  }
  
  .yacht-actions .btn-outline-primary:hover {
    background-color: #c8a97e;
    color: #fff;
  }
  
  .yacht-actions .btn-primary {
    background-color: #c8a97e;
    border-color: #c8a97e;
  }
  
  .yacht-actions .btn-primary:hover {
    background-color: #bc9c6d;
    border-color: #bc9c6d;
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
  }
  
  /* CTA Section */
  .cta-section {
    background: linear-gradient(145deg, #0d2c47, #194c78);
    padding: 80px 0;
    margin-top: 70px;
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  
  .cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('<?=SITE?>assets/img/wave-pattern.png') repeat;
    opacity: 0.05;
  }
  
  .cta-content {
    position: relative;
    z-index: 2;
  }
  
  .cta-content h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 20px;
    font-family: 'Playfair Display', serif;
  }
  
  .cta-content p {
    font-size: 18px;
    margin-bottom: 30px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 300;
    line-height: 1.6;
  }
  
  .cta-content .btn {
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  }
  
  .cta-content .btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
  }
  
  /* No Results */
  .no-results {
    text-align: center;
    padding: 60px 0;
  }
  
  .no-results img {
    width: 150px;
    margin-bottom: 25px;
    opacity: 0.7;
  }
  
  .no-results h3 {
    font-size: 26px;
    margin-bottom: 15px;
    color: #333;
    font-family: 'Playfair Display', serif;
  }
  
  .no-results p {
    color: #666;
    max-width: 400px;
    margin: 0 auto;
    font-size: 16px;
    line-height: 1.6;
  }
  
  /* Hero Yacht Silhouette */
  .hero-yacht-silhouette {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    z-index: 1;
    pointer-events: none;
  }
  
  /* Responsive Adjustments */
  @media (max-width: 1199px) {
    .yacht-card-image {
      height: 220px;
    }
  }
  
  @media (max-width: 991px) {    
    .hero-content h1 {
      font-size: 42px;
    }
    
    .yacht-card-image {
      height: 200px;
    }
  }
  
  @media (max-width: 767px) {
    .yacht-listing-hero {
      height: 400px;
      margin-bottom: 50px;
    }
    
    .hero-content h1 {
      font-size: 36px;
    }
    
    .hero-content p {
      font-size: 16px;
    }
    
    .yachts-header {
      flex-direction: column;
      align-items: flex-start;
    }
    
    .cta-content h2 {
      font-size: 30px;
    }
    
    .cta-section {
      padding: 60px 0;
    }
    
    .hero-yacht-silhouette {
      height: 60px;
    }
  }
  
  @media (max-width: 575px) {
    .yacht-listing-hero {
      height: 350px;
    }
    
    .hero-content h1 {
      font-size: 32px;
    }
    
    .yacht-card-image {
      height: 180px;
    }
    
    .yacht-specs {
      flex-wrap: wrap;
    }
    
    .spec-item {
      flex: 0 0 33.333%;
      margin-bottom: 10px;
    }
    
    .yacht-title {
      font-size: 18px;
    }
    
    .yacht-actions {
      flex-direction: column;
    }
    
    .yacht-card-content {
      padding: 20px;
    }
    
    .cta-content h2 {
      font-size: 26px;
    }
    
    .cta-content p {
      font-size: 16px;
    }
  }
</style> 
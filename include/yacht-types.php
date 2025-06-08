<!-- Yacht Types Page - Showing all yacht types -->
<div class="yacht-types-hero" style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('<?=SITE?>assets/img/yacht-hero-bg.jpg') no-repeat center center/cover;">
  <div class="container">
    <div class="hero-content" data-aos="fade-up">
      <h1>Yacht Types</h1>
      <p>Explore our diverse collection of luxury yachts by type</p>
    </div>
  </div>
  
  <!-- Decorative yacht silhouette -->
  <svg class="hero-yacht-silhouette" viewBox="0 0 1200 200" xmlns="http://www.w3.org/2000/svg">
    <path d="M300,180 C450,150 600,130 750,130 C900,130 1050,150 1200,180 L1200,180 L900,180 C750,160 600,150 450,150 C300,150 150,160 0,180 L300,180 Z" fill="rgba(255,255,255,0.1)"/>
  </svg>
</div>

<div class="container yacht-types-container">
  <!-- Breadcrumbs -->
  <div class="breadcrumbs-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=SITE?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Yacht Types</li>
      </ol>
    </nav>
  </div>

  <?php
  // Get only active yacht types that have active yachts
  $yachtTypes = $VT->VeriGetir("yacht_types 
    INNER JOIN yachts ON yacht_types.ID = yachts.type_id",
    "WHERE yacht_types.durum=? AND yachts.durum=?",
    array(1, 1),
    "GROUP BY yacht_types.ID ORDER BY yacht_types.sirano ASC");
  
  // Remove debugging section for production
    
  if($yachtTypes != false) {
    ?>
    <div class="yacht-types-grid" data-aos="fade-up">
      <div class="row">
        <?php foreach($yachtTypes as $type) {
          // Count yachts for this type
          $yachtCount = $VT->VeriGetir("yachts", 
            "WHERE type_id=? AND durum=?", 
            array($type["ID"], 1),
            "COUNT(ID) as total");
          
          // Get a sample image from one of the yachts
          $sampleYacht = $VT->VeriGetir("yachts", 
            "WHERE type_id=? AND durum=? AND resim IS NOT NULL AND resim != ''", 
            array($type["ID"], 1),
            "ORDER BY ID DESC", 1);
            
          $image_path = "assets/img/yacht-placeholder.jpg";
          if($sampleYacht != false && !empty($sampleYacht[0]["resim"])) {
            $image_path = "images/yachts/" . $sampleYacht[0]["resim"];
          }
          ?>
          <div class="col-md-6 col-lg-4 yacht-type-card-wrapper">
            <a href="<?=SITE?>yacht-type/<?=$type["seflink"]?>" class="yacht-type-card">
              <div class="yacht-type-image">
                <img src="<?=SITE?><?=$image_path?>" alt="<?=$type["baslik"]?>" loading="lazy">
                <div class="yacht-type-overlay"></div>
              </div>
              <div class="yacht-type-content">
                <h2><?=$type["baslik"]?></h2>
                <p class="yacht-count"><?=$yachtCount[0]["total"]?> <?=$yachtCount[0]["total"] > 1 ? 'Yachts' : 'Yacht'?></p>
                <div class="view-type">View Yachts <i class="fas fa-arrow-right"></i></div>
              </div>
            </a>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php
  } else {
    // If no yacht types found
    echo '<div class="no-results" data-aos="fade-up">';
    echo '<img src="'.SITE.'assets/img/no-results.svg" alt="No results found">';
    echo '<h3>No Yacht Types Found</h3>';
    echo '<p>Please check back later for our updated yacht collection.</p>';
    echo '</div>';
  }
  ?>
</div>

<section class="cta-section" data-aos="fade-up">
  <div class="container">
    <div class="cta-content">
      <h2>Find Your Perfect Yacht</h2>
      <p>Our yacht specialists can help you select the ideal vessel for your needs and create a memorable journey.</p>
      <a href="<?=SITE?>contact" class="btn btn-light btn-lg">Contact Us Today</a>
    </div>
  </div>
</section>

<!-- Add custom CSS for the yacht types page -->
<style>
  /* Hero Section */
  .yacht-types-hero {
    height: 500px;
    display: flex;
    align-items: center;
    text-align: center;
    position: relative;
    margin-bottom: 70px;
    background-attachment: fixed;
  }
  
  .yacht-types-hero::before {
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
  
  /* Yacht Types Container */
  .yacht-types-container {
    padding-bottom: 60px;
  }
  
  /* Yacht Type Grid */
  .yacht-types-grid {
    margin-bottom: 60px;
  }
  
  /* Yacht Type Card */
  .yacht-type-card-wrapper {
    margin-bottom: 30px;
  }
  
  .yacht-type-card {
    display: block;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
    height: 300px;
    text-decoration: none;
  }
  
  .yacht-type-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    text-decoration: none;
  }
  
  .yacht-type-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
  
  .yacht-type-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }
  
  .yacht-type-card:hover .yacht-type-image img {
    transform: scale(1.1);
  }
  
  .yacht-type-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0.1) 100%);
    z-index: 1;
  }
  
  .yacht-type-content {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 30px;
    color: #fff;
    z-index: 2;
  }
  
  .yacht-type-content h2 {
    font-size: 28px;
    margin-bottom: 5px;
    font-weight: 600;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    font-family: 'Playfair Display', serif;
  }
  
  .yacht-count {
    font-size: 16px;
    margin-bottom: 15px;
    font-weight: 400;
    opacity: 0.8;
  }
  
  .view-type {
    display: inline-block;
    padding: 8px 15px;
    background-color: #c8a97e;
    color: #fff;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    opacity: 0.9;
  }
  
  .view-type i {
    margin-left: 5px;
    transition: transform 0.3s ease;
  }
  
  .yacht-type-card:hover .view-type {
    opacity: 1;
    background-color: #fff;
    color: #c8a97e;
  }
  
  .yacht-type-card:hover .view-type i {
    transform: translateX(5px);
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
  
  /* Responsive Adjustments */
  @media (max-width: 1199px) {
    .yacht-type-card {
      height: 280px;
    }
    
    .yacht-type-content h2 {
      font-size: 24px;
    }
  }
  
  @media (max-width: 991px) {    
    .hero-content h1 {
      font-size: 42px;
    }
  }
  
  @media (max-width: 767px) {
    .yacht-types-hero {
      height: 400px;
      margin-bottom: 50px;
    }
    
    .hero-content h1 {
      font-size: 36px;
    }
    
    .hero-content p {
      font-size: 16px;
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
    .yacht-types-hero {
      height: 350px;
    }
    
    .hero-content h1 {
      font-size: 32px;
    }
    
    .yacht-type-card {
      height: 250px;
    }
    
    .yacht-type-content h2 {
      font-size: 22px;
    }
    
    .yacht-count {
      font-size: 14px;
    }
    
    .cta-content h2 {
      font-size: 26px;
    }
    
    .cta-content p {
      font-size: 16px;
    }
  }
</style> 
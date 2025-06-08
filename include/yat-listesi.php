<?php
// Temel kontroller
if (!defined('SITE')) {
    die('SITE sabiti tanımlı değil!');
}

if (!class_exists('VT')) {
    die('VT sınıfı bulunamadı!');
}

// Sayfalama değişkenleri
$sayfa = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 9;
$baslangic = ($sayfa - 1) * $limit;

// Filtre parametrelerini al
$location_filter = isset($_GET['location']) ? (int)$_GET['location'] : null;
$type_filter = isset($_GET['type']) ? (int)$_GET['type'] : null;

// Sorgu koşullarını oluştur
$whereKosullar = "WHERE yachts.durum=1";
$parametreler = array();

// Lokasyon filtresi
if($location_filter) {
    $whereKosullar .= " AND yachts.location_id=?";
    $parametreler[] = $location_filter;
}

// Yat tipi filtresi
if($type_filter) {
    $whereKosullar .= " AND yachts.type_id=?";
    $parametreler[] = $type_filter;
}

// Toplam yat sayısını bul
$toplamSorgu = $VT->VeriGetir("yachts", $whereKosullar, $parametreler, "");
$toplamYat = ($toplamSorgu != false) ? count($toplamSorgu) : 0;
$toplamSayfa = ceil($toplamYat / $limit);

// İstatistikler için sorguları yap
$aktif_yat_sayisi = $toplamYat;

// Toplam kabin sayısı
$toplam_kabin = 0;
if($toplamSorgu) {
    foreach($toplamSorgu as $yat) {
        $toplam_kabin += (int)$yat["cabin_count"];
    }
}

// En büyük misafir kapasitesi
$max_misafir = 0;
if($toplamSorgu) {
    foreach($toplamSorgu as $yat) {
        if((int)$yat["guest_capacity"] > $max_misafir) {
            $max_misafir = (int)$yat["guest_capacity"];
        }
    }
}

// Ana sorgu - Lokasyon bilgisini de getir
$yatlar = $VT->VeriGetir(
    "yachts",
    "LEFT JOIN yacht_locations ON yachts.location_id = yacht_locations.ID 
     ".$whereKosullar,
    $parametreler,
    "ORDER BY yachts.ID DESC LIMIT $baslangic, $limit"
);

// Özellikleri getir
$ozellikler = $VT->VeriGetir("ozellikler", "WHERE durum=1", array(), "ORDER BY sira ASC LIMIT 6");

?>

<!-- Hero Section -->
<div class="luxury-hero" style="background: linear-gradient(rgba(11, 34, 66, 0.85), rgba(11, 34, 66, 0.85)), url('<?=SITE?>assets/img/hero-bg.jpg') no-repeat center center/cover;">
    <div class="container">
        <div class="hero-content text-center" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="display-title">Luxury Yacht Collection</h1>
            <div class="title-separator">
                <span class="diamond"></span>
            </div>
            <p class="hero-subtitle">Experience the epitome of maritime luxury</p>
            
            <?php if($ozellikler && count($ozellikler) > 0): ?>
            <div class="feature-badges" data-aos="fade-up" data-aos-delay="300">
                <?php foreach($ozellikler as $ozellik): ?>
                <div class="feature-badge">
                    <i class="<?=$ozellik['ikon']?>"></i>
                    <span><?=$ozellik['baslik']?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?=$aktif_yat_sayisi?>+</span>
                    <span class="stat-label">LUXURY YACHTS</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?=$toplam_kabin?></span>
                    <span class="stat-label">TOTAL CABINS</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?=$max_misafir?></span>
                    <span class="stat-label">MAX GUESTS</span>
                </div>
            </div>
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

<!-- Yacht Card Section -->
<div class="yacht-listing-section">
    <div class="container">
        <!-- Filter Form -->
        <div class="filter-section mb-5" data-aos="fade-up">
            <form id="yachtFilterForm" class="filter-form" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="filter-group">
                            <label for="location_filter">Location</label>
                            <select name="location" id="location_filter" class="form-select">
                                <option value="">All Locations</option>
                                <?php
                                $locations = $VT->VeriGetir("yacht_locations", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
                                if($locations) {
                                    foreach($locations as $loc) {
                                        $selected = isset($_GET['location']) && $_GET['location'] == $loc['ID'] ? 'selected' : '';
                                        echo '<option value="'.$loc['ID'].'" '.$selected.'>'.$loc['baslik'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="filter-group">
                            <label for="type_filter">Yacht Type</label>
                            <select name="type" id="type_filter" class="form-select">
                                <option value="">All Types</option>
                                <?php
                                $types = $VT->VeriGetir("yacht_types", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
                                if($types) {
                                    foreach($types as $type) {
                                        $selected = isset($_GET['type']) && $_GET['type'] == $type['ID'] ? 'selected' : '';
                                        echo '<option value="'.$type['ID'].'" '.$selected.'>'.$type['baslik'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row g-4">
            <?php if($yatlar && count($yatlar) > 0): foreach($yatlar as $yat): ?>
            <div class="col-lg-4 col-md-6">
                        <div class="yacht-card">
                    <div class="yacht-image">
                        <img src="<?=SITE?>images/yachts/<?=$yat["resim"]?>" alt="<?=$yat["baslik"]?>">
                        <div class="price-badge">
                            <div class="price-amount"><?=number_format($yat["price_per_day"], 0)?> €</div>
                            <div class="price-period">PER DAY</div>
                        </div>
                    </div>
                    <div class="yacht-details">
                        <h3 class="yacht-name"><?=$yat["baslik"]?></h3>
                        
                        <!-- Marina Location -->
                        <div class="yacht-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php
                            if(!empty($yat["location_id"])) {
                                $location = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($yat["location_id"]), "ORDER BY ID ASC", 1);
                                echo !empty($location) ? $location[0]["baslik"] : "Port Location Available Upon Request";
                            } else {
                                echo "Port Location Available Upon Request";
                            }
                            ?></span>
                        </div>
                        
                        <div class="yacht-specs">
                            <div class="spec-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span class="spec-value"><?=$yat["length_m"]?>m</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-bed"></i>
                                <span class="spec-value"><?=$yat["cabin_count"]?></span>
                                <span class="spec-label">Cabins</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-users"></i>
                                <span class="spec-value"><?=$yat["guest_capacity"]?></span>
                                <span class="spec-label">Guests</span>
                            </div>
                            <div class="spec-item">
                                <i class="fas fa-user-tie"></i>
                                <span class="spec-value"><?=$yat["crew"]?></span>
                                <span class="spec-label">Crew</span>
                            </div>
                        </div>
                        <!-- Yat Özellikleri -->
                        <div class="yacht-features">
                            <?php
                            // Yat özelliklerini çek
                            $features = $VT->VeriGetir("yacht_features_pivot", 
                                "INNER JOIN yacht_features ON yacht_features_pivot.feature_id = yacht_features.ID 
                                 WHERE yacht_features_pivot.yacht_id=? AND yacht_features.durum=?", 
                                array($yat["ID"], 1), "ORDER BY yacht_features.baslik ASC");
                            
                            if($features != false) {
                                foreach($features as $feature) {
                                    echo '<span class="yacht-feature-tag">';
                                    echo '<i class="' . $feature["icon"] . '"></i>';
                                    echo '<span>' . stripslashes($feature["baslik"]) . '</span>';
                                    echo '</span>';
                                }
                            }
                            ?>
                            </div>
                        <!-- Detay Butonu -->
                        <div class="yacht-detail-action">
                            <a href="<?=SITE?>yat/<?=$yat["seflink"]?>" class="detail-button">
                                <span>View Details</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
            <div class="col-12">
                <div class="no-results">
                    <i class="fas fa-ship"></i>
                    <h3>No Yachts Available</h3>
                    <p>Please check back later for new additions to our fleet.</p>
                </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if($toplamSayfa > 1): ?>
        <div class="pagination-wrapper">
            <div class="pagination">
                <?php
                // Mevcut GET parametrelerini al ve sayfa parametresini çıkar
                $params = $_GET;
                unset($params['p']);
                $queryString = http_build_query($params);
                $queryString = $queryString ? '&' . $queryString : '';

                // Sayfalama linklerini oluştur
                for($i = 1; $i <= $toplamSayfa; $i++): 
                ?>
                <a href="?p=<?=$i?><?=$queryString?>" class="page-link <?=$i == $sayfa ? 'active' : ''?>"><?=$i?></a>
                        <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Luxury Styling with Enhanced Animations */
:root {
    --primary-color: #0B2242;
    --accent-color: #C8A97E;
    --text-color: #333333;
    --light-color: #FFFFFF;
    --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

/* Yacht Card Styles */
.yacht-listing-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.yacht-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.yacht-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.yacht-image {
    position: relative;
    padding-top: 66%;
    background: #f8f9fa;
}

.yacht-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.price-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #1B2B44;
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    text-align: center;
}

.price-amount {
    font-size: 24px;
    font-weight: 600;
    line-height: 1;
}

.price-period {
    font-size: 12px;
    color: #C8A97E;
    text-transform: uppercase;
    margin-top: 2px;
}

.yacht-details {
    padding: 25px;
}

.yacht-name {
    font-size: 24px;
    color: #1B2B44;
    margin-bottom: 10px;
    font-weight: 600;
}

.yacht-specs {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.spec-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.spec-item i {
    font-size: 20px;
    color: #C8A97E;
    margin-bottom: 8px;
}

.spec-value {
    font-size: 16px;
    font-weight: 600;
    color: #1B2B44;
    line-height: 1;
}

.spec-label {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 50px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    gap: 5px;
    background: #fff;
    padding: 5px;
    border-radius: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.page-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #1B2B44;
    text-decoration: none;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #f8f9fa;
}

.page-link.active {
    background: #1B2B44;
    color: #fff;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 50px 20px;
}

.no-results i {
    font-size: 48px;
    color: #C8A97E;
    margin-bottom: 20px;
}

.no-results h3 {
    font-size: 24px;
    color: #1B2B44;
    margin-bottom: 10px;
}

.no-results p {
    color: #6c757d;
}

/* Responsive */
@media (max-width: 991px) {
    .yacht-name {
        font-size: 20px;
    }
    
    .spec-item i {
        font-size: 18px;
    }
    
    .spec-value {
        font-size: 14px;
    }
}

@media (max-width: 767px) {
    .yacht-listing-section {
        padding: 40px 0;
    }
    
    .yacht-details {
    padding: 20px;
}

    .price-badge {
        padding: 8px 12px;
    }
    
    .price-amount {
        font-size: 20px;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Hero Section */
.luxury-hero {
    padding: 200px 0 180px;
    margin-bottom: 80px;
    position: relative;
    overflow: hidden;
    margin-top: -120px;
}

.hero-badge {
    display: inline-block;
    background: rgba(200, 169, 126, 0.2);
    color: var(--accent-color);
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 30px;
    backdrop-filter: blur(5px);
    animation: badgePulse 2s infinite;
}

@keyframes badgePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.display-title {
    font-family: 'Playfair Display', serif;
    font-size: 56px;
    color: var(--light-color);
    margin-bottom: 25px;
    font-weight: 700;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(20px);
    animation: titleFadeIn 1s forwards 0.5s;
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
    width: 10px;
    height: 10px;
    background: var(--accent-color);
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
    width: 100px;
    height: 1px;
    background: rgba(200, 169, 126, 0.5);
    transition: var(--transition);
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

@keyframes lineExtendLeft {
    from { transform: scaleX(0); }
    to { transform: scaleX(1); }
}

@keyframes lineExtendRight {
    from { transform: scaleX(0); }
    to { transform: scaleX(1); }
}

.hero-subtitle {
    color: var(--accent-color);
    font-size: 18px;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 2px;
    margin-top: 20px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    opacity: 0;
    animation: fadeIn 1s forwards 1s;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 60px;
    margin-top: 50px;
    opacity: 0;
    animation: fadeIn 1s forwards 1.2s;
}

.stat-item {
    text-align: center;
    position: relative;
}

.stat-item::after {
    content: '';
    position: absolute;
    right: -30px;
    top: 50%;
    transform: translateY(-50%);
    width: 1px;
    height: 40px;
    background: rgba(200, 169, 126, 0.3);
}

.stat-item:last-child::after {
    display: none;
}

.stat-number {
    display: block;
    font-size: 36px;
    font-weight: 700;
    color: var(--light-color);
    margin-bottom: 5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.stat-label {
    color: var(--accent-color);
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
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

/* Feature Badges */
.feature-badges {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin: 40px auto;
    max-width: 800px;
}

.feature-badge {
    background: rgba(200, 169, 126, 0.1);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(200, 169, 126, 0.2);
    padding: 12px 20px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--light-color);
    transition: all 0.3s ease;
}

.feature-badge:hover {
    background: rgba(200, 169, 126, 0.2);
    transform: translateY(-2px);
}

.feature-badge i {
    color: var(--accent-color);
    font-size: 16px;
}

.feature-badge span {
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

/* Responsive Adjustments for Hero Section */
@media (max-width: 991px) {
    .luxury-hero {
        padding: 180px 0 130px;
        margin-top: -100px;
    }
    
    .display-title {
        font-size: 42px;
    }
    
    .hero-stats {
        gap: 40px;
    }
    
    .stat-item::after {
        right: -20px;
        height: 30px;
    }
    
    .stat-number {
        font-size: 30px;
    }
    
    .feature-badges {
        gap: 15px;
        margin: 30px auto;
    }

    .feature-badge {
        padding: 10px 16px;
    }
    
    .feature-badge i {
        font-size: 14px;
    }

    .feature-badge span {
        font-size: 13px;
    }
}

@media (max-width: 767px) {
    .luxury-hero {
        padding: 160px 0 130px;
        margin-top: -80px;
    }
    
    .display-title {
        font-size: 32px;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 30px;
    }
    
    .stat-item::after {
        display: none;
    }
    
    .stat-number {
        font-size: 28px;
    }
    
    .feature-badges {
        gap: 10px;
        margin: 25px auto;
    }

    .feature-badge {
        padding: 8px 14px;
    }

    .feature-badge span {
        font-size: 12px;
    }
}

/* Yacht Features Styles */
.yacht-features {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    margin-bottom: 20px;
}

.yacht-feature-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(200, 169, 126, 0.1);
    color: #1B2B44;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.yacht-feature-tag i {
    color: #C8A97E;
    font-size: 14px;
}

.yacht-feature-tag:hover {
    background: rgba(200, 169, 126, 0.2);
    transform: translateY(-2px);
}

@media (max-width: 767px) {
    .yacht-features {
        margin-top: 15px;
        padding-top: 12px;
        margin-bottom: 15px;
        gap: 8px;
    }
    
    .yacht-feature-tag {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .yacht-feature-tag i {
        font-size: 13px;
    }
}

/* Detail Button Styles */
.yacht-detail-action {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.detail-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #1B2B44;
    color: #fff;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.detail-button:hover {
    background: #C8A97E;
    color: #fff;
    transform: translateY(-2px);
}

.detail-button i {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.detail-button:hover i {
    transform: translateX(5px);
}

.yacht-location {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    color: #6c757d;
    font-size: 14px;
}

.yacht-location i {
    color: #C8A97E;
    font-size: 16px;
}

.yacht-location span {
    line-height: 1.4;
}

@media (max-width: 991px) {
    .yacht-location {
        font-size: 13px;
    }
    
    .yacht-location i {
        font-size: 15px;
    }
}

@media (max-width: 767px) {
    .yacht-location {
        font-size: 12px;
    }
    
    .yacht-location i {
        font-size: 14px;
    }
}

/* Filter Styles */
.filter-section {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 40px;
}

.filter-group {
    margin-bottom: 0;
}

.filter-group label {
    display: block;
    color: #1B2B44;
    font-weight: 500;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-select {
    height: 48px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 0 15px;
    font-size: 14px;
    color: #1B2B44;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #C8A97E;
    box-shadow: 0 0 0 0.2rem rgba(200, 169, 126, 0.25);
}

.btn-primary {
    height: 48px;
    background: #1B2B44;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #C8A97E;
    transform: translateY(-2px);
}

@media (max-width: 767px) {
    .filter-section {
        padding: 20px;
    }
    
    .filter-group {
        margin-bottom: 15px;
    }
    
    .form-select, .btn-primary {
        height: 44px;
    }
}
</style>

<!-- Required Scripts -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    });
</script>
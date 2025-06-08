<?php
// Yat listeleme sayfası
require_once("data/baglanti.php");

// Sayfalama için değişkenler
$sayfa = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 12;
$baslangic = ($sayfa - 1) * $limit;

// Toplam yat sayısını al
$total_query = $VT->getQuery("SELECT COUNT(*) as total FROM yatlar WHERE durum = 1");
$total_yachts = $total_query[0]["total"];
$total_pages = ceil($total_yachts / $limit);

// Yatları getir
$yachts = $VT->getQuery("SELECT * FROM yatlar WHERE durum = 1 ORDER BY sira ASC LIMIT $baslangic, $limit");
?>

<!-- Hero Section -->
<div class="yacht-listing-hero" style="background: linear-gradient(rgba(11, 34, 66, 0.8), rgba(11, 34, 66, 0.6)), url('assets/img/hero-bg.jpg') no-repeat center center; background-size: cover;">
    <div class="container">
        <div class="hero-content">
            <h1 class="luxury-title">Luxury Yacht Collection</h1>
            <p class="luxury-subtitle">Discover our exclusive fleet of premium yachts</p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <!-- Filter Section -->
        <div class="col-lg-3">
            <div class="filter-section">
                <h3 class="filter-title">Filter Yachts</h3>
                
                <!-- Length Filter -->
                <div class="filter-group">
                    <label class="filter-label">Length</label>
                    <select class="custom-select" name="length">
                        <option value="">All Lengths</option>
                        <option value="20-30">20m - 30m</option>
                        <option value="30-40">30m - 40m</option>
                        <option value="40+">40m+</option>
                    </select>
                </div>

                <!-- Cabins Filter -->
                <div class="filter-group">
                    <label class="filter-label">Cabins</label>
                    <select class="custom-select" name="cabins">
                        <option value="">All Cabins</option>
                        <option value="2-4">2-4 Cabins</option>
                        <option value="4-6">4-6 Cabins</option>
                        <option value="6+">6+ Cabins</option>
                    </select>
                </div>

                <!-- Guests Filter -->
                <div class="filter-group">
                    <label class="filter-label">Guests</label>
                    <select class="custom-select" name="guests">
                        <option value="">All Capacities</option>
                        <option value="1-8">1-8 Guests</option>
                        <option value="8-12">8-12 Guests</option>
                        <option value="12+">12+ Guests</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Yacht Grid -->
        <div class="col-lg-9">
            <div class="yacht-grid">
                <?php if($yachts): foreach($yachts as $yacht): ?>
                <div class="yacht-card">
                    <!-- Price Badge -->
                    <div class="price-badge">
                        <span class="price-amount"><?php echo number_format($yacht["fiyat"], 0, ',', '.'); ?>€</span>
                        <span class="price-period">Per Day</span>
                    </div>

                    <!-- Yacht Image -->
                    <div class="yacht-image">
                        <img src="assets/img/yachts/<?php echo $yacht["resim"]; ?>" alt="<?php echo $yacht["baslik"]; ?>" loading="lazy">
                    </div>

                    <!-- Yacht Details -->
                    <div class="yacht-details">
                        <h3 class="yacht-name"><?php echo $yacht["baslik"]; ?></h3>
                        
                        <!-- Specifications -->
                        <div class="yacht-specs">
                            <div class="spec-item">
                                <i class="spec-icon fas fa-ruler-combined"></i>
                                <div class="spec-info">
                                    <span class="spec-value"><?php echo $yacht["uzunluk"]; ?>m</span>
                                    <span class="spec-label">Length</span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <i class="spec-icon fas fa-bed"></i>
                                <div class="spec-info">
                                    <span class="spec-value"><?php echo $yacht["kabin"]; ?></span>
                                    <span class="spec-label">Cabins</span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <i class="spec-icon fas fa-users"></i>
                                <div class="spec-info">
                                    <span class="spec-value"><?php echo $yacht["misafir"]; ?></span>
                                    <span class="spec-label">Guests</span>
                                </div>
                            </div>
                            
                            <div class="spec-item">
                                <i class="spec-icon fas fa-user-tie"></i>
                                <div class="spec-info">
                                    <span class="spec-value"><?php echo $yacht["murettebat"]; ?></span>
                                    <span class="spec-label">Crew</span>
                                </div>
                            </div>
                        </div>

                        <!-- View Details Button -->
                        <a href="yacht-detail/<?php echo $yacht["seflink"]; ?>" class="view-details">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="empty-state-icon fas fa-ship"></i>
                    <p class="empty-state-text">No yachts found matching your criteria.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <div class="pagination-wrapper text-center mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $sayfa ? 'active' : ''; ?>">
                            <a class="page-link" href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Required Scripts -->
<script src="assets/js/yacht-listing.js"></script> 
<?php
// Check if category parameter exists
$categorySlug = isset($_GET["seflink"]) ? $_GET["seflink"] : "";

if (empty($categorySlug)) {
    // If no category specified, redirect to all yachts
    echo '<script>window.location.href="'.SITE.'yatlar";</script>';
    exit;
}

// Get category information
$category = $VT->VeriGetir("yacht_categories", "WHERE seflink=? AND durum=?", array($categorySlug, 1), "ORDER BY ID ASC", 1);

if ($category == false) {
    // Category not found
    echo '<div class="container mt-5 mb-5">';
    echo '<div class="alert alert-danger">The requested category was not found.</div>';
    echo '<p><a href="'.SITE.'yatlar" class="btn btn-primary">View All Yachts</a></p>';
    echo '</div>';
    exit;
}
?>

<!-- Category Header -->
<section class="catalog-header" style="background-image: url('<?=SITE?>assets/img/luxury-yacht-charter-hero.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="catalog-title">
                    <h1><?=stripslashes($category[0]["baslik"])?></h1>
                    <p><?=stripslashes($category[0]["description"])?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Description -->
<section class="category-description">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="category-content">
                    <p><?=stripslashes($category[0]["metin"])?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yachts Listing -->
<section class="section-goods premium-fleet-section">
    <div class="container">
        <div class="section-goods__list">
            <div class="row">
                <?php
                // Get yachts in this category
                $yachtList = $VT->VeriGetir("yachts", "INNER JOIN yacht_category_rel ON yachts.ID = yacht_category_rel.yacht_id 
                                               WHERE yacht_category_rel.category_id=? AND yachts.durum=?", 
                                               array($category[0]["ID"], 1), "ORDER BY yachts.sirano ASC");
                
                if ($yachtList != false && count($yachtList) > 0) {
                    foreach ($yachtList as $yacht) {
                        $imagePath = SITE . "images/yachts/" . $yacht["resim"];
                ?>
                <div class="col-xl-4 col-md-6 mb-4">
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
                                    // Yacht features
                                    $features = $VT->VeriGetir("yacht_features_pivot", 
                                        "INNER JOIN yacht_features ON yacht_features_pivot.feature_id = yacht_features.ID 
                                         WHERE yacht_features_pivot.yacht_id=? AND yacht_features.durum=?", 
                                        array($yacht["ID"], 1), "ORDER BY yacht_features.baslik ASC", 5);
                                    
                                    if($features != false) {
                                        foreach($features as $index => $feature) {
                                            echo '<span class="yacht-feature-tag">' . stripslashes($feature["baslik"]) . '</span>';
                                            if($index >= 2 && count($features) > 3) {
                                                echo '<span class="yacht-feature-more">+' . (count($features) - 3) . '</span>';
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
                } else {
                ?>
                <div class="col-12">
                    <div class="alert alert-info">No yachts found in this category yet.</div>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="text-center mt-4 mb-5">
                <a class="btn btn-primary view-all-boats" href="<?=SITE?>yatlar">View All Yachts</a>
            </div>
        </div>
    </div>
</section>

<style>
.catalog-header {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 120px 0;
    position: relative;
    margin-bottom: 60px;
}

.catalog-header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.catalog-title {
    position: relative;
    color: #fff;
}

.catalog-title h1 {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}

.catalog-title p {
    font-size: 18px;
    max-width: 700px;
    margin: 0 auto;
}

.category-description {
    margin-bottom: 50px;
}

.category-content {
    font-size: 16px;
    line-height: 1.8;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.luxury-yacht-card {
    border: none;
    box-shadow: 0 5px 20px rgba(0,0,0,0.07);
    transition: all 0.3s ease;
    height: 100%;
}

.luxury-yacht-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.yacht-card-overlay {
    background: rgba(0,0,0,0.4);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.luxury-yacht-card:hover .yacht-card-overlay {
    opacity: 1;
}

.explore-btn {
    color: #fff;
    background: rgba(200, 169, 126, 0.9);
    padding: 10px 25px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.luxury-yacht-card:hover .explore-btn {
    transform: translateY(0);
}

.yacht-feature-tag {
    display: inline-block;
    background: #f5f5f5;
    color: #333;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 12px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.yacht-feature-more {
    display: inline-block;
    background: #e9e9e9;
    color: #777;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 12px;
}

.b-goods__title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
    color: #333;
}

.yacht-meta {
    font-size: 13px;
    color: #777;
    margin-bottom: 15px;
}

@media (max-width: 767px) {
    .catalog-header {
        padding: 80px 0;
    }
    
    .catalog-title h1 {
        font-size: 36px;
    }
}
</style> 
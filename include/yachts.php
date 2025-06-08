<?php
// Sayfa başlığı ve meta bilgileri
$meta_title = t('yacht.list.meta_title');
$meta_description = t('yacht.list.meta_description');
$meta_keywords = t('yacht.list.meta_keywords');

// Aktif filtreleri alın (URL parametrelerinden)
$selectedType = isset($_GET["tip"]) ? $VT->filter($_GET["tip"]) : "";
$selectedLocation = isset($_GET["lokasyon"]) ? $VT->filter($_GET["lokasyon"]) : "";
$selectedCapacity = isset($_GET["kapasite"]) ? $VT->filter($_GET["kapasite"]) : "";
$priceMin = isset($_GET["fiyat_min"]) ? $VT->filter($_GET["fiyat_min"]) : "";
$priceMax = isset($_GET["fiyat_max"]) ? $VT->filter($_GET["fiyat_max"]) : "";

// Sıralama seçeneğini al
$sort = isset($_GET["siralama"]) ? $VT->filter($_GET["siralama"]) : "price_asc";

// Filtreleme için WHERE koşulu oluştur
$whereClause = "WHERE y.durum=? AND y.availability=?";
$whereParams = array(1, 1); // Aktif ve kiralanabilir yatlar

if(!empty($selectedType)) {
  $whereClause .= " AND y.type_id=?";
  $whereParams[] = $selectedType;
}

if(!empty($selectedLocation)) {
  $whereClause .= " AND y.location_id=?";
  $whereParams[] = $selectedLocation;
}

if(!empty($selectedCapacity)) {
  $whereClause .= " AND y.capacity>=?";
  $whereParams[] = $selectedCapacity;
}

if(!empty($priceMin)) {
  $whereClause .= " AND y.price_per_day>=?";
  $whereParams[] = $priceMin;
}

if(!empty($priceMax)) {
  $whereClause .= " AND y.price_per_day<=?";
  $whereParams[] = $priceMax;
}

// Sıralama seçeneğine göre ORDER BY oluştur
switch($sort) {
  case "price_asc":
    $orderBy = "ORDER BY y.price_per_day ASC";
    break;
  case "price_desc":
    $orderBy = "ORDER BY y.price_per_day DESC";
    break;
  case "name_asc":
    $orderBy = "ORDER BY y.baslik ASC";
    break;
  case "name_desc":
    $orderBy = "ORDER BY y.baslik DESC";
    break;
  case "length_asc":
    $orderBy = "ORDER BY y.length_m ASC";
    break;
  case "length_desc":
    $orderBy = "ORDER BY y.length_m DESC";
    break;
  case "newest":
    $orderBy = "ORDER BY y.ID DESC";
    break;
  default:
    $orderBy = "ORDER BY y.price_per_day ASC";
}

// Optimize edilmiş sorgu: Yatları, resimleri ve konum bilgilerini tek sorguda getir
$yatSorgusu = "SELECT 
                y.*, 
                yt.baslik as type_name,
                yl.baslik as location_name,
                yl.sehir as location_city,
                (SELECT r.resim FROM resimler r WHERE r.tablo='yachts' AND r.KID=y.ID ORDER BY r.ID ASC LIMIT 1) as resim
              FROM 
                yachts y
              LEFT JOIN 
                yacht_types yt ON y.type_id = yt.ID
              LEFT JOIN 
                yacht_locations yl ON y.location_id = yl.ID
              $whereClause
              $orderBy";

$yatlar = $VT->SorguCalistir($yatSorgusu, $whereParams);

// Yat tipleri ve lokasyonları getir (filtreler için)
$yatTipleri = $VT->VeriGetir("yacht_types", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
$lokasyonlar = $VT->VeriGetir("yacht_locations", "WHERE durum=?", array(1), "ORDER BY baslik ASC");

// Çok dilli içerik için ID'leri topla
$yatIDleri = array();
$tipIDleri = array();
$lokasyonIDleri = array();

if($yatlar != false) {
  foreach($yatlar as $yat) {
    $yatIDleri[] = $yat["ID"];
    if(!empty($yat["type_id"]) && !in_array($yat["type_id"], $tipIDleri)) {
      $tipIDleri[] = $yat["type_id"];
    }
    if(!empty($yat["location_id"]) && !in_array($yat["location_id"], $lokasyonIDleri)) {
      $lokasyonIDleri[] = $yat["location_id"];
    }
  }
}

if($yatTipleri != false) {
  foreach($yatTipleri as $tip) {
    if(!in_array($tip["ID"], $tipIDleri)) {
      $tipIDleri[] = $tip["ID"];
    }
  }
}

if($lokasyonlar != false) {
  foreach($lokasyonlar as $lok) {
    if(!in_array($lok["ID"], $lokasyonIDleri)) {
      $lokasyonIDleri[] = $lok["ID"];
    }
  }
}

// Toplu olarak çevirileri getir
$translatedYachts = array();
$translatedYachtTypes = array();
$translatedLocations = array();

if(!empty($yatIDleri)) {
  $yachtIDsStr = implode(',', $yatIDleri);
  $translationsQuery = "SELECT * FROM translate_items WHERE tablo='yachts' AND dil=? AND KID IN ($yachtIDsStr)";
  $yachtTranslations = $VT->SorguCalistir($translationsQuery, array($_SESSION["dil"]));
  
  if($yachtTranslations != false) {
    foreach($yachtTranslations as $trans) {
      $translatedYachts[$trans["KID"]] = $trans;
    }
  }
}

if(!empty($tipIDleri)) {
  $typeIDsStr = implode(',', $tipIDleri);
  $typeTranslationsQuery = "SELECT * FROM translate_items WHERE tablo='yacht_types' AND dil=? AND KID IN ($typeIDsStr)";
  $typeTranslations = $VT->SorguCalistir($typeTranslationsQuery, array($_SESSION["dil"]));
  
  if($typeTranslations != false) {
    foreach($typeTranslations as $trans) {
      $translatedYachtTypes[$trans["KID"]] = $trans;
    }
  }
}

if(!empty($lokasyonIDleri)) {
  $locationIDsStr = implode(',', $lokasyonIDleri);
  $locationTranslationsQuery = "SELECT * FROM translate_items WHERE tablo='yacht_locations' AND dil=? AND KID IN ($locationIDsStr)";
  $locationTranslations = $VT->SorguCalistir($locationTranslationsQuery, array($_SESSION["dil"]));
  
  if($locationTranslations != false) {
    foreach($locationTranslations as $trans) {
      $translatedLocations[$trans["KID"]] = $trans;
    }
  }
}
?>

<!-- Page Title Section -->
<div class="section-title-page area-bg area-bg_dark area-bg_op_60">
  <div class="area-bg__inner">
    <div class="container text-center">
      <h1 class="b-title-page">
        <?= t('yacht.fleet_title') ?>
      </h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=SITE?>"><?= t('nav.home') ?></a></li>
          <li class="breadcrumb-item active" aria-current="page"><?= t('nav.yachts') ?></li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br><br><br>
<!-- Main Listing Section -->
<div class="container">
  <div class="row">
    <!-- Sidebar/Filters -->
    <div class="col-xl-3 col-lg-4">
      <aside class="l-sidebar l-sidebar_no-space">
        <div class="widget section-sidebar bg-gray-lighter">
          <h3 class="widget-title bg-dark"><?= t('yacht.filters') ?></h3>
          <div class="widget-content">
            <form action="<?=SITE?>yatlar" method="GET" class="b-filter">
              
              <!-- Yat Tipi Filtresi -->
              <div class="b-filter__item">
                <div class="b-filter__title"><?= t('yacht.type') ?></div>
                <div class="b-filter__checkboxes">
                  <?php if($yatTipleri != false): ?>
                    <?php foreach($yatTipleri as $tip): ?>
                      <div class="b-filter__checkbox">
                        <input class="filter__checkbox-input" type="radio" name="tip" id="type<?=$tip['ID']?>" value="<?=$tip['ID']?>" <?= $selectedType == $tip['ID'] ? 'checked' : '' ?>>
                        <label class="filter__checkbox-label" for="type<?=$tip['ID']?>">
                          <?= isset($translatedYachtTypes[$tip['ID']]) ? $translatedYachtTypes[$tip['ID']]['baslik'] : $tip['baslik'] ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
              
              <!-- Lokasyon Filtresi -->
              <div class="b-filter__item">
                <div class="b-filter__title"><?= t('yacht.location') ?></div>
                <div class="b-filter__checkboxes">
                  <?php if($lokasyonlar != false): ?>
                    <?php foreach($lokasyonlar as $lok): ?>
                      <div class="b-filter__checkbox">
                        <input class="filter__checkbox-input" type="radio" name="lokasyon" id="loc<?=$lok['ID']?>" value="<?=$lok['ID']?>" <?= $selectedLocation == $lok['ID'] ? 'checked' : '' ?>>
                        <label class="filter__checkbox-label" for="loc<?=$lok['ID']?>">
                          <?= isset($translatedLocations[$lok['ID']]) ? $translatedLocations[$lok['ID']]['baslik'] : $lok['baslik'] ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
              
              <!-- Kapasite Filtresi -->
              <div class="b-filter__item">
                <div class="b-filter__title"><?= t('yacht.guest_capacity') ?></div>
                <div class="b-filter__checkboxes">
                  <div class="b-filter__checkbox">
                    <input class="filter__checkbox-input" type="radio" name="kapasite" id="cap1" value="4" <?= $selectedCapacity == '4' ? 'checked' : '' ?>>
                    <label class="filter__checkbox-label" for="cap1">4+ <?= t('yacht.persons') ?></label>
                  </div>
                  <div class="b-filter__checkbox">
                    <input class="filter__checkbox-input" type="radio" name="kapasite" id="cap2" value="8" <?= $selectedCapacity == '8' ? 'checked' : '' ?>>
                    <label class="filter__checkbox-label" for="cap2">8+ <?= t('yacht.persons') ?></label>
                  </div>
                  <div class="b-filter__checkbox">
                    <input class="filter__checkbox-input" type="radio" name="kapasite" id="cap3" value="12" <?= $selectedCapacity == '12' ? 'checked' : '' ?>>
                    <label class="filter__checkbox-label" for="cap3">12+ <?= t('yacht.persons') ?></label>
                  </div>
                  <div class="b-filter__checkbox">
                    <input class="filter__checkbox-input" type="radio" name="kapasite" id="cap4" value="16" <?= $selectedCapacity == '16' ? 'checked' : '' ?>>
                    <label class="filter__checkbox-label" for="cap4">16+ <?= t('yacht.persons') ?></label>
                  </div>
                </div>
              </div>
              
              <!-- Fiyat Filtresi -->
              <div class="b-filter__item">
                <div class="b-filter__title"><?= t('yacht.price_range') ?></div>
                <div class="b-filter__price">
                  <div class="b-filter__price-title">
                    <input class="form-control" type="number" name="fiyat_min" placeholder="Min" value="<?=$priceMin?>">
                    <span class="b-filter__price-separator">-</span>
                    <input class="form-control" type="number" name="fiyat_max" placeholder="Max" value="<?=$priceMax?>">
                    <span class="b-filter__price-unit">₺</span>
                  </div>
                </div>
              </div>
              
              <!-- Sıralama -->
              <div class="b-filter__item">
                <div class="b-filter__title"><?= t('yacht.sort_by') ?></div>
                <select class="form-control" name="siralama">
                  <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>><?= t('yacht.sort.price_low') ?></option>
                  <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>><?= t('yacht.sort.price_high') ?></option>
                  <option value="length_desc" <?= $sort == 'length_desc' ? 'selected' : '' ?>><?= t('yacht.sort.length_large') ?></option>
                  <option value="length_asc" <?= $sort == 'length_asc' ? 'selected' : '' ?>><?= t('yacht.sort.length_small') ?></option>
                  <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>><?= t('yacht.sort.newest') ?></option>
                </select>
              </div>
              
              <!-- Filtreleme Butonları -->
              <div class="b-filter__btns">
                <button class="b-filter__btn btn btn-primary" type="submit"><?= t('yacht.filter_apply') ?></button>
                <a href="<?=SITE?>yatlar" class="b-filter__btn btn btn-outline-primary"><?= t('yacht.filter_reset') ?></a>
              </div>
            </form>
          </div>
        </div>
      </aside>
    </div>
    
    <!-- Yacht Listing -->
    <div class="col-xl-9 col-lg-8">
      <!-- Results Summary -->
      <div class="yacht-listing-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h2 class="yacht-listing-title"><?= t('yacht.available_yachts') ?></h2>
          </div>
          <div class="col-md-6">
            <div class="yacht-listing-count text-md-right">
              <?php if($yatlar != false): ?>
                <?= count($yatlar) ?> <?= t('yacht.yachts_found') ?>
              <?php else: ?>
                0 <?= t('yacht.yachts_found') ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Yacht Listings -->
      <div class="row yacht-listing-row">
        <?php if($yatlar != false): ?>
          <?php foreach($yatlar as $yat): ?>
            <?php
              // Çeviri verilerini kullan
              $yatTitle = isset($translatedYachts[$yat["ID"]]) ? $translatedYachts[$yat["ID"]]["baslik"] : $yat["baslik"];
              $yatDesc = isset($translatedYachts[$yat["ID"]]) ? $translatedYachts[$yat["ID"]]["description"] : $yat["description"];
              
              // Lokasyon bilgisini al
              $lokasyonAdi = isset($translatedLocations[$yat["location_id"]]) ? 
                            $translatedLocations[$yat["location_id"]]["baslik"] : 
                            ($yat["location_name"] ?? t('yacht.location_unknown'));
              
              // Resim yolu
              $resimYolu = !empty($yat["resim"]) ? $yat["resim"] : "default-yacht.jpg";
            ?>
            
            <!-- Yacht Card -->
            <div class="col-lg-6 col-md-6">
              <div class="card yacht-card">
                <div class="yacht-card-image">
                  <a href="<?=SITE?>yat/<?=$yat["seflink"]?>">
                    <img src="<?=SITE?>images/yachts/<?=$resimYolu?>" alt="<?=$yatTitle?>" class="card-img-top" loading="lazy">
                  </a>
                  <?php if($yat["is_featured"]): ?>
                    <div class="yacht-badge"><?= t('yacht.featured') ?></div>
                  <?php endif; ?>
                </div>
                
                <div class="card-body">
                  <h3 class="yacht-title">
                    <a href="<?=SITE?>yat/<?=$yat["seflink"]?>"><?=$yatTitle?></a>
                  </h3>
                  
                  <div class="yacht-location">
                    <i class="fas fa-map-marker-alt"></i> <?=$lokasyonAdi?>
                  </div>
                  
                  <div class="yacht-specs">
                    <span><i class="fas fa-ruler"></i> <?=$yat["length_m"]?> m</span>
                    <span><i class="fas fa-user"></i> <?=$yat["capacity"]?> <?= t('yacht.persons') ?></span>
                    <span><i class="fas fa-bed"></i> <?=$yat["cabins"]?> <?= t('yacht.cabins') ?></span>
                    <span><i class="fas fa-user-tie"></i> <?=$yat["crew"]?> <?= t('yacht.crew') ?></span>
                  </div>
                  
                  <div class="yacht-price">
                    <span class="price-label"><?= t('yacht.price_per_day') ?>:</span>
                    <span class="price-value"><?=number_format($yat["price_per_day"], 0, ',', '.')?> ₺</span>
                  </div>
                  
                  <div class="yacht-actions">
                    <a href="<?=SITE?>yat/<?=$yat["seflink"]?>" class="btn btn-outline-primary btn-sm"><?= t('yacht.details') ?></a>
                    <a href="<?=SITE?>rezervasyon-yap?yat_id=<?=$yat["ID"]?>" class="btn btn-primary btn-sm"><?= t('yacht.booking') ?></a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="alert alert-info">
              <?= t('yacht.no_results') ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Yacht Inquiry CTA Section -->
<section class="section-default bg-gray">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 text-center">
        <div class="ui-title-block">
          <h2 class="ui-title"><?= t('yacht.custom_request_title') ?></h2>
          <div class="ui-subtitle"><?= t('yacht.custom_request_subtitle') ?></div>
        </div>
        <a href="<?=SITE?>iletisim" class="btn btn-primary btn-lg"><?= t('nav.contact_us') ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Include CSS and JS files -->
<link rel="stylesheet" href="<?=SITE?>assets/css/yacht-listing.css">
<meta name="site-url" content="<?=SITE?>">
<script src="<?=SITE?>assets/js/yacht-listing.js"></script>
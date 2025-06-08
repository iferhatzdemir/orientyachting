<!-- ==========================-->
<!-- SEARCH MODAL-->
<!-- ==========================-->
<div class="header-search open-search">
  <div class="container">
    <div class="row">
      <div class="col-sm-8 offset-sm-2 col-10 offset-1">
        <div class="navbar-search">
          <form class="search-global">
            <input class="search-global__input" type="text" placeholder="Type to search" autocomplete="off" name="s" value=""/>
            <button class="search-global__btn"><i class="icon stroke icon-Search"></i></button>
            <div class="search-global__note">Begin typing your search above and press return to search.</div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <button class="search-close close" type="button"><i class="fa fa-times"></i></button>
</div>

<div class="section-title-page area-bg area-bg_dark area-bg_op_60">
  <div class="area-bg__inner">
    <div class="container text-center">
      <h1 class="b-title-page">Yat Filosu</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Anasayfa</a></li>
          <li class="breadcrumb-item active" aria-current="page">Yatlar</li>
        </ol>
      </nav>
      <!-- end .breadcrumb-->
    </div>
  </div>
</div>
<!-- end .b-title-page-->

<div class="l-main-content">
  <div class="ui-decor ui-decor_mirror ui-decor_sm-h bg-primary"></div>
    
  <div class="container">
    <div class="b-filter-goods b-filter-goods_center mb-5 pb-4">
      <?php
      // Filtre metinlerini veritabanından çek
      $filtreMetinleri = $VT->VeriGetir("filtre_metinleri", "WHERE durum=?", array(1), "ORDER BY ID ASC", 1);
      
      // Varsayılan değerler (veritabanında kayıt yoksa kullanılacak)
      $filtre_metinleri = array(
        'tum_yat_tipleri' => 'Tüm Yat Tipleri',
        'kapasite_baslik' => 'Kapasite',
        'tum_kapasiteler' => 'Tüm Kapasiteler',
        'kisi_1_4' => '1-4 Kişi',
        'kisi_5_8' => '5-8 Kişi',
        'kisi_9_12' => '9-12 Kişi',
        'kisi_13_plus' => '13+ Kişi',
        'fiyat_baslik' => 'Fiyat Aralığı',
        'tum_fiyatlar' => 'Tüm Fiyatlar',
        'gunluk_100_kadar' => 'Günlük 100₺\'ye kadar',
        'gunluk_100_300' => 'Günlük 100₺-300₺',
        'gunluk_300_500' => 'Günlük 300₺-500₺',
        'gunluk_500_plus' => 'Günlük 500₺ ve üzeri',
        'sayfa_basina' => 'Sayfa Başına',
        'yat_12' => '12 Yat',
        'yat_24' => '24 Yat',
        'yat_36' => '36 Yat',
        'siralama_turu' => 'Sıralama Türü',
        'ekleme_tarihi' => 'Ekleme Tarihi',
        'fiyat' => 'Fiyat',
        'uzunluk' => 'Uzunluk',
        'uretim_yili' => 'Üretim Yılı',
        'siralama_yonu' => 'Sıralama Yönü',
        'azalan' => 'Azalan (Z-A)',
        'artan' => 'Artan (A-Z)'
      );
      
      // Veritabanından gelen metinleri kullan (eğer varsa)
      if($filtreMetinleri != false) {
        foreach($filtreMetinleri[0] as $key => $value) {
          if(isset($filtre_metinleri[$key]) && !empty($value)) {
            $filtre_metinleri[$key] = $value;
          }
        }
      }

      // Filtre başlıklarını veritabanından çek
      $filtreBasliklari = $VT->VeriGetir("filtre_basliklar", "WHERE durum=?", array(1), "ORDER BY ID ASC", 1);
      
      // Varsayılan değerler
      $basliklar = array(
        'yat_tipleri' => 'Yat Tipi'
      );
      
      // Veritabanından gelen başlıkları kullan (eğer varsa)
      if($filtreBasliklari != false) {
        foreach($filtreBasliklari[0] as $key => $value) {
          if(isset($basliklar[$key]) && !empty($value)) {
            $basliklar[$key] = $value;
          }
        }
      }

      // Filtre parametrelerini al
      $limit = isset($_GET["limit"]) ? (int)$_GET["limit"] : 12;
      $siralama = isset($_GET["siralama"]) ? $_GET["siralama"] : "desc";
      $siralamaTuru = isset($_GET["siralamaTuru"]) ? $_GET["siralamaTuru"] : "ID";
      $yatTipi = isset($_GET["yatTipi"]) ? (int)$_GET["yatTipi"] : 0;
      $kapasite = isset($_GET["kapasite"]) ? $_GET["kapasite"] : '';
      $fiyatAralik = isset($_GET["fiyatAralik"]) ? $_GET["fiyatAralik"] : '';
      
      // Sayfa numarası
      $sayfa = isset($_GET["sayfa"]) ? (int)$_GET["sayfa"] : 1;
      $offset = ($sayfa - 1) * $limit;
      
      // Sorgu koşullarını oluştur
      $whereKosullar = "WHERE durum=?";
      $parametreler = array(1);
      
      // Yat tipi filtrelemesi
      if($yatTipi > 0) {
        $whereKosullar .= " AND type_id=?";
        $parametreler[] = $yatTipi;
      }
      
      // Kapasite filtrelemesi
      if(!empty($kapasite)) {
        $kapasiteAralik = explode('-', $kapasite);
        if(count($kapasiteAralik) == 2) {
          // Aralık belirtilmiş (örn: 5-8)
          $whereKosullar .= " AND capacity BETWEEN ? AND ?";
          $parametreler[] = $kapasiteAralik[0];
          $parametreler[] = $kapasiteAralik[1];
        } else if($kapasite == "13+") {
          // 13 ve üzeri
          $whereKosullar .= " AND capacity >= ?";
          $parametreler[] = 13;
        }
      }
      
      // Fiyat aralığı filtrelemesi
      if(!empty($fiyatAralik)) {
        $fiyatAralikDeger = explode('-', $fiyatAralik);
        if(count($fiyatAralikDeger) == 2) {
          // Aralık belirtilmiş (örn: 100-300)
          $whereKosullar .= " AND price_per_day BETWEEN ? AND ?";
          $parametreler[] = $fiyatAralikDeger[0];
          $parametreler[] = $fiyatAralikDeger[1];
        } else if(strpos($fiyatAralik, '+') !== false) {
          // Alt limit belirtilmiş (örn: 500+)
          $altLimit = (int)str_replace('+', '', $fiyatAralik);
          $whereKosullar .= " AND price_per_day >= ?";
          $parametreler[] = $altLimit;
        } else if(strpos($fiyatAralik, '-') === 0) {
          // Üst limit belirtilmiş (örn: -100)
          $ustLimit = (int)str_replace('-', '', $fiyatAralik);
          $whereKosullar .= " AND price_per_day <= ?";
          $parametreler[] = $ustLimit;
        }
      }
      
      // Toplam yat sayısını bul
      $toplamSorgu = $VT->VeriGetir("yachts", $whereKosullar, $parametreler, "");
      $toplamYat = ($toplamSorgu != false) ? count($toplamSorgu) : 0;
      $toplamSayfa = ceil($toplamYat / $limit);
      
      // Sıralama için ORDER BY oluştur
      $orderBy = "ORDER BY $siralamaTuru $siralama";
      
      // Ana sorgu
      $yatlarSorgu = $VT->VeriGetir("yachts", $whereKosullar, $parametreler, "$orderBy LIMIT $offset, $limit");
      
      // Yat tipleri listesini al
      $yatTipleri = $VT->VeriGetir("yacht-types", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
      
      // Görüntülenen yat sayısı
      $gosterilenBaslangic = $offset + 1;
      $gosterilenBitis = min($offset + $limit, $toplamYat);
      
      // Current query parameters for preserving filters
      $currentParams = $_GET;
      unset($currentParams['sayfa']); // Pagination will be handled separately
      $queryString = http_build_query($currentParams);
      $queryPrefix = !empty($queryString) ? '&' : '';
      ?>
      
      <div class="b-filter-goods__info">Sonuçlar gösteriliyor<strong> <?=$gosterilenBaslangic?> - <?=$gosterilenBitis?></strong>
        Toplam <?=$toplamYat?> yat
      </div>
      
      <form id="filterForm" method="GET" action="<?=SITE?>yatlar">
        <!-- Yat tipi filtresi -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="yatTipi" data-width="170px" title="<?=$basliklar['yat_tipleri']?>" data-style="ui-select">
            <option value="0" <?=($yatTipi == 0 ? 'selected' : '')?>><?=$filtre_metinleri['tum_yat_tipleri']?></option>
            <?php
            if($yatTipleri != false) {
              foreach($yatTipleri as $tip) {
            ?>
            <option value="<?=$tip["ID"]?>" <?=($yatTipi == $tip["ID"] ? 'selected' : '')?>><?=$tip["baslik"]?></option>
            <?php
              }
            }
            ?>
          </select>
        </div>
        
        <!-- Kapasite filtresi -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="kapasite" data-width="170px" title="<?=$filtre_metinleri['kapasite_baslik']?>" data-style="ui-select">
            <option value="" <?=($kapasite == '' ? 'selected' : '')?>><?=$filtre_metinleri['tum_kapasiteler']?></option>
            <option value="1-4" <?=($kapasite == '1-4' ? 'selected' : '')?>><?=$filtre_metinleri['kisi_1_4']?></option>
            <option value="5-8" <?=($kapasite == '5-8' ? 'selected' : '')?>><?=$filtre_metinleri['kisi_5_8']?></option>
            <option value="9-12" <?=($kapasite == '9-12' ? 'selected' : '')?>><?=$filtre_metinleri['kisi_9_12']?></option>
            <option value="13+" <?=($kapasite == '13+' ? 'selected' : '')?>><?=$filtre_metinleri['kisi_13_plus']?></option>
          </select>
        </div>
        
        <!-- Fiyat aralığı filtresi -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="fiyatAralik" data-width="190px" title="<?=$filtre_metinleri['fiyat_baslik']?>" data-style="ui-select">
            <option value="" <?=($fiyatAralik == '' ? 'selected' : '')?>><?=$filtre_metinleri['tum_fiyatlar']?></option>
            <option value="-100" <?=($fiyatAralik == '-100' ? 'selected' : '')?>><?=$filtre_metinleri['gunluk_100_kadar']?></option>
            <option value="100-300" <?=($fiyatAralik == '100-300' ? 'selected' : '')?>><?=$filtre_metinleri['gunluk_100_300']?></option>
            <option value="300-500" <?=($fiyatAralik == '300-500' ? 'selected' : '')?>><?=$filtre_metinleri['gunluk_300_500']?></option>
            <option value="500+" <?=($fiyatAralik == '500+' ? 'selected' : '')?>><?=$filtre_metinleri['gunluk_500_plus']?></option>
          </select>
        </div>
        
        <!-- Sayfa başına gösterilecek adet -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="limit" data-width="120px" title="<?=$filtre_metinleri['sayfa_basina']?>" data-style="ui-select">
            <option value="12" <?=($limit == 12 ? 'selected' : '')?>><?=$filtre_metinleri['yat_12']?></option>
            <option value="24" <?=($limit == 24 ? 'selected' : '')?>><?=$filtre_metinleri['yat_24']?></option>
            <option value="36" <?=($limit == 36 ? 'selected' : '')?>><?=$filtre_metinleri['yat_36']?></option>
          </select>
        </div>
        
        <!-- Sıralama türü -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="siralamaTuru" data-width="190px" title="<?=$filtre_metinleri['siralama_turu']?>" data-style="ui-select">
            <option value="ID" <?=($siralamaTuru == 'ID' ? 'selected' : '')?>><?=$filtre_metinleri['ekleme_tarihi']?></option>
            <option value="price_per_day" <?=($siralamaTuru == 'price_per_day' ? 'selected' : '')?>><?=$filtre_metinleri['fiyat']?></option>
            <option value="length_m" <?=($siralamaTuru == 'length_m' ? 'selected' : '')?>><?=$filtre_metinleri['uzunluk']?></option>
            <option value="build_year" <?=($siralamaTuru == 'build_year' ? 'selected' : '')?>><?=$filtre_metinleri['uretim_yili']?></option>
          </select>
        </div>
        
        <!-- Sıralama yönü -->
        <div class="b-filter-goods__select">
          <select class="selectpicker filter-option" name="siralama" data-width="190px" title="<?=$filtre_metinleri['siralama_yonu']?>" data-style="ui-select">
            <option value="desc" <?=($siralama == 'desc' ? 'selected' : '')?>><?=$filtre_metinleri['azalan']?></option>
            <option value="asc" <?=($siralama == 'asc' ? 'selected' : '')?>><?=$filtre_metinleri['artan']?></option>
          </select>
        </div>
      </form>
    </div>

    <div class="b-goods-group-2 row">
      <?php
      if($yatlarSorgu != false) {
        foreach($yatlarSorgu as $yat) {
          // Resim yolunu ve kullanılacak uzantıyı belirle
          $resimAdi = !empty($yat["resim"]) ? pathinfo($yat["resim"], PATHINFO_FILENAME) : "default";
          $resimUzanti = !empty($yat["resim"]) ? pathinfo($yat["resim"], PATHINFO_EXTENSION) : "jpg";
          
          // Farklı uzantıları dene
          $uzantilar = ['jpg', 'jpeg', 'png', 'gif'];
          $bulunduResim = null;
          
          // Önce orijinal uzantıyı kontrol et
          $orijinalResimYolu = $_SERVER['DOCUMENT_ROOT']."/orient/images/yachts/".$resimAdi.".".$resimUzanti;
          if(file_exists($orijinalResimYolu)) {
              $bulunduResim = $resimAdi.".".$resimUzanti;
          } else {
              // Eğer orijinal uzantı yoksa, diğer uzantıları dene
              foreach($uzantilar as $uzanti) {
                  $kontrolYolu = $_SERVER['DOCUMENT_ROOT']."/orient/images/yachts/".$resimAdi.".".$uzanti;
                  if(file_exists($kontrolYolu)) {
                      $bulunduResim = $resimAdi.".".$uzanti;
                      break;
                  }
              }
          }
          
          // Eğer hiçbir dosya bulunamadıysa, varsayılan resmi kullan
          if($bulunduResim === null) {
              $bulunduResim = "default.jpg";
          }
          
          $lokasyon = "Belirtilmemiş";
          if(!empty($yat["location_id"])) {
            $lokasyonBilgisi = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($yat["location_id"]), "ORDER BY ID ASC", 1);
            if($lokasyonBilgisi != false) {
                $lokasyon = $lokasyonBilgisi[0]["baslik"];
            }
          }
      ?>
      <div class="col-xl-4 col-md-6">
        <div class="b-goods-flip">
          <button class="flip-btn"><span></span><span class="flip-btn-mdl"></span><span></span></button>
          <div class="flip-container">
            <div class="flipper">
              <div class="flip__front">
                <div class="b-goods-flip__img">
                  <img class="img-scale" src="<?=SITE?>images/yachts/<?=$bulunduResim?>" alt="<?=$yat["baslik"]?>"/>
                </div>
                <div class="b-goods-flip__main">
                  <div class="b-goods-flip__header row no-gutters align-items-center">
                    <div class="col"><a class="b-goods-flip__title" href="<?=SITE?>yat/<?=$yat["seflink"]?>"><?=$yat["baslik"]?></a></div>
                    <div class="col-auto">
                      <div class="b-goods-flip__price text-primary"><?=number_format($yat["price_per_day"], 2, ',', '.')?> TL <span>/ günlük</span></div>
                    </div>
                  </div>
                  <div class="b-goods-descrip_nev_wrap">
                    <div class="b-ex-info"><?=mb_substr(strip_tags($yat["description"]), 0, 120, "UTF-8")?>...</div>
                    <a class="btn btn-default w-100" href="<?=SITE?>yat/<?=$yat["seflink"]?>">DETAYLAR</a>
                  </div>
                </div>
              </div>
              <div class="flip__back">
                <div class="b-goods-flip__header">
                  <div class="b-goods-flip__title"><?=$yat["baslik"]?></div>
                  <div class="b-goods-flip__category"><?=$lokasyon?></div>
                  <div class="flip-btn-hide"></div>
                </div>
                <div class="b-goods-flip-info">
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Marka</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["brand"]?></span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Model</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["model"]?></span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Uzunluk</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["length_m"]?> m</span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Yolcu Kapasitesi</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["capacity"]?></span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Kabin Sayısı</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["cabins"]?></span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Üretim Yılı</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$yat["build_year"]?></span>
                  </div>
                  <div class="b-goods-flip-info__item row no-gutters justify-content-between">
                    <span class="b-goods-flip-info__title col-auto">Lokasyon</span>
                    <span class="b-goods-flip-info__desc col-auto"><?=$lokasyon?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
        } // foreach end
      } else {
      ?>
      <div class="col-12">
        <div class="alert alert-warning">Gösterilecek yat bulunamadı.</div>
      </div>
      <?php
      }
      ?>
    </div>

    <div class="row">
      <div class="col-12">
        <ul class="pagination justify-content-center">
          <?php if($sayfa > 1): ?>
          <li class="page-item"><a class="page-link" href="?<?=$queryString.$queryPrefix?>sayfa=<?=$sayfa-1?>"><i class="fas fa-angle-left"></i></a></li>
          <?php else: ?>
          <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
          <?php endif; ?>
          
          <?php
          // Sayfa numaralarını göster
          $startPage = max(1, $sayfa - 2);
          $endPage = min($toplamSayfa, $sayfa + 2);
          
          // İlk sayfa
          if($startPage > 1): ?>
          <li class="page-item"><a class="page-link" href="?<?=$queryString.$queryPrefix?>sayfa=1">1</a></li>
          <?php if($startPage > 2): ?>
          <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
          <?php endif; ?>
          <?php endif; ?>
          
          <?php for($i = $startPage; $i <= $endPage; $i++): ?>
          <li class="page-item <?=($i == $sayfa ? 'active' : '')?>">
            <a class="page-link" href="?<?=$queryString.$queryPrefix?>sayfa=<?=$i?>"><?=$i?></a>
          </li>
          <?php endfor; ?>
          
          <?php 
          // Son sayfa
          if($endPage < $toplamSayfa): 
            if($endPage < $toplamSayfa - 1): ?>
          <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
          <?php endif; ?>
          <li class="page-item"><a class="page-link" href="?<?=$queryString.$queryPrefix?>sayfa=<?=$toplamSayfa?>"><?=$toplamSayfa?></a></li>
          <?php endif; ?>
          
          <?php if($sayfa < $toplamSayfa): ?>
          <li class="page-item"><a class="page-link" href="?<?=$queryString.$queryPrefix?>sayfa=<?=$sayfa+1?>"><i class="fas fa-angle-right"></i></a></li>
          <?php else: ?>
          <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Filtreleme için JavaScript kodu -->
<script>
$(document).ready(function() {
  // Filtre değişikliklerinde sayfayı yeniden yükleme
  $('.filter-option').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    $('#filterForm').submit();
  });
});
</script> 
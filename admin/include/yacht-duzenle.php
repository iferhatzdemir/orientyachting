<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

if(!empty($_GET["ID"])) {
    $ID = $VT->filter($_GET["ID"]);
    
    $veri = $VT->VeriGetir("yachts", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
    if($veri != false) {
        // Mevcut verileri al
        // Dil verilerini al
        $dilVerileri = array();
        $dilKayitlari = $VT->VeriGetir("urunler_dil", "WHERE urun_id=?", array($ID), "ORDER BY ID ASC");
        if($dilKayitlari != false) {
            foreach($dilKayitlari as $dilKayit) {
                $dilVerileri[$dilKayit["lang"]] = $dilKayit;
            }
        }
        
        // Yatın özelliklerini al
        $secilenOzellikler = array();
        $ozellikler = $VT->VeriGetir("yacht_features_pivot", "WHERE yacht_id=?", array($ID), "ORDER BY ID ASC");
        if($ozellikler != false) {
            foreach($ozellikler as $ozellik) {
                $secilenOzellikler[] = $ozellik["feature_id"];
            }
        }
        
        if($_POST) {
            if(!empty($_POST["baslik"]) && !empty($_POST["type_id"]) && !empty($_POST["location_id"]) && !empty($_POST["price_per_day"])) {
                
                $baslik = $VT->filter($_POST["baslik"]);
                $type_id = $VT->filter($_POST["type_id"]);
                $length_m = $VT->filter($_POST["length_m"]);
                $capacity = $VT->filter($_POST["capacity"]);
                $cabin_count = $VT->filter($_POST["cabin_count"]);
                $guest_capacity = $VT->filter($_POST["guest_capacity"]);
                $location_id = $VT->filter($_POST["location_id"]);
                $crew = $VT->filter($_POST["crew"]);
                $build_year = $VT->filter($_POST["build_year"]);
                $price_per_day = $VT->filter($_POST["price_per_day"]);
                $price_per_week = $VT->filter($_POST["price_per_week"]);
                $metin = $VT->filter($_POST["metin"], true);
                $seo_title = $VT->filter($_POST["seo_title"]);
                $seo_desc = $VT->filter($_POST["seo_desc"]);
                
                if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
                if(!empty($_POST["availability"])) { $availability = 1; } else { $availability = 2; }
                
                $seflink = $VT->seflink($baslik);
                
                // Resim Yükleme İşlemi
                if(!empty($_FILES["resim"]["name"])) {
                    $yukle = $VT->upload("resim", "../images/yachts/");
                    if($yukle != false) {
                        $guncelle = $VT->SorguCalistir("UPDATE yachts", "SET baslik=?, type_id=?, length_m=?, capacity=?, cabin_count=?, guest_capacity=?, location_id=?, crew=?, build_year=?, price_per_day=?, price_per_week=?, metin=?, resim=?, seo_title=?, seo_desc=?, durum=?, availability=?, seflink=? WHERE ID=?", array($baslik, $type_id, $length_m, $capacity, $cabin_count, $guest_capacity, $location_id, $crew, $build_year, $price_per_day, $price_per_week, $metin, $yukle, $seo_title, $seo_desc, $durum, $availability, $seflink, $ID));
                    } else {
                        $bildirim = "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Resim yükleme işleminiz başarısız.'
                            });
                        </script>";
                    }
                } else {
                    $guncelle = $VT->SorguCalistir("UPDATE yachts", "SET baslik=?, type_id=?, length_m=?, capacity=?, cabin_count=?, guest_capacity=?, location_id=?, crew=?, build_year=?, price_per_day=?, price_per_week=?, metin=?, seo_title=?, seo_desc=?, durum=?, availability=?, seflink=? WHERE ID=?", array($baslik, $type_id, $length_m, $capacity, $cabin_count, $guest_capacity, $location_id, $crew, $build_year, $price_per_day, $price_per_week, $metin, $seo_title, $seo_desc, $durum, $availability, $seflink, $ID));
                }
                
                if($guncelle != false) {
                    // Dil bilgilerini güncelle
                    $dilSayisi = 0;
                    $toplamDil = 0;
                    $dilHatasi = false;
                    $hataliDiller = '';
                    
                    // Önce önceki özellikleri temizle
                    $VT->SorguCalistir("DELETE FROM yacht_features_pivot", "WHERE yacht_id=?", array($ID));
                    
                    // Sonra seçilen özellikleri ekle
                    if(!empty($_POST["features"])) {
                        foreach($_POST["features"] as $feature) {
                            $VT->SorguCalistir("INSERT INTO yacht_features_pivot", "SET yacht_id=?, feature_id=?", array($ID, $feature));
                        }
                    }
                    
                    if(!empty($_POST["lang"])) {
                        foreach($_POST["lang"] as $lang => $value) {
                            if(!empty($value["baslik"])) {
                                $toplamDil++;
                                $dil_baslik = $VT->filter($value["baslik"]);
                                $dil_metin = $VT->filter($value["metin"], true);
                                $dil_seo_title = $VT->filter($value["seo_title"]);
                                $dil_seo_desc = $VT->filter($value["seo_desc"]);
                                
                                // Eğer dil kaydı varsa güncelle, yoksa ekle
                                if(isset($dilVerileri[$lang])) {
                                    $dilGuncelle = $VT->SorguCalistir("UPDATE urunler_dil", "SET baslik=?, metin=?, seo_title=?, seo_desc=? WHERE urun_id=? AND lang=?", array($dil_baslik, $dil_metin, $dil_seo_title, $dil_seo_desc, $ID, $lang));
                                } else {
                                    $dilGuncelle = $VT->SorguCalistir("INSERT INTO urunler_dil", "SET urun_id=?, lang=?, baslik=?, metin=?, seo_title=?, seo_desc=?", array($ID, $lang, $dil_baslik, $dil_metin, $dil_seo_title, $dil_seo_desc));
                                }
                                
                                if($dilGuncelle != false) {
                                    $dilSayisi++;
                                } else {
                                    $dilHatasi = true;
                                    $hataliDiller .= $lang . ', ';
                                }
                            }
                        }
                    }
                    
                    // Başarı bildirimi
                    if($toplamDil > 0) {
                        if($dilHatasi) {
                            $hataliDiller = rtrim($hataliDiller, ', ');
                            $bildirim = "<script>
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Yat güncellendi, bazı dil kayıtları başarısız!',
                                    html: 'Yat başarıyla güncellendi.<br>Toplam ".$toplamDil." dil kaydından ".$dilSayisi." tanesi başarılı.<br>Başarısız diller: ".$hataliDiller."',
                                    showConfirmButton: true,
                                    timer: 3000,
                                    timerProgressBar: true
                                }).then((result) => {
                                    window.location.href = '".SITE."yachts';
                                });
                            </script>";
                        } else {
                            $bildirim = "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    html: 'Yat başarıyla güncellendi.<br>Toplam ".$toplamDil." dil kaydının tamamı başarıyla güncellendi.',
                                    showConfirmButton: true,
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then((result) => {
                                    window.location.href = '".SITE."yachts';
                                });
                            </script>";
                        }
                    } else {
                        $bildirim = "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: 'Yat başarıyla güncellendi.',
                                showConfirmButton: true,
                                timer: 2000,
                                timerProgressBar: true
                            }).then((result) => {
                                window.location.href = '".SITE."yachts';
                            });
                        </script>";
                    }
                } else {
                    $bildirim = "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Yat bilgileri güncellenirken bir sorun oluştu.'
                        });
                    </script>";
                }
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Zorunlu alanları doldurunuz.'
                    });
                </script>";
            }
        }
?>

<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Yat Düzenle</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Yat Düzenle</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
       <div class="row">
       <div class="col-md-12">
       <a href="<?=SITE?>yachts" class="btn btn-info" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="fas fa-bars"></i> LİSTE</a> 
        <a href="<?=SITE?>yacht-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ EKLE</a>
       </div>
       </div>
       
       <div class="row">
         <div class="col-md-12">
           <div class="alert alert-info">
             <i class="fa fa-info-circle"></i> * işaretli alanlar zorunludur.
           </div>
         </div>
       </div>
       
       <form action="#" method="post" enctype="multipart/form-data">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Yat Bilgileri</h3>
            </div>
            <div class="card-body">
            
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="genel-tab" data-toggle="tab" href="#genel" role="tab" aria-controls="genel" aria-selected="true">Genel Bilgiler</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tr" role="tab" aria-controls="tr" aria-selected="false">Türkçe</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">İngilizce</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="de-tab" data-toggle="tab" href="#de" role="tab" aria-controls="de" aria-selected="false">Almanca</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="ru-tab" data-toggle="tab" href="#ru" role="tab" aria-controls="ru" aria-selected="false">Rusça</a>
                  </li>
                </ul>
                
                <div class="tab-content mt-3">
                
                  <!-- Genel Bilgiler Sekmesi -->
                  <div class="tab-pane fade show active" id="genel" role="tabpanel" aria-labelledby="genel-tab">
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Yat Adı *</label>
                          <input type="text" class="form-control" placeholder="Yat Adı" name="baslik" value="<?=$veri[0]["baslik"]?>" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Yat Tipi *</label>
                          <select class="form-control select2" style="width: 100%;" name="type_id" required>
                          <option value="">Yat Tipi Seçiniz</option>
                          <?php
                            $yatTipleri = $VT->VeriGetir("yacht_types", "WHERE durum=?", array(1), "ORDER BY ID ASC");
                            if($yatTipleri != false) {
                                for($i=0; $i<count($yatTipleri); $i++) {
                                    if($veri[0]["type_id"] == $yatTipleri[$i]["ID"]) {
                                        echo '<option value="'.$yatTipleri[$i]["ID"].'" selected>'.$yatTipleri[$i]["baslik"].'</option>';
                                    } else {
                                        echo '<option value="'.$yatTipleri[$i]["ID"].'">'.$yatTipleri[$i]["baslik"].'</option>';
                                    }
                                }
                            }
                          ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Uzunluk (metre)</label>
                          <input type="number" class="form-control" placeholder="Uzunluk" name="length_m" step="0.01" value="<?=$veri[0]["length_m"]?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Kapasite (kişi)</label>
                          <input type="number" class="form-control" placeholder="Kapasite" name="capacity" value="<?= $veri[0]["capacity"] ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Mürettebat Sayısı</label>
                          <input type="number" class="form-control" placeholder="Mürettebat" name="crew" value="<?= $veri[0]["crew"] ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Kabin Sayısı</label>
                          <input type="number" class="form-control" placeholder="Kabin Sayısı" name="cabin_count" value="<?= $veri[0]["cabin_count"] ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Konaklama Kapasitesi (kişi)</label>
                          <input type="number" class="form-control" placeholder="Konaklama Kapasitesi" name="guest_capacity" value="<?= $veri[0]["guest_capacity"] ?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Yapım Yılı</label>
                          <input type="number" class="form-control" placeholder="Yapım Yılı" name="build_year" value="<?=$veri[0]["build_year"]?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Günlük Fiyat (TL) *</label>
                          <input type="number" class="form-control" placeholder="Günlük Fiyat" name="price_per_day" step="0.01" required value="<?=$veri[0]["price_per_day"]?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Haftalık Fiyat (TL)</label>
                          <input type="number" class="form-control" placeholder="Haftalık Fiyat" name="price_per_week" step="0.01" value="<?=$veri[0]["price_per_week"]?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Lokasyon *</label>
                          <select class="form-control select2" style="width: 100%;" name="location_id" required>
                          <option value="">Lokasyon Seçiniz</option>
                          <?php
                            $lokasyonlar = $VT->VeriGetir("yacht_locations", "WHERE durum=?", array(1), "ORDER BY ID ASC");
                            if($lokasyonlar != false) {
                                for($i=0; $i<count($lokasyonlar); $i++) {
                                    if($veri[0]["location_id"] == $lokasyonlar[$i]["ID"]) {
                                        echo '<option value="'.$lokasyonlar[$i]["ID"].'" selected>'.$lokasyonlar[$i]["baslik"].' ('.$lokasyonlar[$i]["sehir"].')</option>';
                                    } else {
                                        echo '<option value="'.$lokasyonlar[$i]["ID"].'">'.$lokasyonlar[$i]["baslik"].' ('.$lokasyonlar[$i]["sehir"].')</option>';
                                    }
                                }
                            }
                          ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Müsaitlik Durumu</label>
                          <select class="form-control" name="availability">
                            <option value="1" <?=($veri[0]["availability"]==1 ? 'selected' : '')?>>Kiralanabilir</option>
                            <option value="0" <?=($veri[0]["availability"]==0 ? 'selected' : '')?>>Kiralanmaz (Pasif)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Açıklama (Varsayılan Dil)</label>
                          <textarea class="textarea" placeholder="Yat hakkında detaylı bilgi" name="metin" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=stripslashes($veri[0]["metin"])?></textarea>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Ana Resim</label>
                          <input type="file" class="form-control" placeholder="Resim" name="resim">
                        </div>
                      </div>
                      <?php 
                      if(!empty($veri[0]["resim"])) {
                          echo '<div class="col-md-12">
                              <div class="form-group">
                                  <img src="'.SITE.'images/yachts/'.$veri[0]["resim"].'" style="height: 150px;">
                              </div>
                          </div>';
                      }
                      ?>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Başlık</label>
                          <input type="text" class="form-control" placeholder="SEO Başlık" name="seo_title" value="<?=$veri[0]["seo_title"]?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Açıklama</label>
                          <input type="text" class="form-control" placeholder="SEO Açıklama" name="seo_desc" value="<?=$veri[0]["seo_desc"]?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Durum</label><br />
                          <input type="checkbox" name="durum" <?=($veri[0]["durum"]==1 ? 'checked' : '')?> value="1" data-bootstrap-switch>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Yat Özellikleri</label>
                          <select class="form-control select2" multiple="multiple" name="features[]" style="width: 100%;">
                            <?php
                              $features = $VT->VeriGetir("yacht_features", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
                              if($features != false) {
                                  for($i=0; $i<count($features); $i++) {
                                      $selected = in_array($features[$i]["ID"], $secilenOzellikler) ? 'selected' : '';
                                      echo '<option value="'.$features[$i]["ID"].'" '.$selected.'>'.$features[$i]["baslik"].'</option>';
                                  }
                              }
                            ?>
                          </select>
                          <small class="text-muted">Birden fazla seçim için CTRL tuşuna basılı tutarak seçim yapabilirsiniz.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Türkçe Sekmesi -->
                  <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Başlık (TR)</label>
                          <input type="text" class="form-control" placeholder="Türkçe Başlık" name="lang[tr][baslik]" value="<?=(isset($dilVerileri['tr']) ? $dilVerileri['tr']['baslik'] : '')?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Açıklama (TR)</label>
                          <textarea class="textarea" placeholder="Türkçe açıklama" name="lang[tr][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=(isset($dilVerileri['tr']) ? stripslashes($dilVerileri['tr']['metin']) : '')?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Başlık (TR)</label>
                          <input type="text" class="form-control" placeholder="Türkçe SEO Başlık" name="lang[tr][seo_title]" value="<?=(isset($dilVerileri['tr']) ? $dilVerileri['tr']['seo_title'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Açıklama (TR)</label>
                          <input type="text" class="form-control" placeholder="Türkçe SEO Açıklama" name="lang[tr][seo_desc]" value="<?=(isset($dilVerileri['tr']) ? $dilVerileri['tr']['seo_desc'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                  </div>
                  
                  <!-- İngilizce Sekmesi -->
                  <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Başlık (EN)</label>
                          <input type="text" class="form-control" placeholder="İngilizce Başlık" name="lang[en][baslik]" value="<?=(isset($dilVerileri['en']) ? $dilVerileri['en']['baslik'] : '')?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Açıklama (EN)</label>
                          <textarea class="textarea" placeholder="İngilizce açıklama" name="lang[en][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=(isset($dilVerileri['en']) ? stripslashes($dilVerileri['en']['metin']) : '')?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Başlık (EN)</label>
                          <input type="text" class="form-control" placeholder="İngilizce SEO Başlık" name="lang[en][seo_title]" value="<?=(isset($dilVerileri['en']) ? $dilVerileri['en']['seo_title'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Açıklama (EN)</label>
                          <input type="text" class="form-control" placeholder="İngilizce SEO Açıklama" name="lang[en][seo_desc]" value="<?=(isset($dilVerileri['en']) ? $dilVerileri['en']['seo_desc'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                  </div>
                  
                  <!-- Almanca Sekmesi -->
                  <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Başlık (DE)</label>
                          <input type="text" class="form-control" placeholder="Almanca Başlık" name="lang[de][baslik]" value="<?=(isset($dilVerileri['de']) ? $dilVerileri['de']['baslik'] : '')?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Açıklama (DE)</label>
                          <textarea class="textarea" placeholder="Almanca açıklama" name="lang[de][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=(isset($dilVerileri['de']) ? stripslashes($dilVerileri['de']['metin']) : '')?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Başlık (DE)</label>
                          <input type="text" class="form-control" placeholder="Almanca SEO Başlık" name="lang[de][seo_title]" value="<?=(isset($dilVerileri['de']) ? $dilVerileri['de']['seo_title'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Açıklama (DE)</label>
                          <input type="text" class="form-control" placeholder="Almanca SEO Açıklama" name="lang[de][seo_desc]" value="<?=(isset($dilVerileri['de']) ? $dilVerileri['de']['seo_desc'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                  </div>
                  
                  <!-- Rusça Sekmesi -->
                  <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Başlık (RU)</label>
                          <input type="text" class="form-control" placeholder="Rusça Başlık" name="lang[ru][baslik]" value="<?=(isset($dilVerileri['ru']) ? $dilVerileri['ru']['baslik'] : '')?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Açıklama (RU)</label>
                          <textarea class="textarea" placeholder="Rusça açıklama" name="lang[ru][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?=(isset($dilVerileri['ru']) ? stripslashes($dilVerileri['ru']['metin']) : '')?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Başlık (RU)</label>
                          <input type="text" class="form-control" placeholder="Rusça SEO Başlık" name="lang[ru][seo_title]" value="<?=(isset($dilVerileri['ru']) ? $dilVerileri['ru']['seo_title'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>SEO Açıklama (RU)</label>
                          <input type="text" class="form-control" placeholder="Rusça SEO Açıklama" name="lang[ru][seo_desc]" value="<?=(isset($dilVerileri['ru']) ? $dilVerileri['ru']['seo_desc'] : '')?>">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
              
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
          </div>
        </div>
       </form>
       
      </div>
    </section>
  </div>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').summernote();
    
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
  });
</script> 

<!-- SweetAlert2 kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bildirim çıktısı -->
<?php echo $bildirim; ?>

<?php
    } else {
        echo '<meta http-equiv="refresh" content="0;url='.SITE.'yachts">';
    }
} else {
    echo '<meta http-equiv="refresh" content="0;url='.SITE.'yachts">';
}
?> 
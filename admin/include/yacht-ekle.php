<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Include language helper if not already included
if (!function_exists('lang')) {
    require_once dirname(dirname(__DIR__)) . '/helpers/language_helper.php';
}

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

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
        $motor_model = $VT->filter($_POST["motor_model"]);
        
        if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
        if(!empty($_POST["availability"])) { $availability = 1; } else { $availability = 2; }
        
        $seflink = $VT->seflink($baslik);
        
        // Resim Yükleme İşlemi
        if(!empty($_FILES["resim"]["name"])) {
            $yukle = $VT->upload("resim", "../images/yachts/");
            if($yukle != false) {
                $ekle = $VT->SorguCalistir("INSERT INTO yachts", "SET baslik=?, type_id=?, length_m=?, capacity=?, cabin_count=?, guest_capacity=?, location_id=?, crew=?, build_year=?, price_per_day=?, price_per_week=?, metin=?, resim=?, seo_title=?, seo_desc=?, durum=?, availability=?, seflink=?, motor_model=?, tarih=?", array($baslik, $type_id, $length_m, $capacity, $cabin_count, $guest_capacity, $location_id, $crew, $build_year, $price_per_day, $price_per_week, $metin, $yukle, $seo_title, $seo_desc, $durum, $availability, $seflink, $motor_model, date("Y-m-d")));
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Resim yükleme işleminiz başarısız.'
                    });
                </script>";
            }
        } else {
            $ekle = $VT->SorguCalistir("INSERT INTO yachts", "SET baslik=?, type_id=?, length_m=?, capacity=?, cabin_count=?, guest_capacity=?, location_id=?, crew=?, build_year=?, price_per_day=?, price_per_week=?, metin=?, seo_title=?, seo_desc=?, durum=?, availability=?, seflink=?, motor_model=?, tarih=?", array($baslik, $type_id, $length_m, $capacity, $cabin_count, $guest_capacity, $location_id, $crew, $build_year, $price_per_day, $price_per_week, $metin, $seo_title, $seo_desc, $durum, $availability, $seflink, $motor_model, date("Y-m-d")));
        }
        
        if($ekle != false) {
            $sonID = $VT->baglanti->lastInsertId();
            
            // Özellikleri kaydet
            if(!empty($_POST["features"])) {
                foreach($_POST["features"] as $feature) {
                    $VT->SorguCalistir("INSERT INTO yacht_features_pivot", "SET yacht_id=?, feature_id=?", array($sonID, $feature));
                }
            }
            
            // Çoklu dil kayıtları
            $dilSayisi = 0;
            $toplamDil = 0;
            $dilHatasi = false;
            $hataliDiller = '';
            
            if(!empty($_POST["lang"])) {
                foreach($_POST["lang"] as $lang => $value) {
                    if(!empty($value["baslik"])) {
                        $toplamDil++;
                        $dil_baslik = $VT->filter($value["baslik"]);
                        $dil_metin = $VT->filter($value["metin"], true);
                        $dil_seo_title = $VT->filter($value["seo_title"]);
                        $dil_seo_desc = $VT->filter($value["seo_desc"]);
                        
                        $dilEkle = $VT->SorguCalistir("INSERT INTO urunler_dil", "SET urun_id=?, lang=?, baslik=?, metin=?, seo_title=?, seo_desc=?", array($sonID, $lang, $dil_baslik, $dil_metin, $dil_seo_title, $dil_seo_desc));
                        
                        if($dilEkle != false) {
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
                            title: 'Yat eklendi, bazı dil kayıtları başarısız!',
                            html: 'Yat başarıyla eklendi.<br>Toplam ".$toplamDil." dil kaydından ".$dilSayisi." tanesi başarılı.<br>Başarısız diller: ".$hataliDiller."',
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
                            title: '".lang('admin.success')."',
                            html: 'Yat başarıyla eklendi.<br>Toplam ".$toplamDil." dil kaydının tamamı başarıyla eklendi.',
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
                        title: '".lang('admin.success')."',
                        text: 'Yat başarıyla eklendi.',
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
                    title: '".lang('admin.error')."',
                    text: 'Yat eklenirken bir sorun oluştu.'
                });
            </script>";
        }
    } else {
        $bildirim = "<script>
            Swal.fire({
                icon: 'error',
                title: '".lang('admin.error')."',
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
            <h1 class="m-0 text-dark"><?=lang('admin.add_yacht')?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>"><?=lang('nav.home')?></a></li>
              <li class="breadcrumb-item active"><?=lang('admin.add_yacht')?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
       <div class="row">
       <div class="col-md-12">
       <a href="<?=SITE?>yachts" class="btn btn-info" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="fas fa-bars"></i> <?=lang('admin.list')?></a> 
        <a href="<?=SITE?>yacht-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> <?=lang('admin.add')?></a>
       </div>
       </div>
       
       <div class="row">
         <div class="col-md-12">
           <div class="alert alert-info">
             <i class="fa fa-info-circle"></i> <?=lang('admin.required_fields')?>
           </div>
         </div>
       </div>
       
       <form action="#" method="post" enctype="multipart/form-data">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><?=lang('admin.yacht_info')?></h3>
            </div>
            <div class="card-body">
            
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="genel-tab" data-toggle="tab" href="#genel" role="tab" aria-controls="genel" aria-selected="true"><?=lang('admin.general_info')?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tr" role="tab" aria-controls="tr" aria-selected="false"><?=lang('admin.turkish')?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false"><?=lang('admin.english')?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="de-tab" data-toggle="tab" href="#de" role="tab" aria-controls="de" aria-selected="false"><?=lang('admin.german')?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="ru-tab" data-toggle="tab" href="#ru" role="tab" aria-controls="ru" aria-selected="false"><?=lang('admin.russian')?></a>
                  </li>
                </ul>
                
                <div class="tab-content mt-3">
                
                  <!-- Genel Bilgiler Sekmesi -->
                  <div class="tab-pane fade show active" id="genel" role="tabpanel" aria-labelledby="genel-tab">
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_name')?> *</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_name')?>" name="baslik" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_type')?> *</label>
                          <select class="form-control select2" style="width: 100%;" name="type_id" required>
                          <option value=""><?=lang('admin.select')?> <?=lang('admin.yacht_type')?></option>
                          <?php
                            $yatTipleri = $VT->VeriGetir("yacht_types", "WHERE durum=?", array(1), "ORDER BY ID ASC");
                            if($yatTipleri != false) {
                                for($i=0; $i<count($yatTipleri); $i++) {
                                    echo '<option value="'.$yatTipleri[$i]["ID"].'">'.$yatTipleri[$i]["baslik"].'</option>';
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
                          <label><?=lang('admin.yacht_length')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_length')?>" name="length_m" step="0.01">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_capacity')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_capacity')?>" name="capacity">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_crew')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_crew')?>" name="crew">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_cabins')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_cabins')?>" name="cabin_count">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_guest_capacity')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_guest_capacity')?>" name="guest_capacity">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_build_year')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_build_year')?>" name="build_year">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Motor Modeli/Tipi</label>
                          <input type="text" class="form-control" placeholder="Motor Modeli/Tipi" name="motor_model">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_price_day')?> (TL) *</label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_price_day')?>" name="price_per_day" step="0.01" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_price_week')?> (TL)</label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.yacht_price_week')?>" name="price_per_week" step="0.01">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_location')?> *</label>
                          <select class="form-control select2" style="width: 100%;" name="location_id" required>
                          <option value=""><?=lang('admin.select')?> <?=lang('admin.yacht_location')?></option>
                          <?php
                            $lokasyonlar = $VT->VeriGetir("yacht_locations", "WHERE durum=?", array(1), "ORDER BY ID ASC");
                            if($lokasyonlar != false) {
                                for($i=0; $i<count($lokasyonlar); $i++) {
                                    echo '<option value="'.$lokasyonlar[$i]["ID"].'">'.$lokasyonlar[$i]["baslik"].' ('.$lokasyonlar[$i]["sehir"].')</option>';
                                }
                            }
                          ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_availability')?></label>
                          <select class="form-control" name="availability">
                            <option value="1" selected><?=lang('admin.yacht_available')?></option>
                            <option value="0"><?=lang('admin.yacht_unavailable')?></option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_description')?></label>
                          <textarea class="textarea" placeholder="<?=lang('admin.yacht_description')?>" name="metin" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_main_image')?></label>
                          <input type="file" class="form-control" placeholder="<?=lang('admin.yacht_main_image')?>" name="resim">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_title')?></label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_title')?>" name="seo_title">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_desc')?></label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_desc')?>" name="seo_desc">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <!-- Boş bırakıldı - grid dengeleme için -->
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.status')?></label><br />
                          <input type="checkbox" name="durum" value="1" checked data-bootstrap-switch>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_features')?></label>
                          <select class="form-control select2" multiple="multiple" name="features[]" style="width: 100%;">
                            <?php
                              $features = $VT->VeriGetir("yacht_features", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
                              if($features != false) {
                                  for($i=0; $i<count($features); $i++) {
                                      echo '<option value="'.$features[$i]["ID"].'">'.$features[$i]["baslik"].'</option>';
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
                          <label><?=lang('admin.yacht_name')?> (TR)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_name')?> (TR)" name="lang[tr][baslik]">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_description')?> (TR)</label>
                          <textarea class="textarea" placeholder="<?=lang('admin.yacht_description')?> (TR)" name="lang[tr][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_title')?> (TR)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_title')?> (TR)" name="lang[tr][seo_title]">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_desc')?> (TR)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_desc')?> (TR)" name="lang[tr][seo_desc]">
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
                          <label><?=lang('admin.yacht_name')?> (EN)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_name')?> (EN)" name="lang[en][baslik]">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_description')?> (EN)</label>
                          <textarea class="textarea" placeholder="<?=lang('admin.yacht_description')?> (EN)" name="lang[en][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_title')?> (EN)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_title')?> (EN)" name="lang[en][seo_title]">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_desc')?> (EN)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_desc')?> (EN)" name="lang[en][seo_desc]">
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
                          <label><?=lang('admin.yacht_name')?> (DE)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_name')?> (DE)" name="lang[de][baslik]">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_description')?> (DE)</label>
                          <textarea class="textarea" placeholder="<?=lang('admin.yacht_description')?> (DE)" name="lang[de][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_title')?> (DE)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_title')?> (DE)" name="lang[de][seo_title]">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_desc')?> (DE)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_desc')?> (DE)" name="lang[de][seo_desc]">
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
                          <label><?=lang('admin.yacht_name')?> (RU)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_name')?> (RU)" name="lang[ru][baslik]">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_description')?> (RU)</label>
                          <textarea class="textarea" placeholder="<?=lang('admin.yacht_description')?> (RU)" name="lang[ru][metin]" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_title')?> (RU)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_title')?> (RU)" name="lang[ru][seo_title]">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label><?=lang('admin.yacht_seo_desc')?> (RU)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.yacht_seo_desc')?> (RU)" name="lang[ru][seo_desc]">
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
              <button type="submit" class="btn btn-primary"><?=lang('admin.save')?></button>
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
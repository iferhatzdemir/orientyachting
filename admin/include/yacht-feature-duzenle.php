<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

// ID kontrolü
if(empty($_GET["id"])) {
    echo '<meta http-equiv="refresh" content="0;url='.SITE.'yacht-features">';
    exit;
}

$ID = $VT->filter($_GET["id"]);
$veri = $VT->VeriGetir("yacht_features", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);

if($veri == false) {
    echo '<meta http-equiv="refresh" content="0;url='.SITE.'yacht-features">';
    exit;
}

// Dil çevirilerini getir
$dilVerileri = array();
$dilKayitlari = $VT->VeriGetir("yacht_features_dil", "WHERE feature_id=?", array($ID), "ORDER BY ID ASC");

if($dilKayitlari != false) {
    foreach($dilKayitlari as $dilKayit) {
        $dilVerileri[$dilKayit["lang"]] = $dilKayit;
    }
}

// Güncelleme İşlemi
if($_POST) {
    if(!empty($_POST["baslik"])) {
        $baslik = $VT->filter($_POST["baslik"]);
        $sirano = $VT->filter($_POST["sirano"]);
        if(empty($sirano)) { $sirano = 0; }
        if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
        
        $guncelle = $VT->SorguCalistir("UPDATE yacht_features", "SET baslik=?, durum=?, sirano=? WHERE ID=?", array($baslik, $durum, $sirano, $ID));
        
        if($guncelle != false) {
            // Dil çevirilerini güncelle
            if(!empty($_POST["dil"])) {
                foreach($_POST["dil"] as $dil => $degerler) {
                    if(!empty($degerler["baslik"])) {
                        $dil_baslik = $VT->filter($degerler["baslik"]);
                        
                        // Mevcut çeviri var mı kontrol et
                        $dilKontrol = $VT->VeriGetir("yacht_features_dil", "WHERE feature_id=? AND lang=?", array($ID, $dil), "ORDER BY ID ASC", 1);
                        
                        if($dilKontrol != false) {
                            // Varsa güncelle
                            $VT->SorguCalistir("UPDATE yacht_features_dil", "SET baslik=? WHERE feature_id=? AND lang=?", array($dil_baslik, $ID, $dil));
                        } else {
                            // Yoksa ekle
                            $VT->SorguCalistir("INSERT INTO yacht_features_dil", "SET feature_id=?, lang=?, baslik=?", array($ID, $dil, $dil_baslik));
                        }
                    }
                }
            }
            
            $bildirim = "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Yat özelliği başarıyla güncellendi.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '".SITE."yacht-features';
                });
            </script>";
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Yat özelliği güncellenirken bir sorun oluştu.',
                    showConfirmButton: true
                });
            </script>";
        }
    } else {
        $bildirim = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Lütfen özellik adını belirtin.',
                showConfirmButton: true
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
            <h1 class="m-0 text-dark">Yat Özelliği Düzenle</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>yacht-features">Yat Özellikleri</a></li>
              <li class="breadcrumb-item active">Özellik Düzenle</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Özellik Bilgileri</h3>
              </div>
              <form role="form" action="#" method="post" enctype="multipart/form-data">
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
                      <div class="tab-pane fade show active" id="genel" role="tabpanel" aria-labelledby="genel-tab">
                        <div class="form-group">
                          <label>Özellik Adı</label>
                          <input type="text" class="form-control" placeholder="Özellik Adı" name="baslik" value="<?=$veri[0]["baslik"]?>" required>
                        </div>
                        <div class="form-group">
                          <label>Sıra No</label>
                          <input type="number" class="form-control" placeholder="Sıra No" name="sirano" value="<?=$veri[0]["sirano"]?>">
                        </div>
                        <div class="form-group">
                          <label>Durum</label><br>
                          <input type="checkbox" name="durum" value="1" data-bootstrap-switch <?=$veri[0]["durum"] == 1 ? "checked" : ""?>>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="form-group">
                          <label>Özellik Adı (Türkçe)</label>
                          <input type="text" class="form-control" placeholder="Özellik Adı (Türkçe)" name="dil[tr][baslik]" value="<?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="form-group">
                          <label>Özellik Adı (İngilizce)</label>
                          <input type="text" class="form-control" placeholder="Özellik Adı (İngilizce)" name="dil[en][baslik]" value="<?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="form-group">
                          <label>Özellik Adı (Almanca)</label>
                          <input type="text" class="form-control" placeholder="Özellik Adı (Almanca)" name="dil[de][baslik]" value="<?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="form-group">
                          <label>Özellik Adı (Rusça)</label>
                          <input type="text" class="form-control" placeholder="Özellik Adı (Rusça)" name="dil[ru][baslik]" value="<?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["baslik"] : ""?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Güncelle</button>
                  <a href="<?=SITE?>yacht-features" class="btn btn-secondary">İptal</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<script>
  $(function () {
    // Bootstrap Switch
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
  });
</script>

<!-- SweetAlert2 kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bildirim çıktısı -->
<?php echo $bildirim; ?> 
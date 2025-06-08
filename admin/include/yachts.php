<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Silme işlemi
if(isset($_GET["islem"]) && $_GET["islem"] == "sil") {
    $id = $VT->filter($_GET["id"]);
    $kontrol = $VT->VeriGetir("yachts", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
    if($kontrol != false) {
        $sil = $VT->SorguCalistir("DELETE FROM yachts", "WHERE ID=?", array($id));
        if($sil) {
            echo '<div class="alert alert-success">Yat başarıyla silindi.</div>';
        } else {
            echo '<div class="alert alert-danger">Yat silinirken bir sorun oluştu.</div>';
        }
    }
}

// Aktif dil kodu (örneğin: tr, en, de, ru)
$aktivDil = "tr"; // Sistem dilinize göre değiştirin

// Özellikleri dil çevirileriyle birlikte çekme
$features = $VT->VeriGetir("yacht_features 
                         LEFT JOIN yacht_features_dil ON yacht_features.ID = yacht_features_dil.feature_id AND yacht_features_dil.lang = ?", 
                         "WHERE yacht_features.durum=?", 
                         array($aktivDil, 1), 
                         "ORDER BY yacht_features.sirano ASC");

// Bu kodu tamamen kaldıralım çünkü bu sayfada özellikleri göstermeye gerek yok
// Yat listesi sayfasında tekil özellikleri göstermeye gerek yok
// Eğer bu kodu kullanacaksak aşağıdaki gibi düzeltmeliyiz:
/*
// Özellikleri onay kutuları olarak gösterme
if($features != false) {  
    echo '<div class="row">';
    foreach($features as $feature) {
        $featureBaslik = !empty($feature["baslik"]) && isset($feature["lang"]) ? $feature["baslik"] : $feature["baslik"];
        echo '<div class="col-md-3 mb-2">
                <div class="icheck-primary">
                    <input type="checkbox" id="feature'.$feature["ID"].'" name="features[]" value="'.$feature["ID"].'">
                    <label for="feature'.$feature["ID"].'">'.$featureBaslik.'</label>
                </div>
              </div>';
    }
    echo '</div>';
}
*/
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Yat Liste</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Yat Liste</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
      <div class="row">
      <div class="col-md-12">
       <a href="<?=SITE?>yacht-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ YAT EKLE</a>
       </div>
       </div>
       <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width:50px;">Sıra</th>
                  <th>Yat Bilgileri</th>
                  <th style="width:120px;">Fiyat/Gün</th>
                  <th style="width:100px;">Lokasyon</th>
                  <th style="width:50px;">Durum</th>
                  <th style="width:80px;">Tarih</th>
                  <th style="width:180px;">İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // VeriGetir metodunu kullanarak yat verilerini çekiyoruz
                $yatlar = $VT->VeriGetir("yachts", "", "", "ORDER BY ID DESC");
                
                if($yatlar != false) {
                    $sira = 0;
                    for($i=0; $i<count($yatlar); $i++) {
                        $sira++;
                        if($yatlar[$i]["durum"]==1){$aktifpasif=' checked="checked"';}else{$aktifpasif='';}
                        if($yatlar[$i]["availability"]==1){$availableaktifpasif=' checked="checked"';}else{$availableaktifpasif='';}
                        
                        // Lokasyon bilgisini ayrı bir sorgu ile çekiyoruz
                        $lokasyon = "Belirtilmemiş";
                        if(!empty($yatlar[$i]["location_id"])) {
                            $lokasyonBilgisi = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($yatlar[$i]["location_id"]), "ORDER BY ID ASC", 1);
                            if($lokasyonBilgisi != false) {
                                $lokasyon = $lokasyonBilgisi[0]["baslik"];
                            }
                        }
                ?>
                <tr>
                  <td><?=$sira?></td>
                  <td>
                    <?php
                    if(!empty($yatlar[$i]["resim"])) {
                    ?>
                    
                    <a href="<?=ANASITE?>images/yachts/<?=$yatlar[$i]["resim"]?>" data-fancybox="gallery" data-caption="<?=stripslashes($yatlar[$i]["baslik"])?>">
                      <img src="<?=ANASITE?>images/yachts/<?=$yatlar[$i]["resim"]?>" style="height: 60px; width: auto; margin-right: 8px; float: left;" alt="<?=$yatlar[$i]["baslik"]?>">
                    </a>
                    <?php
                    }
                    ?>
                    <strong><?=stripslashes($yatlar[$i]["baslik"])?></strong><br>
                    <p><?=$yatlar[$i]["id"]?></p>
                    
                    <small><?=$yatlar[$i]["length_m"]?> m / <?=$yatlar[$i]["capacity"]?> kişi / <?=$yatlar[$i]["build_year"]?> model</small>
                  </td>
                  <td><?=number_format($yatlar[$i]["price_per_day"], 2, ',', '.')?> TL</td>
                  <td><?=$lokasyon?></td>
                  <td>
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" class="custom-control-input aktifpasif<?=$yatlar[$i]["ID"]?>" id="customSwitch3<?=$yatlar[$i]["ID"]?>"<?=$aktifpasif?> value="<?=$yatlar[$i]["ID"]?>" onclick="aktifpasif(<?=$yatlar[$i]["ID"]?>,'yachts');">
                        <label class="custom-control-label" for="customSwitch3<?=$yatlar[$i]["ID"]?>"></label>
                    </div>
                  </td>
                  <td><?=date("d.m.Y", strtotime($yatlar[$i]["tarih"]))?></td>
                  <td>
                    <a href="<?=SITE?>yacht-duzenle/<?=$yatlar[$i]["ID"]?>" class="btn btn-warning btn-sm" style="margin-bottom: 5px;"><i class="fa fa-edit"></i> Düzenle</a>
                    <a href="<?=SITE?>resimler/yachts/<?=$yatlar[$i]["ID"]?>" class="btn btn-primary btn-sm" style="margin-bottom: 5px;"><i class="fa fa-images"></i> Resimler</a>
                    <a href="<?=SITE?>yacht-sil/<?=$yatlar[$i]["ID"]?>" class="btn btn-danger btn-sm silmeAlani"><i class="fa fa-trash"></i> Sil</a>
                  </td>
                </tr>
                <?php
                    }
                } else {
                    // Veri yoksa mesaj göster
                    echo '<tr><td colspan="7"><div class="alert alert-info">Henüz yat eklenmemiştir. Yat eklemek için "YENİ YAT EKLE" butonunu kullanabilirsiniz.</div></td></tr>';
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sıra</th>
                  <th>Yat Bilgileri</th>
                  <th>Fiyat/Gün</th>
                  <th>Lokasyon</th>
                  <th>Durum</th>
                  <th>Tarih</th>
                  <th>İşlem</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
       
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      }
    });
    
    // Silme onayı için
    $(".silmeAlani").click(function(){
      var silSayfaURL = $(this).attr("href");
      
      swal({
        title: "Emin misiniz?",
        text: "Bu yatı silmek istediğinize emin misiniz?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.location.href = silSayfaURL;
        } else {
          swal("Silme işlemi iptal edildi.");
        }
      });
      
      return false;
    });
  });
  
  function aktifpasif(ID,tablo){
    var durum = 0;
    if($(".aktifpasif"+ID).is(':checked')) { durum = 1; }
    
    $.ajax({
      method: "POST",
      url: "<?=SITE?>ajax.php",
      data: { "tablo":tablo, "ID":ID, "durum":durum, "islem":"aktifpasif" }
    })
    .done(function( msg ) {
      if(msg=="OK"){
        if(durum==1){
          swal("","Yat Aktif Edildi","success");
        }
        else{
          swal("","Yat Pasif Edildi","success");
        }
      }
      else{
        swal("","İşlem Tamamlanamadı","error");
      }
    });
  }
</script> 

<!-- Fancybox CSS ve JS dosyaları -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
  // Fancybox başlatma - jQuery versiyonu
  $(document).ready(function() {
    $("[data-fancybox]").fancybox({
      buttons: [
        "zoom",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
      ],
      animationEffect: "zoom",
      transitionEffect: "fade",
      loop: true,
      protect: true
    });
  });
</script> 
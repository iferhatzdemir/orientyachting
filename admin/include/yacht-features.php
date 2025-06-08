<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Include language helper if not already included
if (!function_exists('lang')) {
    require_once dirname(dirname(__DIR__)) . '/helpers/language_helper.php';
}

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

// Durum değiştirme işlemi
if(isset($_GET["islem"]) && $_GET["islem"] == "durum") {
    $id = $VT->filter($_GET["id"]);
    $durum = $VT->filter($_GET["durum"]);
    $guncelle = $VT->SorguCalistir("UPDATE yacht_features", "SET durum=? WHERE ID=?", array($durum, $id));
    if($guncelle) {
        $bildirim = "<script>
            Swal.fire({
                icon: 'success',
                title: '".lang('admin.success')."',
                text: 'Durum başarıyla güncellendi.',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    } else {
        $bildirim = "<script>
            Swal.fire({
                icon: 'error',
                title: '".lang('admin.error')."',
                text: 'Durum güncellenirken bir sorun oluştu.',
                showConfirmButton: true
            });
        </script>";
    }
}

// Ekleme ve Güncelleme İşlemleri
if($_POST) {
    // Yeni özellik ekleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "ekle") {
        if(!empty($_POST["baslik"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            $ekle = $VT->SorguCalistir("INSERT INTO yacht_features", "SET baslik=?, durum=?, sirano=?", array($baslik, $durum, $sirano));
            
            if($ekle != false) {
                $sonID = $VT->baglanti->lastInsertId();
                
                // Dil çevirilerini ekle
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            
                            $VT->SorguCalistir("INSERT INTO yacht_features_dil", "SET feature_id=?, lang=?, baslik=?", array($sonID, $dil, $dil_baslik));
                        }
                    }
                }
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '".lang('admin.success')."',
                        text: 'Yat özelliği başarıyla eklendi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
                
                // Sayfayı yenile
                echo '<meta http-equiv="refresh" content="1;url='.SITE.'yacht-features">';
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Yat özelliği eklenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".lang('admin.error')."',
                    text: 'Lütfen özellik adını belirtin.',
                    showConfirmButton: true
                });
            </script>";
        }
    }
    
    // Özellik güncelleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "guncelle") {
        if(!empty($_POST["baslik"]) && !empty($_POST["ID"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $ID = $VT->filter($_POST["ID"]);
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
                        title: '".lang('admin.success')."',
                        text: 'Yat özelliği başarıyla güncellendi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
                
                // Sayfayı yenile
                echo '<meta http-equiv="refresh" content="1;url='.SITE.'yacht-features">';
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Yat özelliği güncellenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".lang('admin.error')."',
                    text: 'Lütfen özellik adını ve ID değerini belirtin.',
                    showConfirmButton: true
                });
            </script>";
        }
    }
}

// Düzenleme için veri getirme
$duzenle = false;
if(isset($_GET["islem"]) && $_GET["islem"] == "duzenle" && isset($_GET["id"])) {
    $id = $VT->filter($_GET["id"]);
    $duzenle = $VT->VeriGetir("yacht_features", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
    
    if($duzenle != false) {
        // Dil çevirilerini getir
        $dilVerileri = array();
        $dilKayitlari = $VT->VeriGetir("yacht_features_dil", "WHERE feature_id=?", array($id), "ORDER BY ID ASC");
        
        if($dilKayitlari != false) {
            foreach($dilKayitlari as $dilKayit) {
                $dilVerileri[$dilKayit["lang"]] = $dilKayit;
            }
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=lang('admin.feature_management')?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>"><?=lang('nav.home')?></a></li>
              <li class="breadcrumb-item active"><?=lang('admin.yacht_features')?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><?=$duzenle ? lang('admin.edit_feature') : lang('admin.add_feature')?></h3>
              </div>
              <form role="form" action="#" method="post" enctype="multipart/form-data">
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
                      <div class="tab-pane fade show active" id="genel" role="tabpanel" aria-labelledby="genel-tab">
                        <div class="form-group">
                          <label><?=lang('admin.feature_name')?></label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.feature_name')?>" name="baslik" value="<?=$duzenle ? $duzenle[0]["baslik"] : ""?>" required>
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.order')?></label>
                          <input type="number" class="form-control" placeholder="<?=lang('admin.order')?>" name="sirano" value="<?=$duzenle ? $duzenle[0]["sirano"] : "0"?>">
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.status')?></label><br>
                          <input type="checkbox" name="durum" value="1" data-bootstrap-switch <?=$duzenle && $duzenle[0]["durum"] == 1 ? "checked" : ""?>>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="form-group">
                          <label><?=lang('admin.feature_name')?> (<?=lang('admin.turkish')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.feature_name')?> (<?=lang('admin.turkish')?>)" name="dil[tr][baslik]" value="<?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="form-group">
                          <label><?=lang('admin.feature_name')?> (<?=lang('admin.english')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.feature_name')?> (<?=lang('admin.english')?>)" name="dil[en][baslik]" value="<?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="form-group">
                          <label><?=lang('admin.feature_name')?> (<?=lang('admin.german')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.feature_name')?> (<?=lang('admin.german')?>)" name="dil[de][baslik]" value="<?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["baslik"] : ""?>">
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="form-group">
                          <label><?=lang('admin.feature_name')?> (<?=lang('admin.russian')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.feature_name')?> (<?=lang('admin.russian')?>)" name="dil[ru][baslik]" value="<?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["baslik"] : ""?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <?php if($duzenle) { ?>
                    <input type="hidden" name="islem" value="guncelle">
                    <input type="hidden" name="ID" value="<?=$duzenle[0]["ID"]?>">
                    <button type="submit" class="btn btn-primary"><?=lang('admin.update')?></button>
                  <?php } else { ?>
                    <input type="hidden" name="islem" value="ekle">
                    <button type="submit" class="btn btn-primary"><?=lang('admin.add')?></button>
                  <?php } ?>
                </div>
              </form>
            </div>
          </div>
          
          <div class="col-md-7">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><?=lang('admin.feature_list')?></h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">#</th>
                      <th><?=lang('admin.feature_name')?></th>
                      <th style="width:50px;"><?=lang('admin.order')?></th>
                      <th style="width:80px;"><?=lang('admin.status')?></th>
                      <th style="width:120px;"><?=lang('admin.actions')?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ozellikler = $VT->VeriGetir("yacht_features", "", "", "ORDER BY sirano ASC");
                    if($ozellikler != false) {
                        $sira = 0;
                        for($i=0; $i<count($ozellikler); $i++) {
                            $sira++;
                            if($ozellikler[$i]["durum"] == 1) {
                                $durumRenk = "success";
                                $durumText = lang('admin.active');
                                $durumDeger = 2;
                            } else {
                                $durumRenk = "danger";
                                $durumText = lang('admin.inactive');
                                $durumDeger = 1;
                            }
                    ?>
                    <tr>
                      <td><?=$sira?></td>
                      <td><?=stripslashes($ozellikler[$i]["baslik"])?></td>
                      <td><?=$ozellikler[$i]["sirano"]?></td>
                      <td>
                        <button type="button" class="btn btn-<?=$durumRenk?> btn-sm durum-degistir" data-id="<?=$ozellikler[$i]["ID"]?>" data-durum="<?=$durumDeger?>"><?=$durumText?></button>
                      </td>
                      <td>
                        <a href="<?=SITE?>yacht-features/duzenle/<?=$ozellikler[$i]["ID"]?>" class="btn btn-warning btn-sm"><i class="fa fa-pen"></i></a>
                        <a href="<?=SITE?>yacht-features/sil/<?=$ozellikler[$i]["ID"]?>" class="btn btn-danger btn-sm silmeAlani"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">'.lang('admin.yacht_no_features').'</td></tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
    
    // WYSIHTML5 editörü
    $('.textarea').summernote();
    
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

<!-- Durum değiştirme için AJAX ve SweetAlert2 -->
<script>
$(document).ready(function() {
  // Durum değiştirme butonu tıklandığında
  $('.durum-degistir').on('click', function() {
    var button = $(this);
    var id = button.data('id');
    var durum = button.data('durum');
    
    // SweetAlert2 ile onay al
    Swal.fire({
      title: '<?=lang('admin.confirm_delete')?>',
      text: 'Durum değişikliği yapmak istediğinize emin misiniz?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?=lang('admin.yes_delete')?>',
      cancelButtonText: '<?=lang('admin.cancel')?>'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile durum değiştirme
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            'islem': 'durumDegistir',
            'tablo': 'yacht_features',
            'ID': id,
            'durum': durum
          },
          success: function(response) {
            if(response == "TAMAM") {
              // Başarılı ise butonun durumunu güncelle
              if(durum == 1) {
                button.removeClass('btn-danger').addClass('btn-success');
                button.text('<?=lang('admin.active')?>');
                button.data('durum', 2);
              } else {
                button.removeClass('btn-success').addClass('btn-danger');
                button.text('<?=lang('admin.inactive')?>');
                button.data('durum', 1);
              }
              
              // Başarı bildirimi göster
              Swal.fire({
                icon: 'success',
                title: '<?=lang('admin.success')?>',
                text: 'Durum başarıyla güncellendi.',
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: '<?=lang('admin.error')?>',
                text: 'Durum güncellenirken bir sorun oluştu.',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: '<?=lang('admin.error')?>',
              text: 'Sunucu ile bağlantı kurulurken bir sorun oluştu.',
              showConfirmButton: true
            });
          }
        });
      }
    });
  });
  
  // AJAX ile silme işlemi
  $('.silmeAlani').on('click', function(e) {
    e.preventDefault();
    var deleteButton = $(this);
    var deleteURL = deleteButton.attr('href');
    var featureID = deleteURL.split('/').pop();
    var featureName = deleteButton.closest('tr').find('td:eq(1)').text();
    
    Swal.fire({
      title: '<?=lang('admin.confirm_delete')?>',
      text: '"' + featureName + '" özelliğini silmek istediğinize emin misiniz?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: '<?=lang('admin.yes_delete')?>',
      cancelButtonText: '<?=lang('admin.cancel')?>'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile silme işlemi
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            'islem': 'ozellikSil',
            'ID': featureID
          },
          success: function(response) {
            if(response == "TAMAM") {
              // Başarılı ise satırı tablodan kaldır
              deleteButton.closest('tr').fadeOut('slow', function() {
                $(this).remove();
                
                // Tabloda hiç satır kalmadıysa mesaj göster
                if ($('#example1 tbody tr').length === 0) {
                  $('#example1 tbody').append('<tr><td colspan="5" class="text-center"><?=lang('admin.yacht_no_features')?></td></tr>');
                }
              });
              
              // Başarı bildirimi göster ve ardından yönlendir
              Swal.fire({
                icon: 'success',
                title: '<?=lang('admin.success')?>',
                text: 'Özellik başarıyla silindi.',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location.href = '<?=SITE?>yacht-features';
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: '<?=lang('admin.error')?>',
                text: 'Özellik silinirken bir sorun oluştu.',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: '<?=lang('admin.error')?>',
              text: 'Sunucu ile bağlantı kurulurken bir sorun oluştu.',
              showConfirmButton: true
            });
          }
        });
      }
    });
    
    return false;
  });
});
</script> 
<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Include language helper if not already included
if (!function_exists('lang')) {
    require_once dirname(dirname(__DIR__)) . '/helpers/language_helper.php';
}

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

// Form işlemi takibi için
$islemDurumu = '';

// Durum değiştirme işlemi
if(isset($_GET["islem"]) && $_GET["islem"] == "durum") {
    $id = $VT->filter($_GET["id"]);
    $durum = $VT->filter($_GET["durum"]);
    $guncelle = $VT->SorguCalistir("UPDATE yacht_types", "SET durum=? WHERE ID=?", array($durum, $id));
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
    // Yeni tip ekleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "ekle") {
        if(!empty($_POST["baslik"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $seflink = $VT->seflink($baslik);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            $ekle = $VT->SorguCalistir("INSERT INTO yacht_types", "SET baslik=?, seflink=?, durum=?, sirano=?", array($baslik, $seflink, $durum, $sirano));
            
            if($ekle != false) {
                $sonID = $VT->baglanti->lastInsertId();
                
                // Dil çevirilerini ekle
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            $dil_aciklama = $VT->filter($degerler["aciklama"]);
                            
                            $VT->SorguCalistir("INSERT INTO yacht_types_dil", "SET type_id=?, lang=?, baslik=?, aciklama=?", array($sonID, $dil, $dil_baslik, $dil_aciklama));
                        }
                    }
                }
                
                // İşlem durumunu güncelle
                $islemDurumu = 'ekleme_basarili';
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '".lang('admin.success')."',
                        text: 'Yat tipi başarıyla eklendi.',
                        showConfirmButton: true,
                        confirmButtonText: 'Tamam',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '".SITE."yacht-types';
                        }
                    });
                </script>";
                
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Yat tipi eklenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
                
                // İşlem durumunu güncelle
                $islemDurumu = 'ekleme_hata';
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".lang('admin.error')."',
                    text: 'Lütfen tip adını belirtin.',
                    showConfirmButton: true
                });
            </script>";
            
            // İşlem durumunu güncelle
            $islemDurumu = 'ekleme_eksik';
        }
    }
    
    // Yat tipi güncelleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "guncelle") {
        if(!empty($_POST["baslik"]) && !empty($_POST["ID"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $ID = $VT->filter($_POST["ID"]);
            $seflink = $VT->seflink($baslik);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            $guncelle = $VT->SorguCalistir("UPDATE yacht_types", "SET baslik=?, seflink=?, durum=?, sirano=? WHERE ID=?", array($baslik, $seflink, $durum, $sirano, $ID));
            
            if($guncelle != false) {
                // Dil çevirilerini güncelle
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            $dil_aciklama = $VT->filter($degerler["aciklama"]);
                            
                            // Mevcut çeviri var mı kontrol et
                            $dilKontrol = $VT->VeriGetir("yacht_types_dil", "WHERE type_id=? AND lang=?", array($ID, $dil), "ORDER BY ID ASC", 1);
                            
                            if($dilKontrol != false) {
                                // Varsa güncelle
                                $VT->SorguCalistir("UPDATE yacht_types_dil", "SET baslik=?, aciklama=? WHERE type_id=? AND lang=?", array($dil_baslik, $dil_aciklama, $ID, $dil));
                            } else {
                                // Yoksa ekle
                                $VT->SorguCalistir("INSERT INTO yacht_types_dil", "SET type_id=?, lang=?, baslik=?, aciklama=?", array($ID, $dil, $dil_baslik, $dil_aciklama));
                            }
                        }
                    }
                }
                
                // İşlem durumunu güncelle
                $islemDurumu = 'guncelleme_basarili';
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '".lang('admin.success')."',
                        text: 'Yat tipi başarıyla güncellendi.',
                        showConfirmButton: true,
                        confirmButtonText: 'Tamam',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '".SITE."yacht-types';
                        }
                    });
                </script>";
                
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Yat tipi güncellenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
                
                // İşlem durumunu güncelle
                $islemDurumu = 'guncelleme_hata';
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".lang('admin.error')."',
                    text: 'Lütfen tip adını ve ID değerini belirtin.',
                    showConfirmButton: true
                });
            </script>";
            
            // İşlem durumunu güncelle
            $islemDurumu = 'guncelleme_eksik';
        }
    }
}

// Düzenleme için veri getirme
$duzenle = false;
if(isset($_GET["islem"]) && $_GET["islem"] == "duzenle" && isset($_GET["id"])) {
    $id = $VT->filter($_GET["id"]);
    $duzenle = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
    
    if($duzenle != false) {
        // Dil çevirilerini getir
        $dilVerileri = array();
        $dilKayitlari = $VT->VeriGetir("yacht_types_dil", "WHERE type_id=?", array($id), "ORDER BY ID ASC");
        
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
            <h1 class="m-0 text-dark"><?=lang('admin.type_management')?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>"><?=lang('nav.home')?></a></li>
              <li class="breadcrumb-item active"><?=lang('admin.yacht_types')?></li>
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
                <h3 class="card-title"><?=$duzenle ? lang('admin.edit_type') : lang('admin.add_type')?></h3>
              </div>
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <?php if($islemDurumu == 'ekleme_basarili'): ?>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Yat tipi başarıyla eklendi. Listeye yönlendiriliyorsunuz...
                  </div>
                  <?php elseif($islemDurumu == 'guncelleme_basarili'): ?>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Yat tipi başarıyla güncellendi. Listeye yönlendiriliyorsunuz...
                  </div>
                  <?php elseif($islemDurumu == 'ekleme_hata'): ?>
                  <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Yat tipi eklenirken bir hata oluştu. Lütfen tekrar deneyin.
                  </div>
                  <?php elseif($islemDurumu == 'guncelleme_hata'): ?>
                  <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Yat tipi güncellenirken bir hata oluştu. Lütfen tekrar deneyin.
                  </div>
                  <?php elseif($islemDurumu == 'ekleme_eksik' || $islemDurumu == 'guncelleme_eksik'): ?>
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Lütfen gerekli tüm alanları doldurun.
                  </div>
                  <?php endif; ?>
                  
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
                          <label><?=lang('admin.type_name')?></label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.type_name')?>" name="baslik" value="<?=$duzenle ? $duzenle[0]["baslik"] : ""?>" required>
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
                          <label><?=lang('admin.type_name')?> (<?=lang('admin.turkish')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.turkish')?>)" name="dil[tr][baslik]" value="<?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.type_description')?> (<?=lang('admin.turkish')?>)</label>
                          <textarea class="form-control" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.turkish')?>)" name="dil[tr][aciklama]" rows="3"><?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="form-group">
                          <label><?=lang('admin.type_name')?> (<?=lang('admin.english')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.english')?>)" name="dil[en][baslik]" value="<?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.type_description')?> (<?=lang('admin.english')?>)</label>
                          <textarea class="form-control" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.english')?>)" name="dil[en][aciklama]" rows="3"><?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="form-group">
                          <label><?=lang('admin.type_name')?> (<?=lang('admin.german')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.german')?>)" name="dil[de][baslik]" value="<?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.type_description')?> (<?=lang('admin.german')?>)</label>
                          <textarea class="form-control" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.german')?>)" name="dil[de][aciklama]" rows="3"><?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="form-group">
                          <label><?=lang('admin.type_name')?> (<?=lang('admin.russian')?>)</label>
                          <input type="text" class="form-control" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.russian')?>)" name="dil[ru][baslik]" value="<?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.type_description')?> (<?=lang('admin.russian')?>)</label>
                          <textarea class="form-control" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.russian')?>)" name="dil[ru][aciklama]" rows="3"><?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["aciklama"] : ""?></textarea>
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
                <h3 class="card-title"><?=lang('admin.type_list')?></h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">#</th>
                      <th><?=lang('admin.type_name')?></th>
                      <th style="width:50px;"><?=lang('admin.order')?></th>
                      <th style="width:80px;"><?=lang('admin.status')?></th>
                      <th style="width:120px;"><?=lang('admin.actions')?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $tipler = $VT->VeriGetir("yacht_types", "", "", "ORDER BY sirano ASC");
                    if($tipler != false) {
                        $sira = 0;
                        for($i=0; $i<count($tipler); $i++) {
                            $sira++;
                            if($tipler[$i]["durum"] == 1) {
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
                      <td><?=stripslashes($tipler[$i]["baslik"])?></td>
                      <td><?=$tipler[$i]["sirano"]?></td>
                      <td>
                        <button type="button" class="btn btn-<?=$durumRenk?> btn-sm durum-degistir" data-id="<?=$tipler[$i]["ID"]?>" data-durum="<?=$durumDeger?>"><?=$durumText?></button>
                      </td>
                      <td>
                        <a href="<?=SITE?>yacht-types/duzenle/<?=$tipler[$i]["ID"]?>" class="btn btn-warning btn-sm"><i class="fa fa-pen"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm silmeAlani" data-id="<?=$tipler[$i]["ID"]?>" data-name="<?=stripslashes($tipler[$i]["baslik"])?>"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">'.lang('admin.no_yacht_types').'</td></tr>';
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
    
    // Editor
    $('.textarea').summernote({
      height: 150,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link']]
      ]
    });
    
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

<!-- Durum değiştirme ve silme için AJAX ve SweetAlert2 -->
<script>
$(document).ready(function() {
  // Durum değiştirme butonu tıklandığında
  $('.durum-degistir').on('click', function() {
    var button = $(this);
    var id = button.data('id');
    var durum = button.data('durum');
    
    // SweetAlert2 ile onay al
    Swal.fire({
      title: '<?=lang('admin.confirm_status')?>',
      text: '<?=lang('admin.confirm_status_msg') ?? 'Durum değişikliği yapmak istediğinize emin misiniz?'?>',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?=lang('admin.yes')?>',
      cancelButtonText: '<?=lang('admin.cancel')?>'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile durum değiştirme
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            'islem': 'durumDegistir',
            'tablo': 'yacht_types',
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
                text: '<?=lang('admin.status_update_success')?>',
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: '<?=lang('admin.error')?>',
                text: '<?=lang('admin.status_update_error')?>',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: '<?=lang('admin.error')?>',
              text: '<?=lang('admin.connection_error')?>',
              showConfirmButton: true
            });
          }
        });
      }
    });
  });
  
  // Silme butonu işlemi
  $('.silmeAlani').on('click', function() {
    var deleteButton = $(this);
    var typeID = deleteButton.data('id');
    var typeName = deleteButton.data('name');
    
    Swal.fire({
      title: '<?=lang('admin.confirm_delete')?>',
      text: '<?=lang('admin.delete_type_confirm')?>' + ' "' + typeName + '"?',
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
            'islem': 'tipSil',
            'ID': typeID
          },
          success: function(response) {
            if(response == "TAMAM") {
              // Başarılı ise satırı tablodan kaldır
              deleteButton.closest('tr').fadeOut('slow', function() {
                $(this).remove();
                
                // Tabloda hiç satır kalmadıysa mesaj göster
                if ($('#example1 tbody tr').length === 0) {
                  $('#example1 tbody').append('<tr><td colspan="5" class="text-center"><?php echo lang("admin.no_yacht_types"); ?></td></tr>');
                }
              });
              
              // Başarı bildirimi göster
              Swal.fire({
                icon: 'success',
                title: '<?=lang('admin.success')?>',
                text: '<?=lang('admin.type_delete_success')?>',
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: '<?=lang('admin.error')?>',
                text: '<?=lang('admin.type_delete_error')?>',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: '<?=lang('admin.error')?>',
              text: '<?=lang('admin.connection_error')?>',
              showConfirmButton: true
            });
          }
        });
      }
    });
  });
});
</script> 
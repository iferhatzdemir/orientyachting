<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Include language helper if not already included
if (!function_exists('lang')) {
    require_once dirname(dirname(__DIR__)) . '/helpers/language_helper.php';
}

// Initialize bildirim variable
$bildirim = '';
$basarili = false; // İşlem başarı durumunu izlemek için

// ID kontrolü
if(empty($_GET["ID"])) {
    header("Location: ".SITE."yacht-types");
    exit;
}

$ID = $VT->filter($_GET["ID"]);
$veri = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);

if($veri == false) {
    header("Location: ".SITE."yacht-types");
    exit;
}

// Dil çevirilerini getir
$dilVerileri = array();
$dilKayitlari = $VT->VeriGetir("yacht_types_dil", "WHERE type_id=?", array($ID), "ORDER BY ID ASC");

if($dilKayitlari != false) {
    foreach($dilKayitlari as $dilKayit) {
        $dilVerileri[$dilKayit["lang"]] = $dilKayit;
    }
}

// Güncelleme İşlemi
if($_POST) {
    if(!empty($_POST["baslik"])) {
        try {
            $baslik = $VT->filter($_POST["baslik"]);
            $seflink = $VT->seflink($baslik);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            // Begin transaction for data integrity
            $VT->beginTransaction();
            
            $guncelle = $VT->SorguCalistir("UPDATE yacht_types", "SET baslik=?, seflink=?, durum=?, sirano=? WHERE ID=?", array($baslik, $seflink, $durum, $sirano, $ID));
            
            if($guncelle !== false) {
                // Dil çevirilerini güncelle - tüm işlemleri tek seferde topla
                $dilGuncelleBasarili = true;
                
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            $dil_aciklama = $VT->filter($degerler["aciklama"]);
                            
                            // Mevcut çeviri var mı kontrol et
                            $dilKontrol = $VT->VeriGetir("yacht_types_dil", "WHERE type_id=? AND lang=?", array($ID, $dil), "ORDER BY ID ASC", 1);
                            
                            $dilSonuc = null;
                            if($dilKontrol != false) {
                                // Varsa güncelle
                                $dilSonuc = $VT->SorguCalistir("UPDATE yacht_types_dil", "SET baslik=?, aciklama=? WHERE type_id=? AND lang=?", array($dil_baslik, $dil_aciklama, $ID, $dil));
                            } else {
                                // Yoksa ekle
                                $dilSonuc = $VT->SorguCalistir("INSERT INTO yacht_types_dil", "SET type_id=?, lang=?, baslik=?, aciklama=?", array($ID, $dil, $dil_baslik, $dil_aciklama));
                            }
                            
                            if($dilSonuc === false) {
                                $dilGuncelleBasarili = false;
                                break;
                            }
                        }
                    }
                }
                
                if($dilGuncelleBasarili) {
                    // Commit changes if all operations succeeded
                    $VT->commit();
                    $basarili = true; // İşlem başarılı olarak işaretle
                    
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
                    // Rollback changes if any operation failed
                    $VT->rollBack();
                    
                    $bildirim = "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '".lang('admin.error')."',
                            text: 'Yat tipi dil çevirileri güncellenirken bir sorun oluştu.',
                            showConfirmButton: true
                        });
                    </script>";
                }
            } else {
                // Rollback changes if main update failed
                $VT->rollBack();
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '".lang('admin.error')."',
                        text: 'Yat tipi güncellenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
            }
        } catch (Exception $e) {
            // Ensure transaction is rolled back in case of any exception
            $VT->rollBack();
            
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".lang('admin.error')."',
                    text: 'İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                    showConfirmButton: true
                });
            </script>";
        }
    } else {
        $bildirim = "<script>
            Swal.fire({
                icon: 'warning',
                title: '".lang('admin.warning')."',
                text: 'Lütfen yat tipi adını belirtin.',
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
            <h1 class="m-0 text-dark"><?=lang('admin.edit_type')?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>"><?=lang('nav.home')?></a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>yacht-types"><?=lang('admin.yacht_types')?></a></li>
              <li class="breadcrumb-item active"><?=lang('admin.edit_type')?></li>
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
                <h3 class="card-title"><?=lang('admin.type_details')?></h3>
              </div>
              <form role="form" action="" method="post" enctype="multipart/form-data" id="yacht-type-form">
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
                          <label for="baslik"><?=lang('admin.type_name')?> <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="baslik" placeholder="<?=lang('admin.type_name')?>" name="baslik" value="<?=$veri[0]["baslik"]?>" required>
                          <small class="form-text text-muted"><?=lang('admin.type_name_help')?></small>
                        </div>
                        <div class="form-group">
                          <label for="sirano"><?=lang('admin.order')?></label>
                          <input type="number" class="form-control" id="sirano" placeholder="<?=lang('admin.order')?>" name="sirano" value="<?=$veri[0]["sirano"]?>">
                          <small class="form-text text-muted"><?=lang('admin.order_help')?></small>
                        </div>
                        <div class="form-group">
                          <label><?=lang('admin.status')?></label><br>
                          <input type="checkbox" name="durum" id="durum" value="1" data-bootstrap-switch data-off-color="danger" data-on-color="success" <?=$veri[0]["durum"] == 1 ? "checked" : ""?>>
                          <small class="form-text text-muted ml-3"><?=lang('admin.status_help')?></small>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="form-group">
                          <label for="dil_tr_baslik"><?=lang('admin.type_name')?> (<?=lang('admin.turkish')?>)</label>
                          <input type="text" class="form-control" id="dil_tr_baslik" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.turkish')?>)" name="dil[tr][baslik]" value="<?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label for="dil_tr_aciklama"><?=lang('admin.type_description')?> (<?=lang('admin.turkish')?>)</label>
                          <textarea class="form-control" id="dil_tr_aciklama" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.turkish')?>)" name="dil[tr][aciklama]" rows="3"><?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="form-group">
                          <label for="dil_en_baslik"><?=lang('admin.type_name')?> (<?=lang('admin.english')?>)</label>
                          <input type="text" class="form-control" id="dil_en_baslik" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.english')?>)" name="dil[en][baslik]" value="<?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label for="dil_en_aciklama"><?=lang('admin.type_description')?> (<?=lang('admin.english')?>)</label>
                          <textarea class="form-control" id="dil_en_aciklama" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.english')?>)" name="dil[en][aciklama]" rows="3"><?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="form-group">
                          <label for="dil_de_baslik"><?=lang('admin.type_name')?> (<?=lang('admin.german')?>)</label>
                          <input type="text" class="form-control" id="dil_de_baslik" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.german')?>)" name="dil[de][baslik]" value="<?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label for="dil_de_aciklama"><?=lang('admin.type_description')?> (<?=lang('admin.german')?>)</label>
                          <textarea class="form-control" id="dil_de_aciklama" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.german')?>)" name="dil[de][aciklama]" rows="3"><?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="form-group">
                          <label for="dil_ru_baslik"><?=lang('admin.type_name')?> (<?=lang('admin.russian')?>)</label>
                          <input type="text" class="form-control" id="dil_ru_baslik" placeholder="<?=lang('admin.type_name')?> (<?=lang('admin.russian')?>)" name="dil[ru][baslik]" value="<?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label for="dil_ru_aciklama"><?=lang('admin.type_description')?> (<?=lang('admin.russian')?>)</label>
                          <textarea class="form-control" id="dil_ru_aciklama" placeholder="<?=lang('admin.type_description')?> (<?=lang('admin.russian')?>)" name="dil[ru][aciklama]" rows="3"><?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary"><?=lang('admin.update')?></button>
                  <a href="<?=SITE?>yacht-types" class="btn btn-secondary"><?=lang('admin.cancel')?></a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<!-- SweetAlert2 kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Client-side form validation and UI enhancements -->
<script>
  $(function () {
    // Initialize form validation
    $('#yacht-type-form').validate({
      rules: {
        baslik: {
          required: true,
          minlength: 2
        }
      },
      messages: {
        baslik: {
          required: "<?=lang('admin.type_name_required')?>",
          minlength: "<?=lang('admin.type_name_min_length')?>"
        }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
    
    // Bootstrap Switch initialization
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
    
    // Add smooth tab transition
    $('.nav-tabs a').on('click', function(e) {
      e.preventDefault();
      $(this).tab('show');
    });
  });
</script>

<!-- Bildirim çıktısı -->
<?php echo $bildirim; ?> 

<?php if($basarili): ?>
<script>
  // Başarılı güncelleme durumunda 2 saniye sonra otomatik yönlendirme yap
  setTimeout(function() {
    window.location.href = '<?=SITE?>yacht-types';
  }, 2000);
</script>
<?php endif; ?> 
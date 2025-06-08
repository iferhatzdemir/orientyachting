<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// SweetAlert2 bildirim için JavaScript
$bildirim = '';

// Durum değiştirme işlemi
if(isset($_GET["islem"]) && $_GET["islem"] == "durum") {
    $id = $VT->filter($_GET["id"]);
    $durum = $VT->filter($_GET["durum"]);
    $guncelle = $VT->SorguCalistir("UPDATE yacht_locations", "SET durum=? WHERE ID=?", array($durum, $id));
    if($guncelle) {
        $bildirim = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: 'Durum başarıyla güncellendi.',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    } else {
        $bildirim = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Durum güncellenirken bir sorun oluştu.',
                showConfirmButton: true
            });
        </script>";
    }
}

// Ekleme ve Güncelleme İşlemleri
if($_POST) {
    // Yeni lokasyon ekleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "ekle") {
        if(!empty($_POST["baslik"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $seo_url = $VT->seo($baslik);
            $aciklama = $VT->filter($_POST["aciklama"]);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            $ekle = $VT->SorguCalistir("INSERT INTO yacht_locations", "SET baslik=?, seo_url=?, aciklama=?, durum=?, sirano=?", array($baslik, $seo_url, $aciklama, $durum, $sirano));
            
            if($ekle != false) {
                $sonID = $VT->baglanti->lastInsertId();
                
                // Dil çevirilerini ekle
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            $dil_aciklama = $VT->filter($degerler["aciklama"]);
                            
                            $VT->SorguCalistir("INSERT INTO yacht_locations_dil", "SET location_id=?, lang=?, baslik=?, aciklama=?", array($sonID, $dil, $dil_baslik, $dil_aciklama));
                        }
                    }
                }
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: 'Yat lokasyonu başarıyla eklendi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
                
                // Sayfayı yenile
                echo '<meta http-equiv="refresh" content="1;url='.SITE.'yacht-locations">';
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Yat lokasyonu eklenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Lütfen lokasyon adını belirtin.',
                    showConfirmButton: true
                });
            </script>";
        }
    }
    
    // Yat lokasyonu güncelleme
    if(isset($_POST["islem"]) && $_POST["islem"] == "guncelle") {
        if(!empty($_POST["baslik"]) && !empty($_POST["ID"])) {
            $baslik = $VT->filter($_POST["baslik"]);
            $ID = $VT->filter($_POST["ID"]);
            $seo_url = $VT->seo($baslik);
            $aciklama = $VT->filter($_POST["aciklama"]);
            $sirano = $VT->filter($_POST["sirano"]);
            if(empty($sirano)) { $sirano = 0; }
            if(!empty($_POST["durum"])) { $durum = 1; } else { $durum = 2; }
            
            $guncelle = $VT->SorguCalistir("UPDATE yacht_locations", "SET baslik=?, seo_url=?, aciklama=?, durum=?, sirano=? WHERE ID=?", array($baslik, $seo_url, $aciklama, $durum, $sirano, $ID));
            
            if($guncelle != false) {
                // Dil çevirilerini güncelle
                if(!empty($_POST["dil"])) {
                    foreach($_POST["dil"] as $dil => $degerler) {
                        if(!empty($degerler["baslik"])) {
                            $dil_baslik = $VT->filter($degerler["baslik"]);
                            $dil_aciklama = $VT->filter($degerler["aciklama"]);
                            
                            // Mevcut çeviri var mı kontrol et
                            $dilKontrol = $VT->VeriGetir("yacht_locations_dil", "WHERE location_id=? AND lang=?", array($ID, $dil), "ORDER BY ID ASC", 1);
                            
                            if($dilKontrol != false) {
                                // Varsa güncelle
                                $VT->SorguCalistir("UPDATE yacht_locations_dil", "SET baslik=?, aciklama=? WHERE location_id=? AND lang=?", array($dil_baslik, $dil_aciklama, $ID, $dil));
                            } else {
                                // Yoksa ekle
                                $VT->SorguCalistir("INSERT INTO yacht_locations_dil", "SET location_id=?, lang=?, baslik=?, aciklama=?", array($ID, $dil, $dil_baslik, $dil_aciklama));
                            }
                        }
                    }
                }
                
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: 'Yat lokasyonu başarıyla güncellendi.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
                
                // Sayfayı yenile
                echo '<meta http-equiv="refresh" content="1;url='.SITE.'yacht-locations">';
            } else {
                $bildirim = "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Yat lokasyonu güncellenirken bir sorun oluştu.',
                        showConfirmButton: true
                    });
                </script>";
            }
        } else {
            $bildirim = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Lütfen lokasyon adını ve ID değerini belirtin.',
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
    $duzenle = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
    
    if($duzenle != false) {
        // Dil çevirilerini getir
        $dilVerileri = array();
        $dilKayitlari = $VT->VeriGetir("yacht_locations_dil", "WHERE location_id=?", array($id), "ORDER BY ID ASC");
        
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
            <h1 class="m-0 text-dark">Yat Lokasyonları Yönetimi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Yat Lokasyonları</li>
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
                <h3 class="card-title"><?=$duzenle ? "Yat Lokasyonu Düzenle" : "Yeni Yat Lokasyonu Ekle"?></h3>
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
                          <label>Lokasyon Adı</label>
                          <input type="text" class="form-control" placeholder="Lokasyon Adı" name="baslik" value="<?=$duzenle ? $duzenle[0]["baslik"] : ""?>" required>
                        </div>
                        <div class="form-group">
                          <label>Açıklama</label>
                          <textarea class="form-control" placeholder="Açıklama" name="aciklama" rows="3"><?=$duzenle ? $duzenle[0]["aciklama"] : ""?></textarea>
                        </div>
                        <div class="form-group">
                          <label>Sıra No</label>
                          <input type="number" class="form-control" placeholder="Sıra No" name="sirano" value="<?=$duzenle ? $duzenle[0]["sirano"] : "0"?>">
                        </div>
                        <div class="form-group">
                          <label>Durum</label><br>
                          <input type="checkbox" name="durum" value="1" data-bootstrap-switch <?=$duzenle && $duzenle[0]["durum"] == 1 ? "checked" : ""?>>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="form-group">
                          <label>Lokasyon Adı (Türkçe)</label>
                          <input type="text" class="form-control" placeholder="Lokasyon Adı (Türkçe)" name="dil[tr][baslik]" value="<?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label>Açıklama (Türkçe)</label>
                          <textarea class="form-control" placeholder="Açıklama (Türkçe)" name="dil[tr][aciklama]" rows="3"><?=isset($dilVerileri["tr"]) ? $dilVerileri["tr"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="form-group">
                          <label>Lokasyon Adı (İngilizce)</label>
                          <input type="text" class="form-control" placeholder="Lokasyon Adı (İngilizce)" name="dil[en][baslik]" value="<?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label>Açıklama (İngilizce)</label>
                          <textarea class="form-control" placeholder="Açıklama (İngilizce)" name="dil[en][aciklama]" rows="3"><?=isset($dilVerileri["en"]) ? $dilVerileri["en"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="form-group">
                          <label>Lokasyon Adı (Almanca)</label>
                          <input type="text" class="form-control" placeholder="Lokasyon Adı (Almanca)" name="dil[de][baslik]" value="<?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label>Açıklama (Almanca)</label>
                          <textarea class="form-control" placeholder="Açıklama (Almanca)" name="dil[de][aciklama]" rows="3"><?=isset($dilVerileri["de"]) ? $dilVerileri["de"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                      
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="form-group">
                          <label>Lokasyon Adı (Rusça)</label>
                          <input type="text" class="form-control" placeholder="Lokasyon Adı (Rusça)" name="dil[ru][baslik]" value="<?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["baslik"] : ""?>">
                        </div>
                        <div class="form-group">
                          <label>Açıklama (Rusça)</label>
                          <textarea class="form-control" placeholder="Açıklama (Rusça)" name="dil[ru][aciklama]" rows="3"><?=isset($dilVerileri["ru"]) ? $dilVerileri["ru"]["aciklama"] : ""?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <?php if($duzenle) { ?>
                    <input type="hidden" name="islem" value="guncelle">
                    <input type="hidden" name="ID" value="<?=$duzenle[0]["ID"]?>">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                  <?php } else { ?>
                    <input type="hidden" name="islem" value="ekle">
                    <button type="submit" class="btn btn-primary">Ekle</button>
                  <?php } ?>
                </div>
              </form>
            </div>
          </div>
          
          <div class="col-md-7">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Yat Lokasyonları Listesi</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">Sıra</th>
                      <th>Lokasyon Adı</th>
                      <th style="width:50px;">Sıra No</th>
                      <th style="width:80px;">Durum</th>
                      <th style="width:120px;">İşlem</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $lokasyonlar = $VT->VeriGetir("yacht_locations", "", "", "ORDER BY sirano ASC");
                    if($lokasyonlar != false) {
                        $sira = 0;
                        for($i=0; $i<count($lokasyonlar); $i++) {
                            $sira++;
                            if($lokasyonlar[$i]["durum"] == 1) {
                                $durumRenk = "success";
                                $durumText = "Aktif";
                                $durumDeger = 2;
                            } else {
                                $durumRenk = "danger";
                                $durumText = "Pasif";
                                $durumDeger = 1;
                            }
                    ?>
                    <tr>
                      <td><?=$sira?></td>
                      <td><?=stripslashes($lokasyonlar[$i]["baslik"])?></td>
                      <td><?=$lokasyonlar[$i]["sirano"]?></td>
                      <td>
                        <button type="button" class="btn btn-<?=$durumRenk?> btn-sm durum-degistir" data-id="<?=$lokasyonlar[$i]["ID"]?>" data-durum="<?=$durumDeger?>"><?=$durumText?></button>
                      </td>
                      <td>
                        <a href="<?=SITE?>yacht-locations/duzenle/<?=$lokasyonlar[$i]["ID"]?>" class="btn btn-warning btn-sm"><i class="fa fa-pen"></i></a>
                        <a href="<?=SITE?>yacht-locations/sil/<?=$lokasyonlar[$i]["ID"]?>" class="btn btn-danger btn-sm silmeAlani"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Henüz lokasyon eklenmedi.</td></tr>';
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
    
    // Dropdown menu açılması için
    $('.has-treeview > a').on('click', function(e) {
      e.preventDefault();
      $(this).parent().toggleClass('menu-open');
      $(this).next('.nav-treeview').slideToggle();
    });
    
    // Aktif sayfayı işaretle
    var currentPath = window.location.pathname;
    $('.nav-sidebar .nav-link').each(function() {
      var linkPath = $(this).attr('href');
      if (currentPath.indexOf(linkPath) !== -1) {
        $(this).addClass('active');
        
        // Eğer dropdown içindeyse parentı da active yap
        if ($(this).parents('.has-treeview').length) {
          $(this).parents('.has-treeview').addClass('menu-open');
          $(this).parents('.has-treeview').find('> a').addClass('active');
          $(this).parents('.nav-treeview').show();
        }
      }
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
      title: 'Emin misiniz?',
      text: 'Durum değişikliği yapmak istediğinize emin misiniz?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, değiştir!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile durum değiştirme
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            'islem': 'durumDegistir',
            'tablo': 'yacht_locations',
            'ID': id,
            'durum': durum
          },
          success: function(response) {
            if(response == "TAMAM") {
              // Başarılı ise butonun durumunu güncelle
              if(durum == 1) {
                button.removeClass('btn-danger').addClass('btn-success');
                button.text('Aktif');
                button.data('durum', 2);
              } else {
                button.removeClass('btn-success').addClass('btn-danger');
                button.text('Pasif');
                button.data('durum', 1);
              }
              
              // Başarı bildirimi göster
              Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: 'Durum başarıyla güncellendi.',
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Durum güncellenirken bir sorun oluştu.',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
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
    var locationID = deleteURL.split('/').pop();
    var locationName = deleteButton.closest('tr').find('td:eq(1)').text();
    
    Swal.fire({
      title: 'Emin misiniz?',
      text: '"' + locationName + '" lokasyonunu silmek istediğinize emin misiniz?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile silme işlemi
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            'islem': 'lokasyonSil',
            'ID': locationID
          },
          success: function(response) {
            if(response == "TAMAM") {
              // Başarılı ise satırı tablodan kaldır
              deleteButton.closest('tr').fadeOut('slow', function() {
                $(this).remove();
                
                // Tabloda hiç satır kalmadıysa mesaj göster
                if ($('#example1 tbody tr').length === 0) {
                  $('#example1 tbody').append('<tr><td colspan="5" class="text-center">Henüz lokasyon eklenmedi.</td></tr>');
                }
              });
              
              // Başarı bildirimi göster ve ardından yönlendir
              Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: 'Lokasyon başarıyla silindi.',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location.href = '<?=SITE?>yacht-locations';
              });
            } else {
              // Hata bildirimi göster
              Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Lokasyon silinirken bir sorun oluştu.',
                showConfirmButton: true
              });
            }
          },
          error: function() {
            // Bağlantı hatası bildirimi göster
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
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
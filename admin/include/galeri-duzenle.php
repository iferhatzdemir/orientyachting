<?php
if(!defined("SABIT")) die("Erişim engellendi!");

if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);
  $galeriBilgisi=$VT->VeriGetir("galeri","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
  if($galeriBilgisi!=false)
  {
?>

<!-- SweetAlert2 CSS ve JS - en üstte yüklenmesi için -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<!-- Dropzone.js - doğrudan bu sayfaya yükleme yapmak için -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
  // Dropzone otomatik başlatmayı devre dışı bırak, manuel olarak başlatacağız
  Dropzone.autoDiscover = false;
</script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Galeri Düzenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Galeri Düzenle</li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><?=$galeriBilgisi[0]["baslik"]?> Galeri Düzenleme</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="#" method="post" id="galeriForm">
                <div class="card-body">
                  <div class="form-group">
                    <label for="baslik">Galeri Başlık</label>
                    <input type="text" class="form-control" id="baslik" name="baslik" placeholder="Galeri Başlık" value="<?=$galeriBilgisi[0]["baslik"]?>">
                  </div>
                  <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select class="form-control" name="kategori" id="kategori">
                      <option value="0">Kategorisiz</option>
                      <?php
                      $kategoriler=$VT->VeriGetir("galeri_kategoriler","","","ORDER BY sira ASC");
                      if($kategoriler!=false)
                      {
                        for($i=0;$i<count($kategoriler);$i++)
                        {
                          if($kategoriler[$i]["ID"]==$galeriBilgisi[0]["kategori"])
                          {
                            echo '<option value="'.$kategoriler[$i]["ID"].'" selected="selected">'.$kategoriler[$i]["baslik"].'</option>';
                          }
                          else
                          {
                            echo '<option value="'.$kategoriler[$i]["ID"].'">'.$kategoriler[$i]["baslik"].'</option>';
                          }
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="sira">Sıra</label>
                    <input type="text" class="form-control" id="sira" name="sira" placeholder="Sıra" value="<?=$galeriBilgisi[0]["sira"]?>">
                  </div>
                  <div class="form-group">
                    <label>Durum</label><br />
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="durum1" name="durum" class="custom-control-input" value="1" <?php if($galeriBilgisi[0]["durum"]==1){ echo 'checked'; }?>>
                      <label class="custom-control-label" for="durum1">Aktif</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="durum2" name="durum" class="custom-control-input" value="2" <?php if($galeriBilgisi[0]["durum"]==2){ echo 'checked'; }?>>
                      <label class="custom-control-label" for="durum2">Pasif</label>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-primary" id="galeriKaydet">Değişiklikleri Kaydet</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Medya Yükleme Bölümü -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Resim ve Video Yükleme</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mediaTypeImage" name="mediaType" class="custom-control-input" value="image" checked>
                    <label class="custom-control-label" for="mediaTypeImage">Resim Yükle</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mediaTypeVideo" name="mediaType" class="custom-control-input" value="video">
                    <label class="custom-control-label" for="mediaTypeVideo">Video Yükle</label>
                  </div>
                </div>
                <div class="alert alert-info">
                  <strong>Bilgi:</strong> 
                  <span id="mediaTypeInfo">Resim dosyaları için maksimum boyut: 10MB. Desteklenen formatlar: JPG, PNG, GIF, WEBP, BMP, SVG</span>
                </div>
                <form action="<?=SITE?>ajax.php" method="post" id="mediaUploadDropzone" class="dropzone" enctype="multipart/form-data">
                  <input type="hidden" name="tablo" value="galeri">
                  <input type="hidden" name="ID" value="<?=$ID?>">
                  <input type="hidden" name="mediaType" id="mediaTypeInput" value="image">
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Resim Galerisi -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Galeri Resimleri</h3>
          </div>
          <div class="card-body">
            <div class="row" id="galeri-resimleri">
              <!-- Resimler AJAX ile yüklenecek -->
              <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                  <span class="sr-only">Yükleniyor...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Dosyaları Listesi -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header bg-primary">
            <h3 class="card-title">Video Dosyaları</h3>
          </div>
          <div class="card-body">
            <?php
            // Klasördeki video dosyalarını doğrudan listele
            $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/";
            
            if(is_dir($videoPath)) {
                $videos = glob($videoPath . "*.{mp4,webm,ogg,mov,avi}", GLOB_BRACE);
                
                if(count($videos) > 0) {
                    echo '<div class="table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th style="width:30%">Dosya Adı</th>
                                <th style="width:10%">Boyut</th>
                                <th style="width:15%">Tarih</th>
                                <th style="width:30%">Önizleme</th>
                                <th style="width:15%">İşlemler</th>
                              </tr>
                            </thead>
                            <tbody>';
                    
                    foreach($videos as $video) {
                        $filename = basename($video);
                        $filesize = filesize($video);
                        $lastModified = date("Y-m-d H:i:s", filemtime($video));
                        
                        // Test_video.mp4 dosyasını gösterme
                        if($filename == "test_video.mp4") {
                            continue;
                        }
                        
                        // DB'de kayıt var mı kontrol et
                        $dbCheck = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array("galeri", $ID, $filename), "ORDER BY ID ASC", 1);
                        
                        echo '<tr>
                                <td>' . $filename . '</td>
                                <td>' . number_format($filesize / 1024 / 1024, 2) . ' MB</td>
                                <td>' . $lastModified . '</td>
                                <td>
                                  <video width="200" height="120" controls>
                                    <source src="' . ANASITE . 'images/resimler/videos/' . $filename . '" type="video/mp4">
                                    Tarayıcınız video etiketini desteklemiyor.
                                  </video>
                                </td>
                                <td>';
                        
                        // Dosya DB'de kayıtlı değilse, kaydetme butonu göster
                        if($dbCheck == false) {
                            echo '<button class="btn btn-primary btn-sm" onclick="addVideoToDB(\'' . $filename . '\')">Kaydet</button>';
                        } else {
                            echo '<a href="'.SITE.'galeri-resim-sil/'.$ID.'/'.$dbCheck[0]["ID"].'" class="btn btn-danger btn-sm" onclick="return resimSil(this.href);">Kaldır</a>';
                        }
                        
                        echo ' <button class="btn btn-warning btn-sm" onclick="deleteVideoFile(\'' . $filename . '\')">Dosyayı Sil</button>';
                        
                        echo '</td>
                              </tr>';
                    }
                    
                    echo '</tbody>
                        </table>
                      </div>';
                } else {
                    echo '<div class="alert alert-warning">Klasörde hiç video dosyası bulunamadı.</div>';
                }
            } else {
                echo '<div class="alert alert-warning">Video klasörü bulunamadı. Lütfen bir video yükleyin.</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </section>
  </div>
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Galeri Formu Gönderme
    document.getElementById('galeriKaydet').addEventListener('click', function() {
      // Form verilerini al
      var formData = new FormData(document.getElementById('galeriForm'));
      formData.append('islem', 'galeri-duzenle');
      formData.append('ID', '<?=$ID?>');
      
      // AJAX isteği gönder
      fetch('<?=SITE?>ajax.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        if(data === "TAMAM") {
          Swal.fire({
            icon: 'success',
            title: 'Başarılı!',
            text: 'Galeri bilgileri başarıyla güncellendi.',
            showConfirmButton: false,
            timer: 1500
          });
        } else if(data === "BOS") {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Lütfen gerekli alanları doldurun.'
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Galeri bilgileri güncellenirken bir hata oluştu.'
          });
        }
      })
      .catch(error => {
        console.error('Hata:', error);
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'İşlem sırasında bir hata oluştu.'
        });
      });
    });

    // Galeri resimlerini yükle
    function loadGalleryImages() {
      fetch('<?=SITE?>ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'islem=galeri-listesi-getir&galeri_id=<?=$ID?>'
      })
      .then(response => response.text())
      .then(data => {
        document.getElementById('galeri-resimleri').innerHTML = data;
        
        // Fancybox'ı başlat
        initFancybox();
      })
      .catch(error => {
        console.error('Hata:', error);
        document.getElementById('galeri-resimleri').innerHTML = '<div class="col-12"><div class="alert alert-danger">Resimler yüklenirken bir hata oluştu.</div></div>';
      });
    }
    
    // Fancybox ayarları
    function initFancybox() {
      $.fancybox.defaults.buttons = [
        "zoom",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
      ];

      // Fancybox başlatma
      $("[data-fancybox='gallery']").fancybox({
        loop: true,
        animationEffect: "zoom-in-out",
        transitionEffect: "fade",
        // Video ayarları
        youtube: {
          controls: 1,
          showinfo: 0
        },
        vimeo: {
          color: 'f00'
        }
      });
    }
    
    // Sayfa yüklendiğinde resimleri getir
    loadGalleryImages();
    
    // MediaType değişikliğini dinle
    $('input[name="mediaType"]').on('change', function() {
      console.log("Media type changed to: " + this.value);
      $('#mediaTypeInput').val(this.value);
      
      // Bilgi metnini güncelle
      if(this.value === 'video') {
        $('#mediaTypeInfo').html('Video dosyaları için maksimum boyut: 100MB. Desteklenen formatlar: MP4, WEBM, OGG, MOV, AVI');
      } else {
        $('#mediaTypeInfo').html('Resim dosyaları için maksimum boyut: 10MB. Desteklenen formatlar: JPG, PNG, GIF, WEBP, BMP, SVG');
      }
      
      updateDropzoneConfig();
    });
    
    // Dropzone konfigürasyonu
    var myDropzone;
    
    function updateDropzoneConfig() {
      if (myDropzone) {
        myDropzone.destroy();
      }
      
      var mediaType = $('input[name="mediaType"]:checked').val();
      var acceptedFiles, maxFileSizeValue, messageText;
      
      if (mediaType === 'video') {
        acceptedFiles = ".mp4,.webm,.ogg,.mov,.avi,.MP4,.WEBM,.OGG,.MOV,.AVI";
        maxFileSizeValue = 100; // 100 MB for videos
        messageText = "<i class='fas fa-film fa-3x'></i><br>Video yüklemek için dosyaları bu alana sürükleyip bırakın veya tıklayın";
      } else {
        acceptedFiles = ".jpeg,.jpg,.png,.gif,.JPG,.JPEG,.PNG,.GIF,.webp,.WEBP,.bmp,.BMP,.svg,.SVG";
        maxFileSizeValue = 10; // 10 MB for images
        messageText = "<i class='fas fa-image fa-3x'></i><br>Resim yüklemek için dosyaları bu alana sürükleyip bırakın veya tıklayın";
      }
      
      console.log("Initializing Dropzone with mediaType: " + mediaType);
      
      try {
        myDropzone = new Dropzone("#mediaUploadDropzone", {
          url: "<?=SITE?>ajax.php",
          paramName: "file",
          maxFilesize: maxFileSizeValue,
          acceptedFiles: acceptedFiles,
          dictDefaultMessage: messageText,
          dictFallbackMessage: "Tarayıcınız sürükle bırak yüklemeyi desteklemiyor.",
          dictFileTooBig: "Dosya çok büyük: {{filesize}}MB. Maksimum dosya boyutu: {{maxFilesize}}MB.",
          dictInvalidFileType: "Bu dosya türünü yükleyemezsiniz.",
          dictResponseError: "Sunucu {{statusCode}} hata kodu döndürdü.",
          dictCancelUpload: "Yüklemeyi iptal et",
          dictUploadCanceled: "Yükleme iptal edildi.",
          dictRemoveFile: "Dosyayı kaldır",
          dictMaxFilesExceeded: "Daha fazla dosya yükleyemezsiniz.",
          
          // Dosya adını düzenleme
          renameFile: function(file) {
            // Preserve the exact extension from the original file
            const originalName = file.name;
            const lastDot = originalName.lastIndexOf('.');
            // Extract the extension exactly as it appears (preserving case)
            const extension = lastDot !== -1 ? originalName.substring(lastDot + 1) : '';
            // Set appropriate prefix based on media type
            const prefix = mediaType === 'video' ? 'video_' : 'galeri_';
            // Use original extension with its exact case
            return prefix + new Date().getTime() + '.' + extension;
          },
          
          init: function() {
            console.log("Dropzone initialized");
            this.on("sending", function(file, xhr, formData) {
              console.log("Sending file: " + file.name);
              formData.append("mediaType", mediaType);
            });
            
            this.on("success", function(file, response) {
              console.log("Upload success, response: ", response);
              // Başarılı yükleme bildirimi
              Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: mediaType === 'video' ? 'Video başarıyla yüklendi.' : 'Resim başarıyla yüklendi.',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location.reload();
              });
            });
            
            this.on("error", function(file, errorMessage, xhr) {
              console.error("Upload error: ", errorMessage);
              file.previewElement.classList.add("dz-error");
              
              // Hata mesajını göster
              var errorMsg = errorMessage;
              if(errorMessage === "HATA_DOSYA_TURU") {
                errorMsg = "Geçersiz dosya formatı. Lütfen desteklenen bir format seçin.";
              } else if(errorMessage === "HATA_DOSYA_BOYUTU") {
                errorMsg = "Dosya boyutu çok büyük. Maksimum " + maxFileSizeValue + "MB yükleyebilirsiniz.";
              }
              
              Swal.fire({
                icon: 'error',
                title: 'Yükleme Hatası!',
                text: 'Hata: ' + errorMsg,
                confirmButtonText: 'Tamam'
              });
            });
          }
        });
        
        console.log("Dropzone setup complete");
      } catch (e) {
        console.error("Error initializing Dropzone: ", e);
        Swal.fire({
          icon: 'error', 
          title: 'Hata!',
          text: 'Dosya yükleme alanı başlatılamadı: ' + e.message
        });
      }
    }
    
    // İlk yüklemede Dropzone'u başlat
    try {
      updateDropzoneConfig();
    } catch(e) {
      console.error("Error during initial Dropzone setup: ", e);
      Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: 'Dosya yükleme alanı başlatma hatası: ' + e.message
      });
    }
  });
  
  // Resim silme fonksiyonu
  function resimSil(url) {
    console.log("resimSil fonksiyonu çağrıldı. URL:", url);
    
    // SweetAlert2 ile onay iste
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu medyayı silmek istediğinize emin misiniz?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        console.log("Silme onaylandı, yönlendiriliyor:", url);
        // Silme işlemi başlamadan önce yükleniyor göster
        Swal.fire({
          title: 'İşlem yapılıyor...',
          text: 'Medya siliniyor, lütfen bekleyin.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        
        // Silme işlemini gerçekleştir
        window.location.href = url;
        
        // İşlem tamamlandıktan sonra her durumda sayfayı yenile
        setTimeout(function() {
          window.location.reload();
        }, 1500);
      }
    });
    
    // Bu satır önemli - onclick fonksiyonunda return false; dönerek
    // bağlantının normal davranışını engelliyoruz
    return false;
  }
  
  // Video DB kaydı ekleme JS
  function addVideoToDB(filename) {
    fetch('<?=SITE?>ajax.php?p=add-video-to-db', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'filename=' + encodeURIComponent(filename) + '&tablo=galeri&ID=<?=$ID?>'
    })
    .then(response => response.text())
    .then(data => {
      if(data === 'TAMAM') {
        Swal.fire({
          icon: 'success',
          title: 'Başarılı!',
          text: 'Video başarıyla galeriye eklendi.',
          showConfirmButton: false,
          timer: 1500
        }).then(function() {
          setTimeout(function() {
            window.location.reload();
          }, 500);
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'Hata: ' + data
        }).then(function() {
          // Hata durumunda da sayfayı yenile
          setTimeout(function() {
            window.location.reload();
          }, 1500);
        });
      }
    })
    .catch(error => {
      console.error('Hata:', error);
      Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: 'İşlem sırasında bir hata oluştu.'
      }).then(function() {
        // Bağlantı hatası durumunda da sayfayı yenile
        setTimeout(function() {
          window.location.reload();
        }, 1500);
      });
    });
  }

  // Video dosya silme JS fonksiyonu
  function deleteVideoFile(filename) {
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu video dosyasını disk üzerinden tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Silme işlemi başlamadan önce yükleniyor göster
        Swal.fire({
          title: 'İşlem yapılıyor...',
          text: 'Video siliniyor, lütfen bekleyin.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        
        fetch('<?=SITE?>ajax.php?p=delete-video-file', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'filename=' + encodeURIComponent(filename)
        })
        .then(response => response.text())
        .then(data => {
          if(data === 'TAMAM') {
            Swal.fire({
              icon: 'success',
              title: 'Başarılı!',
              text: 'Video dosyası başarıyla silindi.',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              setTimeout(function() {
                window.location.reload();
              }, 500);
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Hata: ' + data
            }).then(function() {
              // Hata durumunda da sayfayı yenile
              setTimeout(function() {
                window.location.reload();
              }, 1500);
            });
          }
        })
        .catch(error => {
          console.error('Hata:', error);
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'İşlem sırasında bir hata oluştu.'
          }).then(function() {
            // Bağlantı hatası durumunda da sayfayı yenile
            setTimeout(function() {
              window.location.reload();
            }, 1500);
          });
        });
      }
    });
  }
</script>
<?php
  }
  else
  {
    echo '<div class="alert alert-danger">Galeri Bulunamadı!</div>';
  }
}
else
{
  echo '<div class="alert alert-danger">Galeri ID Bulunamadı!</div>';
}
?> 
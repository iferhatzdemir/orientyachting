<?php
if(empty($_GET["ID"]))
{
  echo '<meta http-equiv="refresh" content="0;url='.SITE.'galeri">';
  exit;
}

$ID=$VT->filter($_GET["ID"]);
$galeri=$VT->VeriGetir("galeri","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
if($galeri==false)
{
  echo '<meta http-equiv="refresh" content="0;url='.SITE.'galeri">';
  exit;
}
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
            <h1 class="m-0 text-dark"><?=$galeri[0]["baslik"]?> - Galeri Medyaları</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>galeri">Galeriler</a></li>
              <li class="breadcrumb-item active">Galeri Medyaları</li>
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
                  <div class="alert alert-info">
                    <strong>Bilgi:</strong> 
                    <span id="mediaTypeInfo">Resim dosyaları için maksimum boyut: 10MB. Desteklenen formatlar: JPG, PNG, GIF, WEBP, BMP, SVG</span>
                  </div>
                  <form action="<?=SITE?>ajax.php" method="post" id="mediaUploadDropzone" class="dropzone" enctype="multipart/form-data">
                    <input type="hidden" name="tablo" value="galeri">
                    <input type="hidden" name="ID" value="<?=$ID?>">
                    <input type="hidden" name="islem" value="galeri-resim-yukle">
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
     
      </div><!-- /.container-fluid -->
    </section>

    <!-- Hata/Başarı mesajlarını göstermek için ek alan -->
    <div id="message-container" class="container-fluid mb-3" style="display: none;">
      <div class="alert" id="message-box" role="alert"></div>
    </div>

     <section class="content">
      <div class="container-fluid">
      
       <div class="card">
         <div class="card-header">
           <h3 class="card-title">Yüklenen Medyalar</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
           <div class="row" id="galeri-resimleri">
             <?php
             $resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $ID), "ORDER BY ID DESC");
             if($resimler != false) {
               foreach($resimler as $resim) {
                 // Determine if file is a video
                 $fileExtension = strtolower(pathinfo($resim["resim"], PATHINFO_EXTENSION));
                 $isVideoByName = (strpos($resim["resim"], 'video_') === 0);
                 $isVideoByExt = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
                 $isVideo = $isVideoByName || $isVideoByExt;
                 
                 // Set path based on media type
                 if($isVideo) {
                     $resimYolu = ANASITE."images/resimler/videos/".$resim["resim"];
                     // Fallback to regular path if not found in videos directory
                     $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/".$resim["resim"];
                     if(!file_exists($videoPath)) {
                         $resimYolu = ANASITE."images/resimler/".$resim["resim"];
                     }
                 } else {
                     $resimYolu = ANASITE."images/resimler/".$resim["resim"];
                 }
                 
                 // Skip test_video.mp4
                 if($resim["resim"] == "test_video.mp4") {
                     continue;
                 }
             ?>
             <div class="col-md-3 text-center mb-4 galeri-item">
               <div class="galeri-resim-container">
                 <?php if($isVideo): ?>
                   <a href="<?=$resimYolu?>" data-fancybox="gallery" data-caption="<?=$galeri[0]["baslik"]?> - Video">
                     <video class="img-fluid img-thumbnail galeri-resim" controls>
                       <source src="<?=$resimYolu?>" type="video/<?=$fileExtension?>">
                       Tarayıcınız video etiketini desteklemiyor.
                     </video>
                   </a>
                   <div class="media-type-badge">Video</div>
                 <?php else: ?>
                   <a href="<?=$resimYolu?>" data-fancybox="gallery" data-caption="<?=$galeri[0]["baslik"]?> - Resim">
                     <img src="<?=$resimYolu?>" class="img-fluid img-thumbnail galeri-resim" alt="<?=$galeri[0]["baslik"]?> - Resim" loading="lazy">
                   </a>
                 <?php endif; ?>
                 <div class="galeri-resim-islemler mt-2">
                   <a href="<?=SITE?>galeri-resim-sil/<?=$ID?>/<?=$resim["ID"]?>" class="btn btn-danger btn-sm" onclick="return resimSil(this.href);"><i class="fas fa-trash"></i> Sil</a>
                 </div>
               </div>
             </div>
             <?php
               }
             } else {
               echo '<div class="col-md-12"><div class="alert alert-warning">Bu galeriye henüz medya eklenmemiş.</div></div>';
             }
             ?>
           </div>
         </div>
         <!-- /.card-body -->
       </div>
       
      </div><!-- /.container-fluid -->
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
                        // Dosyanın gerçekten var olduğunu ve erişilebilir olduğunu kontrol et
                        if(!file_exists($video) || !is_readable($video)) {
                            continue; // Dosya yoksa veya okunamıyorsa bu dosyayı atla
                        }
                        
                        $filename = basename($video);
                        $filesize = filesize($video);
                        $lastModified = date("Y-m-d H:i:s", filemtime($video));
                        
                        // Test_video.mp4 dosyasını gösterme
                        if($filename == "test_video.mp4") {
                            continue;
                        }
                        
                        // DB'de kayıt var mı kontrol et
                        $dbCheck = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array("galeri", $ID, $filename), "ORDER BY ID ASC", 1);
                        
                        echo '<tr id="video-row-'.md5($filename).'">
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
                            echo '<button class="btn btn-primary btn-sm mb-1" onclick="addVideoToDB(\'' . $filename . '\')">Kaydet</button><br>';
                        } else {
                            echo '<a href="'.SITE.'galeri-resim-sil/'.$ID.'/'.$dbCheck[0]["ID"].'" class="btn btn-danger btn-sm mb-1" onclick="return resimSil(this.href);">Kaldır</a><br>';
                        }
                        
                        echo '<button class="btn btn-warning btn-sm" onclick="deleteVideoFile(\'' . $filename . '\', \''.md5($filename).'\')">Dosyayı Sil</button>';
                        
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
    <!-- /.content -->
  </div>

<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<style>
.galeri-resim-container {
  position: relative;
  margin-bottom: 15px;
}

.galeri-resim {
  height: 180px;
  width: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.galeri-resim:hover {
  transform: scale(1.03);
}

.dropzone {
  border: 2px dashed #0087F7;
  border-radius: 5px;
  background: #F9F9F9;
  min-height: 150px;
  padding: 20px;
  text-align: center;
}

.dropzone .dz-message {
  font-weight: 500;
  font-size: 1.2em;
  margin: 1em 0;
}

.media-type-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: #007bff;
  color: white;
  padding: 3px 8px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: bold;
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Tüm resim hatalarını bir kere kurtarmaya çalış ve sonra bırak
    const processedImages = new Set();
    const maxRetries = 1; // Her resim için sadece bir kere yeniden deneme
    
    // Image error handler simplified to avoid infinite loops
    function handleImageErrors() {
      const images = document.querySelectorAll('.galeri-resim');
      
      images.forEach(img => {
        // Skip processing if already handled
        if (processedImages.has(img.src)) {
          return;
        }
        
        // Add to processed set immediately to prevent handling multiple times
        processedImages.add(img.src);
        
        // Set error handler
        img.onerror = function() {
          console.error('Resim yüklenemedi:', img.src);
          
          // Don't retry if it's already the fallback image
          if (img.src.includes('no-image.svg')) {
            return;
          }
          
          // Force to fallback image immediately
          console.log('Fallback görüntüsüne geçiliyor');
          this.src = '<?=SITE?>dist/img/no-image.svg';
        };
      });
    }
    
    // Initial call to handle image errors
    handleImageErrors();
    
    // Apply the handler again after any refresh
    function refreshGallery() {
      // Clear any pending refresh
      if (window.refreshDebounceTimer) {
        clearTimeout(window.refreshDebounceTimer);
      }
      
      window.refreshDebounceTimer = setTimeout(function() {
        // Prevent multiple simultaneous refreshes
        if (window.isRefreshing) {
          return;
        }
        
        // Prevent too frequent refreshes
        var now = new Date().getTime();
        if (window.lastRefreshTime && (now - window.lastRefreshTime < 2000)) {
          return;
        }
        
        window.isRefreshing = true;
        window.lastRefreshTime = now;
        
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            islem: 'galeri-listesi-getir',
            galeri_id: <?=$ID?>
          },
          success: function(response) {
            $('#galeri-resimleri').html(response);
            
            // Reinitialize Fancybox
            $("[data-fancybox='gallery']").fancybox({
              loop: true,
              animationEffect: "zoom-in-out",
              transitionEffect: "fade"
            });
            
            // Apply error handler to new images
            setTimeout(handleImageErrors, 300);
            
            window.isRefreshing = false;
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Galeri resimleri yüklenirken bir hata oluştu.',
              confirmButtonText: 'Tamam'
            });
            window.isRefreshing = false;
          }
        });
      }, 500);
    }
    
    // Make sure refreshGallery is globally available
    window.refreshGallery = refreshGallery;
    
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
        showMessage('danger', 'Dropzone başlatılamadı: ' + e.message);
      }
    }
    
    // İlk yüklemede Dropzone'u başlat
    try {
      updateDropzoneConfig();
    } catch(e) {
      console.error("Error during initial Dropzone setup: ", e);
      showMessage('danger', 'Dropzone başlatma hatası: ' + e.message);
    }
  });
  
  // Video dosyasını DB'ye kaydetme fonksiyonu
  function addVideoToDB(filename) {
    if (!filename) {
      Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: 'Dosya adı belirtilmedi!'
      });
      return;
    }
    
    // AJAX isteği ile sunucuya bilgi gönder
    $.ajax({
      url: '<?=SITE?>ajax.php',
      type: 'POST',
      data: {
        action: 'add_video',
        filename: filename,
        gallery_id: <?=$ID?>
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          Swal.fire({
            icon: 'success',
            title: 'Başarılı!',
            text: 'Video başarıyla kaydedildi!',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: response.message || 'Bir hata oluştu!'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'Sunucu ile iletişim kurulamadı!'
        });
      }
    });
  }
  
  // Video dosyasını fiziksel olarak silme fonksiyonu
  function deleteVideoFile(filename, rowId) {
    if (!filename) {
      Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: 'Dosya adı belirtilmedi!'
      });
      return;
    }
    
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu dosyayı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX isteği ile sunucuya bilgi gönder
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            action: 'delete_video_file',
            filename: filename
          },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: 'Dosya başarıyla silindi!',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                // Eğer rowId varsa, ilgili satırı kaldır
                if (rowId) {
                  $('#video-row-' + rowId).fadeOut(300, function() { 
                    $(this).remove(); 
                    
                    // Eğer tablo boşsa, mesaj göster
                    if ($('table tbody tr').length <= 1) {
                      $('table').replaceWith('<div class="alert alert-warning">Klasörde hiç video dosyası bulunamadı.</div>');
                    }
                  });
                } else {
                  window.location.reload();
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: response.message || 'Dosya silinemedi!'
              });
            }
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Sunucu ile iletişim kurulamadı!'
            });
          }
        });
      }
    });
  }
  
  // Resim silme onay fonksiyonu
  function resimSil(url) {
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu medyayı kaldırmak istediğinizden emin misiniz?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Evet, kaldır!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
    
    return false;
  }
</script> 
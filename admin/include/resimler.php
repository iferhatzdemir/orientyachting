<?php
if(!empty($_GET["tablo"]) && !empty($_GET["ID"]))
{
  $tablo=$VT->filter($_GET["tablo"]);
  $ID=$VT->filter($_GET["ID"]);
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
            <h1 class="m-0 text-dark">Medya Yönetimi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Medya Yönetimi</li>
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
                </div>
                <div class="alert alert-info">
                  <strong>Bilgi:</strong> 
                  <span id="mediaTypeInfo">Resim dosyaları için maksimum boyut: 10MB. Desteklenen formatlar: JPG, PNG, GIF, WEBP</span>
                </div>
                <form action="<?=SITE?>ajax.php" method="post" id="mediaUploadDropzone" class="dropzone" enctype="multipart/form-data">
                  <input type="hidden" name="tablo" value="<?=$tablo?>">
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
     
      </div><!-- /.container-fluid -->
    </section>

    <!-- Hata/Başarı mesajlarını göstermek için ek alan -->
    <div id="message-container" class="container-fluid mb-3" style="display: none;">
      <div class="alert" id="message-box" role="alert"></div>
    </div>

     <section class="content">
      <div class="container-fluid">
      
       <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width:50px;">Sıra</th>
                  <th>Medya</th>
                  <th style="width:80px;">Tür</th>
                  <th style="width:80px;">Tarih</th>
                  <th style="width:120px;">Kaldır</th>
                </tr>
                </thead>
                <tbody>
                <?php
        $veriler=$VT->VeriGetir("resimler","WHERE tablo=? AND KID=?",array($tablo,$ID),"ORDER BY ID ASC");
        if($veriler!=false)
        {
          $sira=0;
          for($i=0;$i<count($veriler);$i++)
          {
            $sira++;
            // Ensure resim value is not empty
            if(empty($veriler[$i]["resim"])) continue;
            
            // Determine correct path based on table
            if($tablo == "yachts") {
                $resimDosyasi = $veriler[$i]["resim"];
                
                // Normal dosya kontrolü
                // Check if file is a video (based on known video prefix)
                $isVideoByName = (strpos($resimDosyasi, 'video_') === 0);
                $fileExtension = strtolower(pathinfo($resimDosyasi, PATHINFO_EXTENSION));
                $isVideoByExt = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
                $isVideo = $isVideoByName || $isVideoByExt;
                
                // Set correct path based on media type
                if($isVideo) {
                    $resimYolu = ANASITE."images/yachts/videos/".$resimDosyasi;
                    // Fallback to regular path if not found in videos directory
                    $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/yachts/videos/".$resimDosyasi;
                    if(!file_exists($videoPath)) {
                        $resimYolu = ANASITE."images/yachts/".$resimDosyasi;
                    }
                } else {
                    $resimYolu = ANASITE."images/yachts/".$resimDosyasi;
                }
                
                // Check if file exists with different case
                $dirPath = $isVideo ? 
                    $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/yachts/videos/" : 
                    $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/yachts/";
                
                if(!file_exists($dirPath.$resimDosyasi) && is_dir($dirPath)) {
                    $files = scandir($dirPath);
                    foreach($files as $file) {
                        if(strtolower($file) === strtolower($resimDosyasi)) {
                            $resimYolu = $isVideo ? 
                                ANASITE."images/yachts/videos/".$file : 
                                ANASITE."images/yachts/".$file;
                            break;
                        }
                    }
                }
            } else {
                $resimDosyasi = $veriler[$i]["resim"];
                
                // Check if file is a video (based on known video prefix)
                $isVideoByName = (strpos($resimDosyasi, 'video_') === 0);
                $fileExtension = strtolower(pathinfo($resimDosyasi, PATHINFO_EXTENSION));
                $isVideoByExt = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
                $isVideo = $isVideoByName || $isVideoByExt;
                
                // Set correct path based on media type
                if($isVideo) {
                    $resimYolu = ANASITE."images/resimler/videos/".$resimDosyasi;
                    // Fallback to regular path if not found in videos directory
                    $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/".$resimDosyasi;
                    if(!file_exists($videoPath)) {
                        $resimYolu = ANASITE."images/resimler/".$resimDosyasi;
                    }
                } else {
                    $resimYolu = ANASITE."images/resimler/".$resimDosyasi;
                }
                
                // Check if file exists with different case
                $dirPath = $isVideo ? 
                    $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/" : 
                    $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/";
                
                if(!file_exists($dirPath.$resimDosyasi) && is_dir($dirPath)) {
                    $files = scandir($dirPath);
                    foreach($files as $file) {
                        if(strtolower($file) === strtolower($resimDosyasi)) {
                            $resimYolu = $isVideo ? 
                                ANASITE."images/resimler/videos/".$file : 
                                ANASITE."images/resimler/".$file;
                            break;
                        }
                    }
                }
            }
            
            // Determine file type (image or video)
            if(!isset($isVideo)) {
                $fileExtension = strtolower(pathinfo($resimDosyasi, PATHINFO_EXTENSION));
                $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
            }

            // Test_video.mp4 dosyasını gösterme
            if($resimDosyasi == "test_video.mp4") {
                continue;
            }
            ?>
                        <tr>
                          <td><?=$sira?></td>
                          <td>
                            <?php if($isVideo): ?>
                              <a href="<?php echo $resimYolu; ?>" data-fancybox="gallery" data-caption="<?=$tablo?> - Video <?=$sira?>">
                                <video width="120" height="70" controls style="margin-right: 8px; float: left;">
                                  <source src="<?php echo $resimYolu; ?>" type="video/<?=$fileExtension?>">
                                  Tarayıcınız video etiketini desteklemiyor.
                                </video>
                              </a>
                            <?php else: ?>
                              <a href="<?php echo $resimYolu; ?>" data-fancybox="gallery" data-caption="<?=$tablo?> - Resim <?=$sira?>">
                                <img src="<?php echo $resimYolu; ?>" style="height: 60px; width: auto; margin-right: 8px; float: left;"> 
                              </a>
                            <?php endif; ?>
                          </td>
                          <td><?php echo $isVideo ? 'Video' : 'Resim'; ?></td>
                          <td><?=$veriler[$i]["tarih"]?></td>
                          <td>
                          <a href="<?=SITE?>resim-sil/<?=$tablo?>/<?=$ID?>/<?=$veriler[$i]["ID"]?>" onclick="return resimSil(this.href); return false;" class="btn btn-danger btn-sm">Kaldır</a>
                          </td>
                        </tr>
                        <?php
          }
        }
        ?>               
                </tbody>
                <tfoot>
                <tr>
                  <th>Sıra</th>
                  <th>Medya</th>
                  <th>Tür</th>
                  <th>Tarih</th>
                  <th>Kaldır</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
       
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

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
            $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/yachts/videos/";
            
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
                        $dbCheck = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array($tablo, $ID, $filename), "ORDER BY ID ASC", 1);
                        
                        echo '<tr>
                                <td>' . $filename . '</td>
                                <td>' . number_format($filesize / 1024 / 1024, 2) . ' MB</td>
                                <td>' . $lastModified . '</td>
                                <td>
                                  <video width="200" height="120" controls>
                                    <source src="' . ANASITE . 'images/yachts/videos/' . $filename . '" type="video/mp4">
                                    Tarayıcınız video etiketini desteklemiyor.
                                  </video>
                                </td>
                                <td>';
                        
                        // Dosya DB'de kayıtlı değilse, kaydetme butonu göster
                        if($dbCheck == false) {
                            echo '<button class="btn btn-primary btn-sm" onclick="addVideoToDB(\'' . $filename . '\')">Kaydet</button>';
                        } else {
                            echo '<a href="'.SITE.'resim-sil/'.$tablo.'/'.$ID.'/'.$dbCheck[0]["ID"].'" class="btn btn-danger btn-sm" onclick="return resimSil(this.href);">Kaldır</a>';
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
 
<?php
}
?>

<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script>
$(document).ready(function() {
    var myDropzone;
    
    // Media type değiştiğinde
    $('input[name="mediaType"]').change(function() {
        var mediaType = $(this).val();
        $('#mediaTypeInput').val(mediaType);
        
        // Info mesajını güncelle
        if(mediaType === 'video') {
            $('#mediaTypeInfo').html('Video dosyaları için maksimum boyut: 100MB. Desteklenen formatlar: MP4, WEBM, OGG, MOV, AVI');
        } else {
            $('#mediaTypeInfo').html('Resim dosyaları için maksimum boyut: 10MB. Desteklenen formatlar: JPG, PNG, GIF, WEBP');
        }
        
        // Dropzone'u yeniden yapılandır
        updateDropzoneConfig();
    });
    
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
            acceptedFiles = ".jpeg,.jpg,.png,.gif,.JPG,.JPEG,.PNG,.GIF,.webp,.WEBP";
            maxFileSizeValue = 10; // 10 MB for images
            messageText = "<i class='fas fa-image fa-3x'></i><br>Resim yüklemek için dosyaları bu alana sürükleyip bırakın veya tıklayın";
        }
        
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
                
                init: function() {
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("mediaType", mediaType);
                    });
                    
                    this.on("success", function(file, response) {
                        if(response === "OK") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: mediaType === 'video' ? 'Video başarıyla yüklendi.' : 'Resim başarıyla yüklendi.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            try {
                                var jsonResponse = JSON.parse(response);
                                if(!jsonResponse.success) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Yükleme Hatası!',
                                        text: jsonResponse.message || 'Bir hata oluştu.',
                                        confirmButtonText: 'Tamam'
                                    });
                                }
                            } catch(e) {
                                console.error("Response parsing error:", e);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Yükleme Hatası!',
                                    text: 'Beklenmeyen bir hata oluştu.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        }
                    });
                    
                    this.on("error", function(file, errorMessage) {
                        file.previewElement.classList.add("dz-error");
                        
                        var errorMsg = errorMessage;
                        if(typeof errorMessage === 'string') {
                            if(errorMessage === "HATA_DOSYA_TURU") {
                                errorMsg = "Geçersiz dosya formatı. Lütfen desteklenen bir format seçin.";
                            } else if(errorMessage === "HATA_DOSYA_BOYUTU") {
                                errorMsg = "Dosya boyutu çok büyük. Maksimum " + maxFileSizeValue + "MB yükleyebilirsiniz.";
                            }
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Yükleme Hatası!',
                            text: errorMsg,
                            confirmButtonText: 'Tamam'
                        });
                    });
                }
            });
        } catch(e) {
            console.error("Dropzone initialization error:", e);
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Yükleme arayüzü başlatılamadı: ' + e.message,
                confirmButtonText: 'Tamam'
            });
        }
    }
    
    // İlk yüklemede Dropzone'u başlat
    updateDropzoneConfig();
});

// Resim/Video silme fonksiyonu
function resimSil(ID) {
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
            $.ajax({
                url: '<?=SITE?>ajax.php',
                type: 'POST',
                data: {
                    islem: 'resim-sil',
                    ID: ID
                },
                success: function(response) {
                    if(response === "OK") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: 'Medya başarıyla silindi.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Silme işlemi başarısız oldu.',
                            confirmButtonText: 'Tamam'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Sunucu hatası oluştu.',
                        confirmButtonText: 'Tamam'
                    });
                }
            });
        }
    });
    return false;
}

// Video DB kaydı ekleme JS
function addVideoToDB(filename) {
    // Filename parametresini kontrol et
    if (!filename || filename === '') {
        Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Dosya adı belirtilmedi!'
        });
        return; // Boş filename ile devam etme
    }
    
    fetch('<?=SITE?>ajax.php?p=add-video-to-db', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'filename=' + encodeURIComponent(filename) + '&tablo=<?=$tablo?>&ID=<?=$ID?>'
    })
    .then(response => response.text())
    .then(data => {
        try {
            // JSON yanıtını parse et
            const jsonData = JSON.parse(data);
            if(jsonData.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: jsonData.message || 'Video başarıyla veritabanına eklendi.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: jsonData.message || 'İşlem başarısız oldu.'
                });
            }
        } catch (e) {
            // JSON parse hatası - düz metin yanıtı
            console.error('JSON Parse hatası:', e);
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Yanıt işlenirken bir hata oluştu: ' + data
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
}

// Video dosya silme JS fonksiyonu
function deleteVideoFile(filename) {
    // Filename parametresini kontrol et
    if (!filename || filename === '') {
        Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Dosya adı belirtilmedi!'
        });
        return; // Boş filename ile devam etme
    }
    
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
            fetch('<?=SITE?>ajax.php?p=delete-video-file', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'filename=' + encodeURIComponent(filename)
            })
            .then(response => response.text())
            .then(data => {
                try {
                    // JSON yanıtını parse et
                    const jsonData = JSON.parse(data);
                    if(jsonData.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: jsonData.message || 'Video dosyası başarıyla silindi.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: jsonData.message || 'Silme işlemi başarısız oldu.'
                        });
                    }
                } catch (e) {
                    // JSON parse hatası - düz metin yanıtı
                    console.error('JSON Parse hatası:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Yanıt işlenirken bir hata oluştu: ' + data
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
        }
    });
}
</script>
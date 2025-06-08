<?php
if(!empty($_SESSION["ID"]) && !empty($_SESSION["adsoyad"]) && !empty($_SESSION["mail"])) {
    // Dizin yolunu belirleyelim
    $galleryPath = "../assets/img/gallery/";
    
    // PHP bilgileri
    $phpVersion = phpversion();
    $maxUploadSize = ini_get('upload_max_filesize');
    $maxPostSize = ini_get('post_max_size');
    $maxExecutionTime = ini_get('max_execution_time');
    
    // Dizin bilgilerini kontrol edelim
    $dirExists = file_exists($galleryPath);
    $dirIsWritable = is_writable($galleryPath);
    
    // Dizin yoksa oluşturmayı deneyelim
    $dirCreated = false;
    if(!$dirExists) {
        $dirCreated = mkdir($galleryPath, 0777, true);
    }
    
    // Eğer dosya yüklenirse
    $uploadSuccess = false;
    $uploadError = "";
    $uploadedFile = "";
    
    if(isset($_POST["submit"])) {
        if(isset($_FILES["test_file"]) && $_FILES["test_file"]["error"] == 0) {
            $filename = time() . "_" . basename($_FILES["test_file"]["name"]);
            $targetFile = $galleryPath . $filename;
            
            // Dosya tipini kontrol et
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = array("jpg", "jpeg", "png", "gif");
            
            if(in_array($imageFileType, $allowedTypes)) {
                // Yüklemeyi dene
                if(move_uploaded_file($_FILES["test_file"]["tmp_name"], $targetFile)) {
                    $uploadSuccess = true;
                    $uploadedFile = $targetFile;
                    
                    // Veritabanına kaydet
                    try {
                        $insertResult = $VT->SorguCalistir("INSERT INTO gallery_images", 
                            "SET image_file=?, category_id=?, durum=?, tarih=?", 
                            array($filename, 0, 1, date("Y-m-d H:i:s"))
                        );
                        
                        if($insertResult === false) {
                            $uploadError = "Dosya yüklendi ama veritabanına kaydedilemedi.";
                        }
                    } catch (Exception $e) {
                        $uploadError = "Veritabanı hatası: " . $e->getMessage();
                    }
                } else {
                    $uploadError = "Dosya yüklenirken bir hata oluştu. move_uploaded_file başarısız oldu.";
                }
            } else {
                $uploadError = "Sadece JPG, JPEG, PNG & GIF dosyaları izin verilmektedir.";
            }
        } else {
            $uploadError = "Dosya yüklenirken bir hata oluştu. Hata kodu: " . $_FILES["test_file"]["error"];
        }
    }
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Yükleme Testi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE?>">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active">Yükleme Testi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- PHP Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">PHP Bilgileri</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>PHP Versiyonu:</strong> <?=$phpVersion?></li>
                        <li><strong>Max Upload Size:</strong> <?=$maxUploadSize?></li>
                        <li><strong>Max Post Size:</strong> <?=$maxPostSize?></li>
                        <li><strong>Max Execution Time:</strong> <?=$maxExecutionTime?> saniye</li>
                    </ul>
                </div>
            </div>
            
            <!-- Dizin Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dizin Bilgileri</h3>
                </div>
                <div class="card-body">
                    <p><strong>Galeri Dizini:</strong> <?=$galleryPath?></p>
                    <ul>
                        <li><strong>Dizin Var mı?:</strong> <?=$dirExists ? '<span class="text-success">Evet</span>' : '<span class="text-danger">Hayır</span>'?></li>
                        <?php if($dirCreated): ?>
                        <li><strong>Dizin Oluşturuldu mu?:</strong> <span class="text-success">Evet</span></li>
                        <?php endif; ?>
                        <li><strong>Yazılabilir mi?:</strong> <?=$dirIsWritable ? '<span class="text-success">Evet</span>' : '<span class="text-danger">Hayır</span>'?></li>
                    </ul>
                    
                    <?php if(!$dirExists || !$dirIsWritable): ?>
                    <div class="alert alert-warning">
                        <p><strong>Uyarı:</strong> Galeri dizini sorunlu. Dizin yok veya yazma izni yok. Aşağıdaki adımları izleyin:</p>
                        <ol>
                            <li>Dizinin var olduğundan emin olun: <code><?=$galleryPath?></code></li>
                            <li>Dizinin yazma izinlerine sahip olduğundan emin olun (chmod 777)</li>
                        </ol>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Test Yükleme Formu -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Test Dosyası Yükleme</h3>
                </div>
                <div class="card-body">
                    <?php if($uploadSuccess): ?>
                    <div class="alert alert-success">
                        <p><strong>Başarılı!</strong> Dosya başarıyla yüklendi: <?=$uploadedFile?></p>
                        <p>Yüklenen dosya:</p>
                        <img src="<?=$uploadedFile?>" alt="Uploaded Test File" style="max-width: 300px;">
                    </div>
                    <?php elseif(!empty($uploadError)): ?>
                    <div class="alert alert-danger">
                        <p><strong>Hata:</strong> <?=$uploadError?></p>
                    </div>
                    <?php endif; ?>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="test_file">Test Dosyası Seçin:</label>
                            <input type="file" name="test_file" id="test_file" class="form-control-file">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Yükle</button>
                    </form>
                </div>
            </div>
            
            <!-- Veritabanı Tabloları -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Veritabanı Tabloları</h3>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        // Tabloları kontrol edelim
                        $tablesExist = false;
                        $galleryImagesCheck = $VT->SorguCalistir("SHOW TABLES LIKE 'gallery_images'", "", array(), 2);
                        
                        if($galleryImagesCheck != false && count($galleryImagesCheck) > 0) {
                            $tablesExist = true;
                            
                            // Son eklenen resimleri göster
                            $latestImages = $VT->VeriGetir("gallery_images", "", array(), "ORDER BY ID DESC LIMIT 5");
                            
                            if($latestImages != false) {
                                echo "<h4>Son Eklenen Resimler:</h4>";
                                echo "<div class='row'>";
                                foreach($latestImages as $image) {
                                    echo '<div class="col-md-3">';
                                    echo '<div class="card">';
                                    echo '<img src="' . $galleryPath . $image["image_file"] . '" class="card-img-top" alt="Gallery Image">';
                                    echo '<div class="card-body">';
                                    echo '<p class="card-text">ID: ' . $image["ID"] . '<br>Dosya: ' . $image["image_file"] . '<br>Tarih: ' . $image["tarih"] . '</p>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-info'>Henüz resim eklenmemiş.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>gallery_images tablosu bulunamadı. Lütfen önce <a href='".SITE."db-check'>Veritabanı Kontrol</a> sayfasından tabloları oluşturun.</div>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger'>Veritabanı hatası: " . $e->getMessage() . "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
} else {
    echo '<meta http-equiv="refresh" content="0; url='.SITE.'giris-yap">';
}
?> 
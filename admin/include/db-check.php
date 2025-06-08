<?php
if(!empty($_SESSION["ID"]) && !empty($_SESSION["adsoyad"]) && !empty($_SESSION["mail"])) {
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Veritabanı Kontrol</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Ana Sayfa</a></li>
              <li class="breadcrumb-item active">Veritabanı Kontrol</li>
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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Galeri Veritabanı Tabloları</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php
                    // Veritabanındaki tabloları listele
                    try {
                        // $VT sınıfının bağlantısını kullanarak tabloları kontrol edelim
                        
                        // Check gallery_categories table
                        $categoriesTableExists = false;
                        $categoriesCheck = $VT->SorguCalistir("SHOW TABLES LIKE 'gallery_categories'", "", array(), 2);
                        if($categoriesCheck != false && count($categoriesCheck) > 0) {
                            $categoriesTableExists = true;
                        }
                        
                        // Check gallery_categories_dil table
                        $categoriesDilTableExists = false;
                        $categoriesDilCheck = $VT->SorguCalistir("SHOW TABLES LIKE 'gallery_categories_dil'", "", array(), 2);
                        if($categoriesDilCheck != false && count($categoriesDilCheck) > 0) {
                            $categoriesDilTableExists = true;
                        }
                        
                        // Check gallery_images table
                        $imagesTableExists = false;
                        $imagesCheck = $VT->SorguCalistir("SHOW TABLES LIKE 'gallery_images'", "", array(), 2);
                        if($imagesCheck != false && count($imagesCheck) > 0) {
                            $imagesTableExists = true;
                        }
                        
                        // Display results
                        echo "<h4>Tablo Durumu:</h4>";
                        echo "<ul>";
                        echo "<li>gallery_categories: " . ($categoriesTableExists ? "<span class='text-success'>Var</span>" : "<span class='text-danger'>Yok</span>") . "</li>";
                        echo "<li>gallery_categories_dil: " . ($categoriesDilTableExists ? "<span class='text-success'>Var</span>" : "<span class='text-danger'>Yok</span>") . "</li>";
                        echo "<li>gallery_images: " . ($imagesTableExists ? "<span class='text-success'>Var</span>" : "<span class='text-danger'>Yok</span>") . "</li>";
                        echo "</ul>";
                        
                        // Offer to create missing tables
                        if(!$categoriesTableExists || !$categoriesDilTableExists || !$imagesTableExists) {
                            echo "<h4>Eksik Tabloları Oluştur</h4>";
                            echo "<p>Aşağıdaki SQL kodunu PHP MyAdmin'de çalıştırın:</p>";
                            echo "<pre style='background-color: #f4f4f4; padding: 10px; border-radius: 5px;'>";
                            echo "-- Galeri kategorileri için tablo\n";
                            echo "CREATE TABLE IF NOT EXISTS `gallery_categories` (\n";
                            echo "  `ID` int(11) NOT NULL AUTO_INCREMENT,\n";
                            echo "  `title` varchar(255) NOT NULL,\n";
                            echo "  `sira` int(11) NOT NULL DEFAULT 0,\n";
                            echo "  `durum` tinyint(1) NOT NULL DEFAULT 1,\n";
                            echo "  `tarih` date NOT NULL,\n";
                            echo "  PRIMARY KEY (`ID`)\n";
                            echo ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;\n\n";
                            
                            echo "-- Galeri kategorileri için dil çevirileri (çoklu dil desteği için)\n";
                            echo "CREATE TABLE IF NOT EXISTS `gallery_categories_dil` (\n";
                            echo "  `ID` int(11) NOT NULL AUTO_INCREMENT,\n";
                            echo "  `category_id` int(11) NOT NULL,\n";
                            echo "  `dil` varchar(10) NOT NULL,\n";
                            echo "  `title` varchar(255) NOT NULL,\n";
                            echo "  PRIMARY KEY (`ID`),\n";
                            echo "  KEY `category_id` (`category_id`)\n";
                            echo ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;\n\n";
                            
                            echo "-- Galeri resimleri için tablo\n";
                            echo "CREATE TABLE IF NOT EXISTS `gallery_images` (\n";
                            echo "  `ID` int(11) NOT NULL AUTO_INCREMENT,\n";
                            echo "  `category_id` int(11) NOT NULL DEFAULT 0,\n";
                            echo "  `image_file` varchar(255) NOT NULL,\n";
                            echo "  `title` varchar(255) DEFAULT NULL,\n";
                            echo "  `alt_text` varchar(255) DEFAULT NULL,\n";
                            echo "  `durum` tinyint(1) NOT NULL DEFAULT 1,\n";
                            echo "  `tarih` datetime NOT NULL,\n";
                            echo "  PRIMARY KEY (`ID`),\n";
                            echo "  KEY `category_id` (`category_id`)\n";
                            echo ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
                            echo "</pre>";
                            
                            if(isset($_GET["create"]) && $_GET["create"] == "1") {
                                try {
                                    // Her tabloyu ayrı ayrı oluşturalım
                                    if(!$categoriesTableExists) {
                                        $categoryCreate = $VT->SorguCalistir("CREATE TABLE IF NOT EXISTS `gallery_categories` (
                                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                                          `title` varchar(255) NOT NULL,
                                          `sira` int(11) NOT NULL DEFAULT 0,
                                          `durum` tinyint(1) NOT NULL DEFAULT 1,
                                          `tarih` date NOT NULL,
                                          PRIMARY KEY (`ID`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;", "", array());
                                    }
                                    
                                    if(!$categoriesDilTableExists) {
                                        $categoryDilCreate = $VT->SorguCalistir("CREATE TABLE IF NOT EXISTS `gallery_categories_dil` (
                                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                                          `category_id` int(11) NOT NULL,
                                          `dil` varchar(10) NOT NULL,
                                          `title` varchar(255) NOT NULL,
                                          PRIMARY KEY (`ID`),
                                          KEY `category_id` (`category_id`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;", "", array());
                                    }
                                    
                                    if(!$imagesTableExists) {
                                        $imagesCreate = $VT->SorguCalistir("CREATE TABLE IF NOT EXISTS `gallery_images` (
                                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                                          `category_id` int(11) NOT NULL DEFAULT 0,
                                          `image_file` varchar(255) NOT NULL,
                                          `title` varchar(255) DEFAULT NULL,
                                          `alt_text` varchar(255) DEFAULT NULL,
                                          `durum` tinyint(1) NOT NULL DEFAULT 1,
                                          `tarih` datetime NOT NULL,
                                          PRIMARY KEY (`ID`),
                                          KEY `category_id` (`category_id`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;", "", array());
                                    }
                                    
                                    echo "<div class='alert alert-success'>Tablolar başarıyla oluşturuldu. Sayfayı yenileyin.</div>";
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger'>Tablolar oluşturulurken hata: " . $e->getMessage() . "</div>";
                                }
                            } else {
                                echo "<a href='?sayfa=db-check&create=1' class='btn btn-primary'>Tabloları Otomatik Oluştur</a>";
                            }
                        } else {
                            echo "<div class='alert alert-success'>Tüm gerekli tablolar mevcut. Sistem hazır.</div>";
                        }
                        
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger'>Veritabanı hatası: " . $e->getMessage() . "</div>";
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
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
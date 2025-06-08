<?php
if(!empty($_SESSION["ID"]) && !empty($_SESSION["adsoyad"]) && !empty($_SESSION["mail"])) {
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Veritabanı Durum Kontrolü</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE?>">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active">Veritabanı Durum Kontrolü</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Veritabanı Durum Raporu</h3>
                </div>
                <div class="card-body">
                    <?php 
                    // Tüm hataları görüntüle
                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);
                    
                    echo "<h4>Veritabanı Bağlantı Bilgileri:</h4>";
                    echo "<p>Admin tarafında, VT sınıfı nesnesi VT.php dosyasından çağrılıyor.</p>";
                    
                    // VT sınıfını kontrol et
                    echo "<h4>VT Sınıfı Kontrolü:</h4>";
                    echo "<pre>";
                    echo "VT Sınıfı Türü: " . get_class($VT) . "\n";
                    echo "</pre>";
                    
                    // VT sınıfı metodlarını listele
                    echo "<h4>VT Sınıfı Metodları:</h4>";
                    echo "<pre>";
                    print_r(get_class_methods($VT));
                    echo "</pre>";
                    
                    // Mevcut tüm tabloları listeleyelim
                    echo "<h4>Veritabanı Tabloları:</h4>";
                    try {
                        $tables = $VT->SorguCalistir("SHOW TABLES", "", array(), 2);
                        if($tables !== false) {
                            echo "<ul>";
                            foreach($tables as $table) {
                                $tableName = $table[0]; // İlk sütun tablo adını içerir
                                echo "<li>" . $tableName . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p class='text-danger'>Tablolar listelenemedi.</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='text-danger'>Hata: " . $e->getMessage() . "</p>";
                    }
                    
                    // Gallery tablolarını özel olarak kontrol edelim
                    echo "<h4>Galeri Tabloları Kontrolü:</h4>";
                    $galleryTables = array(
                        "gallery_categories",
                        "gallery_categories_dil",
                        "gallery_images"
                    );
                    
                    foreach($galleryTables as $tableName) {
                        echo "<h5>Tablo: " . $tableName . "</h5>";
                        try {
                            $exists = $VT->SorguCalistir("SHOW TABLES LIKE ?", "", array($tableName), 2);
                            if($exists !== false && count($exists) > 0) {
                                echo "<p class='text-success'>Tablo mevcut.</p>";
                                
                                // Tablo yapısını göster
                                $structure = $VT->SorguCalistir("DESCRIBE " . $tableName, "", array(), 2);
                                if($structure !== false) {
                                    echo "<table class='table table-bordered'>";
                                    echo "<thead><tr><th>Alan</th><th>Tür</th><th>Null</th><th>Anahtar</th><th>Varsayılan</th><th>Ekstra</th></tr></thead>";
                                    echo "<tbody>";
                                    foreach($structure as $column) {
                                        echo "<tr>";
                                        echo "<td>" . $column["Field"] . "</td>";
                                        echo "<td>" . $column["Type"] . "</td>";
                                        echo "<td>" . $column["Null"] . "</td>";
                                        echo "<td>" . $column["Key"] . "</td>";
                                        echo "<td>" . $column["Default"] . "</td>";
                                        echo "<td>" . $column["Extra"] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                    
                                    // Kayıt sayısını göster
                                    $count = $VT->SorguCalistir("SELECT COUNT(*) as total FROM " . $tableName, "", array(), 1);
                                    if($count !== false) {
                                        echo "<p>Kayıt Sayısı: " . $count["total"] . "</p>";
                                    }
                                } else {
                                    echo "<p class='text-danger'>Tablo yapısı alınamadı.</p>";
                                }
                            } else {
                                echo "<p class='text-danger'>Tablo mevcut değil.</p>";
                                
                                // Tablo oluşturma sorguları
                                if($tableName == "gallery_categories") {
                                    echo "<pre>CREATE TABLE IF NOT EXISTS `gallery_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sira` int(11) NOT NULL DEFAULT 0,
  `durum` tinyint(1) NOT NULL DEFAULT 1,
  `tarih` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;</pre>";
                                } elseif($tableName == "gallery_categories_dil") {
                                    echo "<pre>CREATE TABLE IF NOT EXISTS `gallery_categories_dil` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `dil` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;</pre>";
                                } elseif($tableName == "gallery_images") {
                                    echo "<pre>CREATE TABLE IF NOT EXISTS `gallery_images` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `image_file` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `durum` tinyint(1) NOT NULL DEFAULT 1,
  `tarih` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;</pre>";
                                }
                            }
                        } catch (Exception $e) {
                            echo "<p class='text-danger'>Hata: " . $e->getMessage() . "</p>";
                        }
                    }
                    ?>
                    
                    <h4>Veritabanı Adı Kontrolü:</h4>
                    <?php
                    try {
                        $dbName = $VT->SorguCalistir("SELECT DATABASE() as db_name", "", array(), 1);
                        if($dbName !== false) {
                            echo "<p>Şu anda kullanılan veritabanı: <strong>" . $dbName["db_name"] . "</strong></p>";
                        } else {
                            echo "<p class='text-danger'>Veritabanı adı alınamadı.</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='text-danger'>Hata: " . $e->getMessage() . "</p>";
                    }
                    ?>
                    
                    <h4>Önceki Çalışan SQL Sorgularını Test Et:</h4>
                    <?php
                    try {
                        $testQuery = $VT->VeriGetir("ayarlar", "WHERE ID=?", array(1), "ORDER BY ID ASC", 1);
                        if($testQuery !== false) {
                            echo "<p class='text-success'>Basit sorgu testi başarılı (ayarlar tablosu).</p>";
                        } else {
                            echo "<p class='text-danger'>Basit sorgu testi başarısız (ayarlar tablosu).</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='text-danger'>Basit sorgu hatası: " . $e->getMessage() . "</p>";
                    }
                    ?>
                    
                    <h4>Manuel SQL Sorgusu Çalıştır:</h4>
                    <form method="post" action="">
                        <div class="form-group">
                            <label>SQL Sorgusu:</label>
                            <textarea name="sql_query" class="form-control" rows="5"><?php echo isset($_POST['sql_query']) ? htmlspecialchars($_POST['sql_query']) : "SHOW TABLES"; ?></textarea>
                        </div>
                        <button type="submit" name="run_query" class="btn btn-primary">Çalıştır</button>
                    </form>
                    
                    <?php
                    if(isset($_POST['run_query']) && !empty($_POST['sql_query'])) {
                        $query = $_POST['sql_query'];
                        echo "<h5>Sorgu Sonucu:</h5>";
                        try {
                            $result = $VT->SorguCalistir($query, "", array(), 2);
                            if($result !== false) {
                                echo "<pre>";
                                print_r($result);
                                echo "</pre>";
                            } else {
                                echo "<p class='text-danger'>Sorgu sonuçsuz döndü veya bir hata oluştu.</p>";
                            }
                        } catch (Exception $e) {
                            echo "<p class='text-danger'>Sorgu hatası: " . $e->getMessage() . "</p>";
                        }
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
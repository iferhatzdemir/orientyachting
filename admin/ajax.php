<?php
@session_start();
@ob_start();
define("DATA","data/");
define("SAYFA","include/");
define("SINIF","class/");

// Include our error handling helpers
if (file_exists("../helpers/error_helper.php")) {
    include_once("../helpers/error_helper.php");
} else {
    // Define fallback if helpers don't exist
    if (!function_exists('log_error')) {
        function log_error($message, $type = 'general', $context = []) {
            error_log("[$type] $message");
        }
    }
    
    if (!function_exists('ajax_error')) {
        function ajax_error($message, $technical_message = '', $status_code = 400) {
            if (!empty($technical_message)) {
                error_log($technical_message);
            }
            echo json_encode(['success' => false, 'message' => $message]);
            exit();
        }
    }
}

include_once(DATA."baglanti.php");
define("SITE",$siteURL."admin/");
define("ANASITE",$siteURL);
if(!empty($_POST["tablo"]) && !empty($_FILES["file"]) && !empty($_POST["ID"]))
{
    $tablo=$VT->filter($_POST["tablo"]);
    $ID=$VT->filter($_POST["ID"]);
    $mediaType = isset($_POST["mediaType"]) ? $_POST["mediaType"] : "image";
    
    if($tablo == "yachts") {
        // Yat resimleri için özel işlem
        if($mediaType == "video") {
            // Video dosyası boyut kontrolü (100MB)
            $maxVideoSize = 104857600; // 100MB in bytes
            if($_FILES["file"]["size"][0] > $maxVideoSize) {
                echo json_encode([
                    'success' => false,
                    'message' => "Video dosyası çok büyük. Maksimum 100MB yükleyebilirsiniz."
                ]);
                exit;
            }
            
            // Process the video file
            $videoUploadPath = "../images/yachts/videos/";
            // Create directory if it doesn't exist
            if(!file_exists($videoUploadPath)) {
                if(!mkdir($videoUploadPath, 0777, true)) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Video dizini oluşturulamadı. Lütfen sistem yöneticinize başvurun."
                    ]);
                    exit;
                }
            }
            // Explicitly set "video" as the type parameter
            $resim=$VT->uploadMulti("file",$tablo,$ID,$videoUploadPath,"video");
        } else {
            // Standard image upload
            $resim=$VT->uploadMulti("file",$tablo,$ID,"../images/yachts/");
        }
    } else {
        // For other tables, check if it's video or image
        if($mediaType == "video") {
            $videoUploadPath = "../images/resimler/videos/";
            // Create directory if it doesn't exist
            if(!file_exists($videoUploadPath)) {
                if(!mkdir($videoUploadPath, 0777, true)) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Video dizini oluşturulamadı. Lütfen sistem yöneticinize başvurun."
                    ]);
                    exit;
                }
            }
            // Explicitly set "video" as the type parameter
            $resim=$VT->uploadMulti("file",$tablo,$ID,$videoUploadPath,"video");
        } else {
            $resim=$VT->uploadMulti("file",$tablo,$ID,"../images/resimler/");
        }
    }
    
    if ($resim) {
        echo "OK";
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Medya yüklenirken bir hata oluştu. Lütfen tekrar deneyin."
        ]);
    }
    exit;
}

if($_POST)
{
	// Galeri resim silme işlemi
	if(!empty($_POST["islem"]) && $_POST["islem"]=="galeri-resim-sil")
	{
		try {
			if(empty($_POST["resimID"])) {
				echo json_encode([
                    'success' => false,
                    'message' => "Resim ID bilgisi bulunamadı."
                ]);
				exit;
			}
			
			$resimID = $VT->filter($_POST["resimID"]);
			
			// Resmin var olup olmadığını kontrol et
			$resim = $VT->VeriGetir("resimler", "WHERE ID=?", array($resimID), "ORDER BY ID ASC", 1);
			if($resim != false) {
				// Resmi diskten sil
				$resim_path = $_SERVER["DOCUMENT_ROOT"].'/images/resimler/'.$resim[0]["resim"];
				$deleted = false;
				
				// Try to delete with original extension first
				if(file_exists($resim_path)) {
					if(@unlink($resim_path)) {
						$deleted = true;
						log_error("Resim başarıyla silindi", "image_delete", [
						    'path' => $resim_path,
						    'id' => $resimID
						]);
					} else {
						log_error("Resim silinemedi (orijinal uzantı)", "image_delete_failed", [
						    'path' => $resim_path,
						    'id' => $resimID,
						    'error' => error_get_last()
						]);
					}
				} else {
					log_error("Resim bulunamadı (orijinal uzantı)", "image_not_found", [
					    'path' => $resim_path,
					    'id' => $resimID
					]);
				}
				
				// Even if file deletion fails, always remove the database record
				$sil = $VT->SorguCalistir("DELETE FROM resimler", "WHERE ID=?", array($resimID));
				if($sil !== false) {
					log_error("Resim veritabanından silindi", "image_db_delete", ['id' => $resimID]);
					echo json_encode([
                        'success' => true,
                        'message' => "Resim başarıyla silindi."
                    ]);
				} else {
					log_error("Resim veritabanından silinemedi", "image_db_delete_failed", ['id' => $resimID]);
					echo json_encode([
                        'success' => false,
                        'message' => "Resim dosyası silindi ancak veritabanı kaydı güncellenemedi."
                    ]);
				}
			} else {
				echo json_encode([
                    'success' => false,
                    'message' => "Belirtilen resim bulunamadı."
                ]);
			}
		} catch(PDOException $e) {
			log_error("Galeri resim silme - PDO hatası: " . $e->getMessage(), "db_error");
			echo json_encode([
                'success' => false,
                'message' => "Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin."
            ]);
		} catch(Exception $e) {
			log_error("Galeri resim silme - Genel hata: " . $e->getMessage(), "general_error");
			echo json_encode([
                'success' => false,
                'message' => "İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin."
            ]);
		}
		exit;
	}
	
	// Module deletion handler
	if(!empty($_POST["action"]) && $_POST["action"]=="deleteModule" && !empty($_POST["id"]))
	{
		$modulID = $VT->filter($_POST["id"]);
		
		try {
			// Verify module exists
			$modulKontrol = $VT->VeriGetir("moduller", "WHERE ID=?", array($modulID), "ORDER BY ID ASC", 1);
			
			if($modulKontrol != false) {
				// Delete the module
				$silme = $VT->SorguCalistir("DELETE FROM moduller", "WHERE ID=?", array($modulID));
				
				if($silme !== false) {
					echo json_encode([
						'success' => true,
						'message' => 'Modül başarıyla silindi.'
					]);
				} else {
					echo json_encode([
						'success' => false,
						'message' => 'Modül silinirken bir hata oluştu.'
					]);
				}
			} else {
				echo json_encode([
					'success' => false,
					'message' => 'Modül bulunamadı.'
				]);
			}
		} catch(Exception $e) {
			log_error("Modül silme hatası: " . $e->getMessage(), "module_delete", [
				'id' => $modulID
			]);
			
			echo json_encode([
				'success' => false,
				'message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()
			]);
		}
		
		exit;
	}
	
	// Galeri listesi getirme 
	else if(!empty($_POST["islem"]) && $_POST["islem"]=="galeri-listesi-getir")
	{
		if(empty($_POST["galeri_id"])) {
			echo "ID_YOK";
			exit;
		}
		
		$galeri_id = $VT->filter($_POST["galeri_id"]);
		
		if(!empty($galeri_id))
		{
			$resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $galeri_id), "ORDER BY ID DESC");
			$galeri = $VT->VeriGetir("galeri", "WHERE ID=?", array($galeri_id), "ORDER BY ID ASC", 1);
			
			if($resimler != false) {
				foreach($resimler as $resim) {
					// Doğrudan resim yolunu kullan, uzantı değişikliği yapma
					$resimYolu = ANASITE."images/resimler/".$resim["resim"];
					
					// Önbellek sorunlarını önlemek için
					$cacheBuster = "?v=" . time() . rand(1000, 9999);
					
					echo '<div class="col-md-3 text-center mb-4 galeri-item">
							<div class="galeri-resim-container">
								<a href="'.$resimYolu.'" data-fancybox="gallery" data-caption="'.($galeri ? $galeri[0]["baslik"] : "Galeri").' - Resim">
									<img src="'.$resimYolu.$cacheBuster.'" class="img-fluid img-thumbnail galeri-resim" 
										onerror="this.src=\''.SITE.'dist/img/no-image.svg\'; console.error(\'Resim yüklenemedi: '.$resimYolu.'\');"
										alt="Galeri Resim" loading="lazy">
								</a>
								<div class="galeri-resim-islemler mt-2">
									<a href="'.SITE.'galeri-resim-sil/'.$galeri_id.'/'.$resim["ID"].'" class="btn btn-danger btn-sm" onclick="return resimSil(this.href);"><i class="fas fa-trash"></i> Sil</a>
								</div>
							</div>
						</div>';
				}
			} else {
				echo '<div class="col-md-12"><div class="alert alert-warning">Bu galeriye henüz resim eklenmemiş.</div></div>';
			}
		}
		else
		{
			echo '<div class="col-md-12"><div class="alert alert-danger">Galeri ID bulunamadı!</div></div>';
		}
		exit;
	}
	
	// Yat özellikleri durum değiştirme işlemi
	else if(!empty($_POST["islem"]) && $_POST["islem"] == "durumDegistir" && !empty($_POST["tablo"]) && !empty($_POST["ID"]) && !empty($_POST["durum"]))
	{
		$tablo=$VT->filter($_POST["tablo"]);
		$ID=$VT->filter($_POST["ID"]);
		$durum=$VT->filter($_POST["durum"]);
		$guncelle=$VT->SorguCalistir("UPDATE ".$tablo,"SET durum=? WHERE ID=?",array($durum,$ID),1);
		if($guncelle!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
		exit;
	}
	
	// Yat özelliği silme işlemi
	if(!empty($_POST["islem"]) && $_POST["islem"] == "ozellikSil" && !empty($_POST["ID"]))
	{
		$ID=$VT->filter($_POST["ID"]);
		
		// Önce dil çevirilerini sil
		$dilSil = $VT->SorguCalistir("DELETE FROM yacht_features_dil", "WHERE feature_id=?", array($ID));
		
		// Yatlardaki özellik bağlantılarını sil
		$pivotSil = $VT->SorguCalistir("DELETE FROM yacht_features_pivot", "WHERE feature_id=?", array($ID));
		
		// Sonra ana özelliği sil
		$ozellikSil = $VT->SorguCalistir("DELETE FROM yacht_features", "WHERE ID=?", array($ID));
		
		if($ozellikSil!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
		exit;
	}
	
	// Yat tipi silme işlemi
	if(!empty($_POST["islem"]) && $_POST["islem"] == "tipSil" && !empty($_POST["ID"]))
	{
		$ID=$VT->filter($_POST["ID"]);
		
		// Önce dil çevirilerini sil
		$dilSil = $VT->SorguCalistir("DELETE FROM yacht_types_dil", "WHERE type_id=?", array($ID));
		
		// Bu tip ile ilişkili yatlar varsa tipini güncelle (opsiyonel güvenlik)
		$yatGuncelle = $VT->SorguCalistir("UPDATE yachts", "SET type_id=0 WHERE type_id=?", array($ID));
		
		// Sonra ana tipi sil
		$tipSil = $VT->SorguCalistir("DELETE FROM yacht_types", "WHERE ID=?", array($ID));
		
		if($tipSil!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
		exit;
	}
	
	// Yat lokasyonu silme işlemi
	if(!empty($_POST["islem"]) && $_POST["islem"] == "lokasyonSil" && !empty($_POST["ID"]))
	{
		$ID=$VT->filter($_POST["ID"]);
		
		// Önce dil çevirilerini sil
		$dilSil = $VT->SorguCalistir("DELETE FROM yacht_locations_dil", "WHERE location_id=?", array($ID));
		
		// Bu lokasyon ile ilişkili yatlar varsa lokasyonunu güncelle (opsiyonel güvenlik)
		$yatGuncelle = $VT->SorguCalistir("UPDATE yachts", "SET location_id=0 WHERE location_id=?", array($ID));
		
		// Sonra ana lokasyonu sil
		$lokasyonSil = $VT->SorguCalistir("DELETE FROM yacht_locations", "WHERE ID=?", array($ID));
		
		if($lokasyonSil!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
		exit;
	}
	
	if(!empty($_POST["tablo"]) && !empty($_POST["ID"]) && !empty($_POST["durum"]))
	{
		$tablo=$VT->filter($_POST["tablo"]);
		$ID=$VT->filter($_POST["ID"]);
		$durum=$VT->filter($_POST["durum"]);
		$guncelle=$VT->SorguCalistir("UPDATE ".$tablo,"SET durum=? WHERE ID=?",array($durum,$ID),1);
		if($guncelle!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
	}
	else if(!empty($_POST["tablo"]) && !empty($_POST["ID"]) && !empty($_POST["vitrindurum"]))
	{
		$tablo=$VT->filter($_POST["tablo"]);
		$ID=$VT->filter($_POST["ID"]);
		$durum=$VT->filter($_POST["vitrindurum"]);
		$guncelle=$VT->SorguCalistir("UPDATE ".$tablo,"SET vitrindurum=? WHERE ID=?",array($durum,$ID),1);
		if($guncelle!=false)
		{
			echo "TAMAM";
		}
		else
		{
			echo "HATA";
		}
	}
	else if(!empty($_POST["varyasyon1"]) && !empty($_POST["secenek1"]))
	{
		$varyasyon1=$VT->filter($_POST["varyasyon1"]);
		if(!empty($_POST["varyasyon2"]) && !empty($_POST["secenek2"]))
		{
			$varyasyon2=$VT->filter($_POST["varyasyon2"]);
			$_SESSION["varyasyonlar"]=array($varyasyon1,$varyasyon2);
			$_SESSION["secenekler"]=array($varyasyon1=>$_POST["secenek1"],$varyasyon2=>$_POST["secenek2"]);
			?>
			<table class="table">
				
				
					<?php
					for($i=0;$i<count($_POST["secenek1"]);$i++)
					{
						echo '<tr>';
						for($x=0;$x<count($_POST["secenek2"]);$x++)
						{
							?>
							<td><?=$_POST["secenek1"][$i]?> <?=$varyasyon1?> <?=$_POST["secenek2"][$x]?> <?=$varyasyon2?></td>
							<td><input type="number" value="1" name="stok[]" min="1"></td>
							<?php
						}
						echo '</tr>';
						
					}
					?>
				

			</table>

			<?php
		}
		else
		{
			$_SESSION["varyasyonlar"]=array($varyasyon1);
			$_SESSION["secenekler"]=array($varyasyon1=>$_POST["secenek1"]);
			?>
			<table class="table">
				
				
					<?php
					for($i=0;$i<count($_POST["secenek1"]);$i++)
					{
						?>
						<tr>
						<td><?=$_POST["secenek1"][$i]?> <?=$varyasyon1?></td>
						<td><input class="form-control" type="number" value="1" name="stok[]" min="1"></td>
						</tr>
						<?php
					}
					?>
				

			</table>

			<?php

		}
	}
	else if(!empty($_POST["islem"])) {
		// Özel işlemler
		if($_POST["islem"]=="galeri-ekle")
		{
		  $baslik=$VT->filter($_POST["baslik"]);
		  $seflink= $VT->seflink($baslik);
		  $kategori=$VT->filter($_POST["kategori"]);
		  $sira=$VT->filter($_POST["sira"]);
		  $durum=$VT->filter($_POST["durum"]);
		  
		  if(!empty($baslik))
		  {  
			$ekle=$VT->SorguCalistir("INSERT INTO galeri", "SET baslik=?, seflink=?, kategori=?, sira=?, durum=?", array($baslik, $seflink, $kategori, $sira, $durum));
			
			if($ekle!=false)
			{
			  echo "TAMAM";
			}
			else
			{
			  echo "HATA";
			}
		  }
		  else
		  {
			echo "BOS";
		  }
		}
		else if($_POST["islem"]=="galeriSil" && !empty($_POST["ID"]))
		{
		  $ID=$VT->filter($_POST["ID"]);
		  
		  // Önce galerideki tüm resimleri al
		  $resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $ID), "ORDER BY ID ASC");
		  
		  // Resimleri fiziksel olarak sil
		  if($resimler != false) {
			  foreach($resimler as $resim) {
				  $resimYolu = $_SERVER["DOCUMENT_ROOT"].'/images/resimler/'.$resim["resim"];
				  if(file_exists($resimYolu)) {
					  @unlink($resimYolu);
				  }
			  }
			  
			  // Resimleri veritabanından sil
			  $VT->SorguCalistir("DELETE FROM resimler", "WHERE tablo=? AND KID=?", array("galeri", $ID));
		  }
		  
		  // Galeriyi sil
		  $galeriSil = $VT->SorguCalistir("DELETE FROM galeri", "WHERE ID=?", array($ID), 1);
		  
		  if($galeriSil != false) {
			  echo "TAMAM";
		  } else {
			  echo "HATA";
		  }
		}
		else if($_POST["islem"]=="galeri-duzenle")
		{
		  $ID=$VT->filter($_POST["ID"]);
		  $baslik=$VT->filter($_POST["baslik"]);
		  $seflink= $VT->seflink($baslik);
		  $kategori=$VT->filter($_POST["kategori"]);
		  $sira=$VT->filter($_POST["sira"]);
		  $durum=$VT->filter($_POST["durum"]);
		  
		  if(!empty($baslik) && !empty($ID))
		  {
			$duzenle=$VT->SorguCalistir("UPDATE galeri", "SET baslik=?, seflink=?, kategori=?, sira=?, durum=? WHERE ID=?", array($baslik, $seflink, $kategori, $sira, $durum, $ID));
			
			if($duzenle!=false)
			{
			  echo "TAMAM";
			}
			else
			{
			  echo "HATA";
			}
		  }
		  else
		  {
			echo "BOS";
		  }
		}
		else if($_POST["islem"]=="galeri-kategori-ekle")
		{
		  $baslik=$VT->filter($_POST["baslik"]);
		  $seflink= $VT->seflink($baslik);
		  $sira=$VT->filter($_POST["sira"]);
		  $durum=$VT->filter($_POST["durum"]);
		  
		  if(!empty($baslik))
		  {  
			$ekle=$VT->SorguCalistir("INSERT INTO galeri_kategoriler", "SET baslik=?, seflink=?, sira=?, durum=?", array($baslik, $seflink, $sira, $durum));
			
			if($ekle!=false)
			{
			  echo "TAMAM";
			}
			else
			{
			  echo "HATA";
			}
		  }
		  else
		  {
			echo "BOS";
		  }
		}
		else if($_POST["islem"]=="galeri-kategori-duzenle")
		{
		  $ID=$VT->filter($_POST["ID"]);
		  $baslik=$VT->filter($_POST["baslik"]);
		  $seflink= $VT->seflink($baslik);
		  $sira=$VT->filter($_POST["sira"]);
		  $durum=$VT->filter($_POST["durum"]);
		  
		  if(!empty($baslik) && !empty($ID))
		  {
			$duzenle=$VT->SorguCalistir("UPDATE galeri_kategoriler", "SET baslik=?, seflink=?, sira=?, durum=? WHERE ID=?", array($baslik, $seflink, $sira, $durum, $ID));
			
			if($duzenle!=false)
			{
			  echo "TAMAM";
			}
			else
			{
			  echo "HATA";
			}
		  }
		  else
		  {
			echo "BOS";
		  }
		}
		else if($_POST["islem"]=="galeri-resim-yukle")
		{
		  $tablo = $VT->filter($_POST["tablo"]);
		  $KID = $VT->filter($_POST["ID"]);
		  
		  // Logging for debugging
		  error_log("Galeri resim yükleme isteği: Tablo=$tablo, KID=$KID, MediaType=". ($_POST["mediaType"] ?? "img"));
		  
		  if(!empty($_FILES["file"]["name"]))
		  {
			$file = $_FILES["file"];
			$uzanti = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
			// Uzantıyı değiştirmeden koruyalım
			$originalExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
			
			// Klasör kontrolü
			$klasor = "../images/gallery/";
			if(!file_exists($klasor)) {
				mkdir($klasor, 0777, true);
			}
			
			// Dosya adını benzersiz hale getir ve orijinal uzantıyı koru
			$resimAdi = md5(time().rand(0, 999999)).".".$originalExtension;
			$hedefYol = $klasor.$resimAdi;
			
			// Dosyayı taşı
			if(move_uploaded_file($file["tmp_name"], $hedefYol)) {
				// Veritabanına ekle
				$ekle = $VT->SorguCalistir("INSERT INTO gallery SET baslik=?, resim=?, durum=?, tarih=?", 
					array("Galeri Resim", $resimAdi, 1, date("Y-m-d H:i:s")));
				
				if($ekle != false) {
					$response = array(
						"success" => true,
						"filename" => $resimAdi,
						"filepath" => $hedefYol
					);
				} else {
					// Yüklenen dosyayı sil
					@unlink($hedefYol);
					$response = array(
						"success" => false,
						"message" => "Veritabanına kaydedilirken bir hata oluştu."
					);
				}
				echo json_encode($response);
			} else {
				$response = array(
					"success" => false,
					"message" => "Dosya yüklenirken bir hata oluştu."
				);
				echo json_encode($response);
			}
		  }
		  else
		  {
			echo "BOS";
		  }
		  exit;
		}
		else if($_POST["islem"]=="galeri-listesi-getir")
		{
		  $galeri_id = $VT->filter($_POST["galeri_id"]);
		  
		  if(!empty($galeri_id))
		  {
			$resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $galeri_id), "ORDER BY ID DESC");
			$galeri = $VT->VeriGetir("galeri", "WHERE ID=?", array($galeri_id), "ORDER BY ID ASC", 1);
			
			if($resimler != false) {
			  foreach($resimler as $resim) {
				// Doğrudan resim yolunu kullan, uzantı değişikliği yapma
				$resimYolu = ANASITE."images/resimler/".$resim["resim"];
				
				// Önbellek sorunlarını önlemek için
				$cacheBuster = "?v=" . time() . rand(1000, 9999);
				
				echo '<div class="col-md-3 text-center mb-4 galeri-item">
						<div class="galeri-resim-container">
							<a href="'.$resimYolu.'" data-fancybox="gallery" data-caption="'.($galeri ? $galeri[0]["baslik"] : "Galeri").' - Resim">
								<img src="'.$resimYolu.$cacheBuster.'" class="img-fluid img-thumbnail galeri-resim" 
									onerror="this.src=\''.SITE.'dist/img/no-image.svg\'; console.error(\'Resim yüklenemedi: '.$resimYolu.'\');"
									alt="Galeri Resim" loading="lazy">
							</a>
							<div class="galeri-resim-islemler mt-2">
								<a href="'.SITE.'galeri-resim-sil/'.$galeri_id.'/'.$resim["ID"].'" class="btn btn-danger btn-sm" onclick="return resimSil(this.href);"><i class="fas fa-trash"></i> Sil</a>
							</div>
						</div>
					</div>';
			  }
			} else {
			  echo '<div class="col-md-12"><div class="alert alert-warning">Bu galeriye henüz resim eklenmemiş.</div></div>';
			}
		  }
		  else
		  {
			echo '<div class="col-md-12"><div class="alert alert-danger">Galeri ID bulunamadı!</div></div>';
		  }
		}
		else {
			// Tanımlanmamış işlem
			echo "EKSIK_PARAMETRE";
		}
		exit;
	}
	else
	{
		// Genel hata durumu
		echo "BOS";
		exit;
	}
}

// Gallery image upload
if(!empty($_GET["p"]) && $_GET["p"]=="gallery-upload" && !empty($_FILES)) {
    $targetDir = "../assets/img/gallery/";
    
    error_log("Gallery upload başlıyor...");
    error_log("POST değişkenler: " . print_r($_POST, true));
    error_log("FILES değişkenler: " . print_r($_FILES, true));
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        if(mkdir($targetDir, 0777, true)) {
            error_log("Dizin oluşturuldu: " . $targetDir);
        } else {
            error_log("Dizin oluşturulamadı: " . $targetDir);
            echo "directory_error";
            exit;
        }
    } else {
        // Dizin zaten var, yazılabilir mi?
        if(!is_writable($targetDir)) {
            error_log("Dizin yazılabilir değil: " . $targetDir);
            echo "directory_not_writable";
            exit;
        }
    }
    
    if(!empty($_FILES["file"])) {
        $file = $_FILES["file"];
        
        if($file["error"] !== 0) {
            error_log("Dosya yükleme hatası: " . $file["error"]);
            echo "upload_error_" . $file["error"];
            exit;
        }
        
        $fileName = time() . '_' . basename($file["name"]);
        $targetFile = $targetDir . $fileName;
        
        error_log("Hedef dosya: " . $targetFile);
        
        // Check file type
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        error_log("Dosya tipi: " . $fileType);
        
        if(in_array($fileType, $allowedTypes)) {
            // Upload file
            if(move_uploaded_file($file["tmp_name"], $targetFile)) {
                error_log("Dosya başarıyla yüklendi: " . $targetFile);
                
                // Insert record into database
                try {
                    error_log("Veritabanına kayıt ekleme başlıyor...");
                    $result = $VT->SorguCalistir("INSERT INTO gallery_images", 
                        "SET image_file=?, category_id=?, durum=?, tarih=?", 
                        array($fileName, 0, 1, date("Y-m-d H:i:s"))
                    );
                    
                    if($result !== false) {
                        error_log("Veritabanına kayıt başarılı");
                        // Sonuç başarılı
                        echo "success";
                    } else {
                        error_log("Veritabanına kayıt başarısız: DB hatası");
                        echo "db_error";
                        // Yüklenen dosyayı sil
                        if(file_exists($targetFile)) {
                            unlink($targetFile);
                            error_log("Yüklenen dosya silindi çünkü DB kaydı başarısız");
                        }
                    }
                } catch (Exception $e) {
                    error_log("Veritabanı istisna hatası: " . $e->getMessage());
                    echo "db_exception";
                    // Yüklenen dosyayı sil
                    if(file_exists($targetFile)) {
                        unlink($targetFile);
                        error_log("Yüklenen dosya silindi çünkü DB istisna hatası");
                    }
                }
            } else {
                error_log("Dosya yükleme başarısız (move_uploaded_file): " . $file["tmp_name"] . " -> " . $targetFile);
                echo "move_error";
            }
        } else {
            error_log("Geçersiz dosya tipi: " . $fileType);
            echo "invalid_type";
        }
    } else {
        error_log("FILES['file'] boş");
        echo "no_file";
    }
    exit;
}

// Gallery image delete
if(!empty($_GET["p"]) && $_GET["p"]=="gallery-delete" && !empty($_POST["imagefile"])) {
    $imagefile = $VT->filter($_POST["imagefile"]);
    $filePath = "../assets/img/gallery/" . $imagefile;
    
    // Delete database record if we have an ID
    if(!empty($_POST["image_id"])) {
        $imageID = $VT->filter($_POST["image_id"]);
        $VT->SorguCalistir("DELETE FROM gallery_images", "WHERE ID=?", array($imageID));
    }
    
    if(file_exists($filePath)) {
        if(unlink($filePath)) {
            echo "ok";
        } else {
            echo "error";
        }
    } else {
        echo "file_not_found";
    }
    exit;
}

// Gallery category delete
if(!empty($_POST["islem"]) && $_POST["islem"] == "kategorySil" && !empty($_POST["ID"])) {
    $ID=$VT->filter($_POST["ID"]);
    
    // Kategori silmeden önce, kategoriye ait galerileri Kategorisiz yap
    $VT->SorguCalistir("UPDATE galeri", "SET kategori=? WHERE kategori=?", array(0, $ID));
    
    // Sonra kategoriyi sil
    $kategoriSil = $VT->SorguCalistir("DELETE FROM galeri_kategoriler", "WHERE ID=?", array($ID), 1);
    
    if($kategoriSil!=false) {
        echo "TAMAM";
    } else {
        echo "HATA";
    }
    exit;
}

// Yacht Gallery Refresh - Return refreshed gallery HTML
if(!empty($_GET["p"]) && $_GET["p"]=="yacht-gallery-refresh") {
    if(!empty($_POST["yacht_id"])) {
        $yachtID = $VT->filter($_POST["yacht_id"]);
        $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($yachtID), "ORDER BY ID ASC", 1);
        $resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("yachts", $yachtID), "ORDER BY ID DESC");
        
        if($yacht != false) {
            ob_start(); // Start buffer to capture output
            
            if($resimler != false) {
                foreach($resimler as $resim) {
                    // Check both possible image paths
                    $resimYachtsYolu = SITE."../images/yachts/".$resim["resim"];
                    $resimResimlerYolu = SITE."../images/resimler/".$resim["resim"];
                    
                    // Determine correct path
                    $yachtsPath = $_SERVER['DOCUMENT_ROOT'].'/'.ltrim(SITE, '/').'images/yachts/'.$resim["resim"];
                    $resimlerPath = $_SERVER['DOCUMENT_ROOT'].'/'.ltrim(SITE, '/').'images/resimler/'.$resim["resim"];
                    
                    $resimYolu = file_exists($yachtsPath) ? $resimYachtsYolu : $resimResimlerYolu;
                    ?>
                    <div class="col-sm-2 col-md-3 col-lg-2 mb-4">
                        <div class="gallery-item">
                            <div class="position-relative">
                                <img src="<?=$resimYolu?>" class="img-fluid mb-2" alt="<?=$yacht[0]["baslik"]?> Resim">
                                <div class="gallery-item-actions">
                                    <button class="btn btn-sm btn-danger delete-image" data-id="<?=$resim["ID"]?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted"><?=date("d.m.Y H:i", strtotime($resim["tarih"]))?></small>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center p-5"><i class="fas fa-images fa-3x mb-3 text-muted"></i><p class="text-muted">Henüz yüklenmiş resim bulunmuyor.</p></div>';
            }
            
            $output = ob_get_clean(); // Get buffer contents
            echo $output;
        } else {
            echo '<div class="col-12 text-center"><p class="text-danger">Yat bulunamadı.</p></div>';
        }
    } else {
        echo '<div class="col-12 text-center"><p class="text-danger">Yat ID belirtilmedi.</p></div>';
    }
    exit;
}

// Gallery image category update
if(!empty($_POST["islem"]) && $_POST["islem"] == "resimKategoriGuncelle" && !empty($_POST["resim_id"]) && isset($_POST["kategori_id"])) {
    $resimID = $VT->filter($_POST["resim_id"]);
    $kategoriID = $VT->filter($_POST["kategori_id"]);
    
    if($resimID === "latest") {
        // This is a special case for a new upload
        // Get the latest uploaded image with the filename
        if(!empty($_POST["filename"])) {
            $filename = $VT->filter($_POST["filename"]);
            $filenameSearch = time() . '_' . $filename; // Format must match how we create filenames in gallery-upload
            
            // Find the most recent image with this filename pattern
            $resim = $VT->VeriGetir("gallery_images", "WHERE image_file LIKE ?", array('%'.$filename.'%'), "ORDER BY ID DESC LIMIT 1");
            
            if($resim != false) {
                $resimID = $resim[0]["ID"];
                $guncelle = $VT->SorguCalistir("UPDATE gallery_images", "SET category_id=? WHERE ID=?", array($kategoriID, $resimID));
                
                if($guncelle!=false) {
                    echo "TAMAM";
                } else {
                    echo "HATA";
                }
            } else {
                echo "RESIM_BULUNAMADI";
            }
        } else {
            echo "FILENAME_EKSIK";
        }
    } else {
        // Standard update of an existing image
        $guncelle = $VT->SorguCalistir("UPDATE gallery_images", "SET category_id=? WHERE ID=?", array($kategoriID, $resimID));
        
        if($guncelle!=false) {
            echo "TAMAM";
        } else {
            echo "HATA";
        }
    }
    exit;
}

// Video dosyasını DB'ye ekleme
if(!empty($_GET["p"]) && $_GET["p"]=="add-video-to-db") {
    if(!empty($_POST["filename"]) && !empty($_POST["tablo"]) && !empty($_POST["ID"])) {
        $filename = $VT->filter($_POST["filename"]);
        $tablo = $VT->filter($_POST["tablo"]);
        $ID = $VT->filter($_POST["ID"]);
        
        // Dosyanın varlığını kontrol et
        $videoPath = "../images/yachts/videos/" . $filename;
        $altVideoPath = "../images/resimler/videos/" . $filename;
        $videoExists = file_exists($videoPath) || file_exists($altVideoPath);
        
        if($videoExists) {
            // DB'de zaten var mı kontrol et
            $kontrol = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array($tablo, $ID, $filename), "ORDER BY ID ASC", 1);
            
            if($kontrol == false) {
                // DB'ye ekle
                $ekle = $VT->SorguCalistir("INSERT INTO resimler", "SET tablo=?, KID=?, resim=?, tarih=?", array($tablo, $ID, $filename, date("Y-m-d")));
                
                if($ekle != false) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Video başarıyla veritabanına eklendi.'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Veritabanına eklenirken bir hata oluştu.'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bu video zaten veritabanında kayıtlı.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Video dosyası bulunamadı.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Eksik parametreler: Dosya adı, tablo ve ID gerekli.'
        ]);
    }
    exit;
}

// Test video dosyası için düzeltme
if(!empty($_GET["p"]) && $_GET["p"]=="fix-test-video") {
    // Test videosu için dosya içeriği oluştur (boş dosyayı doldur)
    $testVideoPath = "../images/yachts/videos/test_video.mp4";
    $sourceVideoPath = "../images/yachts/videos/video_168224c3102cc4.mp4";
    
    // Hata ayıklama
    error_log("Test video yolu: " . realpath($testVideoPath));
    error_log("Kaynak video yolu: " . realpath($sourceVideoPath));
    
    // Dizin oluştur
    if(!file_exists("../images/yachts/videos/")) {
        mkdir("../images/yachts/videos/", 0777, true);
        error_log("Video dizini oluşturuldu");
    }
    
    if(file_exists($sourceVideoPath)) {
        // Kaynak dosyayı test dosyasına kopyala
        if(copy($sourceVideoPath, $testVideoPath)) {
            echo "TAMAM";
        } else {
            error_log("Kopyalama hatası: " . error_get_last()['message']);
            echo "KOPYALAMA_HATASI";
        }
    } else {
        echo "DOSYA_YOK";
    }
    exit;
}

// Video dosyası silme endpoint'i
if(!empty($_GET["p"]) && $_GET["p"]=="delete-video-file") {
    if(!empty($_POST["filename"])) {
        $filename = $VT->filter($_POST["filename"]);
        
        // Check both potential video locations
        $yachtsVideoPath = "../images/yachts/videos/" . $filename;
        $resimlerVideoPath = "../images/resimler/videos/" . $filename;
        
        // Determine which path to use
        $videoPath = null;
        if(file_exists($yachtsVideoPath)) {
            $videoPath = $yachtsVideoPath;
        } else if(file_exists($resimlerVideoPath)) {
            $videoPath = $resimlerVideoPath;
        }
        
        // Log the deletion attempt
        error_log("Video silme isteği: " . $filename . ", Path: " . $videoPath);
        
        // Dosyanın varlığını kontrol et
        if($videoPath && file_exists($videoPath)) {
            // DB'de kayıt var mı kontrol et ve sil
            $dbKayit = $VT->VeriGetir("resimler", "WHERE resim=?", array($filename));
            if($dbKayit != false) {
                $VT->SorguCalistir("DELETE FROM resimler", "WHERE resim=?", array($filename));
                error_log("Video veritabanı kaydı silindi: " . $filename);
            }
            
            // Fiziksel dosyayı sil
            if(unlink($videoPath)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Video dosyası başarıyla silindi.'
                ]);
                error_log("Video dosyası silindi: " . $videoPath);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Video dosyası silinirken bir hata oluştu.'
                ]);
                error_log("Video dosyası silinemedi: " . $videoPath);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Video dosyası bulunamadı.'
            ]);
            error_log("Video dosyası bulunamadı: " . $filename);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Dosya adı belirtilmedi.'
        ]);
    }
    exit;
}

// Video dosyasının kullanımını kontrol et
if(!empty($_GET["p"]) && $_GET["p"]=="check-video-usage") {
    if(!empty($_POST["filename"])) {
        $filename = $VT->filter($_POST["filename"]);
        
        // Veritabanında dosyanın tüm kullanımlarını kontrol et
        $kullanımlar = $VT->VeriGetir("resimler", "WHERE resim=?", array($filename), "ORDER BY ID ASC");
        
        if($kullanımlar != false) {
            if(count($kullanımlar) > 1) {
                // Video birden fazla yerde kullanılıyor
                $kullanılanYerler = array();
                foreach($kullanımlar as $kullanım) {
                    $tablo = $kullanım["tablo"];
                    $kid = $kullanım["KID"];
                    
                    // Galeri/yat adını bul
                    if($tablo == "galeri") {
                        $galeri = $VT->VeriGetir("galeri", "WHERE ID=?", array($kid), "ORDER BY ID ASC", 1);
                        if($galeri != false) {
                            $kullanılanYerler[] = "Galeri: " . $galeri[0]["baslik"];
                        }
                    } else if($tablo == "yachts") {
                        $yat = $VT->VeriGetir("yachts", "WHERE ID=?", array($kid), "ORDER BY ID ASC", 1);
                        if($yat != false) {
                            $kullanılanYerler[] = "Yat: " . $yat[0]["baslik"];
                        }
                    }
                }
                
                error_log("Video birden fazla yerde kullanılıyor: " . implode(", ", $kullanılanYerler));
                echo "KULLANILIYOR";
            } else {
                // Video sadece bir yerde kullanılıyor
                echo "KULLANILMIYOR";
            }
        } else {
            // Video hiçbir yerde kullanılmıyor
            echo "KULLANILMIYOR";
        }
    } else {
        error_log("Video kullanım kontrolü için dosya adı parametresi eksik");
        echo "PARAMETRE_EKSIK";
    }
    exit;
}

// Yat Tipi Durum Değiştirme
if(isset($_POST["islem"]) && $_POST["islem"] == "yacht_type_durum_degistir" && isset($_POST["id"]) && isset($_POST["durum"])) {
    $id = $VT->filter($_POST["id"]);
    $durum = $VT->filter($_POST["durum"]);
    
    // ID ve durum değerinin geçerli olduğunu kontrol et
    if(is_numeric($id) && ($durum == 0 || $durum == 1)) {
        $guncelle = $VT->SorguCalistir("UPDATE yacht_types", "SET durum=? WHERE ID=?", array($durum, $id));
        
        if($guncelle) {
            echo "OK";
        } else {
            echo "HATA";
        }
    } else {
        echo "HATA";
    }
    exit;
}

if($_GET) {
    // İşlem türüne göre
    if($_GET["islem"] == "basit-galeri-yukle") {
        // Dropzone ile resim yükleme işlemi
        if(!empty($_FILES["file"]["name"])) {
            // Resim yükleme sınırlamaları
            $izinli_uzantilar = array("jpg", "jpeg", "png", "gif", "webp");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            
            // Dosya boyutu kontrolü (5MB max)
            if($_FILES["file"]["size"] > 5242880) {
                $response = array(
                    "success" => false,
                    "message" => "Dosya boyutu çok büyük. Maximum 5MB olmalıdır."
                );
                echo json_encode($response);
                exit;
            }
            
            // Uzantı kontrolü
            if(!in_array(strtolower($extension), $izinli_uzantilar)) {
                $response = array(
                    "success" => false,
                    "message" => "Geçersiz dosya uzantısı. Sadece jpg, jpeg, png, gif ve webp dosyaları yüklenebilir."
                );
                echo json_encode($response);
                exit;
            }
            
            // Klasör kontrolü
            $klasor = "../images/gallery/";
            if(!file_exists($klasor)) {
                mkdir($klasor, 0777, true);
            }
            
            // Dosya adını benzersiz hale getir
            $resimAdi = md5(time().rand(0, 999999)).".".$extension;
            $hedefYol = $klasor.$resimAdi;
            
            // Dosyayı taşı
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $hedefYol)) {
                // Veritabanına ekle
                $ekle = $VT->SorguCalistir("INSERT INTO gallery", "SET baslik=?, resim=?, durum=?, tarih=?", 
                    array("Galeri Resim", $resimAdi, 1, date("Y-m-d H:i:s")));
                
                if($ekle != false) {
                    $response = array(
                        "success" => true,
                        "filename" => $resimAdi,
                        "filepath" => $hedefYol
                    );
                } else {
                    // Yüklenen dosyayı sil
                    @unlink($hedefYol);
                    $response = array(
                        "success" => false,
                        "message" => "Veritabanına kaydedilirken bir hata oluştu."
                    );
                }
                echo json_encode($response);
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Dosya yüklenirken bir hata oluştu."
                );
                echo json_encode($response);
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Dosya seçilmedi."
            );
            echo json_encode($response);
        }
    }
    elseif($_GET["islem"] == "galeri-listesi-getir") {
        // Veritabanından galeri resimlerini çek
        $resimler = $VT->VeriGetir("gallery", "WHERE durum=?", array(1), "ORDER BY ID DESC");
        ob_start(); // Buffer başlat
        
        if($resimler != false) {
            foreach($resimler as $resim) {
                $resimYolu = SITE."../images/gallery/".$resim["resim"];
                ?>
                <div class="col-sm-2 col-md-3 col-lg-2 mb-4">
                    <div class="gallery-item">
                        <div class="position-relative">
                            <img src="<?=$resimYolu?>" class="img-fluid mb-2" alt="<?=stripslashes($resim["baslik"])?>">
                            <div class="gallery-item-actions">
                                <button class="btn btn-sm btn-danger delete-image" data-id="<?=$resim["ID"]?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted"><?=date("d.m.Y H:i", strtotime($resim["tarih"]))?></small>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center p-5"><i class="fas fa-images fa-3x mb-3 text-muted"></i><p class="text-muted">Henüz yüklenmiş resim bulunmuyor.</p></div>';
        }
        
        $content = ob_get_clean(); // Buffer içeriğini al
        echo $content;
    }
    elseif($_GET["islem"] == "galeri-resim-sil") {
        if(!empty($_POST["id"])) {
            $id = $VT->filter($_POST["id"]);
            $resim = $VT->VeriGetir("gallery", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
            
            if($resim != false) {
                $resimDosyasi = $resim[0]["resim"];
                $resimYolu = "../images/gallery/".$resimDosyasi;
                
                // Dosyayı sil
                if(file_exists($resimYolu)) {
                    @unlink($resimYolu);
                }
                
                // Veritabanı kaydını sil
                $sil = $VT->SorguCalistir("DELETE FROM gallery", "WHERE ID=?", array($id));
                
                if($sil != false) {
                    $response = array(
                        "success" => true,
                        "message" => "Resim başarıyla silindi."
                    );
                } else {
                    $response = array(
                        "success" => false,
                        "message" => "Veritabanı kaydı silinirken bir hata oluştu."
                    );
                }
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Resim bulunamadı."
                );
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Resim ID'si belirtilmedi."
            );
        }
        
        echo json_encode($response);
    }
    elseif($_GET["islem"] == "galeri-resim-yukle") {
        // Dropzone ile resim yükleme işlemi
        if(!empty($_FILES["file"]["name"])) {
            // Resim yükleme sınırlamaları
            $izinli_uzantilar = array("jpg", "jpeg", "png");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            
            // Dosya boyutu kontrolü (5MB max)
            if($_FILES["file"]["size"] > 5242880) {
                $response = array(
                    "success" => false,
                    "message" => "Dosya boyutu çok büyük. Maximum 5MB olmalıdır."
                );
                echo json_encode($response);
                exit;
            }
            
            // Uzantı kontrolü
            if(!in_array(strtolower($extension), $izinli_uzantilar)) {
                $response = array(
                    "success" => false,
                    "message" => "Geçersiz dosya uzantısı. Sadece jpg, jpeg ve png dosyaları yüklenebilir."
                );
                echo json_encode($response);
                exit;
            }
            
            // Klasör kontrolü
            $klasor = "../images/gallery/";
            if(!file_exists($klasor)) {
                mkdir($klasor, 0777, true);
            }
            
            // Dosya adını benzersiz hale getir
            $resimAdi = md5(time().rand(0, 999999)).".".$extension;
            $hedefYol = $klasor.$resimAdi;
            
            // Dosyayı taşı
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $hedefYol)) {
                $response = array(
                    "success" => true,
                    "filename" => $resimAdi,
                    "filepath" => $hedefYol
                );
                echo json_encode($response);
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Dosya yüklenirken bir hata oluştu."
                );
                echo json_encode($response);
            }
        } else {
            $response = array(
                "success" => false,
                "message" => "Dosya seçilmedi."
            );
            echo json_encode($response);
        }
    } 
    // Toplu galeri resimlerini kaydet
    elseif($_GET["islem"] == "galeri-toplu-kaydet") {
        if(!empty($_POST["files"]) && is_array($_POST["files"])) {
            $files = $_POST["files"];
            $kategori = $VT->filter($_POST["kategori"]);
            $sirano = (int)$VT->filter($_POST["sirano"]);
            
            $basarili = 0;
            $basarisiz = 0;
            
            foreach($files as $index => $file) {
                $siraNo = $sirano + $index;
                $baslik = "Galeri Resim ".($index + 1);
                
                $ekle = $VT->SorguCalistir("INSERT INTO gallery SET baslik=?, resim=?, kategori=?, sirano=?, durum=?", 
                    array($baslik, $file, $kategori, $siraNo, 1));
                
                if($ekle!=false) {
                    $basarili++;
                } else {
                    $basarisiz++;
                }
            }
            
            $response = array(
                "success" => true,
                "message" => $basarili." resim başarıyla eklendi. ".$basarisiz." resim eklenemedi."
            );
            echo json_encode($response);
        } else {
            $response = array(
                "success" => false,
                "message" => "Hiç dosya seçilmedi."
            );
            echo json_encode($response);
        }
    }
    // Diğer ajax işlemleri...
    elseif($_GET["islem"] == "durum") {
        if(!empty($_GET["tablo"]) && !empty($_GET["ID"])) {
            $ID = $VT->filter($_GET["ID"]);
            $tablo = $VT->filter($_GET["tablo"]);
            
            $kontrol = $VT->VeriGetir($tablo, "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
            if($kontrol!=false) {
                if($kontrol[0]["durum"]==1) {
                    $durum = 0;
                } else {
                    $durum = 1;
                }
                $guncelle = $VT->SorguCalistir("UPDATE ".$tablo." SET durum=? WHERE ID=?", array($durum, $ID));
                if($guncelle!=false) {
                    echo "TAMAM";
                } else {
                    echo "HATA";
                }
            } else {
                echo "HATA";
            }
        } else {
            echo "HATA";
        }
    }
}
?>
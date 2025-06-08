<?php
@session_start();
@ob_start();
define("DATA","data/");
define("SAYFA","include/");
define("SINIF","admin/class/");
include_once(DATA."baglanti.php");
define("SITE",$siteurl);

// Initialize VT class
$VT = new VT();

// Set JSON response headers
header('Content-Type: application/json');

// Get the action parameter
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Response array
$response = array(
	'success' => false,
	'message' => 'Bilinmeyen işlem'
);

// Handle special endpoints with GET parameter p
if(isset($_GET['p'])) {
    switch($_GET['p']) {
        case 'add-video-to-db':
            // Get parameters
            $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
            $tablo = isset($_POST['tablo']) ? $_POST['tablo'] : '';
            $ID = isset($_POST['ID']) ? intval($_POST['ID']) : 0;
            
            // Validate parameters
            if(empty($filename) || empty($tablo) || $ID <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Geçersiz parametreler'
                ]);
                exit;
            }
            
            // Security check - prevent directory traversal
            if(strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Geçersiz dosya adı'
                ]);
                exit;
            }
            
            // Check if the file exists
            $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/";
            $fullPath = $videoPath . $filename;
            
            if(!file_exists($fullPath)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dosya bulunamadı: ' . $filename
                ]);
                exit;
            }
            
            // Check if already exists in database
            $checkExisting = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array(
                $tablo, $ID, $filename
            ), "", 1);
            
            if($checkExisting != false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bu video zaten eklenmiş'
                ]);
                exit;
            }
            
            // Add to database
            $ekle = $VT->SorguCalistir("INSERT INTO resimler", "SET tablo=?, KID=?, resim=?, durum=?, tarih=?", array(
                $tablo, $ID, $filename, 1, date("Y-m-d")
            ));
            
            if($ekle) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Video başarıyla eklendi'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Video eklenirken bir hata oluştu'
                ]);
            }
            exit;
            
        case 'delete-video-file':
            // Get filename parameter
            $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
            
            // Validate filename
            if(empty($filename)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dosya adı belirtilmedi'
                ]);
                exit;
            }
            
            // Security check - prevent directory traversal
            if(strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Geçersiz dosya adı'
                ]);
                exit;
            }
            
            // File path
            $videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/";
            $fullPath = $videoPath . $filename;
            
            // Check if file exists
            if(!file_exists($fullPath)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dosya bulunamadı: ' . $filename
                ]);
                exit;
            }
            
            // Try to delete the file
            if(unlink($fullPath)) {
                // Also remove from database if exists
                $VT->SorguCalistir("DELETE FROM resimler", "WHERE resim=?", array($filename));
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Dosya başarıyla silindi'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dosya silinirken bir hata oluştu: ' . (error_get_last() ? error_get_last()['message'] : 'Bilinmeyen hata')
                ]);
            }
            exit;
            
        default:
            // Unknown endpoint
            echo json_encode([
                'success' => false,
                'message' => 'Bilinmeyen işlem'
            ]);
            exit;
    }
}

if($_POST)
{
	if(!empty($_POST["islemtipi"]))
	{
		$islemtipi=$VT->filter($_POST["islemtipi"]);

		switch ($islemtipi) {
			case 'sepeteEkle':
				
				if(!empty($_POST["urunID"]) && !empty($_POST["adet"]) && ctype_digit($_POST["urunID"]) && ctype_digit($_POST["adet"]))
				{
					$urunID=$VT->filter($_POST["urunID"]);
					$adet=$VT->filter($_POST["adet"]);
					$kontrol=$VT->VeriGetir("urunler","WHERE ID=? AND durum=?",array($urunID,1),"ORDER BY ID ASC",1);
					if($kontrol!=false)
					{
						$urunstok=$kontrol[0]["stok"];
						if($urunstok>=$adet)
						{

							$varyasyonkontrol=$VT->VeriGetir("urunvaryasyonlari","WHERE urunID=?",array($kontrol[0]["ID"]),"ORDER BY ID ASC");
							if($varyasyonkontrol!=false)
							{
								/*Eğer ürüne ait bir varyasyon var ise bu kısmı çalıştır.*/
								if(!empty($_POST["varyasyon"]) && !empty($_POST["varyasyon"][0]))
								{
									if(count($_POST["varyasyon"])==count($varyasyonkontrol))
									{
										$islemdurumu=false;
										$secenekID=array();
										foreach ($_POST["varyasyon"] as $secenek) {
											
											$secenekBilgisi=$VT->filter($secenek);
											$secenekKontrol=$VT->VeriGetir("urunvaryasyonsecenekleri","WHERE ID=? AND urunID=?",array($secenekBilgisi,$kontrol[0]["ID"]),"ORDER BY ID ASC",1);
											if($secenekKontrol!=false)
											{
												$secenekID[]=$secenekKontrol[0]["ID"];
												$islemdurumu=true;
											}
											else
											{
												$islemdurumu=false;
												break;
											}
										}

										if($islemdurumu!=false)
										{
											if(count($secenekID)>1)
											{
												$sqlsecenek=implode("@", $secenekID);
											}
											else
											{
												$sqlsecenek=$secenekID[0];
											}

											$secenekStokKontrol=$VT->VeriGetir("urunvaryasyonstoklari","WHERE urunID=? AND secenekID=?",array($kontrol[0]["ID"],$sqlsecenek),"ORDER BY ID ASC",1);
											if($secenekStokKontrol!=false)
											{
												/**********************/
							if(!empty($_SESSION["sepet"]) && !empty($_SESSION["sepet"][$kontrol[0]["ID"]]) && !empty($_SESSION["sepetVaryasyon"][$kontrol[0]["ID"]][$secenekStokKontrol[0]["ID"]]["adet"]))
								{
									$sepettekiadet=$_SESSION["sepetVaryasyon"][$kontrol[0]["ID"]][$secenekStokKontrol[0]["ID"]]["adet"];
									$toplammiktar=($sepettekiadet+$adet);
									if($secenekStokKontrol[0]["stok"]>=$toplammiktar)
									{
										$_SESSION["sepet"][$kontrol[0]["ID"]]["adet"]=$toplammiktar;
										$_SESSION["sepetVaryasyon"][$kontrol[0]["ID"]][$secenekStokKontrol[0]["ID"]]["adet"]=$toplammiktar;
										echo "TAMAM";
									}
									else
									{
										echo "STOK";
									}
								}
								else
								{
									if($secenekStokKontrol[0]["stok"]>=$adet)
									{
									$_SESSION["sepet"][$kontrol[0]["ID"]]=array("adet"=>$adet,"varyasyondurumu"=>true);
									$_SESSION["sepetVaryasyon"][$kontrol[0]["ID"]][$secenekStokKontrol[0]["ID"]]=array("adet"=>$adet);
									echo "TAMAM";
									}
									else
									{
										echo "STOK";
									}
								}
								/*-------------*/
											}
											else
											{

												echo "ERROR";
											}
										}
										else
										{

											echo "ERROR";
										}
									}
									else
									{

										echo "ERROR";
									}

								}
								else
								{
									echo "ERROR";
								}


							}
							else
							{
								/*Eğer ürüne ait varyasyon yok ise bu kısmı çalıştır.*/

								if(!empty($_SESSION["sepet"]) && !empty($_SESSION["sepet"][$kontrol[0]["ID"]]) && !empty($_SESSION["sepet"][$kontrol[0]["ID"]]["adet"]))
								{
									$sepettekiadet=$_SESSION["sepet"][$kontrol[0]["ID"]]["adet"];
									$toplammiktar=($sepettekiadet+$adet);
									if($urunstok>=$toplammiktar)
									{
										$_SESSION["sepet"][$kontrol[0]["ID"]]["adet"]=$toplammiktar;
										echo "TAMAM";
									}
									else
									{
										echo "STOK";
									}
								}
								else
								{
									$_SESSION["sepet"][$kontrol[0]["ID"]]=array("adet"=>$adet,"varyasyondurumu"=>false);
									echo "TAMAM";
								}
								

							}

						}
						else
						{
							echo "STOK";
						}
					}
					else
					{
						echo "ERROR";
					}
				}
				else
				{
					echo "ERROR";
				}
				break;
				case "sifreIste":
				if(!empty($_POST["mailadresi"]))
				{
					$mail=$VT->filter($_POST["mailadresi"]);
					$kontrol=$VT->VeriGetir("uyeler","WHERE mail=? AND durum=?",array($mail,1),"ORDER BY ID ASC",1);
					if($kontrol!=false)
					{
						$dogrulamaKodu="RFR".rand(10000,99999);/*RFT56985*/
						$mailGonder=$VT->MailGonder($kontrol[0]["mail"],"Şifre Doğrulama","Doğrulama Kodunuz : ".$dogrulamaKodu);
						$_SESSION["dogrulamaKodu"]=$dogrulamaKodu;
						$_SESSION["uyeninSifresiIcinID"]=$kontrol[0]["ID"];
						echo "TAMAM";
					}
					else
					{
						echo "ERROR";
					}
				}
				else
				{
					echo "ERROR";
				}
				break;
				case "favoriyeEkle":
				if(!empty($_SESSION["uyeID"]))
				{
					$uyeID=$VT->filter($_SESSION["uyeID"]);
					$uyebilgisi=$VT->VeriGetir("uyeler","WHERE ID=? AND durum=?",array($uyeID,1),"ORDER BY ID ASC",1);
					if($uyebilgisi!=false)
					{
						if(!empty($_POST["urunID"]) && !empty($_POST["urunKey"]))
						{
							$urunID=$VT->filter($_POST["urunID"]);
							$karsilatirmaKey=md5(sha1($urunID));
							$key=$VT->filter($_POST["urunKey"]);

							if($karsilatirmaKey==$key)
							{
								$urunBilgisi=$VT->VeriGetir("urunler","WHERE ID=? AND durum=?",array($urunID,1),"ORDER BY ID ASC",1);
								if($urunBilgisi!=false)
								{
									$favoriKontrol=$VT->VeriGetir("favoriler","WHERE uyeID=? AND urunID=?",array($uyebilgisi[0]["ID"],$urunBilgisi[0]["ID"]));
									if($favoriKontrol!=false)
									{
										echo "VAR";
									}
									else
									{
										$ekle=$VT->SorguCalistir("INSERT INTO favoriler","SET uyeID=?, urunID=?, tarih=?",array($uyebilgisi[0]["ID"],$urunBilgisi[0]["ID"],date("Y-m-d")));
										echo "TAMAM";
									}
								}
								else
								{
									echo "HATA";
								}
							}
							else
							{
								echo "GUVENLIK";
							}
						}
						else
						{
							echo "HATA";
						}
					}
					else
					{
						echo "HATA";
					}
				}
				else
				{
					echo "HATA";
				}
				break;
			default:
				echo "ERROR";
				break;
		}

	}
	else
	{
		echo "ERROR";
	}


}
else
{
	echo "ERROR";
}

// Process based on action
switch($action) {
	case 'add_video':
		// Get parameters
		$filename = isset($_POST['filename']) ? $_POST['filename'] : '';
		$galleryId = isset($_POST['gallery_id']) ? intval($_POST['gallery_id']) : 0;
		
		// Validate parameters
		if(empty($filename) || $galleryId <= 0) {
			$response['message'] = 'Geçersiz parametreler';
			break;
		}
		
		// Security check - prevent directory traversal
		if(strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
			$response['message'] = 'Geçersiz dosya adı';
			break;
		}
		
		// Check if the file exists
		$videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/";
		$fullPath = $videoPath . $filename;
		
		if(!file_exists($fullPath)) {
			$response['message'] = 'Dosya bulunamadı: ' . $filename;
			break;
		}
		
		// Check if already exists in database
		$checkExisting = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=? AND resim=?", array(
			"galeri", $galleryId, $filename
		), "", 1);
		
		if($checkExisting != false) {
			$response['message'] = 'Bu video zaten galeriye eklenmiş';
			break;
		}
		
		// Add to database
		$ekle = $VT->SorguCalistir("INSERT INTO resimler", "SET tablo=?, KID=?, resim=?, durum=?, tarih=?", array(
			"galeri", $galleryId, $filename, 1, date("Y-m-d")
		));
		
		if($ekle) {
			$response['success'] = true;
			$response['message'] = 'Video başarıyla galeriye eklendi';
		} else {
			$response['message'] = 'Video eklenirken bir hata oluştu';
		}
		break;
		
	case 'delete_video_file':
		// Get filename parameter
		$filename = isset($_POST['filename']) ? $_POST['filename'] : '';
		
		// Validate filename
		if(empty($filename)) {
			$response['message'] = 'Dosya adı belirtilmedi';
			break;
		}
		
		// Security check - prevent directory traversal
		if(strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
			$response['message'] = 'Geçersiz dosya adı';
			break;
		}
		
		// File path
		$videoPath = $_SERVER['DOCUMENT_ROOT'].parse_url(ANASITE, PHP_URL_PATH)."/images/resimler/videos/";
		$fullPath = $videoPath . $filename;
		
		// Check if file exists
		if(!file_exists($fullPath)) {
			$response['message'] = 'Dosya bulunamadı: ' . $filename;
			break;
		}
		
		// Try to delete the file
		if(unlink($fullPath)) {
			// Also remove from database if exists
			$VT->SorguCalistir("DELETE FROM resimler", "WHERE tablo=? AND resim=?", array("galeri", $filename));
			
			$response['success'] = true;
			$response['message'] = 'Dosya başarıyla silindi';
		} else {
			$response['message'] = 'Dosya silinirken bir hata oluştu: ' . (error_get_last() ? error_get_last()['message'] : 'Bilinmeyen hata');
		}
		break;
		
	default:
		$response['message'] = 'Bilinmeyen işlem: ' . $action;
		break;
}

// Return JSON response
echo json_encode($response);
?>
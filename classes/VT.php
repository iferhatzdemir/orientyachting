<?php
class VT{
	
	var $sunucu="localhost";
	var $user="root";
	var $password="";
	var $dbname="eticaret";
	var $baglanti;
	
	function __construct($db = null)
	{
		try{
			if($db !== null) {
				$this->baglanti = $db;
			} else {
				$this->baglanti=new PDO("mysql:host=".$this->sunucu.";dbname=".$this->dbname.";charset=utf8;",$this->user,$this->password);
			}
			$this->baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $error){
			error_log("VT.__construct PDO hatasi: " . $error->getMessage());
			if(function_exists('http_response_code') && http_response_code() !== false && !headers_sent()) {
				header('HTTP/1.1 500 Internal Server Error');
			}
			echo "Veritabanı bağlantı hatası. Lütfen daha sonra tekrar deneyiniz.";
			exit();
		}catch(Exception $error){
			error_log("VT.__construct genel hata: " . $error->getMessage());
			if(function_exists('http_response_code') && http_response_code() !== false && !headers_sent()) {
				header('HTTP/1.1 500 Internal Server Error');
			}
			echo "Sistem hatası. Lütfen daha sonra tekrar deneyiniz.";
			exit();
		}
	}
	
	public function VeriGetir($tablo,$wherealanlar="",$wherearraydeger="",$ordeby="ORDER BY ID ASC",$limit="")
	{
		try {
			$this->baglanti->query("SET CHARACTER SET utf8");
			$sql = "SELECT * FROM " . $tablo;
			
			if(!empty($wherealanlar) && !empty($wherearraydeger)) {
				$sql .= " " . $wherealanlar;
				if(!empty($ordeby)) { $sql .= " " . $ordeby; }
				if(!empty($limit)) { $sql .= " LIMIT " . $limit; }
				
				$calistir = $this->baglanti->prepare($sql);
				$sonuc = $calistir->execute($wherearraydeger);
				$veri = $calistir->fetchAll(PDO::FETCH_ASSOC);
			} else {
				if(!empty($ordeby)) { $sql .= " " . $ordeby; }
				if(!empty($limit)) { $sql .= " LIMIT " . $limit; }
				
				$veri = $this->baglanti->query($sql, PDO::FETCH_ASSOC);
			}
			
			if($veri != false && !empty($veri)) {
				$datalar = array();
				foreach($veri as $bilgiler) {
					$datalar[] = $bilgiler;
				}
				return $datalar;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			error_log("VT.VeriGetir PDO hatasi: " . $e->getMessage() . " (SQL: $sql)");
			return false;
		} catch (Exception $e) {
			error_log("VT.VeriGetir genel hata: " . $e->getMessage());
			return false;
		}
	}
	
	public function SorguCalistir($tablo,$alanlar="",$degerlerarray="",$limit="")
	{
		try {
			$this->baglanti->query("SET CHARACTER SET utf8");
			$sql = "";
			if(!empty($alanlar) && !empty($degerlerarray))
			{
				$sql=$tablo." ".$alanlar;
				if(!empty($limit)){$sql.=" LIMIT ".$limit;}
				$calistir=$this->baglanti->prepare($sql);
				$sonuc=$calistir->execute($degerlerarray);
			}
			else
			{
				$sql=$tablo;
				if(!empty($limit)){$sql.=" LIMIT ".$limit;}
				$sonuc=$this->baglanti->exec($sql);
			}
			
			if($sonuc!=false)
			{
				return true;
			}
			else
			{
				error_log("VT.SorguCalistir başarısız: $sql");
				return false;
			}
		} catch (PDOException $e) {
			error_log("VT.SorguCalistir PDO hatasi: " . $e->getMessage() . " (SQL: $sql)");
			return false;
		} catch (Exception $e) {
			error_log("VT.SorguCalistir genel hata: " . $e->getMessage());
			return false;
		}
	}
	
	public function seflink($val)
	{
		$find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#','?','*','!','.','(',')');
		$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp','','','','','','');
		$string = strtolower(str_replace($find, $replace, $val));
		$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
		$string = trim(preg_replace('/\s+/', ' ', $string));
		$string = str_replace(' ', '-', $string);
		return $string;
	}
	
	public function filter($val,$tf=false)
	{
		if($tf==false){$val=strip_tags($val);}
		$val=addslashes(trim($val));
		return $val;
	}
	
	public function uzanti($dosyaadi)
	{
		$parca=explode(".",$dosyaadi);
		$uzanti=$parca[count($parca)-1];
		return $uzanti;
	}
	
	public function IDGetir($tablo)
	{
		$sql=$this->baglanti->query("SHOW TABLE STATUS FROM `".$this->dbname."` LIKE '".$tablo."'");
		$statsarray=$sql->fetch();
		$nextid=$statsarray['Auto_increment'];
		return $nextid;
	}
	
	public function uploadMulti($nesnename,$tablo='nan',$KID=1,$yuklenecekyer='images/',$tur='img',$w='',$h='',$resimyazisi='')
	{
		if($tur=="img")
		{
			if(!empty($_FILES[$nesnename]["name"][0]))
			{
				$dosyanizinadi=$_FILES[$nesnename]["name"][0];
				$tmp_name=$_FILES[$nesnename]["tmp_name"][0];
				$uzanti=$this->uzanti($dosyanizinadi);
				if($uzanti=="png" || $uzanti=="jpg" || $uzanti=="jpeg" || $uzanti=="gif" || $uzanti=="webp")
				{
					$resimler = array();
					foreach ($_FILES[$nesnename] as $k => $l) {
					  foreach ($l as $i => $v) {
						if (!array_key_exists($i, $resimler))
						  $resimler[$i] = array();
						$resimler[$i][$k] = $v;
					  }
					}
					
					foreach ($resimler as $resim){
						$uzanti=$this->uzanti($resim["name"]);
						if($uzanti=="png" || $uzanti=="jpg" || $uzanti=="jpeg" || $uzanti=="gif" || $uzanti=="webp")
						{
							$handle = new Upload($resim);
							if ($handle->uploaded) {
								/* Resmi Yeniden Adlandır */
								$rand=uniqid(true);
								$handle->file_new_name_body = $rand;
								
								/* Resmi Yeniden Boyutlandır */
								if(!empty($w))
								{
									if(!empty($h))
									{
										$handle->image_resize = true;
										$handle->image_x = $w;
										$handle->image_y = $h;
									}
									else
									{
										if($handle->image_src_x>$w)
										{
											$handle->image_resize = true;
											$handle->image_ratio_y = true;
											$handle->image_x = $w;
										}
									}
								}
								else if(!empty($h))
								{
									if($handle->image_src_h>$h)
									{
										$handle->image_resize = true;
										$handle->image_ratio_x = true;
										$handle->image_y = $h;
									}
								}
								
								if(!empty($resimyazisi))
								{
									$handle->image_text = $resimyazisi;
									$handle->image_text_color = '#FFFFFF';
									$handle->image_text_opacity = 70;
									$handle->image_text_background = '#000000';
									$handle->image_text_background_opacity = 40;
									$handle->image_text_font = 5;
									$handle->image_text_padding = 20;
								}
								
								// Windows uyumlu yol oluştur
								$yuklenecekyer = rtrim(str_replace('/', DIRECTORY_SEPARATOR, $yuklenecekyer), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
								
								// Klasör yoksa oluştur
								if (!is_dir($yuklenecekyer)) {
									mkdir($yuklenecekyer, 0755, true);
								}
								
								// Klasör yazılabilir mi kontrol et
								if (!is_writable($yuklenecekyer)) {
									error_log("Klasör yazılabilir değil: " . $yuklenecekyer);
									chmod($yuklenecekyer, 0755);
								}
								
								$handle->Process($yuklenecekyer);
								if($handle->processed)
								{
									$yukleme=$rand.".".$handle->image_src_type;
									if($tablo!="nan")
									{
										$sutunlar=array("KID","resim","tablo");
										$veriler=array($KID,$yukleme,$tablo);
										$this->SorguCalistir("INSERT INTO resimler",$sutunlar,$veriler);
									}
									$handle->Clean();
									return true;
								}
								else
								{
									error_log("Resim işleme hatası: " . $handle->error);
									return false;
								}
							}
							else
							{
								error_log("Resim yükleme hatası: " . $handle->error);
								return false;
							}
						}
					}
				}
				else
				{
					error_log("Geçersiz dosya uzantısı: " . $uzanti);
					return false;
				}
			}
			else
			{
				error_log("Dosya seçilmedi");
				return false;
			}
		}
		else if($tur=="video")
		{
			if(!empty($_FILES[$nesnename]["name"][0]))
			{
				$videolar = array();
				foreach ($_FILES[$nesnename] as $k => $l) {
					foreach ($l as $i => $v) {
						if (!array_key_exists($i, $videolar))
							$videolar[$i] = array();
						$videolar[$i][$k] = $v;
					}
				}
				
				$izinliVideoUzantilari = array("mp4", "webm", "ogg", "mov", "avi");
				$maxVideoSize = 104857600; // 100MB
				$basariliYukleme = false;
				
				foreach ($videolar as $video){
					$uzanti = strtolower($this->uzanti($video["name"]));
					
					// Video dosyası uzantı kontrolü
					if(in_array($uzanti, $izinliVideoUzantilari))
					{
						// Benzersiz dosya adı oluştur
						$rand = 'video_' . uniqid(true);
						$yeniDosyaAdi = $rand . '.' . $uzanti;
						
						// Windows uyumlu yol oluştur
						$yuklenecekyer = rtrim(str_replace('/', DIRECTORY_SEPARATOR, $yuklenecekyer), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
						
						// Yükleme dizini kontrolü
						if (!is_dir($yuklenecekyer)) {
							mkdir($yuklenecekyer, 0755, true);
						}
						
						// Dosyayı yükle
						if(move_uploaded_file($video["tmp_name"], $yuklenecekyer . $yeniDosyaAdi)) {
							error_log("Video dosyası başarıyla yüklendi: " . $yuklenecekyer . $yeniDosyaAdi);
							
							// Veritabanına kaydet
							if($tablo != "nan") {
								$sql = $this->SorguCalistir(
									"INSERT INTO resimler",
									"SET tablo=?, KID=?, resim=?, tarih=?",
									array($tablo, $KID, $yeniDosyaAdi, date("Y-m-d"))
								);
								
								if($sql) {
									error_log("Video DB kaydı başarılı");
									$basariliYukleme = true;
								} else {
									error_log("Video DB kaydı başarısız");
								}
							}
						} else {
							error_log("Video yükleme hatası: " . $video["name"] . " - " . $video["tmp_name"] . " -> " . $yuklenecekyer . $yeniDosyaAdi);
						}
					} else {
						error_log("Geçersiz video uzantısı: " . $uzanti);
					}
				}
				
				return $basariliYukleme;
			}
			
			return false;
		}
		else
		{
			return false;
		}
	}
	
	public function ModulEkle()
	{
		if(!empty($_POST["baslik"]) && !empty($_POST["durum"]) && !empty($_POST["sira"]))
		{
			$baslik=$_POST["baslik"];
			$durum=$_POST["durum"];
			$sira=$_POST["sira"];
			$tablo=$_POST["tablo"];
			
			$sutunlar=array("baslik","tablo","durum","sira");
			$veriler=array($baslik,$tablo,$durum,$sira);
			
			$this->SorguCalistir("INSERT INTO moduller",$sutunlar,$veriler);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function kategoriGetir($tablo,$secID="",$uz=-1)
	{
		$uz++;
		$kategoriler=$this->VeriGetir("kategoriler","WHERE tablo=? AND ust_ID=?",array($tablo,0),"ORDER BY sira ASC");
		if($kategoriler!=false)
		{
			for($i=0;$i<count($kategoriler);$i++)
			{
				$kategoriler[$i]["uzunluk"]=$uz;
				if($secID==$kategoriler[$i]["ID"])
				{$kategoriler[$i]["secim"]="selected";}
				else
				{$kategoriler[$i]["secim"]="";}
				
				$altKategoriler=$this->kategoriGetir($tablo,$secID,$kategoriler[$i]["ID"],$uz);
				if($altKategoriler!=false)
				{
					$kategoriler=array_merge($kategoriler,$altKategoriler);
				}
			}
		}
		else
		{
			$kategoriler=false;
		}
		
		return $kategoriler;
	}
	
	public function kategoriGetir2($tablo,$sef="",$secID="",$uz=-1)
	{
		$uz++;
		$kategoriler=$this->VeriGetir("kategoriler","WHERE seflink=? AND tablo=? AND ust_ID=?",array($sef,$tablo,0),"ORDER BY sira ASC");
		if($kategoriler!=false)
		{
			for($i=0;$i<count($kategoriler);$i++)
			{
				$kategoriler[$i]["uzunluk"]=$uz;
				if($secID==$kategoriler[$i]["ID"])
				{$kategoriler[$i]["secim"]="selected";}
				else
				{$kategoriler[$i]["secim"]="";}
				
				$altKategoriler=$this->kategoriGetir2($tablo,$sef,$kategoriler[$i]["ID"],$uz);
				if($altKategoriler!=false)
				{
					$kategoriler=array_merge($kategoriler,$altKategoriler);
				}
			}
		}
		else
		{
			$kategoriler=false;
		}
		
		return $kategoriler;
	}
	
	public function tekKategori($tablo,$secID="",$uz=-1)
	{
		$uz++;
		$kategoriler=$this->VeriGetir("kategoriler","WHERE tablo=? AND ust_ID=?",array($tablo,0),"ORDER BY sira ASC");
		if($kategoriler!=false)
		{
			for($i=0;$i<count($kategoriler);$i++)
			{
				$kategoriler[$i]["uzunluk"]=$uz;
				if($secID==$kategoriler[$i]["ID"])
				{$kategoriler[$i]["secim"]="selected";}
				else
				{$kategoriler[$i]["secim"]="";}
				
				$altKategoriler=$this->tekKategori($tablo,$secID,$kategoriler[$i]["ID"],$uz);
				if($altKategoriler!=false)
				{
					$kategoriler=array_merge($kategoriler,$altKategoriler);
				}
			}
		}
		else
		{
			$kategoriler=false;
		}
		
		return $kategoriler;
	}
	
	public function TekilCogul()
	{
		$tekil=$this->VeriGetir("ayarlar","WHERE ID=?",array(1),"ORDER BY ID ASC",1);
		if($tekil!=false)
		{
			$sonuc=$tekil[0]["ziyaretci"]+1;
			$this->SorguCalistir("UPDATE ayarlar SET ziyaretci=? WHERE ID=?",array($sonuc,1),1);
		}
	}
	
	public function tarayiciGetir()
	{
		$tarayici=$_SERVER["HTTP_USER_AGENT"];
		$msie=strpos($tarayici,'MSIE') ? true : false;
		$firefox=strpos($tarayici,'Firefox') ? true : false;
		$chrome=strpos($tarayici,'Chrome') ? true : false;
		$safari=strpos($tarayici,'Safari') ? true : false;
		$opera=strpos($tarayici,'Opera') ? true : false;
		$netscape=strpos($tarayici,'Netscape') ? true : false;
		
		if($msie) return "Internet Explorer";
		if($firefox) return "Mozilla Firefox";
		if($chrome) return "Google Chrome";
		if($safari) return "Safari";
		if($opera) return "Opera";
		if($netscape) return "Netscape";
	}
	
	public function ipGetir(){
		if(getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
			if (strstr($ip, ',')) {
				$tmp = explode (',', $ip);
				$ip = trim($tmp[0]);
			}
		} else {
			$ip = getenv("REMOTE_ADDR");
		}
		return $ip;
	}
	
	public function MailGonder($mail, $konu="", $mesaj)
	{
		$posta = new PHPMailer();
		$posta->CharSet = "UTF-8";
		$posta->IsSMTP();                                   // send via SMTP
		$posta->Host     = "mail.siteadi.com"; // SMTP servers
		$posta->SMTPAuth = true;     // turn on SMTP authentication
		$posta->Username = "info@siteadi.com";  // SMTP username
		$posta->Password = "mailsifresi"; // SMTP password
		$posta->From     = "info@siteadi.com"; // smtp kullanýcý adýnýz ile ayný olmalý
		$posta->Fromname = "www.siteadi.com";
		$posta->AddAddress($mail, "Müşteri");
		$posta->Subject  =  $konu;
		$posta->Body     =  $mesaj;
		
		if(!$posta->Send())
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
?> 
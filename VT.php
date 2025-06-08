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
			$sql="SELECT * FROM ".$tablo; /*SELECT * FROM ayarlar*/
			if(!empty($wherealanlar) && !empty($wherearraydeger))
			{
				$sql.=" ".$wherealanlar; /*SELECT * FROM ayarlarWHERE */
				if(!empty($ordeby)){$sql.=" ".$ordeby;}
				if(!empty($limit)){$sql.=" LIMIT ".$limit;}
				$calistir=$this->baglanti->prepare($sql);
				$sonuc=$calistir->execute($wherearraydeger);
				$veri=$calistir->fetchAll(PDO::FETCH_ASSOC);
			}
			else
			{
				if(!empty($ordeby)){$sql.=" ".$ordeby;}
				if(!empty($limit)){$sql.=" LIMIT ".$limit;}
				$veri=$this->baglanti->query($sql,PDO::FETCH_ASSOC);
			}
			
			if($veri!=false && !empty($veri))
			{
				$datalar=array();
				foreach($veri as $bilgiler)
				{
					$datalar[]=$bilgiler; 
				}
				return $datalar;
			}
			else
			{
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
			error_log("VT.SorguCalistir PDO hatasi: " . $e->getMessage() . " (SQL: $sql, Params: " . json_encode($degerlerarray) . ")");
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
				if($uzanti=="png" || $uzanti=="jpg" || $uzanti=="jpeg" || $uzanti=="gif")
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
						if($uzanti=="png" || $uzanti=="jpg" || $uzanti=="jpeg" || $uzanti=="gif")
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
									$handle->image_x = $w;
									$handle->image_ratio_y = true;
								}
							}
						}
						else if(!empty($h))
						{
							if($handle->image_src_h>$h)
							{
								$handle->image_resize = true;
								$handle->image_y = $h;
								$handle->image_ratio_x = true;
							}
						}
		
		//üzerine yazı yazdırma
		if(!empty($resimyazisi))
		{
							$handle->image_text   = $resimyazisi;
						$handle->image_text_color      = '#FFFFFF';
						$handle->image_text_opacity    = 80;
						//$handle->image_text_background = '#FFFFFF';
						$handle->image_text_background_opacity = 70;
						$handle->image_text_font       = 5;
						$handle->image_text_padding    = 1;
		}
			
			
		/* Resim Yükleme İzni */
		$handle->allowed = array('image/*');
		
		/* Resmi İşle */
		//$handle->Process(realpath("../")."/upload/resim/");
		$handle->Process($yuklenecekyer);
		if ($handle->processed) {
		      $yukleme=$rand.".".$handle->image_src_type;
			  if(!empty($yukleme))
				{
					//$yuklemekontrol=$fnk->DKontrol("../images/resimler/".$yukleme);
					$sira=$this->IDGetir("resimler");
					
					// Check if the upload directory exists, if not create it
					if (!is_dir($yuklenecekyer)) {
						mkdir($yuklenecekyer, 0755, true);
					}
					
					// Ensure correct path for database (store relative path)
					$dbPath = $yukleme;
					
					// Insert the record with the correct path
					$sql=$this->SorguCalistir("INSERT INTO resimler","SET tablo=?, KID=?, resim=?, tarih=?",array($tablo,$KID,$dbPath,date("Y-m-d")));
					
					
				}
				else
				{
					 return false;
				}
				
		} else {
                   return false;
		}

		$handle-> Clean();

	} else {
		return false;
	}
					
					
						}
					}
					return true;
					
					
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function ModulEkle()
	{
		if(!empty($_POST["baslik"]))
		{
			$baslik=$_POST["baslik"];
			if(!empty($_POST["durum"])){$durum=1;}else{$durum=2;}
			$tablo=str_replace("-","",$this->seflink($baslik));
			$kontrol=$this->VeriGetir("moduller","WHERE tablo=?",array($tablo),"ORDER BY ID ASC",1);
			if($kontrol!=false)
			{
				return false;
			}
			else
			{
				$ekle=$this->SorguCalistir("INSERT INTO moduller","SET baslik=?, tablo=?, durum=?, tarih=?",array($baslik,$tablo,$durum,date("Y-m-d")));
				//$this->ModulTabloOlustur($tablo);
				if($ekle!=false)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}

	public function upload($nesnename,$yuklenecekyer='images/',$tur='img',$w='',$h='',$resimyazisi='')
	{
		if($tur=="img")
		{
			if(!empty($_FILES[$nesnename]["name"]))
			{
				$dosyanizinadi=$_FILES[$nesnename]["name"];
				$tmp_name=$_FILES[$nesnename]["tmp_name"];
				$uzanti=$this->uzanti($dosyanizinadi);
				if($uzanti=="png" || $uzanti=="jpg" || $uzanti=="jpeg" || $uzanti=="gif")
				{
					$classIMG=new upload($_FILES[$nesnename]);
					if($classIMG->uploaded)
					{
						if(!empty($w))
						{
							if(!empty($h))
							{
								$classIMG->image_resize=true;
								$classIMG->image_x=$w;
								$classIMG->image_y=$h;
							}
							else
							{
								if($classIMG->image_src_x>$w)
								{
									$classIMG->image_resize=true;
									$classIMG->image_ratio_y=true;
									$classIMG->image_x=$w;
								}
							}
						}
						else if(!empty($h))
						{
							if($classIMG->image_src_h>$h)
							{
								$classIMG->image_resize=true;
								$classIMG->image_ratio_x=true;
								$classIMG->image_y=$h;
							}
						}
						
						if(!empty($resimyazisi))
						{
							$classIMG->image_text = $resimyazisi;

						    $classIMG->image_text_direction = 'v';
						
						    $classIMG->image_text_color = '#FFFFFF';
						
						    $classIMG->image_text_position = 'BL';
						}
						$rand=uniqid(true);
						$classIMG->file_new_name_body=$rand;
						$classIMG->Process($yuklenecekyer);
						if($classIMG->processed)
						{
							$resimadi=$rand.".".$classIMG->image_src_type;
							return $resimadi;
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else if($tur=="ds")
		{
			
			if(!empty($_FILES[$nesnename]["name"]))
			{
				
				$dosyanizinadi=$_FILES[$nesnename]["name"];
				$tmp_name=$_FILES[$nesnename]["tmp_name"];
				$uzanti=$this->uzanti($dosyanizinadi);
				if($uzanti=="doc" || $uzanti=="docx" || $uzanti=="pdf" || $uzanti=="xlsx" || $uzanti=="xls" || $uzanti=="ppt" || $uzanti=="xml" || $uzanti=="mp4" || $uzanti=="avi" || $uzanti=="mov")
				{
					
					$classIMG=new upload($_FILES[$nesnename]);
					if($classIMG->uploaded)
					{
						$rand=uniqid(true);
						$classIMG->file_new_name_body=$rand;
						$classIMG->Process($yuklenecekyer);
						if($classIMG->processed)
						{
							$dokuman=$rand.".".$uzanti;
							return $dokuman;
						}
						else
						{
							return false;
						}
					}
				}
			}
		}
	}
	
	
	public function kategoriGetir($tablo,$secID="",$uz=-1)
	{
		$uz++;
		$kategori=$this->VeriGetir("kategoriler","WHERE tablo=? AND kullanim=? ORDER BY sira ASC",array($tablo,1));
		if($kategori!=false)
		{
			for($q=0;$q<count($kategori);$q++)
			{
				$kategoriseflink=$kategori[$q]["seflink"];
				$ID=$kategori[$q]["ID"];
				if($secID==$ID)
				{
					echo '<option value="'.$ID.'" selected="selected">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				else
				{
					echo '<option value="'.$ID.'">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				
				if($kategoriseflink==$this->seflink($tablo))
				{
					$this->kategoriGetir($ID,$secID,$uz);
				}
				
			}
		}
	}
	
	public function kategoriGetir2($tablo,$sef="",$secID="",$uz=-1)
	{
		$uz++;
		$kategori=$this->VeriGetir("kategoriler","WHERE seflink=? AND kullanim=? ORDER BY sira ASC",array($tablo,1));
		if($kategori!=false)
		{
			for($q=0;$q<count($kategori);$q++)
			{
				$kategoriseflink=$kategori[$q]["seflink"];
				$ID=$kategori[$q]["ID"];
				if($secID==$ID)
				{
					echo '<option value="'.$ID.'" selected="selected">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				else
				{
					echo '<option value="'.$ID.'">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				
				if($kategoriseflink==$sef)
				{
					$this->kategoriGetir2($ID,$sef,$secID,$uz);
				}
				
			}
		}
	}
	
	public function tekKategori($tablo,$secID="",$uz=-1)
	{
		$uz++;
		$kategori=$this->VeriGetir("kategoriler","WHERE ID=? ORDER BY sira ASC",array($tablo));
		if($kategori!=false)
		{
			for($q=0;$q<count($kategori);$q++)
			{
				$kategoriseflink=$kategori[$q]["seflink"];
				$ID=$kategori[$q]["ID"];
				if($secID==$ID)
				{
					echo '<option value="'.$ID.'" selected="selected">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				else
				{
					echo '<option value="'.$ID.'">'.str_repeat("&nbsp;&nbsp;&nbsp;",$uz).stripslashes($kategori[$q]["baslik"]).'</option>';
				}
				
			}
		}
	}
	
	
	public function TekilCogul()
	{
		$tr_case = array(
            'tr'=>array(
                array('yat','tekne','bot','yelkenli'),
                array('yatlar','tekneler','botlar','yelkenliler')
            ),
            'en'=>array(
                array('yacht','boat','sailboat'),
                array('yachts','boats','sailboats')
            ),
            'de'=>array(
                array('yacht','boot','segelboot'),
                array('yachten','boote','segelboote')
            ),
            'ru'=>array(
                array('яхта','лодка','парусник'),
                array('яхты','лодки','парусники')
            ),
            'fr'=>array(
                array('yacht','bateau','voilier'),
                array('yachts','bateaux','voiliers')
            )
        );
        
        return $tr_case;
	}
	
	public function tarayiciGetir()
	{
		$tarayicibul = array('MSIE', 'Trident', 'Edge', 'Firefox', 'OPR', 'Opera', 'Chrome', 'Safari', 'Netscape', 'Konqueror', 'Gecko');
		$tarayicilarr = array('Internet Explorer', 'Internet Explorer', 'Microsoft Edge', 'Mozilla Firefox', 'Opera', 'Opera', 'Google Chrome', 'Safari', 'Netscape', 'Konqueror', 'Mozilla');
		
		$user = $_SERVER['HTTP_USER_AGENT'];
		
		foreach ($tarayicibul as $key => $değer)
		{
			if (strpos($user, $değer) !== false)
			{
				return $tarayicilarr[$key];
			}
		}
		return 'Diğer';
	}
	
	public function ipGetir(){
		if(isset($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else{
			$ip = $_SERVER["REMOTE_ADDR"];
		}
		return $ip;
	}
	
	public function MailGonder($mail, $konu="", $mesaj)
	{
		// First check if PHPMailer files are included
		if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
			// Try to include PHPMailer if not already loaded
			$phpmailer_path = __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
			if (file_exists($phpmailer_path)) {
				require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
				require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
				require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
			} else {
				// Try alternative location (in case PHPMailer is not installed via composer)
				$alt_path = __DIR__ . '/../includes/PHPMailer/PHPMailer.php';
				if (file_exists($alt_path)) {
					require_once __DIR__ . '/../includes/PHPMailer/PHPMailer.php';
					require_once __DIR__ . '/../includes/PHPMailer/SMTP.php';
					require_once __DIR__ . '/../includes/PHPMailer/Exception.php';
				} else {
					error_log("PHPMailer not found at expected paths");
					return false;
				}
			}
		}
		
		try {
			// Use the appropriate namespace based on how PHPMailer is installed
			if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
				$posta = new \PHPMailer\PHPMailer\PHPMailer(true);
			} else if (class_exists('PHPMailer')) {
				$posta = new \PHPMailer(true);
			} else {
				error_log("PHPMailer class still not available after inclusion attempts");
				return false;
			}
			
			$posta->CharSet = "UTF-8";
			
			$posta->IsSMTP();
			$posta->Host     = "mail.orientyacht.com";  // Update with actual mail server
			$posta->SMTPAuth = true;
			$posta->Username = "info@orientyachting.com";  // Update with actual email
			$posta->Password = "OrientYacht2024";      // Update with actual password
			$posta->Port     = 587;
			$posta->SMTPSecure = 'tls';
			$posta->SMTPDebug = 0;  // Set to 2 for debugging
			
			$posta->From     = "info@orientyachting.com";
			$posta->Fromname = "Orient Yacht";
			$posta->AddAddress($mail, "Orient Yacht Customer");
			$posta->Subject  = $konu;
			$posta->Body     = $mesaj;
			$posta->IsHTML(true);
			
			if(!$posta->Send()) {
				error_log("Email sending failed. Recipient: $mail, Subject: $konu, Error: " . $posta->ErrorInfo);
				return false;
			}
			
			return true;
		} catch (Exception $e) {
			error_log("Exception in email sending to $mail: " . $e->getMessage());
			return false;
		}
	}
}
?> 
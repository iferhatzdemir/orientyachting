<?php
if(!empty($_GET["silinecekID"]))
{
	$ID=$VT->filter($_GET["silinecekID"]);
	$tablo=$VT->filter($_GET["tablo"]);
	$kayitID=$VT->filter($_GET["ID"]);
	
	// Silme işlemi hata ayıklama bilgileri
	$silmeLog = "<!-- Silme işlemi başlatıldı:
		Silinecek ID: {$ID}
		Tablo: {$tablo}
		Kayıt ID: {$kayitID}
	-->";
	
	$logData = array(
	    'id' => $ID,
	    'tablo' => $tablo,
	    'kayitID' => $kayitID
	);
	
	// Medya bilgilerini veritabanından al
	$veri=$VT->VeriGetir("resimler","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
	if($veri!=false)
	{
		$resim=$veri[0]["resim"];
		$dbTablo=$veri[0]["tablo"]; // Veritabanından gelen tablo bilgisi
		
		$silmeLog .= "<!-- Veritabanından alınan medya:
		  Dosya Adı: {$resim}
		  DB Tablo: {$dbTablo}
		-->";
		
		$fileDeleted = false;
		
		// Tüm olası dosya yollarını kontrol et
		$possiblePaths = array(
		    "../images/yachts/".$resim,             // Yat resim klasörü
		    "../images/yachts/videos/".$resim,      // Yat video klasörü
		    "../images/resimler/".$resim,           // Genel resim klasörü
		    "../images/resimler/videos/".$resim,    // Genel video klasörü
		    "../images/gallery/".$resim             // Galeri klasörü
		);
		
		$silmeLog .= "<!-- Kontrol edilen dosya yolları: -->";
		
		foreach($possiblePaths as $path) {
		    $silmeLog .= "<!-- Kontrol: {$path} - ";
		    
		    if(file_exists($path)) {
		        $silmeLog .= "BULUNDU - ";
		        
		        // Dosya erişim izinlerini kontrol et
		        if(is_writable($path)) {
		            $silmeLog .= "YAZILABİLİR - ";
		            
		            try {
		                if(@unlink($path)) {
		                    $fileDeleted = true;
		                    $silmeLog .= "SİLİNDİ";
		                    break; // Dosya silindi, döngüden çık
		                } else {
		                    $silmeLog .= "SİLİNEMEDİ, HATA: " . error_get_last()['message'];
		                }
		            } catch(Exception $e) {
		                $silmeLog .= "SİLME HATA: " . $e->getMessage();
		            }
		        } else {
		            $silmeLog .= "YAZMA İZNİ YOK";
		        }
		    } else {
		        $silmeLog .= "BULUNAMADI";
		    }
		    
		    $silmeLog .= " -->";
		}
		
		// Veritabanı kaydını sil
		$sil=$VT->SorguCalistir("DELETE FROM resimler","WHERE ID=?",array($ID),1);
		$silmeLog .= "<!-- Veritabanı silme sonucu: ".($sil ? 'Başarılı' : 'Başarısız')." -->";
		
		// Debug bilgileri
		echo $silmeLog;
		
		// Yönlendirme mesajı
		if($sil) {
			?>
			<!-- SweetAlert2 kütüphanesi -->
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					Swal.fire({
						icon: 'success',
						title: 'Başarılı!',
						text: '<?=$fileDeleted ? "Medya dosyası ve veritabanı kaydı" : "Veritabanı kaydı"?> başarıyla silindi.',
						showConfirmButton: false,
						timer: 1500
					}).then(function() {
						window.location.href = "<?=SITE?>resimler/<?=$tablo?>/<?=$kayitID?>";
					});
				});
			</script>
		<?php
		} else {
			?>
			<!-- SweetAlert2 kütüphanesi -->
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					Swal.fire({
						icon: 'error',
						title: 'Hata!',
						text: 'Veritabanından kayıt silinemedi.',
						showConfirmButton: false,
						timer: 1500
					}).then(function() {
						window.location.href = "<?=SITE?>resimler/<?=$tablo?>/<?=$kayitID?>";
					});
				});
			</script>
		<?php
		}
	}
	else
	{
		?>
		<!-- SweetAlert2 kütüphanesi -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				Swal.fire({
					icon: 'error',
					title: 'Hata!',
					text: 'Silinecek medya bulunamadı.',
					showConfirmButton: false,
					timer: 1500
				}).then(function() {
					window.location.href = "<?=SITE?>resimler/<?=$tablo?>/<?=$kayitID?>";
				});
			});
		</script>
        <?php
	}
}
else
{
	?>
	<!-- SweetAlert2 kütüphanesi -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			Swal.fire({
				icon: 'error',
				title: 'Hata!',
				text: 'Silinecek medya ID bilgisi eksik.',
				showConfirmButton: false,
				timer: 1500
			}).then(function() {
				window.location.href = "<?=SITE?>";
			});
		});
	</script>
    <?php
}
?>
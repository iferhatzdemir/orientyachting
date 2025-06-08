<?php
if($_POST) {
  if(!empty($_POST["adsoyad"]) && !empty($_POST["email"]) && !empty($_POST["telefon"]) 
     && !empty($_POST["kisi_sayisi"]) && !empty($_POST["baslangic_tarihi"]) 
     && !empty($_POST["bitis_tarihi"]) && !empty($_POST["yat_id"])) {
    
    $adsoyad = $VT->filter($_POST["adsoyad"]);
    $email = $VT->filter($_POST["email"]);
    $telefon = $VT->filter($_POST["telefon"]);
    $kisi_sayisi = $VT->filter($_POST["kisi_sayisi"]);
    $baslangic_tarihi = $VT->filter($_POST["baslangic_tarihi"]);
    $bitis_tarihi = $VT->filter($_POST["bitis_tarihi"]);
    $mesaj = $VT->filter($_POST["mesaj"]);
    $yat_id = $VT->filter($_POST["yat_id"]);
    
    // Check if yacht exists
    $yat = $VT->VeriGetir("yatlar", "WHERE ID=? AND durum=?", array($yat_id, 1), "ORDER BY ID ASC", 1);
    if($yat != false) {
      // Check date format
      if(strtotime($baslangic_tarihi) && strtotime($bitis_tarihi)) {
        // Check if end date is after start date
        if(strtotime($bitis_tarihi) > strtotime($baslangic_tarihi)) {
          // Check if person count is valid
          if($kisi_sayisi > 0 && $kisi_sayisi <= $yat[0]["yolcu_kapasitesi"]) {
            // Check for availability (this is a simple check, could be more complex)
            $tarihKontrol = $VT->VeriGetir("rezervasyonlar", 
                "WHERE yat_id=? AND durum=? AND 
                ((baslangic_tarihi BETWEEN ? AND ?) OR 
                (bitis_tarihi BETWEEN ? AND ?) OR 
                (baslangic_tarihi <= ? AND bitis_tarihi >= ?))", 
                array($yat_id, 1, $baslangic_tarihi, $bitis_tarihi, 
                      $baslangic_tarihi, $bitis_tarihi, 
                      $baslangic_tarihi, $bitis_tarihi));
            
            if($tarihKontrol == false) {
              // All checks passed, save reservation
              $ekle = $VT->SorguCalistir("INSERT INTO rezervasyonlar", 
                      "SET yat_id=?, adsoyad=?, email=?, telefon=?, kisi_sayisi=?, 
                       baslangic_tarihi=?, bitis_tarihi=?, mesaj=?, durum=?, tarih=?", 
                      array($yat_id, $adsoyad, $email, $telefon, $kisi_sayisi, 
                            $baslangic_tarihi, $bitis_tarihi, $mesaj, 0, date("Y-m-d H:i:s")));
              
              if($ekle != false) {
                // Success message
                echo '<div class="container mt-5 mb-5">
                  <div class="alert alert-success">
                    <h4 class="alert-heading">Rezervasyon Talebiniz Alındı!</h4>
                    <p>Rezervasyon talebinizi aldık ve en kısa sürede sizinle iletişime geçeceğiz.</p>
                    <hr>
                    <p class="mb-0">Rezervasyon detaylarını e-posta adresinize gönderdik.</p>
                  </div>
                  <div class="text-center">
                    <a href="'.SITE.'" class="btn btn-primary">Anasayfaya Dön</a>
                    <a href="'.SITE.'yatlar" class="btn btn-secondary">Diğer Yatları İncele</a>
                  </div>
                </div>';
                
                // Send email notification to admin
                $mailIcerik = "Yeni Rezervasyon Talebi\n\n";
                $mailIcerik .= "Yat: ".$yat[0]["baslik"]."\n";
                $mailIcerik .= "Ad Soyad: ".$adsoyad."\n";
                $mailIcerik .= "E-posta: ".$email."\n";
                $mailIcerik .= "Telefon: ".$telefon."\n";
                $mailIcerik .= "Kişi Sayısı: ".$kisi_sayisi."\n";
                $mailIcerik .= "Başlangıç: ".date("d.m.Y", strtotime($baslangic_tarihi))."\n";
                $mailIcerik .= "Bitiş: ".date("d.m.Y", strtotime($bitis_tarihi))."\n";
                $mailIcerik .= "Mesaj: ".$mesaj."\n";
                
                $VT->MailGonder($sitemail, "Yeni Rezervasyon Talebi", $mailIcerik);
              } else {
                echo '<div class="container mt-5 mb-5">
                  <div class="alert alert-danger">
                    <h4 class="alert-heading">Hata!</h4>
                    <p>Rezervasyon talebiniz kaydedilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.</p>
                  </div>
                  <div class="text-center">
                    <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
                  </div>
                </div>';
              }
            } else {
              echo '<div class="container mt-5 mb-5">
                <div class="alert alert-warning">
                  <h4 class="alert-heading">Tarih Çakışması!</h4>
                  <p>Seçtiğiniz tarihler için bu yat müsait değil. Lütfen başka bir tarih aralığı seçiniz.</p>
                </div>
                <div class="text-center">
                  <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
                </div>
              </div>';
            }
          } else {
            echo '<div class="container mt-5 mb-5">
              <div class="alert alert-warning">
                <h4 class="alert-heading">Geçersiz Kişi Sayısı!</h4>
                <p>Girdiğiniz kişi sayısı yatın kapasitesinden fazla olamaz. Bu yatın maksimum kapasitesi: '.$yat[0]["yolcu_kapasitesi"].' kişidir.</p>
              </div>
              <div class="text-center">
                <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
              </div>
            </div>';
          }
        } else {
          echo '<div class="container mt-5 mb-5">
            <div class="alert alert-warning">
              <h4 class="alert-heading">Geçersiz Tarih Aralığı!</h4>
              <p>Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.</p>
            </div>
            <div class="text-center">
              <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
            </div>
          </div>';
        }
      } else {
        echo '<div class="container mt-5 mb-5">
          <div class="alert alert-warning">
            <h4 class="alert-heading">Geçersiz Tarih Formatı!</h4>
            <p>Lütfen geçerli bir tarih formatı kullanınız.</p>
          </div>
          <div class="text-center">
            <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
          </div>
        </div>';
      }
    } else {
      echo '<div class="container mt-5 mb-5">
        <div class="alert alert-danger">
          <h4 class="alert-heading">Yat Bulunamadı!</h4>
          <p>Rezervasyon yapmak istediğiniz yat bulunamadı veya aktif değil.</p>
        </div>
        <div class="text-center">
          <a href="'.SITE.'yatlar" class="btn btn-primary">Yat Listesine Dön</a>
        </div>
      </div>';
    }
  } else {
    echo '<div class="container mt-5 mb-5">
      <div class="alert alert-warning">
        <h4 class="alert-heading">Eksik Bilgi!</h4>
        <p>Lütfen rezervasyon formunda tüm zorunlu alanları doldurunuz.</p>
      </div>
      <div class="text-center">
        <a href="javascript:history.back()" class="btn btn-primary">Geri Dön</a>
      </div>
    </div>';
  }
} else {
  // Redirect to homepage if form not submitted
  ?>
  <meta http-equiv="refresh" content="0;url=<?=SITE?>">
  <?php
}
?> 
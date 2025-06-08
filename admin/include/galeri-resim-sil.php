<?php
if(!empty($_GET["ID"]) && !empty($_GET["resimID"]))
{
  $ID=$VT->filter($_GET["ID"]);
  $resimID=$VT->filter($_GET["resimID"]);
  
  try {
    // Galerinin var olup olmadığını kontrol et
    $galeri=$VT->VeriGetir("galeri", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
    if($galeri!=false)
    {
      // Resmin var olup olmadığını kontrol et
      $resim=$VT->VeriGetir("resimler", "WHERE ID=? AND tablo=? AND KID=?", array($resimID, "galeri", $ID), "ORDER BY ID ASC", 1);
      if($resim!=false)
      {
        // Silme işlemi bilgisi - debug için
        $silmeLog = "<!-- Galeri Resim Silme:
          Galeri ID: {$ID}
          Resim ID: {$resimID}
          Resim: {$resim[0]["resim"]}
        -->";
        
        $fileDeleted = false;
        
        // Tüm olası dosya yollarını kontrol et
        $possiblePaths = array(
            "../images/resimler/".$resim[0]["resim"],           // Genel resim klasörü
            "../images/resimler/videos/".$resim[0]["resim"],    // Genel video klasörü 
            "../images/gallery/".$resim[0]["resim"]             // Eski galeri klasörü
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
                            $silmeLog .= "SİLİNEMEDİ, HATA: " . (error_get_last() ? error_get_last()['message'] : 'Bilinmeyen hata');
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
        $sil=$VT->SorguCalistir("DELETE FROM resimler", "WHERE ID=?", array($resimID));
        $silmeLog .= "<!-- Veritabanı silme sonucu: ".($sil !== false ? 'Başarılı' : 'Başarısız')." -->";
        
        // Debug bilgilerini ekrana yaz
        echo $silmeLog;
        
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'success',
              title: 'Başarılı!',
              text: '<?=$fileDeleted ? "Resim dosyası ve veritabanı kaydı" : "Veritabanı kaydı"?> başarıyla silindi.',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              window.location.href = "<?=SITE?>galeri-resimler/<?=$ID?>";
            });
          });
        </script>
        <?php
      }
      else
      {
        // Resim bulunamadı
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Silinecek resim bulunamadı.',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              window.location.href = "<?=SITE?>galeri-resimler/<?=$ID?>";
            });
          });
        </script>
        <?php
      }
    }
    else
    {
      // Galeri bulunamadı
      ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'Galeri bulunamadı.',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            window.location.href = "<?=SITE?>galeri";
          });
        });
      </script>
      <?php
    }
  } catch(Exception $e) {
    // Beklenmeyen hata
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Hata!',
          text: 'İşlem sırasında bir hata oluştu: <?=$e->getMessage()?>',
          showConfirmButton: false,
          timer: 2000
        }).then(function() {
          window.location.href = "<?=SITE?>galeri-resimler/<?=$ID?>";
        });
      });
    </script>
    <?php
  }
}
else
{
  // ID bilgisi eksik
  ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: 'Galeri veya resim ID bilgisi eksik.',
        showConfirmButton: false,
        timer: 1500
      }).then(function() {
        window.location.href = "<?=SITE?>galeri";
      });
    });
  </script>
  <?php
}
?>
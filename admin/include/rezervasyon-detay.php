<?php
if(!empty($_GET["ID"])) {
  $ID = $VT->filter($_GET["ID"]);
  $rezervasyon = $VT->VeriGetir("rezervasyonlar", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
  
  if($rezervasyon != false) {
    // Get yacht details
    $yat = $VT->VeriGetir("yatlar", "WHERE ID=?", array($rezervasyon[0]["yat_id"]), "ORDER BY ID ASC", 1);
    $yatAdi = $yat != false ? $yat[0]["baslik"] : "Silinmiş Yat";
    
    // Update reservation status
    if(!empty($_POST["durum"])) {
      $durum = $VT->filter($_POST["durum"]);
      $guncelle = $VT->SorguCalistir("UPDATE rezervasyonlar", "SET durum=? WHERE ID=?", array($durum, $ID));
      
      if($guncelle != false) {
        echo '<div class="alert alert-success">Rezervasyon durumu güncellendi.</div>';
        $rezervasyon[0]["durum"] = $durum; // Update local variable
      } else {
        echo '<div class="alert alert-danger">Rezervasyon durumu güncellenirken bir sorun oluştu.</div>';
      }
    }
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Rezervasyon Detayı</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
            <li class="breadcrumb-item"><a href="<?=SITE?>rezervasyonlar">Rezervasyonlar</a></li>
            <li class="breadcrumb-item active">Rezervasyon Detayı</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Rezervasyon Bilgileri</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 150px;">Rezervasyon ID</th>
                  <td><?=$rezervasyon[0]["ID"]?></td>
                </tr>
                <tr>
                  <th>Yat</th>
                  <td>
                    <?=$yatAdi?>
                    <?php if($yat != false) { ?>
                    <a href="<?=ANASITE?>yat/<?=$yat[0]["seflink"]?>" target="_blank" class="btn btn-xs btn-info ml-2">
                      <i class="fas fa-external-link-alt"></i> Yat Sayfasını Gör
                    </a>
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <th>Başlangıç Tarihi</th>
                  <td><?=date("d.m.Y", strtotime($rezervasyon[0]["baslangic_tarihi"]))?></td>
                </tr>
                <tr>
                  <th>Bitiş Tarihi</th>
                  <td><?=date("d.m.Y", strtotime($rezervasyon[0]["bitis_tarihi"]))?></td>
                </tr>
                <tr>
                  <th>Kişi Sayısı</th>
                  <td><?=$rezervasyon[0]["kisi_sayisi"]?></td>
                </tr>
                <tr>
                  <th>Durum</th>
                  <td>
                    <?php if($rezervasyon[0]["durum"] == 1) { ?>
                      <span class="badge badge-success">Onaylandı</span>
                    <?php } else { ?>
                      <span class="badge badge-warning">Beklemede</span>
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <th>Rezervasyon Tarihi</th>
                  <td><?=date("d.m.Y H:i", strtotime($rezervasyon[0]["tarih"]))?></td>
                </tr>
              </table>
              
              <form action="#" method="post" class="mt-3">
                <div class="form-group">
                  <label>Rezervasyon Durumunu Güncelle</label>
                  <select name="durum" class="form-control">
                    <option value="0" <?=$rezervasyon[0]["durum"] == 0 ? 'selected' : ''?>>Beklemede</option>
                    <option value="1" <?=$rezervasyon[0]["durum"] == 1 ? 'selected' : ''?>>Onaylandı</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save mr-1"></i> Güncelle
                </button>
                <a href="<?=SITE?>rezervasyonlar" class="btn btn-secondary ml-2">
                  <i class="fas fa-arrow-left mr-1"></i> Rezervasyonlara Dön
                </a>
              </form>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Müşteri Bilgileri</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 150px;">Ad Soyad</th>
                  <td><?=$rezervasyon[0]["adsoyad"]?></td>
                </tr>
                <tr>
                  <th>E-posta</th>
                  <td><?=$rezervasyon[0]["email"]?></td>
                </tr>
                <tr>
                  <th>Telefon</th>
                  <td><?=$rezervasyon[0]["telefon"]?></td>
                </tr>
              </table>
              
              <div class="mt-4">
                <h5 class="font-weight-bold">Müşteri Mesajı:</h5>
                <div class="p-3 bg-light rounded">
                  <?=!empty($rezervasyon[0]["mesaj"]) ? nl2br($rezervasyon[0]["mesaj"]) : 'Mesaj bulunmuyor.'?>
                </div>
              </div>
              
              <div class="mt-4">
                <h5 class="font-weight-bold">Hızlı İletişim:</h5>
                <a href="mailto:<?=$rezervasyon[0]["email"]?>" class="btn btn-info">
                  <i class="fas fa-envelope mr-1"></i> E-posta Gönder
                </a>
                <a href="tel:<?=$rezervasyon[0]["telefon"]?>" class="btn btn-success ml-2">
                  <i class="fas fa-phone mr-1"></i> Ara
                </a>
              </div>
            </div>
          </div>
          
          <?php if($yat != false) { ?>
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Yat Fiyat Bilgisi</h3>
            </div>
            <div class="card-body">
              <?php
              // Calculate date difference
              $baslangic = new DateTime($rezervasyon[0]["baslangic_tarihi"]);
              $bitis = new DateTime($rezervasyon[0]["bitis_tarihi"]);
              $fark = $baslangic->diff($bitis);
              $gun = $fark->days;
              
              // Calculate total price
              $gunlukFiyat = $yat[0]["fiyat"];
              $toplamFiyat = $gun * $gunlukFiyat;
              ?>
              
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Günlük Fiyat:</strong> $<?=number_format($gunlukFiyat, 2)?></p>
                  <p><strong>Gün Sayısı:</strong> <?=$gun?> gün</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Toplam Tutar:</strong> <span class="text-danger font-weight-bold">$<?=number_format($toplamFiyat, 2)?></span></p>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
  } else {
    echo '<div class="alert alert-danger">Rezervasyon bulunamadı.</div>';
    ?>
    <meta http-equiv="refresh" content="2;url=<?=SITE?>rezervasyonlar">
    <?php
  }
} else {
  ?>
  <meta http-equiv="refresh" content="0;url=<?=SITE?>rezervasyonlar">
  <?php
}
?> 
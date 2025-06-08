<?php
if(empty($_GET["seflink"])) {
  echo '<meta http-equiv="refresh" content="0;url='.ANASITE.'galeri">';
  exit;
}

$seflink = $VT->filter($_GET["seflink"]);
$galeri = $VT->VeriGetir("galeri", "WHERE seflink=? AND durum=?", array($seflink, 1), "ORDER BY ID ASC", 1);

if($galeri == false) {
  echo '<meta http-equiv="refresh" content="0;url='.ANASITE.'galeri">';
  exit;
}

// Get gallery images
$resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $galeri[0]["ID"]), "ORDER BY ID ASC");

// Get category info if applicable
$kategori = "";
if(!empty($galeri[0]["kategori"])) {
  $kategoriData = $VT->VeriGetir("galeri_kategoriler", "WHERE ID=?", array($galeri[0]["kategori"]), "ORDER BY ID ASC", 1);
  if($kategoriData != false) {
    $kategori = $kategoriData[0]["baslik"];
  }
}
?>

<!-- Page Title
============================================= -->
<section id="page-title" class="page-title-mini">
  <div class="container clearfix">
    <h1><?=$galeri[0]["baslik"]?></h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=ANASITE?>">Anasayfa</a></li>
      <li class="breadcrumb-item"><a href="<?=ANASITE?>galeri">Galeri</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?=$galeri[0]["baslik"]?></li>
    </ol>
  </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
  <div class="content-wrap">
    <div class="container clearfix">

      <div class="row mb-5">
        <div class="col-12">
          <h2 class="mb-3"><?=$galeri[0]["baslik"]?></h2>
          <?php if(!empty($kategori)): ?>
            <div class="badge bg-primary p-2 mb-3"><?=$kategori?></div>
          <?php endif; ?>
        </div>
      </div>

      <?php if($resimler != false): ?>
        <!-- Gallery Grid
        ============================================= -->
        <div class="row col-mb-30">
          
          <?php foreach($resimler as $resim): 
            $resimYolu = ANASITE."images/resimler/".$resim["resim"];
          ?>
            <div class="col-lg-4 col-md-6 col-12">
              <div class="grid-inner">
                <div class="portfolio-image">
                  <a href="<?=$resimYolu?>" data-lightbox="gallery-item">
                    <img src="<?=$resimYolu?>" alt="<?=$galeri[0]["baslik"]?>">
                  </a>
                  <div class="bg-overlay">
                    <div class="bg-overlay-content dark" data-hover-animate="fadeIn">
                      <a href="<?=$resimYolu?>" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350" data-lightbox="gallery-item"><i class="icon-line-zoom-in"></i></a>
                    </div>
                    <div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          
        </div>
      <?php else: ?>
        <div class="alert alert-info">Bu galeriye ait resim bulunamadı.</div>
      <?php endif; ?>

      <!-- Back Button -->
      <div class="row mt-5">
        <div class="col-12">
          <a href="<?=ANASITE?>galeri" class="button button-3d button-rounded button-blue"><i class="icon-arrow-left"></i> Geri Dön</a>
        </div>
      </div>

    </div>
  </div>
</section><!-- #content end --> 
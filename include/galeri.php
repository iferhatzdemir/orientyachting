<?php
// Get active gallery categories
$kategoriler = $VT->VeriGetir("galeri_kategoriler", "WHERE durum=?", array(1), "ORDER BY sira ASC");

// Get all active galleries
$galeriler = $VT->VeriGetir("galeri", "WHERE durum=?", array(1), "ORDER BY sira ASC");
?>

<!-- Page Title
		============================================= -->
<section id="page-title" class="page-title-mini">
  <div class="container clearfix">
    <h1>Galeri</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=ANASITE?>">Anasayfa</a></li>
      <li class="breadcrumb-item active" aria-current="page">Galeri</li>
    </ol>
  </div>
</section><!-- #page-title end -->

<!-- Content
		============================================= -->
<section id="content">
  <div class="content-wrap">
    <div class="container clearfix">

      <?php if ($kategoriler != false || $galeriler != false): ?>
        
        <?php if ($kategoriler != false): ?>
          <!-- Gallery Filter
          ============================================= -->
          <div class="mb-5">
            <h3 class="mb-2">Kategoriler</h3>
            <ul id="portfolio-filter" class="portfolio-filter clearfix" data-container="#portfolio">
              <li class="activeFilter"><a href="#" data-filter="*">Tümü</a></li>
              <?php foreach ($kategoriler as $kategori): ?>
                <li><a href="#" data-filter=".kategori-<?=$kategori["ID"]?>"><?=$kategori["baslik"]?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Gallery Items
        ============================================= -->
        <div id="portfolio" class="portfolio row grid-container g-0" data-layout="fitRows">
          
          <?php if ($galeriler != false): ?>
            <?php foreach ($galeriler as $galeri): 
              // Get gallery images
              $resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $galeri["ID"]), "ORDER BY ID ASC");
              if ($resimler != false): 
                $ilkResim = $resimler[0]; // Use first image as gallery cover
                $resimYolu = ANASITE."images/resimler/".$ilkResim["resim"];
                
                // Get kategori class
                $kategoriClass = "";
                if (!empty($galeri["kategori"])) {
                  $kategoriClass = "kategori-".$galeri["kategori"];
                }
            ?>
            
            <article class="portfolio-item col-md-4 col-sm-6 col-12 <?=$kategoriClass?>">
              <div class="grid-inner">
                <div class="portfolio-image">
                  <a href="<?=ANASITE?>galeri-detay/<?=$galeri["seflink"]?>">
                    <img src="<?=$resimYolu?>" alt="<?=$galeri["baslik"]?>">
                  </a>
                  <div class="bg-overlay">
                    <div class="bg-overlay-content dark" data-hover-animate="fadeIn">
                      <a href="<?=$resimYolu?>" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350" data-lightbox="image" title="<?=$galeri["baslik"]?>"><i class="icon-line-plus"></i></a>
                      <a href="<?=ANASITE?>galeri-detay/<?=$galeri["seflink"]?>" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350"><i class="icon-line-ellipsis"></i></a>
                    </div>
                    <div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
                  </div>
                </div>
                <div class="portfolio-desc">
                  <h3><a href="<?=ANASITE?>galeri-detay/<?=$galeri["seflink"]?>"><?=$galeri["baslik"]?></a></h3>
                  <span>
                    <?php 
                    if (!empty($galeri["kategori"])) {
                      $kategoriData = $VT->VeriGetir("galeri_kategoriler", "WHERE ID=?", array($galeri["kategori"]), "ORDER BY ID ASC", 1);
                      if ($kategoriData != false) {
                        echo $kategoriData[0]["baslik"];
                      }
                    } else {
                      echo "Genel";
                    }
                    ?>
                  </span>
                </div>
              </div>
            </article>
            
            <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info">Henüz galeri eklenmemiş.</div>
            </div>
          <?php endif; ?>

        </div><!-- #portfolio end -->

      <?php else: ?>
        <div class="alert alert-info">Henüz galeri eklenmemiş.</div>
      <?php endif; ?>

    </div>
  </div>
</section><!-- #content end --> 
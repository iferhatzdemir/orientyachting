
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Banner Liste</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Banner Liste</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
      <div class="row">
      <div class="col-md-12">
       <a href="<?=SITE?>banner-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ EKLE</a>
       </div>
       </div>
       <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width:50px;">Sıra</th>
                  <th>Açıklama</th>
                  <th style="width:50px;">Durum</th>
                  <th style="width:80px;">Tarih</th>
                  <th style="width:120px;">Diller</th>
                  <th style="width:120px;">İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php
				$veriler=$VT->VeriGetir("banner","","","ORDER BY ID ASC");
				if($veriler!=false)
				{
					$sira=0;
					for($i=0;$i<count($veriler);$i++)
					{
						$sira++;
						if($veriler[$i]["durum"]==1){$aktifpasif=' checked="checked"';}else{$aktifpasif='';}
						
						// Hangi dillerde çeviri var kontrol et
						$diller = array();
						$ceviriler = $VT->VeriGetir("banner_dil", "WHERE banner_id=?", array($veriler[$i]["ID"]));
						if($ceviriler != false) {
							foreach($ceviriler as $ceviri) {
								$diller[] = strtoupper($ceviri["lang"]);
							}
						}
						?>
                        <tr>
                          <td><?=$sira?></td>
                          <td>
                          <a href="<?=ANASITE?>images/banner/<?=$veriler[$i]["resim"]?>" data-fancybox="gallery" data-caption="<?=stripslashes($veriler[$i]["baslik"])?>">
                              <img src="<?=ANASITE?>images/banner/<?=$veriler[$i]["resim"]?>" style="height: 60px; width: auto; margin-right: 8px; float: left;">
                            </a>
                            <?=stripslashes($veriler[$i]["baslik"])?>
                            <?php if(!empty($veriler[$i]["video"])): 
                          
                            ?>
                            <br>
                            <video width="320" height="240" controls>
                                <source src="<?=ANASITE?>images/banner/<?=$veriler[0]["video"]?>" type="video/mp4">
                                Video desteklenmiyor.
                              </video>
                            <?php endif; ?>
                          </td>
                          <td>
                          <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      <input type="checkbox" class="custom-control-input aktifpasif<?=$veriler[$i]["ID"]?>" id="customSwitch3<?=$veriler[$i]["ID"]?>"<?=$aktifpasif?> value="<?=$veriler[$i]["ID"]?>" onclick="aktifpasif(<?=$veriler[$i]["ID"]?>,'banner');">
                      <label class="custom-control-label" for="customSwitch3<?=$veriler[$i]["ID"]?>"></label>
                    </div>
                          </td>
                          <td><?=$veriler[$i]["tarih"]?></td>
                          <td>
                            <?php if(count($diller) > 0): ?>
                              <?php foreach($diller as $dil): ?>
                                <span class="badge badge-info"><?=$dil?></span>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <span class="badge badge-warning">Çeviri Yok</span>
                            <?php endif; ?>
                          </td>
                          <td>
                          <a href="<?=SITE?>banner-duzenle/<?=$veriler[$i]["ID"]?>" class="btn btn-warning btn-sm">Düzenle</a>
                          <a href="<?=SITE?>banner-sil/<?=$veriler[$i]["ID"]?>" class="btn btn-danger btn-sm silmeAlani">Kaldır</a>
                          </td>
                        </tr>
                        <?php
					}
				}
				?>               
                </tbody>
                <tfoot>
                <tr>
                  <th>Sıra</th>
                  <th>Açıklama</th>
                  <th>Durum</th>
                  <th>Tarih</th>
                  <th>Diller</th>
                  <th>İşlem</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
       
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 

<!-- Fancybox CSS ve JS dosyaları -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
  // Fancybox başlatma - jQuery versiyonu
  $(document).ready(function() {
    $("[data-fancybox]").fancybox({
      buttons: [
        "zoom",
        "slideShow",
        "fullScreen",
        "download",
        "thumbs",
        "close"
      ],
      animationEffect: "zoom",
      transitionEffect: "fade",
      loop: true,
      protect: true
    });
  });
</script>

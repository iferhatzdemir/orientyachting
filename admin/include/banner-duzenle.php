<?php
$ID=$VT->filter($_GET["ID"]);
$veri=$VT->VeriGetir("banner","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Banner Düzenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Banner Düzenle</li>
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
      <a href="<?=SITE?>banner-liste" class="btn btn-info" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="fas fa-bars"></i> LİSTE</a> 
       <a href="<?=SITE?>banner-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ EKLE</a>
       </div>
       </div>
       <?php
	   if($_POST)
	   {
		   if(!empty($_POST["baslik"]) && !empty($_POST["sirano"]))
		   {
			   $baslik=$VT->filter($_POST["baslik"]);
         $aciklama=$VT->filter($_POST["aciklama"]);
         $url=$VT->filter($_POST["url"]);
			   $sirano=$VT->filter($_POST["sirano"]);
			   
         // Resim yükleme
         $resimGuncelle = false;
         if (!empty($_FILES["resim"]["name"]))
         {
             if ($_FILES["resim"]["error"] === UPLOAD_ERR_INI_SIZE || $_FILES["resim"]["error"] === UPLOAD_ERR_FORM_SIZE) {
                 echo "<div class='alert alert-danger'>Resim boyutu sunucu limitlerinden büyük. upload_max_filesize ve post_max_size değerlerini kontrol edin.</div>";
             } else {
                 $yukle = $VT->upload("resim","../images/banner/");
                 if($yukle !== false)
                 {
                     $resimGuncelle = true;
                     // Eski resmi silme işlemi
                     if(!empty($veri[0]["resim"])) {
                         unlink("../images/banner/".$veri[0]["resim"]);
                     }
                 }
             }
         }
         
         // Video yükleme
         $videoGuncelle = false;
         if (!empty($_FILES["video"]["name"])) {
             if ($_FILES["video"]["error"] === UPLOAD_ERR_INI_SIZE || $_FILES["video"]["error"] === UPLOAD_ERR_FORM_SIZE) {
                 echo "<div class='alert alert-danger'>Video boyutu sunucu limitlerinden büyük. upload_max_filesize ve post_max_size değerlerini kontrol edin.</div>";
             } else {
                 $videoYukle = $VT->upload("video","../images/banner/","video");
                 if($videoYukle !== false) {
                     $videoGuncelle = true;
                     // Eski videoyu silme işlemi
                     if(!empty($veri[0]["video"])) {
                         unlink("../images/banner/".$veri[0]["video"]);
                     }
                 }
             }
         }
         
         // Güncellenecek değerleri ayarla (NULL veya yeni yükleme)
         $resimDeger = $resimGuncelle ? $yukle : null;
         $videoDeger = $videoGuncelle ? $videoYukle : null;
         
         // Veri güncelleme
         // Güncelleme sorgusunu oluştur
         $fields = [
           'baslik'   => $baslik,
           'aciklama' => $aciklama,
           'url'      => $url,
           'durum'    => 1,
           'sirano'   => $sirano,
           'tarih'    => date("Y-m-d")
         ];
         $sqlSet = "SET baslik=?, aciklama=?, url=?";
         $params = [$fields['baslik'], $fields['aciklama'], $fields['url']];
         if($resimDeger !== null) {
             $sqlSet .= ", resim=?";
             $params[] = $resimDeger;
         }
         if($videoDeger !== null) {
             $sqlSet .= ", video=?";
             $params[] = $videoDeger;
         }
         $sqlSet .= ", durum=?, sirano=?, tarih=? WHERE ID=?";
         $params[] = $fields['durum'];
         $params[] = $fields['sirano'];
         $params[] = $fields['tarih'];
         $params[] = $veri[0]['ID'];
         $guncelle = $VT->SorguCalistir(
           "UPDATE banner",
           $sqlSet,
           $params
         );
				   
			   if($guncelle!=false)
			   {
				    // Dil çevirilerini güncelle
					if(!empty($_POST["dil"])) {
						foreach($_POST["dil"] as $dil => $degerler) {
							if(!empty($degerler["baslik"])) {
								$dil_baslik = $VT->filter($degerler["baslik"]);
								$dil_aciklama = $VT->filter($degerler["aciklama"]);
								$dil_url = $VT->filter($degerler["url"]);
								
								// Önce mevcut çeviri var mı kontrol et
								$dil_kontrol = $VT->VeriGetir("banner_dil", "WHERE banner_id=? AND lang=?", array($veri[0]["ID"], $dil), "ORDER BY ID ASC", 1);
								
								if($dil_kontrol != false) {
									// Güncelle
									$VT->SorguCalistir("UPDATE banner_dil", "SET baslik=?, aciklama=?, url=? WHERE ID=?", 
									array($dil_baslik, $dil_aciklama, $dil_url, $dil_kontrol[0]["ID"]));
								} else {
									// Ekle
									$VT->SorguCalistir("INSERT INTO banner_dil", "SET banner_id=?, lang=?, baslik=?, aciklama=?, url=?", 
									array($veri[0]["ID"], $dil, $dil_baslik, $dil_aciklama, $dil_url));
								}
							}
						}
					}

				    ?>
                   <div class="alert alert-success">İşleminiz başarıyla kaydedildi.</div>
                   <?php
			   }
			   else
			   {
				    ?>
                   <div class="alert alert-danger">İşleminiz sırasında bir sorunla karşılaşıldı. Lütfen daha sonra tekrar deneyiniz.</div>
                   <?php
			   }
		   }
		   else
		   {
			   ?>
               <div class="alert alert-danger">Boş bıraktığınız alanları doldurunuz.</div>
               <?php
		   }
	   }
	   ?>
       <form action="#" method="post" enctype="multipart/form-data">
       <div class="col-md-12">
       <div class="card-body card card-primary">
            <div class="row">
            
            <!-- Dil tabları oluşturma -->
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="genel-tab" data-toggle="tab" href="#genel" role="tab" aria-controls="genel" aria-selected="true">Genel Bilgiler</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tr" role="tab" aria-controls="tr" aria-selected="false">Türkçe</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">İngilizce</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="de-tab" data-toggle="tab" href="#de" role="tab" aria-controls="de" aria-selected="false">Almanca</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="ru-tab" data-toggle="tab" href="#ru" role="tab" aria-controls="ru" aria-selected="false">Rusça</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content" id="language-tabsContent">
                      <!-- Genel bilgiler -->
                      <div class="tab-pane fade show active" id="genel" role="tabpanel" aria-labelledby="genel-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Başlık</label>
                              <input type="text" class="form-control" placeholder="Başlık ..." name="baslik" value="<?=stripslashes($veri[0]["baslik"])?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>Açıklama</label>
                              <input type="text" class="form-control" placeholder="Açıklama ..." name="aciklama" value="<?=stripslashes($veri[0]["aciklama"])?>">
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Resim (Ana Görsel)</label>
                              <input type="file" class="form-control" placeholder="Resim Seçiniz ..." name="resim">
                              <small class="text-muted">Video yüklerseniz resim yüklemeniz zorunlu değildir.</small>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Video (MP4, MOV, AVI)</label>
                              <input type="file" class="form-control" placeholder="Video Seçiniz ..." name="video" accept="video/mp4,video/x-m4v,video/mov,video/avi">
                              <small class="text-muted">Video yüklerseniz görsel yerine video gösterilecektir.</small>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="form-group">
                              <label>Sıra No</label>
                              <input type="number" class="form-control" placeholder="Sıra No ..." name="sirano" style="width:100px;" value="<?=stripslashes($veri[0]["sirano"])?>">
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Url</label>
                              <input type="text" class="form-control" placeholder="Url ..." name="url" value="<?=stripslashes($veri[0]["url"])?>">
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Mevcut Resim</label>
                              <img src="../images/banner/<?=$veri[0]["resim"]?>" style="height: 150px;">
                              </div>
                          </div>
                          <?php if(!empty($veri[0]["video"])): ?>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Mevcut Video</label>
                              <video width="320" height="240" controls>
                                <source src="<?phpANASITE?>/images/banner/<?=$veri[0]["video"]?>" type="video/mp4">
                                Video desteklenmiyor.
                              </video>
                              </div>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                      
                      <?php
                      // Çevirileri yükle
                      $diller = array("tr", "en", "de", "ru");
                      $ceviriler = array();
                      
                      foreach($diller as $dil) {
                          $ceviri = $VT->VeriGetir("banner_dil", "WHERE banner_id=? AND lang=?", array($veri[0]["ID"], $dil), "ORDER BY ID ASC", 1);
                          if($ceviri != false) {
                              $ceviriler[$dil] = $ceviri[0];
                          }
                      }
                      ?>
                      
                      <!-- Türkçe tab -->
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Başlık (Türkçe)</label>
                              <input type="text" class="form-control" placeholder="Başlık ..." name="dil[tr][baslik]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>Açıklama (Türkçe)</label>
                              <input type="text" class="form-control" placeholder="Açıklama ..." name="dil[tr][aciklama]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (Türkçe)</label>
                              <input type="text" class="form-control" placeholder="URL ..." name="dil[tr][url]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["url"]) : ""?>">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- İngilizce tab -->
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Başlık (İngilizce)</label>
                              <input type="text" class="form-control" placeholder="Title ..." name="dil[en][baslik]" value="<?=!empty($ceviriler["en"]) ? stripslashes($ceviriler["en"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>Açıklama (İngilizce)</label>
                              <input type="text" class="form-control" placeholder="Description ..." name="dil[en][aciklama]" value="<?=!empty($ceviriler["en"]) ? stripslashes($ceviriler["en"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (İngilizce)</label>
                              <input type="text" class="form-control" placeholder="URL ..." name="dil[en][url]" value="<?=!empty($ceviriler["en"]) ? stripslashes($ceviriler["en"]["url"]) : ""?>">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Almanca tab -->
                      <div class="tab-pane fade" id="de" role="tabpanel" aria-labelledby="de-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Başlık (Almanca)</label>
                              <input type="text" class="form-control" placeholder="Titel ..." name="dil[de][baslik]" value="<?=!empty($ceviriler["de"]) ? stripslashes($ceviriler["de"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>Açıklama (Almanca)</label>
                              <input type="text" class="form-control" placeholder="Beschreibung ..." name="dil[de][aciklama]" value="<?=!empty($ceviriler["de"]) ? stripslashes($ceviriler["de"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (Almanca)</label>
                              <input type="text" class="form-control" placeholder="URL ..." name="dil[de][url]" value="<?=!empty($ceviriler["de"]) ? stripslashes($ceviriler["de"]["url"]) : ""?>">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Rusça tab -->
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Başlık (Rusça)</label>
                              <input type="text" class="form-control" placeholder="Заголовок ..." name="dil[ru][baslik]" value="<?=!empty($ceviriler["ru"]) ? stripslashes($ceviriler["ru"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>Açıklama (Rusça)</label>
                              <input type="text" class="form-control" placeholder="Описание ..." name="dil[ru][aciklama]" value="<?=!empty($ceviriler["ru"]) ? stripslashes($ceviriler["ru"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (Rusça)</label>
                              <input type="text" class="form-control" placeholder="URL ..." name="dil[ru][url]" value="<?=!empty($ceviriler["ru"]) ? stripslashes($ceviriler["ru"]["url"]) : ""?>">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary">KAYDET</button>
                </div>
            </div>
           
            
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        </div>
        </div>
       </form>
      </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

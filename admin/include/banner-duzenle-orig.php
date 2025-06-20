<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);

    $veri=$VT->VeriGetir("banner","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
    if($veri!=false)
    {
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Banner DÃ¼zenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Banner DÃ¼zenle</li>
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
      <a href="<?=SITE?>banner-liste" class="btn btn-info" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="fas fa-bars"></i> LÄ°STE</a> 
       <a href="<?=SITE?>banner-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENÄ° EKLE</a>
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
			   
         if(!empty($_FILES["resim"]["name"]))
         {
          $yukle=$VT->upload("resim","../images/banner/");
           if($yukle!=false)
           {
             $guncelle=$VT->SorguCalistir("UPDATE banner","SET baslik=?, aciklama=?, url=?, resim=?, durum=?, sirano=?, tarih=? WHERE ID=?",array($baslik,$aciklama,$url,$yukle,1,$sirano,date("Y-m-d"),$veri[0]["ID"]));
             
             // Eski resmi silme iÅŸlemi
             $resim_yolu = "../images/banner/".$veri[0]["resim"];
             if(file_exists($resim_yolu)) {
                 unlink($resim_yolu);
             }
           }
           else
           {
             $guncelle=false;
              ?>
                   <div class="alert alert-info">Resim yÃ¼kleme iÅŸleminiz baÅŸarÄ±sÄ±z.</div>
                   <?php
           }
         }
         else
         {
          $guncelle=$VT->SorguCalistir("UPDATE banner","SET baslik=?, aciklama=?, url=?, durum=?, sirano=?, tarih=? WHERE ID=?",array($baslik,$aciklama,$url,1,$sirano,date("Y-m-d"),$veri[0]["ID"]));
         }
				   
			  
			   
			   if($guncelle!=false)
			   {
				    // Dil Ã§evirilerini gÃ¼ncelle
					if(!empty($_POST["dil"])) {
						foreach($_POST["dil"] as $dil => $degerler) {
							if(!empty($degerler["baslik"])) {
								$dil_baslik = $VT->filter($degerler["baslik"]);
								$dil_aciklama = $VT->filter($degerler["aciklama"]);
								$dil_url = $VT->filter($degerler["url"]);
								
								// Ã–nce mevcut Ã§eviri var mÄ± kontrol et
								$dil_kontrol = $VT->VeriGetir("banner_dil", "WHERE banner_id=? AND lang=?", array($veri[0]["ID"], $dil), "ORDER BY ID ASC", 1);
								
								if($dil_kontrol != false) {
									// GÃ¼ncelle
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
                   <div class="alert alert-success">Ä°ÅŸleminiz baÅŸarÄ±yla kaydedildi.</div>
                   <?php
			   }
			   else
			   {
				    ?>
                   <div class="alert alert-danger">Ä°ÅŸleminiz sÄ±rasÄ±nda bir sorunla karÅŸÄ±laÅŸÄ±ldÄ±. LÃ¼tfen daha sonra tekrar deneyiniz.</div>
                   <?php
			   }
		   }
		   else
		   {
			   ?>
               <div class="alert alert-danger">BoÅŸ bÄ±raktÄ±ÄŸÄ±nÄ±z alanlarÄ± doldurunuz.</div>
               <?php
		   }
	   }
	   ?>
       <form action="#" method="post" enctype="multipart/form-data">
       <div class="col-md-12">
       <div class="card-body card card-primary">
            <div class="row">
            
            <!-- Dil tablarÄ± oluÅŸturma -->
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="genel-tab" data-toggle="tab" href="#genel" role="tab" aria-controls="genel" aria-selected="true">Genel Bilgiler</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tr" role="tab" aria-controls="tr" aria-selected="false">TÃ¼rkÃ§e</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">Ä°ngilizce</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="de-tab" data-toggle="tab" href="#de" role="tab" aria-controls="de" aria-selected="false">Almanca</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="ru-tab" data-toggle="tab" href="#ru" role="tab" aria-controls="ru" aria-selected="false">RusÃ§a</a>
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
                              <label>BaÅŸlÄ±k</label>
                              <input type="text" class="form-control" placeholder="BaÅŸlÄ±k ..." name="baslik" value="<?=stripslashes($veri[0]["baslik"])?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>AÃ§Ä±klama</label>
                              <input type="text" class="form-control" placeholder="AÃ§Ä±klama ..." name="aciklama" value="<?=stripslashes($veri[0]["aciklama"])?>">
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>Resim (Ana GÃ¶rsel)</label>
                              <input type="file" class="form-control" placeholder="Resim SeÃ§iniz ..." name="resim">
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="form-group">
                              <label>SÄ±ra No</label>
                              <input type="number" class="form-control" placeholder="SÄ±ra No ..." name="sirano" style="width:100px;" value="<?=stripslashes($veri[0]["sirano"])?>">
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
                              <label>Resim</label>
                              <img src="../images/banner/<?=$veri[0]["resim"]?>" style="height: 150px;">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                      <?php
                      // Ã‡evirileri yÃ¼kle
                      $diller = array("tr", "en", "de", "ru");
                      $ceviriler = array();
                      
                      foreach($diller as $dil) {
                          $ceviri = $VT->VeriGetir("banner_dil", "WHERE banner_id=? AND lang=?", array($veri[0]["ID"], $dil), "ORDER BY ID ASC", 1);
                          if($ceviri != false) {
                              $ceviriler[$dil] = $ceviri[0];
                          }
                      }
                      ?>
                      
                      <!-- TÃ¼rkÃ§e tab -->
                      <div class="tab-pane fade" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>BaÅŸlÄ±k (TÃ¼rkÃ§e)</label>
                              <input type="text" class="form-control" placeholder="BaÅŸlÄ±k ..." name="dil[tr][baslik]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>AÃ§Ä±klama (TÃ¼rkÃ§e)</label>
                              <input type="text" class="form-control" placeholder="AÃ§Ä±klama ..." name="dil[tr][aciklama]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (TÃ¼rkÃ§e)</label>
                              <input type="text" class="form-control" placeholder="URL ..." name="dil[tr][url]" value="<?=!empty($ceviriler["tr"]) ? stripslashes($ceviriler["tr"]["url"]) : ""?>">
                              </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Ä°ngilizce tab -->
                      <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>BaÅŸlÄ±k (Ä°ngilizce)</label>
                              <input type="text" class="form-control" placeholder="Title ..." name="dil[en][baslik]" value="<?=!empty($ceviriler["en"]) ? stripslashes($ceviriler["en"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>AÃ§Ä±klama (Ä°ngilizce)</label>
                              <input type="text" class="form-control" placeholder="Description ..." name="dil[en][aciklama]" value="<?=!empty($ceviriler["en"]) ? stripslashes($ceviriler["en"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (Ä°ngilizce)</label>
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
                              <label>BaÅŸlÄ±k (Almanca)</label>
                              <input type="text" class="form-control" placeholder="Titel ..." name="dil[de][baslik]" value="<?=!empty($ceviriler["de"]) ? stripslashes($ceviriler["de"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>AÃ§Ä±klama (Almanca)</label>
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
                      
                      <!-- RusÃ§a tab -->
                      <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                              <label>BaÅŸlÄ±k (RusÃ§a)</label>
                              <input type="text" class="form-control" placeholder="Ð—Ð°Ð³Ð¾Ð»Ð¾Ð²Ð¾Ðº ..." name="dil[ru][baslik]" value="<?=!empty($ceviriler["ru"]) ? stripslashes($ceviriler["ru"]["baslik"]) : ""?>">
                              </div>
                          </div>
                         <div class="col-md-6">
                              <div class="form-group">
                              <label>AÃ§Ä±klama (RusÃ§a)</label>
                              <input type="text" class="form-control" placeholder="ÐžÐ¿Ð¸ÑÐ°Ð½Ð¸Ðµ ..." name="dil[ru][aciklama]" value="<?=!empty($ceviriler["ru"]) ? stripslashes($ceviriler["ru"]["aciklama"]) : ""?>">
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                              <label>URL (RusÃ§a)</label>
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
    <?php
	}
  }
  else
  {
    echo "HatalÄ± bilgi gÃ¶nderildi.";
  }
}
else
{
  echo "Bu sayfaya eriÅŸim izniniz bulunmamaktadÄ±r.";
}
?>

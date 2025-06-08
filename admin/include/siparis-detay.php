<?php
if(!empty($_GET["sipariskodu"]))
{
  $sipariskodu=$VT->filter($_GET["sipariskodu"]);
 
    $siparisler=$VT->VeriGetir("siparisler","WHERE sipariskodu=?",array($sipariskodu),"ORDER BY ID ASC",1);
    if($siparisler!=false)
    {
       $uyebilgisi=$VT->VeriGetir("uyeler","WHERE ID=? AND durum=?",array($siparisler[0]["uyeID"],1),"ORDER BY ID ASC",1);
      if($uyebilgisi!=false)
      {
      }
      else
      {
       ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>siparis-liste">
       <?php
            exit();
      }
    }
    else
    {
      ?>
      <meta http-equiv="refresh" content="0;url=<?=SITE?>siparis-liste">
      <?php
      exit();
    }
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sipariş Yönetim Ekranı</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Sipariş Yönetim Ekranı</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
      
       <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
            
              
            <?php
          
              if($siparisler[0]["odemetipi"]==1){$odemetipi="Kredi Kartı";}
              if($siparisler[0]["odemetipi"]==2){$odemetipi="Havale / EFT";}
              if($siparisler[0]["odemetipi"]==3){$odemetipi="Kapıda Ödeme";}
              ?>
        <h3 style="padding: 2px 10px; display: block; clear: both; background: #343436; color: #fff; padding-top: 5px;">SİPARİŞ ÖZETİ</h3>
        <table class="table solKolonGri">
          <tr>
            <th>SİPARİŞ KODU</th>
            <td><?=$siparisler[0]["sipariskodu"]?></td>
            <th>ÖDEME TİPİ</th>
            <td><?=$odemetipi?></td>
          </tr>
          <tr>
            <th>ALICI BİLGİSİ</th>
            <td><?php
            if($uyebilgisi[0]["tipi"]==1)
            {
              echo stripslashes($uyebilgisi[0]["ad"]." ".$uyebilgisi[0]["soyad"]);
            }
            else
            {
              echo stripslashes($uyebilgisi[0]["firmaadi"]);
            }
            ?></td>
            <th>KDV HARİÇ TUTAR</th>
            <td style="color: #607D8B;"><?=number_format($siparisler[0]["kdvharictutar"],2,",",".")?> TL</td>
          </tr>
          <tr>
            <th>ADRES BİLGİSİ</th>
            <td><?php
            $ilBilgi=$VT->VeriGetir("il","WHERE ID=?",array($uyebilgisi[0]["ilID"]),"ORDER BY ID ASC",1);
            echo stripslashes($uyebilgisi[0]["adres"]." ".$uyebilgisi[0]["ilce"]);
            if($ilBilgi!=false)
            {
              echo "/".mb_convert_case($ilBilgi[0]["ADI"],MB_CASE_UPPER,"UTF-8");
            }
            ?></td>
              <th>KDV TUTAR</th>
            <td style="color: #00bcd4;"><?=number_format($siparisler[0]["kdvtutar"],2,",",".")?> TL</td>
            
          </tr>
          <tr>
            <th>TARİH</th>
            <td><?=date("d.m.Y",strtotime($siparisler[0]["tarih"]))?></td>
            <?php
            $AlanBaslik="ÖDENEN";
            if($siparisler[0]["durum"]==1)
              {
              }
              else
              {
                $AlanBaslik="ÖDENECEK";
              }
            ?>
            <th><?=$AlanBaslik?> TUTAR</th>
            <td><strong style="color: #E91E63;"><?=number_format($siparisler[0]["odenentutar"],2,",",".")?> TL</strong></td>
            
          </tr>
          <tr>
          <th>KARGO BİLGİSİ</th>
          <td>
            <?php
              if($_POST)
              {
                if(!empty($_POST["kargodurum"]) && !empty($_POST["kargoadi"]) && !empty($_POST["takipno"]))
                {
                  $kargoadi=$VT->filter($_POST["kargoadi"]);
                   $takipno=$VT->filter($_POST["takipno"]);

                  $guncelle=$VT->SorguCalistir("UPDATE siparisler","SET kargoadi=?, takipno=? WHERE ID=?",array($kargoadi,$takipno,$siparisler[0]["ID"]),1);
                  ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>siparis-detay/<?=$siparisler[0]["sipariskodu"]?>">
       <?php
                }
              }
              ?>
              <form action="#" method="post">
                <input type="hidden" name="kargodurum" value="1">
                <select name="kargoadi">
                  <option value="Aras Kargo" <?php if($siparisler[0]["kargoadi"]=="Aras Kargo"){echo 'selected="selected"';}?>>Aras Kargo</option>
                 <option value="MNG Kargo" <?php if($siparisler[0]["kargoadi"]=="MNG Kargo"){echo 'selected="selected"';}?>>MNG Kargo</option>
                 <option value="Sürat Kargo" <?php if($siparisler[0]["kargoadi"]=="Sürat Kargo"){echo 'selected="selected"';}?>>Sürat Kargo</option>
                 <option value="Yurtiçi Kargo" <?php if($siparisler[0]["kargoadi"]=="Yurtiçi Kargo"){echo 'selected="selected"';}?>>Yurtiçi Kargo</option>
                 <option value="UPS Kargo" <?php if($siparisler[0]["kargoadi"]=="UPS Kargo"){echo 'selected="selected"';}?>>UPS Kargo</option>
                </select>
                <input type="text" name="takipno" value="<?=$siparisler[0]["takipno"]?>" placeholder="Takip No">
                <input type="submit" name="ilet" value="Güncelle">
              </form>

          </td>
          <th>ÖDEME DURUMU</th>
            <td>
              <?php
              if($_POST)
              {
                if(!empty($_POST["durum"]) && !empty($_POST["odemedurum"]))
                {
                  $odemedurum=$VT->filter($_POST["odemedurum"]);
                  $guncelle=$VT->SorguCalistir("UPDATE siparisler","SET durum=? WHERE ID=?",array($odemedurum,$siparisler[0]["ID"]),1);
                   ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>siparis-detay/<?=$siparisler[0]["sipariskodu"]?>">
       <?php
                }
              }
              ?>
              <form action="#" method="post">
                <input type="hidden" name="durum" value="1">
                <select name="odemedurum">
                  <option value="1" <?php if($siparisler[0]["durum"]==1){echo 'selected="selected"';}?>>Ödendi</option>
                  <option value="2"<?php if($siparisler[0]["durum"]==2){echo 'selected="selected"';}?>>Ödeme Bekliyor</option>
                </select>
                <input type="submit" name="ilet" value="Güncelle">
              </form>
            </td>
          
          </tr>
        </table>

        <h3 style="padding: 2px 10px; display: block; clear: both; background: #343436; color: #fff; padding-top: 5px;">SİPARİŞ VERİLEN ÜRÜNLER</h3>
        <table class="table tabhov">
          <tr>
            <th>ÜRÜN KODU</th>
            <th>RESİM</th>
            <th>AÇIKLAMA</th>
            <th>ÜRÜN FİYATI</th>
            <th>ADET</th>
            <th>TOPLAM TUTAR</th>
          </tr>
          <?php
          $siparisurunler=$VT->VeriGetir("siparisurunler","WHERE siparisID=?",array($siparisler[0]["ID"]),"ORDER BY ID ASC");
          if($siparisurunler!=false)
          {
            for ($i=0; $i <count($siparisurunler); $i++) { 
              $urunler=$VT->VeriGetir("urunler","WHERE ID=?",array($siparisurunler[$i]["urunID"]),"ORDER BY ID ASC",1);
              if($urunler!=false)
              {
                $ozellikler="";
                if(!empty($siparisurunler[$i]["varyasyonID"]))
                {
                  $varyasyonKontrol=$VT->VeriGetir("urunvaryasyonstoklari","WHERE ID=?",array($siparisurunler[$i]["varyasyonID"]),"ORDER BY ID ASC",1);
                  if($varyasyonKontrol!=false)
                  {
                    $varyasyonID=$varyasyonKontrol[0]["varyasyonID"];
                    $secenekID=$varyasyonKontrol[0]["secenekID"];

                    if(strpos($varyasyonID,"@")>0)
                    {
                      $varyasyonDizi=explode("@",$varyasyonID);
                      $secenekDizi=explode("@",$secenekID);
                      for($x=0;$x<count($varyasyonDizi);$x++)
                      {
                        $varyasyonBilgisi=$VT->VeriGetir("urunvaryasyonlari","WHERE ID=?",array($varyasyonDizi[$x]),"ORDER BY ID ASC",1);
                      $secenekBilgisi=$VT->VeriGetir("urunvaryasyonsecenekleri","WHERE ID=?",array($secenekDizi[$x]),"ORDER BY ID ASC",1);

                      if($varyasyonBilgisi!=false && $secenekBilgisi!=false)
                      {
                        $ozellikler=$ozellikler.stripslashes($secenekBilgisi[0]["baslik"])." ".stripslashes($varyasyonBilgisi[0]["baslik"])." ";
                      }
                      }
                    }
                    else
                    {
                      $varyasyonBilgisi=$VT->VeriGetir("urunvaryasyonlari","WHERE ID=?",array($varyasyonID),"ORDER BY ID ASC",1);
                      $secenekBilgisi=$VT->VeriGetir("urunvaryasyonsecenekleri","WHERE ID=?",array($secenekID),"ORDER BY ID ASC",1);

                      if($varyasyonBilgisi!=false && $secenekBilgisi!=false)
                      {
                        $ozellikler=stripslashes($secenekBilgisi[0]["baslik"])." ".stripslashes($varyasyonBilgisi[0]["baslik"]);
                      }

                    }
                  }
                }
                ?>
                <tr>
            <td><?=$urunler[0]["urunkodu"]?></td>
            <td><img src="<?=ANASITE?>images/urunler/<?=$urunler[0]["resim"]?>" style="height: 50px; width: auto; display: block;"></td>
            <td><?=stripslashes($urunler[0]["baslik"])?>
            <br>
            <small style="color: #009688; font-size: 13px;"><?=$ozellikler?></small></td>
            <td><?=number_format($siparisurunler[$i]["uruntutar"],2,",",".")?> TL</td>
            <td><?=$siparisurunler[$i]["adet"]?></td>
            <td><?=number_format($siparisurunler[$i]["toplamtutar"],2,",",".")?> TL</td>
          </tr>
                <?php
              }
            }
          }
          ?>

        </table>
        
       




            </div>
            <!-- /.card-body -->
          </div>
       
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 <?php
}
 ?>

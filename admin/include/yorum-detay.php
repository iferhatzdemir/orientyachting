<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);
 
    $yorumlar=$VT->VeriGetir("yorumlar","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
    if($yorumlar!=false)
    {
       $uyebilgisi=$VT->VeriGetir("uyeler","WHERE ID=? AND durum=?",array($yorumlar[0]["uyeID"],1),"ORDER BY ID ASC",1);
       $urunbilgisi=$VT->VeriGetir("urunler","WHERE ID=?",array($yorumlar[0]["urunID"]),"ORDER BY ID ASC",1);
      if($uyebilgisi!=false && $urunbilgisi!=false)
      {
      }
      else
      {
       ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>yorumlar">
       <?php
            exit();
      }
    }
    else
    {
      ?>
      <meta http-equiv="refresh" content="0;url=<?=SITE?>yorumlar">
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
            <h1 class="m-0 text-dark">Yorum Yönetim Ekranı</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Yorum Yönetim Ekranı</li>
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
            
              
          
        <h3 style="padding: 2px 10px; display: block; clear: both; background: #343436; color: #fff; padding-top: 5px;">YORUM DETAYI</h3>
        <table class="table solKolonGri">
          <tr>
            <th>ÜYE BİLGİSİ</th>
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
            <th>DURUM</th>
            <td>
<?php
 if($yorumlar[0]["durum"]==1)
              {
                ?>
                <strong style="color:#4caf50;">YAYINDA</strong>
                <?php
              }
              else
              {
                ?>
                <strong style="color:#ff9800;">PASİF</strong>
                <?php
              }
?>
            </td>
          </tr>
          <tr>
            <th>ÜRÜN BİLGİSİ</th>
            <td colspan="3">
              <img src="<?=ANASITE?>images/urunler/<?=$urunbilgisi[0]["resim"]?>" style="width: 100px; height: auto; float: left; margin: 5px;">
              <?=stripslashes($urunbilgisi[0]["baslik"])?>
            </td>
          </tr>
          <tr>
            <th>TARİH</th>
            <td><?=date("d.m.Y",strtotime($yorumlar[0]["tarih"]))?></td>
            <th></th>
            <td></td>
            
          </tr>
           <tr>
            <th>Yorumunuz</th>
            <td colspan="3"><?=stripslashes($yorumlar[0]["metin"])?></td>            
          </tr>
         
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

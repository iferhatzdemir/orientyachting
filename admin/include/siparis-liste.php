
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sipariş Listesi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Sipariş Listesi</li>
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>NO</th>
                  <th>MÜŞTERİ BİLGİSİ</th>
            <th>KDV HARİÇ TUTAR</th>
            <th>KDV TUTAR</th>
            <th>ÖDENEN TUTAR</th>
            <th>ÖDEME TİPİ</th>
            <th>ÖDEME DURUMU</th>
            <th>TARİH</th>
            <th>İNCELE</th>
                </tr>
                </thead>
                <tbody>
                  <?php
          $siparisler=$VT->VeriGetir("siparisler","","","ORDER BY takipno,ID DESC");
          if($siparisler!=false)
          {
            $sirano=0;
            for($i=0;$i<count($siparisler);$i++)
            {
              $sirano++;
              $uyebilgisi=$VT->VeriGetir("uyeler","WHERE ID=? AND durum=?",array($siparisler[$i]["uyeID"],1),"ORDER BY ID ASC",1);
  if($uyebilgisi!=false)
  {
              if($siparisler[$i]["odemetipi"]==1){$odemetipi="Kredi Kartı";}
              if($siparisler[$i]["odemetipi"]==2){$odemetipi="Havale / EFT";}
              if($siparisler[$i]["odemetipi"]==3){$odemetipi="Kapıda Ödeme";}
              if($uyebilgisi[0]["tipi"]==1){
                $adsoyad=$uyebilgisi[0]["ad"]." ".$uyebilgisi[0]["soyad"];
              }
              else
              {
                $adsoyad=stripslashes($uyebilgisi[0]["firmaadi"]);
              }
              ?>
              <tr>
                <td><?=$sirano?></td>
            <td><?=$adsoyad?><br>
              <span style="color: #e91e63; font-size: 12px;">Sipariş Kodu : <?=$siparisler[$i]["sipariskodu"]?></span><br />
              <span style="font-size: 12px;">Kargo Durumu : <?php
              if(!empty($siparisler[$i]["takipno"]))
              {
                ?>
                <small style="color: #4CAF50;">Kargolandı</small>
                <?php
              }
              else
              {
                ?>
                <small style="color: #FFC107;">Bekliyor</small>
                <?php
              }
              ?></span>
            </td>
            <td><?=number_format($siparisler[$i]["kdvharictutar"],2,",",".")?> TL</td>
            <td><?=number_format($siparisler[$i]["kdvtutar"],2,",",".")?> TL</td>
            <th style="background: #eee;"><?=number_format($siparisler[$i]["odenentutar"],2,",",".")?> TL</th>
            <td><?=$odemetipi?></td>
            <td style="text-align: center;">
              <?php
              if($siparisler[$i]["durum"]==1)
              {
                ?>
                <strong style="color:#4caf50;">ÖDENDİ</strong>
                <?php
              }
              else
              {
                ?>
                <strong style="color:#ff9800;">ÖDEME BEKLİYOR</strong>
                <?php
              }
              ?>

            </td>
            <td><?=date("d.m.Y",strtotime($siparisler[$i]["tarih"]))?></td>
            <td><a href="<?=SITE?>siparis-detay/<?=$siparisler[$i]["sipariskodu"]?>" class="btn btn-info btn-sm" style="padding: 5px 15px;">İncele &raquo;</a></td>
          </tr>

              <?php
            }
          }
          }
          else
          {
            ?>
            <tr>
              <td colspan="8">Henüz kayıtlı bir siparişiniz bulunmamaktadır.</td>
            </tr>
            <?php
          }
          ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>NO</th>
                 <th>MÜŞTERİ BİLGİSİ</th>
            <th>KDV HARİÇ TUTAR</th>
            <th>KDV TUTAR</th>
            <th>ÖDENEN TUTAR</th>
            <th>ÖDEME TİPİ</th>
            <th>ÖDEME DURUMU</th>
            <th>TARİH</th>
            <th>İNCELE</th>
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
 

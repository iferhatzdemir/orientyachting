<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);

    $veri=$VT->VeriGetir("hesapnumarasi","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
    if($veri!=false)
    {
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Hesap Numarası Düzenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Hesap Numarası Düzenle</li>
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
       <a href="<?=SITE?>hesap-numarasi-liste" class="btn btn-info" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="fas fa-bars"></i> LİSTE</a> 
       <a href="<?=SITE?>hesap-numarasi-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ EKLE</a>
       </div>
       </div>
       <?php
	   if($_POST)
	   {
		   if(!empty($_POST["baslik"]) && !empty($_POST["sirano"]))
		   {
			   $baslik=$VT->filter($_POST["baslik"]);
         $ibanno=$VT->filter($_POST["ibanno"]);
         $hesapno=$VT->filter($_POST["hesapno"]);
		 $hesapsahibi=$VT->filter($_POST["hesapsahibi"]);
			   $sirano=$VT->filter($_POST["sirano"]);
			   
         if(!empty($_FILES["resim"]["name"]))
         {
          $yukle=$VT->upload("resim","../images/hesapnumarasi/");
           if($yukle!=false)
           {
             $ekle=$VT->SorguCalistir("UPDATE hesapnumarasi","SET baslik=?, ibanno=?, hesapno=?, hesapsahibi=?, resim=?, durum=?, sirano=?, tarih=? WHERE ID=?",array($baslik,$ibanno,$hesapno,$hesapsahibi,$yukle,1,$sirano,date("Y-m-d"),$veri[0]["ID"]),1);
           }
           else
           {
             $ekle=false;
              ?>
                   <div class="alert alert-info">Resim yükleme işleminiz başarısız.</div>
                   <?php
           }
         }
         else
         {
          $ekle=$VT->SorguCalistir("UPDATE hesapnumarasi","SET baslik=?, ibanno=?, hesapno=?, hesapsahibi=?, durum=?, sirano=?, tarih=? WHERE ID=?",array($baslik,$ibanno,$hesapno,$hesapsahibi,1,$sirano,date("Y-m-d"),$veri[0]["ID"]),1);
         }
				   
			  
			   
			   if($ekle!=false)
			   {
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
       <div class="col-md-8">
       <div class="card-body card card-primary">
            <div class="row">
             <div class="col-md-12">
                <div class="form-group">
                <label>Banka Adı</label>
                <input type="text" class="form-control" placeholder="Başlık ..." name="baslik" value="<?=$veri[0]["baslik"]?>">
                </div>
            </div>
           <div class="col-md-12">
                <div class="form-group">
                <label>IBAN NO</label>
                <input type="text" class="form-control" placeholder="IBAN NO ..." name="ibanno" value="<?=$veri[0]["ibanno"]?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                <label>Resim</label>
                <input type="file" class="form-control" placeholder="Resim Seçiniz ..." name="resim">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                <label>Sıra No</label>
                <input type="number" class="form-control" placeholder="Sıra No ..." name="sirano" style="width:100px;" value="<?=$veri[0]["sirano"]?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                <label>Hesap No</label>
                <input type="text" class="form-control" placeholder="Hesap No ..." name="hesapno" value="<?=$veri[0]["hesapno"]?>">
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-group">
                <label>Hesap Sahibi</label>
                <input type="text" class="form-control" placeholder="Hesap Sahibi ..." name="hesapsahibi" value="<?=$veri[0]["hesapsahibi"]?>">
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
    </section>
    <!-- /.content -->
  </div>
 
<?php
  }
  else
  {
    echo "Hatalı bilgi gönderildi.";
  }
}
else
{
  echo "Bu sayfaya erişim izniniz bulunmamaktadır.";
}
?>
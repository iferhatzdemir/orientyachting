<?php
if(!empty($_SESSION["ID"]) && !empty($_SESSION["adsoyad"]) && !empty($_SESSION["mail"])) {
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Hizmet Yönetimi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Ana Sayfa</a></li>
              <li class="breadcrumb-item active">Hizmet Yönetimi</li>
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
            <a href="<?=SITE?>hizmet-ekle" class="btn btn-success" style="float:right; margin-bottom:10px;"><i class="fa fa-plus"></i> YENİ EKLE</a>
          </div>
        </div>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width:50px;">Sıra</th>
                  <th>Başlık</th>
                  <th>Kategori</th>
                  <th style="width:50px;">Durum</th>
                  <th style="width:80px;">Tarih</th>
                  <th style="width:120px;">İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php
				$veriler=$VT->VeriGetir("hizmetler","","","ORDER BY ID ASC");
				if($veriler!=false)
				{
					$sira=0;
					for($i=0;$i<count($veriler);$i++)
					{
						$sira++;
						if($veriler[$i]["durum"]==1){$aktifpasif='checked="checked"';}else{$aktifpasif='';}
						?>
						<tr>
                            <td><?=$sira?></td>
                            <td>
                                <?php if(!empty($veriler[$i]["resim"])) {
                                    $resimYol = !file_exists("../assets/img/hizmetler/".$veriler[$i]["resim"]) ? "../assets/img/noimage.jpg" : "../assets/img/hizmetler/".$veriler[$i]["resim"];
                                ?>
                                <img src="<?=$resimYol?>" style="height: 60px; width: auto; margin-right: 8px; float: left;">
                                <?php } ?>
                                <strong><?=stripslashes($veriler[$i]["baslik"])?></strong>
                                <br>
                                <small><?=mb_substr(strip_tags(stripslashes($veriler[$i]["ozet"])), 0, 120, "UTF-8")?>...</small>
                            </td>
                            <td><?=$veriler[$i]["kategori"]?></td>
                            <td>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3<?=$veriler[$i]["ID"]?>" <?=$aktifpasif?> value="<?=$veriler[$i]["ID"]?>" onclick="aktifpasif(<?=$veriler[$i]["ID"]?>,'hizmetler');">
                                    <label class="custom-control-label" for="customSwitch3<?=$veriler[$i]["ID"]?>"></label>
                                </div>
                            </td>
                            <td><?=date("d.m.Y", strtotime($veriler[$i]["tarih"]))?></td>
                            <td>
                                <a href="<?=SITE?>hizmet-duzenle/<?=$veriler[$i]["ID"]?>" class="btn btn-warning btn-sm">Düzenle</a>
                                <a href="<?=SITE?>hizmet-sil/<?=$veriler[$i]["ID"]?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu hizmeti silmek istediğinizden emin misiniz?');">Sil</a>
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
                  <th>Başlık</th>
                  <th>Kategori</th>
                  <th>Durum</th>
                  <th>Tarih</th>
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
  
<script>
function aktifpasif(ID, tablo){
	var durum=0;
	if($('#customSwitch3'+ID).is(':checked')) {
		durum=1;
	}
	
	$.ajax({
		method: "POST",
		url: "<?=SITE?>ajax.php",
		data: {"tablo":tablo, "ID":ID, "durum":durum},
		success: function(sonuc){
			if(sonuc=="TAMAM") {
				
			} else {
				alert("İşleminiz şuan geçersizdir. Lütfen daha sonra tekrar deneyiniz.");
			}
		}
	});
}
</script>
<?php
} else {
	echo '<meta http-equiv="refresh" content="0;url='.SITE.'giris-yap">';
}
?> 
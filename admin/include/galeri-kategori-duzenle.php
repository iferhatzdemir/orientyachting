<?php
if(empty($_GET["ID"]))
{
  echo '<meta http-equiv="refresh" content="0;url='.SITE.'galeri">';
  exit;
}

$ID=$VT->filter($_GET["ID"]);
$veri=$VT->VeriGetir("galeri_kategoriler","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);

if($veri==false)
{
  echo '<meta http-equiv="refresh" content="0;url='.SITE.'galeri">';
  exit;
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Galeri Kategori Düzenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>galeri">Galeriler</a></li>
              <li class="breadcrumb-item active">Kategori Düzenle</li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><?=$veri[0]["baslik"]?> Düzenleniyor</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?=SITE?>ajax.php" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="baslik">Kategori Adı</label>
                    <input type="text" class="form-control" id="baslik" placeholder="Kategori Adı" name="baslik" value="<?=$veri[0]["baslik"]?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="sira">Sıra</label>
                    <input type="text" class="form-control" id="sira" placeholder="Sıra" name="sira" style="width:100px;" value="<?=$veri[0]["sira"]?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="durum">Durum</label><br />
                    <label>
                      <input type="radio" name="durum" value="1" <?php echo ($veri[0]["durum"]==1) ? 'checked' : '' ?>> Aktif
                    </label>
                    <label style="margin-left:30px;">
                      <input type="radio" name="durum" value="0" <?php echo ($veri[0]["durum"]==0) ? 'checked' : '' ?>> Pasif
                    </label>
                  </div>
                  
                  <input type="hidden" name="ID" value="<?=$veri[0]["ID"]?>">
                  <input type="hidden" name="islem" value="galeri-kategori-duzenle">
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Düzenle</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div> 
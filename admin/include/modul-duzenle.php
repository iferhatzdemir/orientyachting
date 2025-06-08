<?php
if(!empty($_GET["ID"]) && ctype_digit($_GET["ID"]))
{
	$ID=$VT->filter($_GET["ID"]);
	$veri=$VT->VeriGetir("moduller","WHERE ID=?",array($ID),"ORDER BY ID DESC",1);
	if($veri!=false)
	{
		
	}
	else
	{
		exit();
	}
}
else
{
	exit();
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Modül Düzenle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>moduller">Modül Yönetimi</a></li>
              <li class="breadcrumb-item active">Modül Düzenle</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <?php
        if($_POST)
        {
            $ekle=$VT->modulDuzenle($veri[0]["tablo"],$ID,$DIL);
            if($ekle!=false)
            {
                ?>
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> Başarılı!</h5>
                    İşleminiz başarıyla kaydedildi.
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Hata!</h5>
                    Boş bıraktığınız alanları doldurunuz.
                </div>
                <?php
            }
        }
        ?>
        
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Modül Düzenleme</h3>
                        <div class="card-tools">
                            <a href="<?=SITE?>modul-ekle" class="btn btn-sm btn-success mr-2"><i class="fas fa-plus"></i> Yeni Ekle</a>
                            <a href="<?=SITE?>moduller" class="btn btn-sm btn-dark"><i class="fas fa-list"></i> Listele</a>
                        </div>
                    </div>
                    <form class="form-horizontal" action="#" role="form" method="post">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="inputBaslik" class="col-lg-2 col-form-label">Modül Başlık</label>
                                <div class="col-lg-10">
                                    <input type="text" id="inputBaslik" class="form-control" name="baslik" 
                                           placeholder="Başlık..." value="<?=$veri[0]["baslik"]?>" maxlength="160">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Durum</label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="durumSwitch" 
                                               name="durum" value="1" <?php if($veri[0]["durum"]==1){echo 'checked';} ?>>
                                        <label class="custom-control-label" for="durumSwitch">
                                            <?php echo ($veri[0]["durum"]==1) ? 'Aktif' : 'Pasif'; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Kaydet</button>
                            <a href="<?=SITE?>moduller" class="btn btn-default ml-2">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
// Switch toggle update label text
document.addEventListener('DOMContentLoaded', function() {
    var switchEl = document.getElementById('durumSwitch');
    if (switchEl) {
        switchEl.addEventListener('change', function() {
            var label = switchEl.nextElementSibling;
            label.textContent = this.checked ? 'Aktif' : 'Pasif';
        });
    }
});
</script>
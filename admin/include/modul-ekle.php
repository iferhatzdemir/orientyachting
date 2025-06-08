<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Yeni Modül Ekle</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item"><a href="<?=SITE?>moduller">Modül Yönetimi</a></li>
              <li class="breadcrumb-item active">Yeni Modül Ekle</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <!-- Alerts -->
        <?php
        if($_POST) {
            $ekle = $VT->modulEkle("moduller", $DIL);
            if($ekle != false) {
                ?>
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> İşlem Başarılı!</h5>
                    Modül başarıyla eklenmiştir. <a href="<?=SITE?>moduller">Tüm modülleri görmek için tıklayınız</a>.
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Hata Oluştu!</h5>
                    Lütfen tüm gerekli alanları doldurunuz ve tekrar deneyiniz.
                </div>
                <?php
            }
        }
        ?>
        
        <!-- Main Form -->
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-puzzle-piece mr-2"></i>Modül Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post" class="needs-validation" novalidate>
                            <div class="form-group row align-items-center">
                                <label for="modulBaslik" class="col-lg-3 col-form-label text-lg-right">
                                    <span>Modül Başlık</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-heading"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="modulBaslik" name="baslik" 
                                               placeholder="Modül başlığını giriniz" required maxlength="160">
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Modül başlığı sayfada görünecek şekilde girilmelidir.
                                    </small>
                                </div>
                            </div>
                            
                            <div class="form-group row align-items-center">
                                <label class="col-lg-3 col-form-label text-lg-right">
                                    <span>Durum</span>
                                </label>
                                <div class="col-lg-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="durumSwitch" name="durum" value="1" checked>
                                        <label class="custom-control-label" for="durumSwitch">
                                            <span id="durumLabel" class="text-success">Aktif</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Modülü aktif veya pasif olarak ayarlayabilirsiniz.
                                    </small>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="form-group mb-0 text-center">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save mr-2"></i>Kaydet
                                </button>
                                <a href="<?=SITE?>moduller" class="btn btn-secondary px-4 ml-2">
                                    <i class="fas fa-times mr-2"></i>İptal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- Custom JS for form validation and switch toggling -->
<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Switch toggle
document.getElementById('durumSwitch').addEventListener('change', function() {
    var durumLabel = document.getElementById('durumLabel');
    if (this.checked) {
        durumLabel.innerText = 'Aktif';
        durumLabel.className = 'text-success';
    } else {
        durumLabel.innerText = 'Pasif';
        durumLabel.className = 'text-danger';
    }
});
</script>
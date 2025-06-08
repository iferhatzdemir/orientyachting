<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Admin kontrolü - sadece adminler görebilsin
if(!isset($_SESSION["ID"]) || $_SESSION["tur"]!="admin") {
    echo '<meta http-equiv="refresh" content="0;url='.SITE.'">';
    exit;
}

// DB güncelleme sayfası
?>

<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Veritabanı Güncellemeleri</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Veritabanı Güncellemeleri</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Sistem Güncellemeleri</h3>
              </div>
              <div class="card-body">
                <p>Bu sayfa, veritabanı şemasını güncellemek için kullanılır. Bir güncelleme seçin ve uygulayın.</p>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Yat Modülü Güncellemeleri</h3>
                      </div>
                      <div class="card-body">
                        <button type="button" class="btn btn-info mb-3" id="btnMotorModel">Motor Model Alanı Ekle</button>
                        <div id="motorModelResult"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $("#btnMotorModel").click(function() {
        $.ajax({
            url: "<?=SITE?>include/db-updates/add_motor_model.php",
            type: "GET",
            success: function(response) {
                $("#motorModelResult").html(response);
            },
            error: function() {
                $("#motorModelResult").html('<div class="alert alert-danger">İşlem sırasında bir hata oluştu.</div>');
            }
        });
    });
});
</script> 
<?php
// Tüm galerileri listele
$galeriler = $VT->VeriGetir("galeri","","","ORDER BY ID DESC");
?>

<!-- SweetAlert2 CSS ve JS - en üstte yüklenmesi için -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<!-- Dropzone.js - doğrudan bu sayfaya yükleme yapmak için -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
  // Dropzone otomatik başlatmayı devre dışı bırak, manuel olarak başlatacağız
  Dropzone.autoDiscover = false;
</script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Galeri Yönetimi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Galeri Yönetimi</li>
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
                <h3 class="card-title">Yeni Galeri Oluştur</h3>
              </div>
              <form action="<?=SITE?>ajax.php" method="post" id="galeriekleform">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Galeri Başlığı</label>
                        <input type="text" class="form-control" placeholder="Galeri Başlığı" name="baslik" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Galeri Kategorisi</label>
                        <select class="form-control" name="kategori">
                          <option value="0">Kategori Seç</option>
                          <?php
                          $kategoriler = $VT->VeriGetir("galeri_kategoriler", "WHERE durum=?", array(1), "ORDER BY sira ASC");
                          if($kategoriler != false){
                            for($i=0; $i<count($kategoriler); $i++){
                              echo '<option value="'.$kategoriler[$i]["ID"].'">'.$kategoriler[$i]["baslik"].'</option>';
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Galeri Sırası</label>
                    <input type="text" class="form-control" placeholder="Sıra" name="sira" style="width:100px;" value="1">
                  </div>
                  <div class="form-group">
                    <label>Durum</label><br />
                    <label>
                      <input type="radio" name="durum" value="1" checked> Aktif
                    </label>
                    <label style="margin-left:30px;">
                      <input type="radio" name="durum" value="0"> Pasif
                    </label>
                  </div>
                  <input type="hidden" name="islem" value="galeri-ekle">
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Galeri Oluştur</button>
                </div>
              </form>
            </div>
          </div>
        </div>
     
      </div><!-- /.container-fluid -->
    </section>

    <!-- Hata/Başarı mesajlarını göstermek için ek alan -->
    <div id="message-container" class="container-fluid mb-3" style="display: none;">
      <div class="alert" id="message-box" role="alert"></div>
    </div>

    <!-- Mevcut Galeriler -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Mevcut Galeriler</h3>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width:50px;">ID</th>
                  <th>Galeri Adı</th>
                  <th style="width:80px;">Kategori</th>
                  <th style="width:80px;">Durum</th>
                  <th style="width:80px;">Tarih</th>
                  <th style="width:180px;">İşlemler</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if($galeriler != false) {
                  for($i=0; $i<count($galeriler); $i++) {
                    $kategoriAdi = "Kategorisiz";
                    if($galeriler[$i]["kategori"] > 0) {
                      $kategori = $VT->VeriGetir("galeri_kategoriler", "WHERE ID=?", array($galeriler[$i]["kategori"]), "ORDER BY ID ASC", 1);
                      if($kategori != false) {
                        $kategoriAdi = $kategori[0]["baslik"];
                      }
                    }
                ?>
                <tr>
                  <td><?=$galeriler[$i]["ID"]?></td>
                  <td><?=$galeriler[$i]["baslik"]?></td>
                  <td><?=$kategoriAdi?></td>
                  <td><?=$galeriler[$i]["durum"] == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Pasif</span>'?></td>
                  <td><?=$galeriler[$i]["tarih"]?></td>
                  <td>
                    <a href="<?=SITE?>galeri-resimler/<?=$galeriler[$i]["ID"]?>" class="btn btn-info btn-sm"><i class="fas fa-images"></i> Medya</a>
                    <a href="<?=SITE?>galeri-duzenle/<?=$galeriler[$i]["ID"]?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i> Düzenle</a>
                    <a href="<?=SITE?>galeri-sil/<?=$galeriler[$i]["ID"]?>" class="btn btn-danger btn-sm" onclick="return galeriSil(this.href);"><i class="fas fa-trash"></i> Sil</a>
                  </td>
                </tr>
                <?php
                  }
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Galeri Adı</th>
                  <th>Kategori</th>
                  <th>Durum</th>
                  <th>Tarih</th>
                  <th>İşlemler</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- Kategoriler -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5">
            <!-- Yeni Kategori Ekleme Formu -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Yeni Kategori Ekle</h3>
              </div>
              <form action="<?=SITE?>ajax.php" method="post" id="kategoriekleform">
                <div class="card-body">
                  <div class="form-group">
                    <label>Kategori Adı</label>
                    <input type="text" class="form-control" placeholder="Kategori Adı" name="baslik" required>
                  </div>
                  <div class="form-group">
                    <label>Sıra</label>
                    <input type="text" class="form-control" placeholder="Sıra" name="sira" style="width:100px;" value="1">
                  </div>
                  <div class="form-group">
                    <label>Durum</label><br />
                    <label>
                      <input type="radio" name="durum" value="1" checked> Aktif
                    </label>
                    <label style="margin-left:30px;">
                      <input type="radio" name="durum" value="0"> Pasif
                    </label>
                  </div>
                  <input type="hidden" name="islem" value="galeri-kategori-ekle">
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Kategori Ekle</button>
                </div>
              </form>
            </div>
          </div>
          
          <div class="col-md-7">
            <!-- Kategori Listesi -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Galeri Kategorileri</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width:50px;">ID</th>
                      <th>Kategori Adı</th>
                      <th style="width:50px;">Sıra</th>
                      <th style="width:80px;">Durum</th>
                      <th style="width:140px;">İşlemler</th>
                    </tr>
                  </thead>
                  <tbody id="kategoriListesi">
                    <?php
                    $kategoriler = $VT->VeriGetir("galeri_kategoriler", "", "", "ORDER BY sira ASC");
                    if($kategoriler != false) {
                      for($i=0; $i<count($kategoriler); $i++) {
                    ?>
                    <tr>
                      <td><?=$kategoriler[$i]["ID"]?></td>
                      <td><?=$kategoriler[$i]["baslik"]?></td>
                      <td><?=$kategoriler[$i]["sira"]?></td>
                      <td><?=$kategoriler[$i]["durum"] == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Pasif</span>'?></td>
                      <td>
                        <a href="<?=SITE?>galeri-kategori-duzenle/<?=$kategoriler[$i]["ID"]?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="<?=SITE?>galeri-kategori-sil/<?=$kategoriler[$i]["ID"]?>" class="btn btn-danger btn-sm" onclick="return kategoriSil(this.href);"><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Fancybox ayarları
    $.fancybox.defaults.buttons = [
      "zoom",
      "slideShow",
      "fullScreen",
      "download",
      "thumbs",
      "close"
    ];

    // Fancybox başlatma
    $("[data-fancybox='gallery']").fancybox({
      loop: true,
      animationEffect: "zoom-in-out",
      transitionEffect: "fade"
    });
    
    // Galeri ekleme formu gönderimi
    $('#galeriekleform').on('submit', function(e) {
      e.preventDefault();
      
      var formData = new FormData(this);
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if(response === 'TAMAM') {
            Swal.fire({
              icon: 'success',
              title: 'Başarılı!',
              text: 'Galeri başarıyla oluşturuldu.',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Hata: ' + response
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'İşlem sırasında bir hata oluştu.'
          });
        }
      });
    });
    
    // Kategori ekleme formu gönderimi
    $('#kategoriekleform').on('submit', function(e) {
      e.preventDefault();
      
      var formData = new FormData(this);
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if(response === 'TAMAM') {
            Swal.fire({
              icon: 'success',
              title: 'Başarılı!',
              text: 'Kategori başarıyla eklendi.',
              showConfirmButton: false,
              timer: 1500
            }).then(function() {
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Hata: ' + response
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: 'İşlem sırasında bir hata oluştu.'
          });
        }
      });
    });
  });
  
  // Galeri silme fonksiyonu
  function galeriSil(url) {
    // URL'den ID'yi çıkar
    var urlParts = url.split('/');
    var ID = urlParts[urlParts.length - 1];
    
    // SweetAlert2 yüklü mü kontrol et
    if (typeof Swal === 'undefined') {
      return confirm("Bu galeriyi silmek istediğinize emin misiniz?");
    }
    
    // SweetAlert2 kullanarak onay iste
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu galeriyi silmek istediğinize emin misiniz? Tüm galeri resimleri de silinecektir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX ile silme işlemi
        $.ajax({
          url: '<?=SITE?>ajax.php',
          type: 'POST',
          data: {
            islem: 'galeriSil',
            ID: ID
          },
          success: function(response) {
            if(response === 'TAMAM') {
              Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: 'Galeri başarıyla silindi.',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Galeri silinirken bir hata oluştu: ' + response,
                showConfirmButton: true
              });
            }
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Hata!',
              text: 'Bağlantı sırasında bir hata oluştu.',
              showConfirmButton: true
            });
          }
        });
      }
    });
    
    return false;
  }
  
  // Kategori silme fonksiyonu
  function kategoriSil(url) {
    // SweetAlert2 yüklü mü kontrol et
    if (typeof Swal === 'undefined') {
      return confirm("Bu kategoriyi silmek istediğinize emin misiniz?");
    }
    
    // SweetAlert2 kullanarak onay iste
    Swal.fire({
      title: 'Emin misiniz?',
      text: "Bu kategoriyi silmek istediğinize emin misiniz?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Doğrudan URL'ye yönlendir
        window.location.href = url;
      }
    });
    
    return false;
  }
</script> 
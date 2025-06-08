<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Modül Yönetimi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Modül Yönetimi</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <!-- Result Messages -->
        <div id="islemsonuc"></div>
        
        <div class="row mb-3">
                        <div class="col-md-12">
            <a href="<?=SITE?>modul-ekle" class="btn btn-success" style="float:right;"><i class="fas fa-plus"></i> YENİ MODÜL EKLE</a>
                                    </div>
                            </div>
                                
        <!-- Modules Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-puzzle-piece mr-2"></i>Modül Listesi
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="modulTable">
                                        <thead>
                                            <tr>
                            <th style="width:50px;" class="text-center">ID</th>
                                                <th>Başlık</th>
                            <th style="width:80px;" class="text-center">Durum</th>
                            <th style="width:180px;" class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                    <tbody>
                        <?php 
                        // Doğrudan modülleri çekelim
                        $moduller = $VT->VeriGetir("moduller", "", array(), "ORDER BY ID DESC");
                        if($moduller != false) {
                            foreach($moduller as $modul) {
                                // Durum bilgisini kontrol edelim
                                $durumClass = ($modul["durum"] == 1) ? "success" : "danger";
                                $durumText = ($modul["durum"] == 1) ? "Aktif" : "Pasif";
                                ?>
                                <tr>
                                    <td class="text-center"><?=$modul["ID"]?></td>
                                    <td><?=stripslashes($modul["baslik"])?></td>
                                    <td class="text-center">
                                        <span class="badge badge-<?=$durumClass?>">
                                            <?=$durumText?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?=SITE?>modul-duzenle/<?=$modul["ID"]?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <button class="btn btn-danger btn-sm btn-delete" 
                                                data-id="<?=$modul["ID"]?>" 
                                                data-title="<?=stripslashes($modul["baslik"])?>">
                                            <i class="fas fa-trash-alt"></i> Sil
                                        </button>
                                    </td>
                                </tr>
                                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">Henüz eklenmiş bir modül bulunmamaktadır.</td></tr>';
                        }
                        ?>
                                        </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Başlık</th>
                            <th class="text-center">Durum</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                    </tfoot>
                                    </table>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title" id="deleteModalLabel">Modül Silme Onayı</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-1">Modülü silmek istediğinize emin misiniz?</p>
                <p class="mb-0 small"><strong>Modül:</strong> <span id="deleteItemName" class="text-danger"></span></p>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-sm btn-danger" id="confirmDelete">Sil</button>
            </div>
                                </div>
                            </div>
                        </div>

<script>
$(document).ready(function() {
    // DataTables kütüphanesi kontrolü ve alternatif yöntem
    try {
        if ($.fn.DataTable !== undefined) {
            $('#modulTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json'
                },
                responsive: true,
                pageLength: 10,
                "autoWidth": false
            });
        } else {
            // Basit arama fonksiyonu
            $("#tableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#modulTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        }
    } catch (e) {
        console.log("DataTables hatası:", e);
    }
    
    // Delete işlemleri
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        
        $('#deleteItemName').text(title);
        $('#confirmDelete').data('id', id);
        $('#deleteModal').modal('show');
    });
    
    $('#confirmDelete').on('click', function() {
        var id = $(this).data('id');
        
        // Modulü silme AJAX
        $.ajax({
            url: '<?=SITE?>ajax.php',
            type: 'POST',
            data: {
                action: 'deleteModule',
                id: id
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                
                try {
                    // Parse the JSON response
                    var result = JSON.parse(response);
                    
                    if (result.success) {
                        // Tablo satırını kaldır ve mesaj göster
                        var row = $(".btn-delete[data-id='" + id + "']").closest('tr');
                        row.fadeOut(400, function() {
                            row.remove();
                            $('#islemsonuc').html('<div class="alert alert-success py-1 px-3 mb-2">Modül başarıyla silindi.</div>');
                            
                            setTimeout(function() {
                                $('#islemsonuc').html('');
                                // Reload the page to ensure list is refreshed from database
                                window.location.reload();
                            }, 1000);
                        });
                    } else {
                        $('#islemsonuc').html('<div class="alert alert-danger py-1 px-3 mb-2">' + result.message + '</div>');
                        setTimeout(function() {
                            $('#islemsonuc').html('');
                        }, 3000);
                    }
                } catch (e) {
                    // Response was not JSON
                    if (response === "OK") {
                        // Tablo satırını kaldır ve mesaj göster
                        var row = $(".btn-delete[data-id='" + id + "']").closest('tr');
                        row.fadeOut(400, function() {
                            row.remove();
                            $('#islemsonuc').html('<div class="alert alert-success py-1 px-3 mb-2">Modül başarıyla silindi.</div>');
                            
                            setTimeout(function() {
                                $('#islemsonuc').html('');
                                // Reload the page to ensure list is refreshed from database
                                window.location.reload();
                            }, 1000);
                        });
                    } else {
                        $('#islemsonuc').html('<div class="alert alert-danger py-1 px-3 mb-2">İşlem sırasında bir hata oluştu.</div>');
                        setTimeout(function() {
                            $('#islemsonuc').html('');
                        }, 3000);
                    }
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                $('#islemsonuc').html('<div class="alert alert-danger py-1 px-3 mb-2">İşlem sırasında bir hata oluştu.</div>');
                setTimeout(function() {
                    $('#islemsonuc').html('');
                }, 3000);
            }
        });
    });
});
</script>
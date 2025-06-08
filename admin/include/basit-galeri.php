<?php
// Başarılı mesajını göster
if(!empty($_GET["islem"]) && $_GET["islem"] == "basarili") {
    echo '<div class="alert alert-success">Resimler başarıyla yüklendi.</div>';
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Basit Galeri Yönetimi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
                        <li class="breadcrumb-item active">Basit Galeri Yönetimi</li>
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
                            <h3 class="card-title">Resim Yükleme</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> İstediğiniz kadar resmi sürükleyip bırakabilir veya tıklayarak seçebilirsiniz. Yüklenen tüm resimler otomatik olarak kaydedilir.
                            </div>
                            <div class="dropzone" id="imageUploader">
                                <div class="dz-message needsclick">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i><br>
                                    Resimleri buraya sürükleyip bırakın veya tıklayarak seçin<br>
                                    <span class="note needsclick">(İstediğiniz kadar resim seçebilirsiniz)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Yüklenen resimler listesi -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Yüklenen Resimler</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" id="refreshGallery">
                                    <i class="fas fa-sync-alt"></i> Yenile
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="galleryItems">
                                <?php
                                // Veritabanından galeri resimlerini çek
                                $resimler = $VT->VeriGetir("gallery", "WHERE durum=?", array(1), "ORDER BY ID DESC");
                                if($resimler != false) {
                                    foreach($resimler as $resim) {
                                        $resimYolu = SITE."../images/gallery/".$resim["resim"];
                                        ?>
                                        <div class="col-sm-2 col-md-3 col-lg-2 mb-4">
                                            <div class="gallery-item">
                                                <div class="position-relative">
                                                    <img src="<?=$resimYolu?>" class="img-fluid mb-2" alt="<?=stripslashes($resim["baslik"])?>">
                                                    <div class="gallery-item-actions">
                                                        <button class="btn btn-sm btn-danger delete-image" data-id="<?=$resim["ID"]?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><?=date("d.m.Y H:i", strtotime($resim["tarih"]))?></small>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<div class="col-12 text-center p-5"><i class="fas fa-images fa-3x mb-3 text-muted"></i><p class="text-muted">Henüz yüklenmiş resim bulunmuyor.</p></div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Custom style for gallery items -->
<style>
.gallery-item {
    position: relative;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
}

.gallery-item-actions {
    position: absolute;
    top: 0;
    right: 0;
    padding: 5px;
    display: none;
}

.gallery-item:hover .gallery-item-actions {
    display: block;
}

.dropzone {
    border: 2px dashed #3c8dbc;
    border-radius: 5px;
    background: #f9f9f9;
}

.dropzone .dz-preview .dz-success-mark,
.dropzone .dz-preview .dz-error-mark {
    margin-top: -25px;
}
</style>

<script>
Dropzone.autoDiscover = false;

$(document).ready(function() {
    var myDropzone = new Dropzone("#imageUploader", {
        url: "<?=SITE?>ajax.php?islem=basit-galeri-yukle",
        paramName: "file",
        maxFilesize: 5, // MB
        acceptedFiles: ".jpg, .jpeg, .png, .gif",
        addRemoveLinks: true,
        dictRemoveFile: "Kaldır",
        dictDefaultMessage: "Resimleri yüklemek için buraya sürükleyin",
        dictFileTooBig: "Dosya çok büyük ({{filesize}}MB). Maksimum dosya boyutu: {{maxFilesize}}MB.",
        dictInvalidFileType: "Bu dosya türü yüklenemez.",
        init: function() {
            this.on("success", function(file, response) {
                console.log("Başarılı yükleme:", response);
                var jsonResponse = JSON.parse(response);
                if(jsonResponse.success) {
                    $(file.previewElement).addClass("dz-success");
                    setTimeout(function() {
                        // Başarı mesajı göster
                        const successNotify = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="fas fa-check-circle"></i> Başarılı!</strong> Resim yüklendi.' +
                            '</div>');
                        
                        $('body').append(successNotify);
                        
                        // 3 saniye sonra kapat
                        setTimeout(function() {
                            successNotify.alert('close');
                        }, 3000);
                        
                        refreshGallery();
                    }, 1000);
                } else {
                    $(file.previewElement).addClass("dz-error");
                    alert("Dosya yüklenemedi: " + jsonResponse.message);
                }
            });
            
            this.on("error", function(file, errorMessage) {
                console.error("Yükleme hatası:", errorMessage);
                $(file.previewElement).addClass("dz-error");
            });
        }
    });
    
    // Galeri yenileme fonksiyonu
    function refreshGallery() {
        $.ajax({
            url: "<?=SITE?>ajax.php?islem=galeri-listesi-getir",
            type: "GET",
            success: function(response) {
                $("#galleryItems").html(response);
            },
            error: function() {
                alert("Galeri listesi yenilenirken bir hata oluştu.");
            }
        });
    }
    
    // Yenile butonu tıklaması
    $("#refreshGallery").on("click", function() {
        refreshGallery();
    });
    
    // Resim silme işlemi (delegasyon ile)
    $(document).on("click", ".delete-image", function() {
        var imageID = $(this).data("id");
        var thisItem = $(this).closest(".col-sm-2");
        
        if(confirm("Bu resmi silmek istediğinize emin misiniz?")) {
            $.ajax({
                url: "<?=SITE?>ajax.php?islem=galeri-resim-sil",
                type: "POST",
                data: { id: imageID },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.success) {
                        thisItem.fadeOut(function() {
                            $(this).remove();
                            
                            // Hiç resim kalmadıysa boş mesajı göster
                            if($("#galleryItems .col-sm-2").length === 0) {
                                $("#galleryItems").html('<div class="col-12 text-center p-5"><i class="fas fa-images fa-3x mb-3 text-muted"></i><p class="text-muted">Henüz yüklenmiş resim bulunmuyor.</p></div>');
                            }
                            
                            // Başarı mesajı göster
                            const successNotify = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                '<strong><i class="fas fa-check-circle"></i> Başarılı!</strong> Resim silindi.' +
                                '</div>');
                            
                            $('body').append(successNotify);
                            
                            // 3 saniye sonra kapat
                            setTimeout(function() {
                                successNotify.alert('close');
                            }, 3000);
                        });
                    } else {
                        alert("Resim silinemedi: " + result.message);
                    }
                },
                error: function() {
                    alert("İşlem sırasında bir hata oluştu.");
                }
            });
        }
    });
});
</script> 
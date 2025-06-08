<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Include language helper if not already included
if (!function_exists('lang')) {
    require_once dirname(dirname(__DIR__)) . '/helpers/language_helper.php';
}

// ID kontrolü yap
if(empty($_GET["ID"])) {
    header("Location: ".SITE."yacht-types");
    exit;
}

$ID = $VT->filter($_GET["ID"]);

// İlgili tipin var olup olmadığını kontrol et
$veri = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
if($veri == false) {
    header("Location: ".SITE."yacht-types");
    exit;
}

// Silme işlemini gerçekleştir
try {
    // Transaction başlat
    $VT->beginTransaction();
    
    // 1. Önce dil çevirilerini sil
    $dilSil = $VT->SorguCalistir("DELETE FROM yacht_types_dil", "WHERE type_id=?", array($ID));
    
    // 2. Bu tip ile ilişkili yatları güncelle
    $yatGuncelle = $VT->SorguCalistir("UPDATE yachts", "SET type_id=0 WHERE type_id=?", array($ID));
    
    // 3. Ana tipi sil
    $tipSil = $VT->SorguCalistir("DELETE FROM yacht_types", "WHERE ID=?", array($ID));
    
    if($tipSil !== false) {
        // İşlem başarılı, değişiklikleri kaydet
        $VT->commit();
        
        // Başarılı bildirim göster ve yönlendir
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "'.lang('admin.success').'",
                    text: "Yat tipi başarıyla silindi.",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "'.SITE.'yacht-types";
                });
            });
        </script>';
        
        // Yönlendirme için meta tag da ekle (JavaScript çalışmazsa backup olarak)
        echo '<meta http-equiv="refresh" content="1.5;url='.SITE.'yacht-types">';
    } else {
        // İşlem başarısız, değişiklikleri geri al
        $VT->rollBack();
        
        // Hata bildirimi göster ve yönlendir
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "'.lang('admin.error').'",
                    text: "Yat tipi silinirken bir sorun oluştu.",
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = "'.SITE.'yacht-types";
                });
            });
        </script>';
        
        // Yönlendirme için meta tag da ekle (JavaScript çalışmazsa backup olarak)
        echo '<meta http-equiv="refresh" content="3;url='.SITE.'yacht-types">';
    }
} catch (Exception $e) {
    // Hata durumunda transaction'ı geri al
    $VT->rollBack();
    
    // Hatayı logla
    error_log("Yat tipi silme hatası: " . $e->getMessage());
    
    // Hata bildirimi göster ve yönlendir
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Sistem Hatası",
                text: "İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.",
                showConfirmButton: true
            }).then(function() {
                window.location.href = "'.SITE.'yacht-types";
            });
        });
    </script>';
    
    // Yönlendirme için meta tag da ekle (JavaScript çalışmazsa backup olarak)
    echo '<meta http-equiv="refresh" content="3;url='.SITE.'yacht-types">';
} 
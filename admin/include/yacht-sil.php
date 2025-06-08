<?php
if(!defined("SABIT")) die("Erişim engellendi!");

if(!empty($_GET["ID"])) {
    $ID = $VT->filter($_GET["ID"]);
    
    $veri = $VT->VeriGetir("yachts", "WHERE ID=?", array($ID), "ORDER BY ID ASC", 1);
    if($veri != false) {
        // Önce bağlı resimler silinsin
        $resimler = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("yachts", $ID));
        if($resimler != false) {
            foreach($resimler as $resim) {
                $resimSil = $VT->SorguCalistir("DELETE FROM resimler", "WHERE ID=?", array($resim["ID"]));
                // Dosya silme işlemi de burada yapılabilir
                if(file_exists("../images/resimler/".$resim["resim"])) {
                    unlink("../images/resimler/".$resim["resim"]);
                }
                if(file_exists("../images/yachts/".$resim["resim"])) {
                    unlink("../images/yachts/".$resim["resim"]);
                }
            }
        }
        
        // Sonra ana tablo kaydı silinsin
        if(!empty($veri[0]["resim"])) {
            // Ana resmi varsa silinsin
            if(file_exists("../images/yachts/".$veri[0]["resim"])) {
                unlink("../images/yachts/".$veri[0]["resim"]);
            }
        }
        
        $sil = $VT->SorguCalistir("DELETE FROM yachts", "WHERE ID=?", array($ID));
        
        ?>
        <div class="alert alert-success">Yat ve ilgili tüm içerikler başarıyla silindi.</div>
        <meta http-equiv="refresh" content="1;url=<?=SITE?>yachts">
        <?php
    } else {
        ?>
        <div class="alert alert-danger">Silinecek yat bulunamadı.</div>
        <meta http-equiv="refresh" content="1;url=<?=SITE?>yachts">
        <?php
    }
} else {
    ?>
    <div class="alert alert-danger">ID bilgisi eksik.</div>
    <meta http-equiv="refresh" content="1;url=<?=SITE?>yachts">
    <?php
}
?> 
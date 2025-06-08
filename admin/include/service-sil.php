<?php
if(!defined("SABIT")) die("Erişim engellendi!");

if(isset($_GET["id"])) {
    $id = $VT->filter($_GET["id"]);
    
    // Hizmetin mevcut olup olmadığını kontrol et
    $kontrol = $VT->VeriGetir("services", "WHERE ID=?", array($id), "ORDER BY ID ASC", 1);
    
    if($kontrol != false) {
        // Hizmetin resmi varsa sil
        if(!empty($kontrol[0]["resim"]) && file_exists("images/services/".$kontrol[0]["resim"])) {
            unlink("images/services/".$kontrol[0]["resim"]);
        }
        
        // Önce çevirileri sil
        $VT->SorguCalistir("DELETE FROM services_dil", "WHERE service_id=?", array($id));
        
        // Sonra ana hizmet kaydını sil
        $sil = $VT->SorguCalistir("DELETE FROM services", "WHERE ID=?", array($id));
        
        if($sil) {
            ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>services">
            <?php
            exit();
        } else {
            ?>
            <meta http-equiv="refresh" content="0;url=<?=SITE?>services">
            <?php
            exit();
        }
    } else {
        ?>
        <meta http-equiv="refresh" content="0;url=<?=SITE?>services">
        <?php
        exit();
    }
} else {
    ?>
    <meta http-equiv="refresh" content="0;url=<?=SITE?>services">
    <?php
    exit();
}
?> 
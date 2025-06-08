<?php
if(!defined("SABIT")) die("Erişim engellendi!");

// Yachts tablosunda motor_model alanı var mı kontrol edilsin
$kolonKontrol = $VT->SorguCalistir("SHOW COLUMNS FROM yachts LIKE 'motor_model'");
$kolonVarMi = $kolonKontrol->rowCount();

if($kolonVarMi == 0) {
    // motor_model alanı yoksa ekle
    $kolonEkle = $VT->SorguCalistir("ALTER TABLE yachts ADD COLUMN motor_model VARCHAR(255) DEFAULT NULL AFTER build_year");
    
    if($kolonEkle !== false) {
        echo '<div class="alert alert-success">Motor Model alanı başarıyla eklendi.</div>';
    } else {
        echo '<div class="alert alert-danger">Motor Model alanı eklenirken bir hata oluştu.</div>';
    }
} else {
    echo '<div class="alert alert-info">Motor Model alanı zaten mevcut.</div>';
}
?> 
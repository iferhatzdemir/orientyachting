<?php
// Dil değiştirme işlemini yöneten sayfa

// URL'den dil kodunu al
$language = isset($seflink) ? $seflink : "tr";

// Geçerli dil kodlarını kontrol et
$validLanguages = ["tr", "en", "de", "ru"];
if (!in_array($language, $validLanguages)) {
    $language = "tr"; // Geçersiz dil kodu ise varsayılan Türkçe olsun
}

// Session'a dil kodunu kaydet
$_SESSION["dil"] = $language;

// Kullanıcının geldiği sayfaya geri yönlendir
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE;
header("Location: ".$referer);
exit;
?> 
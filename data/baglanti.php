<?php
include_once(SINIF."class.phpmailer.php");
include_once(SINIF."VT.php");
$VT=new VT();
$sitebilgileri=$VT->VeriGetir("ayarlar","WHERE ID=?",array(1),"ORDER BY ID ASC",1);

if($sitebilgileri!=false)
{
	$sitebaslik=stripslashes($sitebilgileri[0]["baslik"]);
	$siteanahtar=stripslashes($sitebilgileri[0]["anahtar"]);
	$sitedescription=stripslashes($sitebilgileri[0]["aciklama"]);
	$siteurl=stripslashes($sitebilgileri[0]["url"]);
	
	$sitetelefon=stripslashes($sitebilgileri[0]["telefon"]);
	$sitemail=stripslashes($sitebilgileri[0]["mail"]);
	$sitetelefon2=stripslashes($sitebilgileri[0]["telefon2"]);
	$sitemail2=stripslashes($sitebilgileri[0]["mail2"]);
	$siteadres=stripslashes($sitebilgileri[0]["adres"]);
	$sitefax=stripslashes($sitebilgileri[0]["fax"]);
	$VT->TekilCogul();
}
else
{
	echo "Lütfen bağlantı bilgilerinizi kontrol ediniz.";
	exit();
}

?>
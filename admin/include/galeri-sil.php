<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);
  
  // Önce galeriye ait resimleri bul ve veritabanından sil
  $resimler=$VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("galeri", $ID));
  if($resimler!=false)
  {
    for($i=0;$i<count($resimler);$i++)
    {
      // Resim dosyasını diskten sil
      $resim_path = $_SERVER["DOCUMENT_ROOT"].'/images/resimler/'.$resimler[$i]["resim"];
      if(file_exists($resim_path)) {
        unlink($resim_path);
      }
      
      // Resmi veritabanından sil
      $VT->SorguCalistir("DELETE FROM resimler WHERE ID=?", array($resimler[$i]["ID"]));
    }
  }
  
  // Galeriyi sil
  $veri=$VT->SorguCalistir("DELETE FROM galeri WHERE ID=?", array($ID));
  
  // Başarılı olup olmadığına bakılmaksızın galeri sayfasına yönlendir
  header("Location: ".SITE."galeri");
  exit;
}
else
{
  // ID yoksa galeri sayfasına yönlendir
  header("Location: ".SITE."galeri");
  exit;
}
?> 
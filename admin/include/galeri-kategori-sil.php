<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);
  
  // Kategori silmeden önce, kategoriye ait galerileri Kategorisiz yap
  $VT->SorguCalistir("UPDATE galeri", "SET kategori=? WHERE kategori=?", array(0, $ID));
  
  // Kategoriyi sil
  $veri=$VT->SorguCalistir("DELETE FROM galeri_kategoriler", "WHERE ID=?", array($ID), 1);
  
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
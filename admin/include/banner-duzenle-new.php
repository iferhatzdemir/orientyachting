<?php
if(!empty($_GET["ID"]))
{
  $ID=$VT->filter($_GET["ID"]);

  $veri=$VT->VeriGetir("banner","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
  if($veri!=false)
  {
    // Content of the file goes here
    // ...

    // Properly formatted closing part
  ?>
     </form>
  <?php
  }
  else
  {
    echo "Hatali bilgi gönderildi.";
  }
}
else
{
  echo "Bu sayfaya erisim izniniz bulunmamaktadir.";
}
?>

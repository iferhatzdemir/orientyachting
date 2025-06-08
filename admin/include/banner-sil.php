<?php
if(!empty($_GET["ID"]))
{
	$ID=$VT->filter($_GET["ID"]);
	
	
		$veri=$VT->VeriGetir("banner","WHERE ID=?",array($ID),"ORDER BY ID ASC",1);
		if($veri!=false)
		{
			$resim=$veri[0]["resim"];
			if(file_exists("../images/banner/".$resim))
			{
				unlink("../images/banner/".$resim);
			}
			
			// Önce dil çevirilerini siliyoruz
			$VT->SorguCalistir("DELETE FROM banner_dil","WHERE banner_id=?",array($ID));
			
			// Sonra ana banner kaydını siliyoruz
			$sil=$VT->SorguCalistir("DELETE FROM banner","WHERE ID=?",array($ID));
			?>
        <meta http-equiv="refresh" content="0;url=<?=SITE?>banner-liste">
        <?php
		}
		else
		{
			?>
        <meta http-equiv="refresh" content="0;url=<?=SITE?>banner-liste">
        <?php
		}
 
	
}
else
{
	?>
        <meta http-equiv="refresh" content="0;url=<?=SITE?>banner-liste">
        <?php
}
 ?>
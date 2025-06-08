<!-- Sidebar dropdown menüsü için ek CSS -->
<style>
/* Menü açıldığında görünür olması */
.nav-sidebar .has-treeview.menu-open > .nav-treeview {
  display: block !important;
}

/* Normal durumda alt menü gizli */
.nav-sidebar .has-treeview > .nav-treeview {
  display: none;
}

/* Hover ve focus durumunda stil değişimi */
.nav-sidebar .nav-treeview .nav-item > .nav-link:hover,
.nav-sidebar .nav-treeview .nav-item > .nav-link:focus {
  background-color: rgba(255, 255, 255, 0.1);
  color: #fff;
}

/* Menü açıkken alt menülerin kapatılmasını engelle */
.nav-sidebar .has-treeview.menu-open > .nav-treeview .nav-link {
  pointer-events: auto !important;
}

/* Active class için belirlenen stil */
.nav-sidebar .nav-link.active {
  background-color: #007bff !important;
  color: #ffffff !important;
}
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?=SITE?>" class="nav-link">Anasayfa</a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
     <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <?php
		  $yenimesajlar=$VT->VeriGetir("mesajlar","WHERE durum=?",array(1),"ORDER BY ID DESC");
		if($yenimesajlar!=false)
		{
			?>
            <span class="badge badge-danger navbar-badge"><?=count($yenimesajlar)?></span>
            <?php
		}
		  ?>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php
		$mesajlar=$VT->VeriGetir("mesajlar","","","ORDER BY ID DESC",3);
		if($mesajlar!=false)
		{
			for($i=0;$i<count($mesajlar);$i++)
			{
				?>
                <a href="<?=SITE?>mesaj-detay/<?=$mesajlar[$i]["ID"]?>" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="<?=SITE?>dist/img/user1-128x128.jpg" alt="<?=stripslashes($mesajlar[$i]["adsoyad"])?>" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  <?=stripslashes($mesajlar[$i]["adsoyad"])?>
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm"><?=mb_substr(stripslashes(strip_tags($mesajlar[$i]["metin"])),0,35,"UTF-8")."..."?></p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <?=date("d.m.Y",strtotime($mesajlar[$i]["tarih"]))?></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
                <?php
			}
		}
		?>
          
          <a href="<?=SITE?>mesajlar" class="dropdown-item dropdown-footer">Tüm Mesajlar</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link" href="<?=SITE?>cikis">
          <i class="fa fa-sign-out-alt"></i>
        </a>
        
      </li>
     
    </ul>
  </nav>
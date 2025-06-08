<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=SITE?>" class="brand-link">
      <img src="<?=SITE?>dist/img/AdminLTELogo.png" alt="Mehmet ULUS Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Orient Yacthing</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?=SITE?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=$_SESSION["adsoyad"]?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="<?=SITE?>banner-liste" class="nav-link">
              <i class="nav-icon fas fa-image"></i>
              <p>Banner</p>
            </a>
          </li>
       
          <li class="nav-item">
            <a href="<?=SITE?>modul-ekle" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Modül Ekle
               
              </p>
            </a>
          </li>
          
          <!-- Yat Yönetimi -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-ship"></i>
              <p>
                Yat Yönetimi
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=SITE?>yachts" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yat Listesi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>yacht-ekle" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yeni Yat Ekle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>yacht-types" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yat Tipleri</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>yacht-features" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yat Özellikleri</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>yacht-locations" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yat Lokasyonları</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>yacht-image-check" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Yat Resim Kontrol</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Rezervasyonlar -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                Rezervasyonlar
                <i class="fas fa-angle-left right"></i>
                <?php
                $yenirezervasyon=$VT->VeriGetir("rezervasyonlar", "WHERE durum=?", array(0), "", 0, "ID");
                if($yenirezervasyon!=false) {
                ?>
                <span class="right badge badge-danger"><?=count($yenirezervasyon)?></span>
                <?php
                }
                ?>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=SITE?>rezervasyonlar" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tüm Rezervasyonlar</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>rezervasyonlar/durum/0" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bekleyen Rezervasyonlar</p>
                  <?php
                  if($yenirezervasyon!=false) {
                  ?>
                  <span class="right badge badge-danger"><?=count($yenirezervasyon)?></span>
                  <?php
                  }
                  ?>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>rezervasyonlar/durum/1" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Onaylanan Rezervasyonlar</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="<?=SITE?>yorumlar" class="nav-link">
              <i class="nav-icon fas fa-comment"></i>
              <p>
                Yorumlar
               <?php
               $yeniyorumlar=$VT->VeriGetir("yorumlar","WHERE durum=?",array(2));
               if($yeniyorumlar!=false)
               {
                ?>
                <span class="right badge badge-danger"><?=count($yeniyorumlar)?></span>
                <?php
               }
               ?>
              </p>
            </a>
          </li>
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Galeri Yönetimi
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=SITE?>galeri" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Galeri Yönetimi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=SITE?>galeri-resimler/1" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Galeri Resimleri</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Sayfalar
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             <?php
			 $moduller=$VT->VeriGetir("moduller","WHERE durum=?",array(1),"ORDER BY ID ASC");
			 if($moduller!=false)
			 {
				 for($i=0;$i<count($moduller);$i++)
				 {
					 ?>
                      <li class="nav-item">
                        <a href="<?=SITE?>liste/<?=$moduller[$i]["tablo"]?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p><?=$moduller[$i]["baslik"]?></p>
                        </a>
                      </li>
                     <?php
				 }
			 }
			 ?>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?=SITE?>mesajlar" class="nav-link">
              <i class="nav-icon fas fa-comments"></i>
              <p>
                Mesajlar
               <?php
               $yenimesajlar=$VT->VeriGetir("mesajlar","WHERE durum=?",array(1));
               if($yenimesajlar!=false)
               {
                ?>
                <span class="right badge badge-danger"><?=count($yenimesajlar)?></span>
                <?php
               }
               ?>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=SITE?>hesap-numarasi-liste" class="nav-link">
              <i class="nav-icon fas fa-university"></i>
              <p>
                Banka Hesap Numaraları
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=SITE?>seo-ayarlari" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Seo Ayarları
              </p>
            </a>
          </li>
           <li class="nav-item">
            <a href="<?=SITE?>iletisim-ayarlari" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                İletişim Ayarları
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=SITE?>mehmet-ulus" class="nav-link">
              <i class="nav-icon fas fa-question"></i>
              <p>Destek Hattı</p>
            </a>
          </li>
         <li class="nav-item">
            <a href="<?=SITE?>cikis" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Çıkış Yap
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
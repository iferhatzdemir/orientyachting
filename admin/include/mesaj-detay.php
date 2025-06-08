<?php
				if(!empty($_GET["ID"]) && ctype_digit($_GET["ID"]))
				{
					$mesajID=$VT->filter($_GET["ID"]);
					$mesajbilgisi=$VT->VeriGetir("mesajlar","WHERE ID=?",array($mesajID),"ORDER BY ID ASC",1);
					if($mesajbilgisi!=false)
					{
						$okundu=$VT->SorguCalistir("UPDATE mesajlar","SET durum=? WHERE ID=?",array(2,$mesajbilgisi[0]["ID"]),1);
						?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inbox</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Inbox</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><a href="<?=SITE?>mesajlar"><i class="fas fa-undo-alt"></i></a> Mesaj Detayı</h3>

              <div class="card-tools">
              <a href="mailto:<?=$mesajbilgisi[0]["mail"]?>" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-reply"></i> YANITLA</a>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <div class="float-right">
                 
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
              <div class="mailbox-messages" style="padding: 10px;">
                
                        <div class="col-md-12" style="border-bottom:1px solid #eee;line-height: 2;">
                        <strong>Gönderen</strong> <?=$mesajbilgisi[0]["adsoyad"]?> <span style="float:right;"><?=date("d.m.Y",strtotime($mesajbilgisi[0]["tarih"]))?> </span>
                        </div>
                        <div class="col-md-12" style="border-bottom:1px solid #eee;line-height: 2;">
                        <strong>Telefon</strong> <?=$mesajbilgisi[0]["telefon"]?>
                        </div>
                        <div class="col-md-12" style="border-bottom:1px solid #eee;line-height: 2;">
                        <strong>E-Mail</strong> <?=$mesajbilgisi[0]["mail"]?>
                        </div>
                        
                        <div class="col-md-12" style="min-height:250px; padding-top:10px;">
                        <?=stripslashes($mesajbilgisi[0]["metin"])?>
                        </div>
                        
                
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
               
                <!-- /.btn-group -->
               
                <div class="float-right">
                  
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <?php
					}
					else
					{
						echo "Bu mesaj kaldırılmış olabilir.";
					}
				}
				?>
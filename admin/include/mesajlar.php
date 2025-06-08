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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
              <h3 class="card-title">Gelen Mesaj Kutusu</h3>

              <div class="card-tools">
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
              <div class="table-responsive mailbox-messages" style="padding: 10px;">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width:50px;">SEÇ</th>
                  <th style="width:120px;">GÖNDEREN</th>
                  <th>AÇIKLAMA</th>
                  <th style="width:50px;">DURUM</th>
                  <th style="width:80px;">TARİH</th>
                </tr>
                </thead>
                  <tbody>
                  <?php
				  $mesajlar=$VT->VeriGetir("mesajlar","","","ORDER BY ID DESC");
				  if($mesajlar!=false)
				  {
					  for($i=0;$i<count($mesajlar);$i++)
					  {
						  ?>
                          <tr>
                    <td>
                      <div class="icheck-primary">
                        <input type="checkbox" name="sec" value="<?=$mesajlar[$i]["ID"]?>" id="check1">
                        <label for="check1"></label>
                      </div>
                    </td>
                    <td class="mailbox-name"><a href="<?=SITE?>mesaj-detay/<?=$mesajlar[$i]["ID"]?>"><?=$mesajlar[$i]["adsoyad"]?></a></td>
                    <td class="mailbox-subject">
					<?php if($mesajlar[$i]["durum"]==1){echo '<strong>';} ?>
					<?=mb_substr(stripslashes(strip_tags($mesajlar[$i]["metin"])),0,35,"UTF-8")."..."?>
                    <?php if($mesajlar[$i]["durum"]==1){echo '</strong>';} ?>
                    </td>
                    <td class="mailbox-attachment"><?php
                    if($mesajlar[$i]["durum"]==1)
					{
						echo '<strong class="badge bg-success">Yeni</strong>';
					}
					?></td>
                    <td class="mailbox-date"><?=date("d.m.Y",strtotime($mesajlar[$i]["tarih"]))?></td>
                  </tr>
                          <?php
					  }
				  }
				  ?>
                  </tbody>
                </table>
                <!-- /.table -->
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
<?php
/**
 * Reservations Management Page
 * For handling reservation requests, confirmations, and cancellations
 */

// Set page title for breadcrumbs
$sayfaBaslik = "Rezervasyon Yönetimi";

// Checking authorization
if(!empty($_SESSION["ID"]) && !empty($_SESSION["adsoyad"]) && !empty($_SESSION["mail"])) {
    $VT = new VT();
    $userID = $_SESSION["ID"];
    
    // Handle reservation status updates
    if(!empty($_GET["islem"]) && !empty($_GET["id"])) {
        $reservationID = $VT->filter($_GET["id"]);
        $islem = $VT->filter($_GET["islem"]);
        
        // Get reservation data
        $reservation = $VT->VeriGetir("reservations", "WHERE ID=?", array($reservationID), "", 1);
        
        if($reservation) {
            $updateSuccess = false;
            $updateMessage = "";
            
            switch($islem) {
                case "onayla":
                    // Confirm reservation
                    $updateResult = $VT->SorguCalistir(
                        "UPDATE reservations", 
                        "SET status=?, updated_at=?", 
                        array(1, date("Y-m-d H:i:s")), 
                        "WHERE ID=?", 
                        array($reservationID)
                    );
                    
                    if($updateResult) {
                        $updateSuccess = true;
                        $updateMessage = "Rezervasyon başarıyla onaylandı.";
                        
                        // Get yacht and customer info for email
                        $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($reservation[0]["yacht_id"]), "", 1);
                        
                        if($yacht) {
                            // Send confirmation email to customer
                            $yacht_name = $yacht[0]["baslik"] ?? "Yacht";
                            $customer_name = $reservation[0]["name"];
                            $customer_email = $reservation[0]["email"];
                            $start_date = date('F j, Y', strtotime($reservation[0]["start_date"]));
                            $end_date = date('F j, Y', strtotime($reservation[0]["end_date"]));
                            $confirmation_code = $reservation[0]["confirmation_code"];
                            
                            $subject = "Reservation Confirmed - " . $yacht_name;
                            $message = "
                            <html>
                            <head>
                                <style>
                                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                    .header { background: #002355; color: white; padding: 20px; text-align: center; }
                                    .content { padding: 20px; }
                                    .reservation-details { background: #f7f7f7; padding: 15px; margin: 20px 0; border-left: 4px solid #C6A87B; }
                                    .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 0.8em; }
                                    .btn { display: inline-block; background: #C6A87B; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
                                </style>
                            </head>
                            <body>
                                <div class='container'>
                                    <div class='header'>
                                        <h1>Reservation Confirmed</h1>
                                    </div>
                                    <div class='content'>
                                        <p>Dear $customer_name,</p>
                                        <p>We are pleased to confirm your reservation for $yacht_name.</p>
                                        
                                        <div class='reservation-details'>
                                            <p><strong>Confirmation Code:</strong> $confirmation_code</p>
                                            <p><strong>Yacht:</strong> $yacht_name</p>
                                            <p><strong>Check-in:</strong> $start_date</p>
                                            <p><strong>Check-out:</strong> $end_date</p>
                                        </div>
                                        
                                        <p>Your reservation is now confirmed. Our team will contact you with further details about your boarding.</p>
                                        
                                        <p>Thank you for choosing Orient Yachting for your luxury yacht experience.</p>
                                        
                                        <p>Best regards,<br>The Orient Yachting Team</p>
                                    </div>
                                    <div class='footer'>
                                        <p>Orient Yachting | Luxury Yacht Charter</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                            ";
                            
                            if(method_exists($VT, 'MailGonder')) {
                                $VT->MailGonder($customer_email, $subject, $message);
                            }
                        }
                    } else {
                        $updateMessage = "Rezervasyon onaylanırken bir hata oluştu.";
                    }
                    break;
                    
                case "iptal":
                    // Cancel reservation
                    $updateResult = $VT->SorguCalistir(
                        "UPDATE reservations", 
                        "SET status=?, updated_at=?", 
                        array(2, date("Y-m-d H:i:s")), 
                        "WHERE ID=?", 
                        array($reservationID)
                    );
                    
                    if($updateResult) {
                        $updateSuccess = true;
                        $updateMessage = "Rezervasyon başarıyla iptal edildi.";
                        
                        // Get yacht and customer info for email
                        $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($reservation[0]["yacht_id"]), "", 1);
                        
                        if($yacht) {
                            // Send cancellation email to customer
                            $yacht_name = $yacht[0]["baslik"] ?? "Yacht";
                            $customer_name = $reservation[0]["name"];
                            $customer_email = $reservation[0]["email"];
                            
                            $subject = "Reservation Cancelled - " . $yacht_name;
                            $message = "
                            <html>
                            <head>
                                <style>
                                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                    .header { background: #002355; color: white; padding: 20px; text-align: center; }
                                    .content { padding: 20px; }
                                    .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 0.8em; }
                                </style>
                            </head>
                            <body>
                                <div class='container'>
                                    <div class='header'>
                                        <h1>Reservation Cancelled</h1>
                                    </div>
                                    <div class='content'>
                                        <p>Dear $customer_name,</p>
                                        <p>We regret to inform you that your reservation for $yacht_name has been cancelled.</p>
                                        
                                        <p>If you have any questions about this cancellation, please contact our customer service.</p>
                                        
                                        <p>Thank you for considering Orient Yachting for your yacht charter.</p>
                                        
                                        <p>Best regards,<br>The Orient Yachting Team</p>
                                    </div>
                                    <div class='footer'>
                                        <p>Orient Yachting | Luxury Yacht Charter</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                            ";
                            
                            if(method_exists($VT, 'MailGonder')) {
                                $VT->MailGonder($customer_email, $subject, $message);
                            }
                        }
                    } else {
                        $updateMessage = "Rezervasyon iptal edilirken bir hata oluştu.";
                    }
                    break;
                    
                case "tamamla":
                    // Mark reservation as completed
                    $updateResult = $VT->SorguCalistir(
                        "UPDATE reservations", 
                        "SET status=?, updated_at=?", 
                        array(3, date("Y-m-d H:i:s")), 
                        "WHERE ID=?", 
                        array($reservationID)
                    );
                    
                    if($updateResult) {
                        $updateSuccess = true;
                        $updateMessage = "Rezervasyon başarıyla tamamlandı olarak işaretlendi.";
                    } else {
                        $updateMessage = "Rezervasyon tamamlandı olarak işaretlenirken bir hata oluştu.";
                    }
                    break;
                    
                case "sil":
                    // Delete reservation
                    $deleteResult = $VT->SorguCalistir(
                        "DELETE FROM reservations", 
                        "WHERE ID=?", 
                        array($reservationID)
                    );
                    
                    if($deleteResult) {
                        $updateSuccess = true;
                        $updateMessage = "Rezervasyon başarıyla silindi.";
                    } else {
                        $updateMessage = "Rezervasyon silinirken bir hata oluştu.";
                    }
                    break;
                    
                default:
                    $updateMessage = "Geçersiz işlem.";
                    break;
            }
            
            // Show update message
            if($updateMessage != "") {
                ?>
                <div class="alert alert-<?php echo $updateSuccess ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $updateMessage; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
      <?php
            }
        }
    }
    
    // For handling notes update via AJAX
    if(isset($_POST["ajax_update_notes"]) && !empty($_POST["reservation_id"])) {
        $reservationID = $VT->filter($_POST["reservation_id"]);
        $notes = $VT->filter($_POST["admin_notes"]);
        
        $updateResult = $VT->SorguCalistir(
            "UPDATE reservations", 
            "SET admin_notes=?, updated_at=?", 
            array($notes, date("Y-m-d H:i:s")), 
            "WHERE ID=?", 
            array($reservationID)
        );
        
        if($updateResult) {
            echo json_encode(["status" => "success", "message" => "Notes updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update notes"]);
        }
        
        exit;
    }
    
    // Pagination setup
    $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Filter setup
    $statusFilter = isset($_GET["status"]) ? $VT->filter($_GET["status"]) : "";
    $searchTerm = isset($_GET["search"]) ? $VT->filter($_GET["search"]) : "";
    
    // Build where clause for filtering
    $whereClause = "";
    $whereParams = array();
    
    if($statusFilter !== "") {
        $whereClause = "WHERE status=?";
        $whereParams[] = $statusFilter;
    }
    
    if($searchTerm !== "") {
        if($whereClause === "") {
            $whereClause = "WHERE (name LIKE ? OR email LIKE ? OR phone LIKE ? OR confirmation_code LIKE ?)";
  } else {
            $whereClause .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR confirmation_code LIKE ?)";
        }
        $searchPattern = "%" . $searchTerm . "%";
        $whereParams[] = $searchPattern;
        $whereParams[] = $searchPattern;
        $whereParams[] = $searchPattern;
        $whereParams[] = $searchPattern;
    }
    
    // Get total count for pagination
    $totalCount = $VT->VeriGetir(
        "reservations", 
        $whereClause, 
        $whereParams, 
        "", 
        "", 
        "COUNT(ID) as total"
    );
    
    $totalCount = $totalCount[0]["total"] ?? 0;
    $totalPages = ceil($totalCount / $perPage);
    
    // Get reservations with pagination
    $reservations = $VT->VeriGetir(
        "reservations", 
        $whereClause, 
        $whereParams, 
        "ORDER BY created_at DESC", 
        "$offset,$perPage"
    );
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Rezervasyon Yönetimi</h1>
          </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?=SITE?>">Anasayfa</a></li>
              <li class="breadcrumb-item active">Rezervasyon Yönetimi</li>
          </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

  <section class="content">
    <div class="container-fluid">
        
        <!-- Status update messages -->
        <?php if(isset($updateMessage) && $updateMessage != "") { ?>
        <div class="alert alert-<?php echo $updateSuccess ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo $updateMessage; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>

        <!-- Filters -->
      <div class="card">
        <div class="card-header">
                <h3 class="card-title">Filtreler</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="" class="row">
                    <input type="hidden" name="sayfa" value="rezervasyonlar">
                    
                    <div class="col-md-3 mb-2">
                        <label for="status">Durum:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Tümü</option>
                            <option value="0" <?php echo $statusFilter === "0" ? "selected" : ""; ?>>Beklemede</option>
                            <option value="1" <?php echo $statusFilter === "1" ? "selected" : ""; ?>>Onaylandı</option>
                            <option value="2" <?php echo $statusFilter === "2" ? "selected" : ""; ?>>İptal Edildi</option>
                            <option value="3" <?php echo $statusFilter === "3" ? "selected" : ""; ?>>Tamamlandı</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label for="search">Ara:</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="İsim, e-posta, telefon..." value="<?php echo $searchTerm; ?>">
                    </div>
                    
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">Filtrele</button>
                        <a href="?sayfa=rezervasyonlar" class="btn btn-secondary">Sıfırla</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reservations Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rezervasyonlar <span class="badge badge-info"><?php echo $totalCount; ?></span></h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="reservationsTable">
            <thead>
              <tr>
                            <th>Kodu</th>
                            <th>Müşteri</th>
                <th>Yat</th>
                <th>Tarih Aralığı</th>
                <th>Kişi</th>
                            <th>Toplam Ücret</th>
                <th>Durum</th>
                            <th>Oluşturma Tarihi</th>
                            <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              <?php
                        if($reservations && count($reservations) > 0) {
                            foreach($reservations as $reservation) {
                                // Get yacht name
                                $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($reservation["yacht_id"]), "", 1);
                                $yacht_name = ($yacht) ? $yacht[0]["baslik"] : "Unknown Yacht";
                                
                                // Format dates
                                $start_date = date('d.m.Y', strtotime($reservation["start_date"]));
                                $end_date = date('d.m.Y', strtotime($reservation["end_date"]));
                                $created_date = date('d.m.Y H:i', strtotime($reservation["created_at"]));
                                
                                // Determine status class and label
                                $statusClass = "";
                                $statusLabel = "";
                                
                                switch($reservation["status"]) {
                                    case 0:
                                        $statusClass = "warning";
                                        $statusLabel = "Beklemede";
                                        break;
                                    case 1:
                                        $statusClass = "success";
                                        $statusLabel = "Onaylandı";
                                        break;
                                    case 2:
                                        $statusClass = "danger";
                                        $statusLabel = "İptal Edildi";
                                        break;
                                    case 3:
                                        $statusClass = "info";
                                        $statusLabel = "Tamamlandı";
                                        break;
                                    default:
                                        $statusClass = "secondary";
                                        $statusLabel = "Bilinmiyor";
                  }
              ?>
              <tr>
                                <td><?php echo $reservation["confirmation_code"]; ?></td>
                <td>
                                    <strong><?php echo $reservation["name"]; ?></strong><br>
                                    <small><?php echo $reservation["email"]; ?></small><br>
                                    <small><?php echo $reservation["phone"]; ?></small>
                </td>
                                <td><?php echo $yacht_name; ?></td>
                                <td><?php echo $start_date; ?> - <?php echo $end_date; ?></td>
                                <td><?php echo $reservation["guest_count"]; ?></td>
                                <td>€<?php echo number_format($reservation["total_price"], 2); ?></td>
                                <td><span class="badge badge-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                <td><?php echo $created_date; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            İşlemler
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="showReservationDetail(<?php echo $reservation["ID"]; ?>)">Detayları Göster</a>
                                            
                                            <?php if($reservation["status"] == 0) { ?>
                                                <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=onayla&id=<?php echo $reservation["ID"]; ?>" onclick="return confirm('Bu rezervasyonu onaylamak istediğinize emin misiniz?');">Onayla</a>
                                            <?php } ?>
                                            
                                            <?php if($reservation["status"] == 0 || $reservation["status"] == 1) { ?>
                                                <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=iptal&id=<?php echo $reservation["ID"]; ?>" onclick="return confirm('Bu rezervasyonu iptal etmek istediğinize emin misiniz?');">İptal Et</a>
                                            <?php } ?>
                                            
                                            <?php if($reservation["status"] == 1) { ?>
                                                <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=tamamla&id=<?php echo $reservation["ID"]; ?>" onclick="return confirm('Bu rezervasyonu tamamlandı olarak işaretlemek istediğinize emin misiniz?');">Tamamlandı</a>
                                            <?php } ?>
                                            
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="?sayfa=rezervasyonlar&islem=sil&id=<?php echo $reservation["ID"]; ?>" onclick="return confirm('Bu rezervasyonu silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">Sil</a>
                                        </div>
                                    </div>
                </td>
              </tr>
              <?php
                }
                        } else {
                            ?>
                            <tr>
                                <td colspan="9" class="text-center">Hiç rezervasyon bulunamadı.</td>
                            </tr>
                            <?php
              }
              ?>
            </tbody>
          </table>
        </div>
            
            <!-- Pagination -->
            <?php if($totalPages > 1) { ?>
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <?php if($page > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?sayfa=rezervasyonlar&page=<?php echo ($page-1); ?><?php echo $statusFilter !== "" ? "&status=".$statusFilter : ""; ?><?php echo $searchTerm !== "" ? "&search=".$searchTerm : ""; ?>">«</a>
                        </li>
                    <?php } ?>
                    
                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    for($i=$startPage; $i<=$endPage; $i++) { ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?sayfa=rezervasyonlar&page=<?php echo $i; ?><?php echo $statusFilter !== "" ? "&status=".$statusFilter : ""; ?><?php echo $searchTerm !== "" ? "&search=".$searchTerm : ""; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                    
                    <?php if($page < $totalPages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?sayfa=rezervasyonlar&page=<?php echo ($page+1); ?><?php echo $statusFilter !== "" ? "&status=".$statusFilter : ""; ?><?php echo $searchTerm !== "" ? "&search=".$searchTerm : ""; ?>">»</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- Reservation Detail Modal -->
<div class="modal fade" id="reservationDetailModal" tabindex="-1" role="dialog" aria-labelledby="reservationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationDetailModalLabel">Rezervasyon Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="reservationDetailContent">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Yükleniyor...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
      </div>
    </div>
</div>

<!-- JavaScript for Reservation Detail -->
<script>
function showReservationDetail(reservationID) {
    // Show modal
    $('#reservationDetailModal').modal('show');
    
    // Set loading state
    $('#reservationDetailContent').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Yükleniyor...</span></div></div>');
    
    // Get reservation details via AJAX
    $.ajax({
        url: 'include/ajax/reservation-detail.php',
        type: 'GET',
        data: {
            id: reservationID
        },
        success: function(response) {
            $('#reservationDetailContent').html(response);
            
            // Initialize notes save functionality
            initNotesUpdate();
        },
        error: function() {
            $('#reservationDetailContent').html('<div class="alert alert-danger m-3">Rezervasyon detayları yüklenirken bir hata oluştu.</div>');
        }
    });
}

function initNotesUpdate() {
    $('#saveNotesBtn').on('click', function() {
        var reservationID = $(this).data('reservation-id');
        var notes = $('#admin_notes').val();
        var $btn = $(this);
        
        // Disable button and show loading
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Kaydediliyor...');
        
        // Send AJAX request
        $.ajax({
            url: '',
            type: 'POST',
            data: {
                ajax_update_notes: true,
                reservation_id: reservationID,
                admin_notes: notes
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    // Show success message
                    $('#notesAlert').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + 
                        'Notlar başarıyla kaydedildi.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button></div>');
                } else {
                    // Show error message
                    $('#notesAlert').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + 
                        'Notlar kaydedilirken bir hata oluştu.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button></div>');
                }
            },
            error: function() {
                // Show error message
                $('#notesAlert').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + 
                    'Bir sunucu hatası oluştu. Lütfen tekrar deneyin.' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>');
            },
            complete: function() {
                // Reset button
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Kaydet');
            }
    });
  });
}
</script> 

<?php
} else {
    // Redirect to login if not authorized
    ?>
    <meta http-equiv="refresh" content="0;url=index.php">
    <?php
}
?> 
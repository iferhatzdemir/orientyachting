<?php
/**
 * Reservation Detail AJAX
 * Shows detailed information about a reservation in a modal
 */

// Check if this is a direct request or being included
if (!defined("SITE")) {
    // Include necessary files for direct requests
    include("../../../data/baglanti.php");
}

// Check if a reservation ID is provided
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo '<div class="alert alert-danger">Geçersiz rezervasyon ID\'si.</div>';
    exit;
}

// Get reservation ID
$reservationID = $VT->filter($_GET["id"]);

// Get reservation details
$reservation = $VT->VeriGetir("reservations", "WHERE ID=?", array($reservationID), "", 1);

if (!$reservation) {
    echo '<div class="alert alert-danger">Rezervasyon bulunamadı.</div>';
    exit;
}

// Get yacht details
$yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($reservation[0]["yacht_id"]), "", 1);

// Format dates
$start_date = date('d.m.Y', strtotime($reservation[0]["start_date"]));
$end_date = date('d.m.Y', strtotime($reservation[0]["end_date"]));
$created_date = date('d.m.Y H:i', strtotime($reservation[0]["created_at"]));
$updated_date = !empty($reservation[0]["updated_at"]) ? date('d.m.Y H:i', strtotime($reservation[0]["updated_at"])) : "-";

// Calculate days
$days = round((strtotime($reservation[0]["end_date"]) - strtotime($reservation[0]["start_date"])) / (60 * 60 * 24));

// Determine status class and label
$statusClass = "";
$statusLabel = "";

switch($reservation[0]["status"]) {
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

// Determine payment status
$paymentStatusClass = "";
$paymentStatusLabel = "";

switch($reservation[0]["payment_status"]) {
    case 0:
        $paymentStatusClass = "warning";
        $paymentStatusLabel = "Ödenmedi";
        break;
    case 1:
        $paymentStatusClass = "success";
        $paymentStatusLabel = "Ödendi";
        break;
    case 2:
        $paymentStatusClass = "info";
        $paymentStatusLabel = "Kısmi Ödeme";
        break;
    default:
        $paymentStatusClass = "warning";
        $paymentStatusLabel = "Ödenmedi";
}
?>

<!-- Reservation Status Banner -->
<div class="mb-4">
    <div class="card border-<?php echo $statusClass; ?> shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-0">
                    <span class="badge badge-<?php echo $statusClass; ?> mr-2"><?php echo $statusLabel; ?></span>
                    Rezervasyon #<?php echo $reservation[0]["ID"]; ?>
                </h5>
            </div>
            <div>
                <strong>Konfirmasyon Kodu:</strong> <?php echo $reservation[0]["confirmation_code"]; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-3 bg-gradient-primary text-white">
                <h6 class="m-0 font-weight-bold">Müşteri Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Ad Soyad:</div>
                    <div class="col-sm-8"><?php echo $reservation[0]["name"]; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">E-posta:</div>
                    <div class="col-sm-8">
                        <a href="mailto:<?php echo $reservation[0]["email"]; ?>"><?php echo $reservation[0]["email"]; ?></a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Telefon:</div>
                    <div class="col-sm-8">
                        <a href="tel:<?php echo $reservation[0]["phone"]; ?>"><?php echo $reservation[0]["phone"]; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 font-weight-bold">Kişi Sayısı:</div>
                    <div class="col-sm-8"><?php echo $reservation[0]["guest_count"]; ?> kişi</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-3 bg-gradient-success text-white">
                <h6 class="m-0 font-weight-bold">Yat Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Yat:</div>
                    <div class="col-sm-8">
                        <?php if($yacht): ?>
                            <a href="?sayfa=yacht-duzenle&ID=<?php echo $yacht[0]["ID"]; ?>">
                                <?php echo $yacht[0]["baslik"]; ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Bilinmeyen Yat</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Check-in:</div>
                    <div class="col-sm-8"><?php echo $start_date; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Check-out:</div>
                    <div class="col-sm-8"><?php echo $end_date; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Süre:</div>
                    <div class="col-sm-8"><?php echo $days; ?> gün</div>
                </div>
                <div class="row">
                    <div class="col-sm-4 font-weight-bold">Toplam Ücret:</div>
                    <div class="col-sm-8">
                        <span class="font-weight-bold text-success">€<?php echo number_format($reservation[0]["total_price"], 2); ?></span>
                        <span class="badge badge-<?php echo $paymentStatusClass; ?> ml-2"><?php echo $paymentStatusLabel; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-3 bg-gradient-info text-white">
                <h6 class="m-0 font-weight-bold">Rezervasyon Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Oluşturma Tarihi:</div>
                    <div class="col-sm-8"><?php echo $created_date; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 font-weight-bold">Son Güncelleme:</div>
                    <div class="col-sm-8"><?php echo $updated_date; ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4 font-weight-bold">Durum:</div>
                    <div class="col-sm-8">
                        <div class="btn-group btn-block">
                            <button type="button" class="btn btn-<?php echo $statusClass; ?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $statusLabel; ?>
                            </button>
                            <div class="dropdown-menu">
                                <?php if($reservation[0]["status"] != 1) { ?>
                                    <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=onayla&id=<?php echo $reservation[0]["ID"]; ?>" onclick="return confirm('Bu rezervasyonu onaylamak istediğinize emin misiniz?');">
                                        <i class="fas fa-check text-success"></i> Onayla
                                    </a>
                                <?php } ?>
                                
                                <?php if($reservation[0]["status"] != 2) { ?>
                                    <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=iptal&id=<?php echo $reservation[0]["ID"]; ?>" onclick="return confirm('Bu rezervasyonu iptal etmek istediğinize emin misiniz?');">
                                        <i class="fas fa-ban text-warning"></i> İptal Et
                                    </a>
                                <?php } ?>
                                
                                <?php if($reservation[0]["status"] != 3 && $reservation[0]["status"] != 2) { ?>
                                    <a class="dropdown-item" href="?sayfa=rezervasyonlar&islem=tamamla&id=<?php echo $reservation[0]["ID"]; ?>" onclick="return confirm('Bu rezervasyonu tamamlandı olarak işaretlemek istediğinize emin misiniz?');">
                                        <i class="fas fa-check-double text-info"></i> Tamamlandı
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-3 bg-gradient-secondary text-white">
                <h6 class="m-0 font-weight-bold">Müşteri İstekleri</h6>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded">
                    <?php if(!empty($reservation[0]["special_requests"])): ?>
                        <?php echo nl2br(htmlspecialchars($reservation[0]["special_requests"])); ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Özel istek belirtilmemiş.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-header py-3 bg-gradient-dark text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Admin Notları</h6>
    </div>
    <div class="card-body">
        <div id="notesAlert"></div>
        <div class="form-group mb-2">
            <textarea id="admin_notes" class="form-control" rows="4" placeholder="Rezervasyon hakkında notlarınızı buraya girebilirsiniz..."><?php echo htmlspecialchars($reservation[0]["admin_notes"] ?? ''); ?></textarea>
        </div>
        <button id="saveNotesBtn" class="btn btn-primary" data-reservation-id="<?php echo $reservation[0]["ID"]; ?>">
            <i class="fas fa-save mr-1"></i> Notları Kaydet
        </button>
    </div>
</div>

<div class="text-right mb-3">
    <div class="btn-group">
        <a href="?sayfa=rezervasyonlar&islem=sil&id=<?php echo $reservation[0]["ID"]; ?>" class="btn btn-danger" onclick="return confirm('Bu rezervasyonu silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">
            <i class="fas fa-trash mr-1"></i> Rezervasyonu Sil
        </a>
        <button type="button" class="btn btn-secondary" onclick="window.print();">
            <i class="fas fa-print mr-1"></i> Yazdır
        </button>
    </div>
</div> 
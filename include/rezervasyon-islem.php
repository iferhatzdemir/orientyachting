<?php
/**
 * Reservation Processing Script
 * Handles reservation form submissions, validation, and email notifications
 */

// Make sure the form is submitted
if (!$_POST) {
    header("Location: " . SITE);
    exit;
}

// Include required files
require_once(SINIF . "VT.php");
$VT = new VT();

// Check if all required fields are provided
if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone"]) || 
    empty($_POST["guest_count"]) || empty($_POST["start_date"]) || 
    empty($_POST["end_date"]) || empty($_POST["yacht_id"])) {
    
    echo json_encode([
        "status" => "error",
        "message" => "Please fill all required fields."
    ]);
    exit;
}

// Filter and sanitize input data
$name = $VT->filter($_POST["name"]);
$email = $VT->filter($_POST["email"]);
$phone = $VT->filter($_POST["phone"]);
$guest_count = intval($VT->filter($_POST["guest_count"]));
$start_date = $VT->filter($_POST["start_date"]);
$end_date = $VT->filter($_POST["end_date"]);
$special_requests = $VT->filter($_POST["special_requests"] ?? "");
$yacht_id = intval($VT->filter($_POST["yacht_id"]));

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please enter a valid email address."
    ]);
    exit;
}

// Get yacht information
$yacht = $VT->VeriGetir("yachts", "WHERE ID=? AND durum=?", array($yacht_id, 1), "", 1);
if (!$yacht) {
    echo json_encode([
        "status" => "error",
        "message" => "Selected yacht is not available."
    ]);
    exit;
}

// Validate dates
$start_timestamp = strtotime($start_date);
$end_timestamp = strtotime($end_date);

if (!$start_timestamp || !$end_timestamp) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid date format."
    ]);
    exit;
}

if ($start_timestamp >= $end_timestamp) {
    echo json_encode([
        "status" => "error",
        "message" => "End date must be after start date."
    ]);
    exit;
}

// Make sure dates are not in the past
if ($start_timestamp < strtotime(date('Y-m-d'))) {
    echo json_encode([
        "status" => "error",
        "message" => "Start date cannot be in the past."
    ]);
    exit;
}

// Validate guest count
$max_guests = isset($yacht[0]["capacity"]) ? intval($yacht[0]["capacity"]) : 0;
if ($guest_count <= 0 || ($max_guests > 0 && $guest_count > $max_guests)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid number of guests. Maximum capacity is " . $max_guests . "."
    ]);
    exit;
}

// Check availability (dates not already booked)
$booked = $VT->VeriGetir(
    "reservations", 
    "WHERE yacht_id=? AND status IN (0,1) AND 
    ((start_date BETWEEN ? AND ?) OR 
    (end_date BETWEEN ? AND ?) OR 
    (start_date <= ? AND end_date >= ?))",
    array(
        $yacht_id, 
        $start_date, $end_date, 
        $start_date, $end_date, 
        $start_date, $end_date
    )
);

if ($booked) {
    echo json_encode([
        "status" => "error",
        "message" => "Selected dates are not available. Please choose different dates."
    ]);
    exit;
}

// Calculate total price
$days = ceil(($end_timestamp - $start_timestamp) / 86400); // 86400 = seconds in a day
$price_per_day = $yacht[0]["price_per_day"] ?? 0;
$price_per_week = $yacht[0]["price_per_week"] ?? 0;

$total_price = 0;
if ($days >= 7 && $price_per_week > 0) {
    $weeks = floor($days / 7);
    $remaining_days = $days % 7;
    $total_price = ($weeks * $price_per_week) + ($remaining_days * $price_per_day);
} else {
    $total_price = $days * $price_per_day;
}

// Generate unique confirmation code
$confirmation_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

// Store reservation in database
$result = $VT->SorguCalistir(
    "INSERT INTO reservations",
    "SET yacht_id=?, name=?, email=?, phone=?, guest_count=?, 
    start_date=?, end_date=?, special_requests=?, total_price=?, 
    status=?, confirmation_code=?, created_at=?",
    array(
        $yacht_id, $name, $email, $phone, $guest_count,
        $start_date, $end_date, $special_requests, $total_price,
        0, // Status: Pending
        $confirmation_code,
        date("Y-m-d H:i:s")
    )
);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "We couldn't process your reservation. Please try again later."
    ]);
    exit;
}

// Get the ID of the new reservation
$reservation_id = $VT->baglanti->lastInsertId();

// Prepare email notifications
$yacht_name = $yacht[0]["baslik"] ?? "Yacht";
$yacht_image = !empty($yacht[0]["resim"]) ? SITE . "images/yachts/" . $yacht[0]["resim"] : SITE . "assets/img/yacht-1.jpg";

// Email to customer
$customer_subject = "Your Reservation Request - " . $yacht_name;
$customer_message = "
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
        h2 { color: #002355; }
        .yacht-image { width: 100%; height: auto; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Reservation Confirmation</h1>
        </div>
        <div class='content'>
            <p>Dear $name,</p>
            <p>Thank you for your reservation request. Your request has been received and is being processed.</p>
            
            <img src='$yacht_image' alt='$yacht_name' class='yacht-image'>
            
            <h2>Reservation Details:</h2>
            <div class='reservation-details'>
                <p><strong>Confirmation Code:</strong> $confirmation_code</p>
                <p><strong>Yacht:</strong> $yacht_name</p>
                <p><strong>Check-in:</strong> " . date('F j, Y', strtotime($start_date)) . "</p>
                <p><strong>Check-out:</strong> " . date('F j, Y', strtotime($end_date)) . "</p>
                <p><strong>Guests:</strong> $guest_count</p>
                <p><strong>Total Price:</strong> €" . number_format($total_price, 2) . "</p>
            </div>
            
            <p>Our team will review your reservation request and will contact you shortly to confirm availability and provide payment instructions.</p>
            
            <p>If you have any questions, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>The Orient Yachting Team</p>
        </div>
        <div class='footer'>
            <p>Orient Yachting | Luxury Yacht Charter</p>
        </div>
    </div>
</body>
</html>
";

// Email to admin
$admin_subject = "New Reservation Request - " . $yacht_name;
$admin_message = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #002355; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .reservation-details { background: #f7f7f7; padding: 15px; margin: 20px 0; border-left: 4px solid #C6A87B; }
        .customer-details { background: #f7f7f7; padding: 15px; margin: 20px 0; border-left: 4px solid #002355; }
        .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 0.8em; }
        .btn { display: inline-block; background: #002355; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
        h2 { color: #002355; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>New Reservation Request</h1>
        </div>
        <div class='content'>
            <p>A new reservation request has been submitted.</p>
            
            <h2>Customer Information:</h2>
            <div class='customer-details'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
            </div>
            
            <h2>Reservation Details:</h2>
            <div class='reservation-details'>
                <p><strong>Confirmation Code:</strong> $confirmation_code</p>
                <p><strong>Yacht:</strong> $yacht_name (ID: $yacht_id)</p>
                <p><strong>Check-in:</strong> " . date('F j, Y', strtotime($start_date)) . "</p>
                <p><strong>Check-out:</strong> " . date('F j, Y', strtotime($end_date)) . "</p>
                <p><strong>Guests:</strong> $guest_count</p>
                <p><strong>Total Price:</strong> €" . number_format($total_price, 2) . "</p>
                <p><strong>Special Requests:</strong> " . (empty($special_requests) ? "None" : $special_requests) . "</p>
            </div>
            
            <p>Please log in to the admin panel to review and manage this reservation.</p>
            
            <p><a href='" . SITE . "admin' class='btn'>Go to Admin Panel</a></p>
        </div>
        <div class='footer'>
            <p>Orient Yachting | Reservation System</p>
        </div>
    </div>
</body>
</html>
";

// Send emails
$admin_email = isset($sitemail) ? $sitemail : "info@orientyachting.com";
$mail_sent = false;

if (method_exists($VT, 'MailGonder')) {
    try {
        // Send email to customer
        $mail_sent = $VT->MailGonder($email, $customer_subject, $customer_message);
        if (!$mail_sent) {
            error_log("Failed to send customer email to: $email, Reservation ID: $reservation_id");
        }
        
        // Send email to admin
        $admin_mail_sent = $VT->MailGonder($admin_email, $admin_subject, $admin_message);
        if (!$admin_mail_sent) {
            error_log("Failed to send admin email to: $admin_email, Reservation ID: $reservation_id");
        }
    } catch (Exception $e) {
        error_log("Exception in sending reservation emails: " . $e->getMessage());
    }
}

// Return success response
echo json_encode([
    "status" => "success",
    "message" => "Your reservation request has been received successfully. Confirmation code: " . $confirmation_code,
    "confirmation_code" => $confirmation_code,
    "reservation_id" => $reservation_id,
    "mail_sent" => $mail_sent
]);
exit; 
<?php
if(!defined("SITE")) die("Direct access not allowed!");

if($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once(SINIF."VT.php");
    $VT = new VT();
    
    // Get form data and sanitize
    $yacht_id = isset($_POST["yacht_id"]) ? (int)$_POST["yacht_id"] : 0;
    $name = isset($_POST["name"]) ? htmlspecialchars(strip_tags($_POST["name"])) : "";
    $email = isset($_POST["email"]) ? filter_var($_POST["email"], FILTER_SANITIZE_EMAIL) : "";
    $phone = isset($_POST["phone"]) ? htmlspecialchars(strip_tags($_POST["phone"])) : "";
    $start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : "";
    $end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : "";
    $guest_count = isset($_POST["guest_count"]) ? (int)$_POST["guest_count"] : 0;
    $special_requests = isset($_POST["special_requests"]) ? htmlspecialchars(strip_tags($_POST["special_requests"])) : "";
    
    // Validate required fields
    if(empty($yacht_id) || empty($name) || empty($email) || empty($phone) || 
       empty($start_date) || empty($end_date) || empty($guest_count)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please fill in all required fields."
        ]);
        exit;
    }
    
    // Validate email format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please enter a valid email address."
        ]);
        exit;
    }
    
    // Get yacht details for email
    $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($yacht_id), "", 1);
    if(!$yacht) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid yacht selection."
        ]);
        exit;
    }
    
    // Calculate total days and price
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $days = $end->diff($start)->days;
    
    // Calculate total price based on daily or weekly rate
    $total_price = 0;
    if($days >= 7) {
        $weeks = ceil($days / 7);
        $total_price = $weeks * $yacht[0]["price_per_week"];
    } else {
        $total_price = $days * $yacht[0]["price_per_day"];
    }
    
    // Insert into database
    $ekle = $VT->SorguCalistir("INSERT INTO reservations", 
        array(
            "yacht_id"=>$yacht_id,
            "name"=>$name,
            "email"=>$email,
            "phone"=>$phone,
            "start_date"=>$start_date,
            "end_date"=>$end_date,
            "guest_count"=>$guest_count,
            "special_requests"=>$special_requests,
            "total_price"=>$total_price,
            "status"=>0, // Pending
            "payment_status"=>0, // Unpaid
            "created_at"=>date("Y-m-d H:i:s")
        )
    );
    
    if($ekle) {
        // Prepare email content
        $emailTemplate = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #1B2B44; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { text-align: center; padding: 20px; background: #eee; }
                .details { margin: 20px 0; }
                .details table { width: 100%; border-collapse: collapse; }
                .details td { padding: 10px; border-bottom: 1px solid #ddd; }
                .highlight { color: #C8A97E; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>New Yacht Reservation Request</h2>
                </div>
                <div class="content">
                    <p>A new reservation request has been received for <strong>'.$yacht[0]["baslik"].'</strong></p>
                    
                    <div class="details">
                        <table>
                            <tr>
                                <td><strong>Guest Name:</strong></td>
                                <td>'.$name.'</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>'.$email.'</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>'.$phone.'</td>
                            </tr>
                            <tr>
                                <td><strong>Check-in:</strong></td>
                                <td>'.date("d/m/Y", strtotime($start_date)).'</td>
                            </tr>
                            <tr>
                                <td><strong>Check-out:</strong></td>
                                <td>'.date("d/m/Y", strtotime($end_date)).'</td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>'.$days.' days</td>
                            </tr>
                            <tr>
                                <td><strong>Guests:</strong></td>
                                <td>'.$guest_count.' persons</td>
                            </tr>
                            <tr>
                                <td><strong>Total Price:</strong></td>
                                <td class="highlight">€'.number_format($total_price, 2).'</td>
                            </tr>
                        </table>
                    </div>
                    
                    '.(!empty($special_requests) ? '
                    <div class="special-requests">
                        <h3>Special Requests:</h3>
                        <p>'.$special_requests.'</p>
                    </div>
                    ' : '').'
                </div>
                <div class="footer">
                    <p>This is an automated message from your website reservation system.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Email settings for when site goes live
        $to = "info@orientyachting.com";
        $subject = "New Reservation Request - ".$yacht[0]["baslik"];
        $headers = array(
            "MIME-Version: 1.0",
            "Content-type: text/html; charset=UTF-8",
            "From: Orient Yachting <noreply@orientyachting.com>",
            "Reply-To: ".$name." <".$email.">",
            "X-Mailer: PHP/".phpversion()
        );
        
        // Store email content in session for testing
        $_SESSION["last_reservation_email"] = array(
            "to" => $to,
            "subject" => $subject,
            "message" => $emailTemplate,
            "headers" => $headers
        );
        
        // When site goes live, uncomment this:
        // mail($to, $subject, $emailTemplate, implode("\r\n", $headers));
        
        echo json_encode([
            "status" => "success",
            "message" => "Your reservation request has been received. We will contact you shortly."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "An error occurred while processing your request. Please try again."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
?> 
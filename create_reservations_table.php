<?php
/**
 * Create Reservations Table
 * This script creates the reservations table in the Orient Yachting database
 */

// Include necessary files
define("SITE", "http://localhost/orientyachting/");
define("SINIF", "classes/");

include_once(SINIF."VT.php");
$VT = new VT();

// SQL for creating the reservations table
$sql = "
CREATE TABLE IF NOT EXISTS `reservations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `yacht_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT 'Guest name',
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `guest_count` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `special_requests` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` tinyint(1) DEFAULT 0 COMMENT '0: Unpaid, 1: Paid, 2: Partially Paid',
  `status` tinyint(1) DEFAULT 0 COMMENT '0: Pending, 1: Confirmed, 2: Cancelled, 3: Completed',
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `confirmation_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `yacht_id` (`yacht_id`),
  KEY `status` (`status`),
  KEY `email` (`email`),
  KEY `date_range` (`start_date`,`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";

// Execute the SQL query
$result = $VT->SorguCalistir($sql);

if ($result) {
    echo "Success: Reservations table created or already exists.";
} else {
    echo "Error: Failed to create reservations table.";
}
?> 
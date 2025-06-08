<?php
// Site URL configuration
$site_url = '/orientyachting/'; // Change this according to your setup
define('SITE', $site_url);
define('SINIF', 'inc/');

// Database configuration
define('DBHOST', 'localhost');
define('DBUSER', 'your_database_user');
define('DBPASS', 'your_database_password');
define('DBNAME', 'orient');

// Debug mode
define('DEBUG', false); // Set to true for development

// Time zone
date_default_timezone_set('Europe/Istanbul');

// Error reporting
if(DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?> 
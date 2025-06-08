<?php
// Maximum error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_log("--- Minimal test at " . date('Y-m-d H:i:s') . " ---");

// Session and output buffering
session_start();
ob_start();

// Define constants
define("DATA", "data/");
define("SAYFA", "include/");
define("SINIF", "admin/class/");

// Basic site config
$siteurl = "http://" . $_SERVER['HTTP_HOST'] . "/orient/";
$sitebaslik = "Orient Yacht";
$sitetelefon = "+90 552 123 45 67";
$sitemail = "info@orientyachting.com";

define("SITE", $siteurl);

// Simple HTML output
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Orient Yacht - Minimal Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        h1 { color: #2c3e50; }
        .success { color: green; }
        .error { color: red; }
        .section { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    </style>
</head>
<body>
    <div class='header'>
        <h1>Orient Yacht - Diagnostic Page</h1>
        <p>Testing basic functionality</p>
    </div>
    
    <div class='section'>
        <h2>Environment Info</h2>
        <p>PHP Version: " . phpversion() . "</p>
        <p>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>
        <p>Current Time: " . date('Y-m-d H:i:s') . "</p>
    </div>
    
    <div class='section'>
        <h2>File Existence Checks</h2>";

$files = [
    'data/baglanti.php' => 'Database Connection',
    'data/ust.php' => 'Header Template',
    'data/footer.php' => 'Footer Template',
    'include/home.php' => 'Home Page',
    'include/seo.php' => 'SEO File',
    'admin/class/autoload.php' => 'Autoload File',
    'include/components/language-selector.php' => 'Language Selector'
];

foreach ($files as $file => $description) {
    $exists = file_exists($file);
    echo "<p>" . $description . ": <span class='" . ($exists ? 'success' : 'error') . "'>" . ($exists ? 'Found' : 'Missing') . "</span></p>";
}

echo "</div>
    
    <div class='section'>
        <h2>Testing Database Connection</h2>";

// Include database connection file and check if VT object exists
if (file_exists(DATA . "baglanti.php")) {
    // Basic site config in case database file is missing it
    if (!isset($siteurl)) {
        $siteurl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/orient/";
        define("SITE", $siteurl);
    }
    
    include_once(DATA . "baglanti.php");
    echo "<p>Database file included: <span class='success'>Yes</span></p>";
    echo "<p>VT object exists: <span class='" . (isset($VT) ? 'success' : 'error') . "'>" . (isset($VT) ? 'Yes' : 'No') . "</span></p>";
    
    // Create VT object if it's not defined
    if (!isset($VT)) {
        class EmergencyVT {
            public function VeriGetir($tablo, $where = "", $whereArray = array(), $orderBy = "", $limit = "") {
                return false;
            }
            
            public function SorguCalistir($query, $params = array()) {
                return false;
            }
        }
        
        $VT = new EmergencyVT();
        echo "<p>Created emergency VT fallback</p>";
    }
} else {
    echo "<p>Database file included: <span class='error'>No</span></p>";
}

echo "</div>
    
    <div class='section'>
        <h2>Testing Function Availability</h2>
        <p>t() function: <span class='" . (function_exists('t') ? 'success' : 'error') . "'>" . (function_exists('t') ? 'Available' : 'Not Available') . "</span></p>
        <p>getMultilingualContent() function: <span class='" . (function_exists('getMultilingualContent') ? 'success' : 'error') . "'>" . (function_exists('getMultilingualContent') ? 'Available' : 'Not Available') . "</span></p>
    </div>
    
    <div class='section'>
        <h2>Minimal Content Test</h2>";

// Try to include home.php with error handling
if (file_exists(SAYFA . "home.php")) {
    try {
        // Buffer the output to catch any errors
        ob_start();
        include_once(SAYFA . "home.php");
        $homeOutput = ob_get_clean();
        
        echo "<p>Home page included: <span class='success'>Successfully</span></p>";
        echo "<p>Content length: " . strlen($homeOutput) . " bytes</p>";
    } catch (Exception $e) {
        echo "<p>Error including home page: <span class='error'>" . $e->getMessage() . "</span></p>";
    }
} else {
    echo "<p>Home page file: <span class='error'>Not found</span></p>";
}

echo "</div>
</body>
</html>";

// Flush the output buffer
ob_end_flush();
?> 
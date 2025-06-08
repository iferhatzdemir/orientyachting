<?php
/**
 * Comprehensive Debugging Script for Orient Yacht
 * 
 * This script provides detailed diagnostics for PHP configuration,
 * database connectivity, file existence, functions, and more.
 */

// Start diagnostics
$startTime = microtime(true);

// Maximum error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set up error log
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_log("--- Debug script run at " . date('Y-m-d H:i:s') . " ---");

// Define constants
define("DATA", "data/");
define("SAYFA", "include/");
define("SINIF", "admin/class/");

// Function to check if a directory is writable
function is_really_writable($path) {
    if (DIRECTORY_SEPARATOR === '/') {
        return is_writable($path);
    }
    
    // Windows specific check
    if (is_dir($path)) {
        $path = rtrim($path, '/\\') . '/test.txt';
        $fp = @fopen($path, 'ab');
        if ($fp === false) {
            return false;
        }
        fclose($fp);
        @unlink($path);
        return true;
    } elseif (!is_file($path) || ($fp = @fopen($path, 'ab')) === false) {
        return false;
    }
    
    fclose($fp);
    return true;
}

// Storage for diagnostic results
$diagnostics = [
    'environment' => [],
    'files' => [],
    'database' => [],
    'functions' => [],
    'misc' => []
];

// Begin HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orient Yacht - Comprehensive Diagnostics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        header {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin: 0;
        }
        .section {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .code {
            font-family: monospace;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 3px;
            overflow: auto;
            max-height: 400px;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Orient Yacht</h1>
        <p>Comprehensive Diagnostic Tool</p>
    </header>
    
    <div class="section">
        <h2>Environment Info</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
            <?php
            // Check PHP version
            $phpVersion = phpversion();
            $phpStatus = version_compare($phpVersion, '7.3.0', '>=') ? 'success' : 'error';
            $diagnostics['environment']['php_version'] = [
                'value' => $phpVersion,
                'status' => $phpStatus
            ];
            echo "<tr>
                    <td>PHP Version</td>
                    <td>{$phpVersion}</td>
                    <td class='{$phpStatus}'>" . ($phpStatus == 'success' ? 'Compatible' : 'Not compatible - PHP 7.3+ required') . "</td>
                </tr>";
            
            // Check PHP extensions
            $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'gd', 'session'];
            foreach ($requiredExtensions as $ext) {
                $loaded = extension_loaded($ext);
                $extStatus = $loaded ? 'success' : 'error';
                $diagnostics['environment']['extension_' . $ext] = [
                    'value' => $loaded ? 'Loaded' : 'Not loaded',
                    'status' => $extStatus
                ];
                echo "<tr>
                        <td>PHP Extension: {$ext}</td>
                        <td>" . ($loaded ? 'Loaded' : 'Not loaded') . "</td>
                        <td class='{$extStatus}'>" . ($loaded ? 'OK' : 'Required') . "</td>
                    </tr>";
            }
            
            // Check PHP settings
            $memoryLimit = ini_get('memory_limit');
            $memoryValue = (int)$memoryLimit;
            $memoryStatus = ($memoryValue >= 64 || $memoryValue == -1) ? 'success' : 'warning';
            $diagnostics['environment']['memory_limit'] = [
                'value' => $memoryLimit,
                'status' => $memoryStatus
            ];
            echo "<tr>
                    <td>Memory Limit</td>
                    <td>{$memoryLimit}</td>
                    <td class='{$memoryStatus}'>" . ($memoryStatus == 'success' ? 'OK' : 'Recommended: at least 64M') . "</td>
                </tr>";
            
            // Check max execution time
            $maxExecutionTime = ini_get('max_execution_time');
            $executionStatus = ($maxExecutionTime >= 30 || $maxExecutionTime == 0) ? 'success' : 'warning';
            $diagnostics['environment']['max_execution_time'] = [
                'value' => $maxExecutionTime,
                'status' => $executionStatus
            ];
            echo "<tr>
                    <td>Max Execution Time</td>
                    <td>{$maxExecutionTime} seconds</td>
                    <td class='{$executionStatus}'>" . ($executionStatus == 'success' ? 'OK' : 'Recommended: at least 30 seconds') . "</td>
                </tr>";
            
            // Check session status
            $sessionActive = session_status() === PHP_SESSION_ACTIVE;
            $sessionStatus = $sessionActive ? 'success' : 'error';
            $diagnostics['environment']['session'] = [
                'value' => $sessionActive ? 'Active' : 'Inactive',
                'status' => $sessionStatus
            ];
            echo "<tr>
                    <td>Session</td>
                    <td>" . ($sessionActive ? 'Active' : 'Inactive') . "</td>
                    <td class='{$sessionStatus}'>" . ($sessionActive ? 'OK' : 'Sessions required') . "</td>
                </tr>";
            
            // Check server software
            $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
            echo "<tr>
                    <td>Server Software</td>
                    <td>{$serverSoftware}</td>
                    <td class='success'>Informational</td>
                </tr>";
                
            // Check if running on localhost or production
            $host = $_SERVER['HTTP_HOST'] ?? 'unknown';
            $isLocalhost = strpos($host, 'localhost') !== false || $host === '127.0.0.1' || $host === '::1';
            echo "<tr>
                    <td>Environment</td>
                    <td>" . ($isLocalhost ? 'Development (localhost)' : 'Production') . "</td>
                    <td class='success'>Informational</td>
                </tr>";
            ?>
        </table>
    </div>
    
    <div class="section">
        <h2>File System Checks</h2>
        <table>
            <tr>
                <th>File/Directory</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
            <?php
            // Essential files to check
            $essentialFiles = [
                'data/baglanti.php' => 'Database Connection',
                'data/db-credentials.php' => 'Database Credentials',
                'data/ust.php' => 'Header Template',
                'data/footer.php' => 'Footer Template',
                'include/home.php' => 'Home Page',
                'include/seo.php' => 'SEO File',
                'admin/class/autoload.php' => 'Autoload File',
                'admin/class/LanguageController.php' => 'Language Controller',
                'include/components/language-selector.php' => 'Language Selector',
                'lang/en.php' => 'English Language File'
            ];
            
            foreach ($essentialFiles as $file => $description) {
                $exists = file_exists($file);
                $fileStatus = $exists ? 'success' : 'error';
                $diagnostics['files'][$file] = [
                    'value' => $exists ? 'Exists' : 'Missing',
                    'status' => $fileStatus
                ];
                echo "<tr>
                        <td>{$description} ({$file})</td>
                        <td class='{$fileStatus}'>" . ($exists ? 'Found' : 'Missing') . "</td>
                        <td>" . ($exists ? 'Last modified: ' . date('Y-m-d H:i:s', filemtime($file)) : 'Create this file to fix the issue') . "</td>
                    </tr>";
            }
            
            // Check directory permissions
            $essentialDirs = [
                'data' => 'Data Directory',
                'include' => 'Include Directory',
                'admin' => 'Admin Directory',
                'assets' => 'Assets Directory',
                'lang' => 'Language Directory',
                'images' => 'Images Directory'
            ];
            
            foreach ($essentialDirs as $dir => $description) {
                $exists = is_dir($dir);
                $writable = $exists && is_really_writable($dir);
                $dirStatus = !$exists ? 'error' : ($writable ? 'success' : 'warning');
                $diagnostics['files']['dir_' . $dir] = [
                    'value' => !$exists ? 'Missing' : ($writable ? 'Writable' : 'Not writable'),
                    'status' => $dirStatus
                ];
                echo "<tr>
                        <td>{$description} ({$dir}/)</td>
                        <td class='{$dirStatus}'>" . (!$exists ? 'Missing' : ($writable ? 'Writable' : 'Not writable')) . "</td>
                        <td>" . (!$exists ? 'Create this directory' : ($writable ? 'OK' : 'Change permissions to make writable')) . "</td>
                    </tr>";
            }
            
            // Check .htaccess
            $htaccessExists = file_exists('.htaccess');
            $htaccessStatus = $htaccessExists ? 'success' : 'warning';
            $diagnostics['files']['htaccess'] = [
                'value' => $htaccessExists ? 'Exists' : 'Missing',
                'status' => $htaccessStatus
            ];
            echo "<tr>
                    <td>.htaccess File</td>
                    <td class='{$htaccessStatus}'>" . ($htaccessExists ? 'Found' : 'Missing') . "</td>
                    <td>" . ($htaccessExists ? 'OK' : 'Create .htaccess for friendly URLs') . "</td>
                </tr>";
            ?>
        </table>
    </div>
    
    <div class="section">
        <h2>Database Connectivity</h2>
        <?php
        // Try to connect to database
        try {
            // Include database credentials file if it exists
            $dbCredentialsFile = DATA . 'db-credentials.php';
            if (file_exists($dbCredentialsFile)) {
                include_once($dbCredentialsFile);
                echo "<p class='success'>Successfully included database credentials file.</p>";
                $diagnostics['database']['credentials_file'] = [
                    'value' => 'Found and included',
                    'status' => 'success'
                ];
            } else {
                echo "<p class='warning'>Database credentials file not found. Using default settings.</p>";
                $diagnostics['database']['credentials_file'] = [
                    'value' => 'Missing',
                    'status' => 'warning'
                ];
                // Default values
                $host = "localhost";
                $dbname = "orient";
                $username = "root";
                $password = "";
            }
            
            // Try database connection
            $dbConnectionStart = microtime(true);
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3
            ];
            
            $DB = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
            $dbConnectionTime = round((microtime(true) - $dbConnectionStart) * 1000, 2);
            
            echo "<p class='success'>Database connection successful! Connection time: {$dbConnectionTime}ms</p>";
            $diagnostics['database']['connection'] = [
                'value' => 'Connected successfully',
                'status' => 'success'
            ];
            $diagnostics['database']['connection_time'] = [
                'value' => $dbConnectionTime . 'ms',
                'status' => $dbConnectionTime < 100 ? 'success' : ($dbConnectionTime < 500 ? 'warning' : 'error')
            ];
            
            // Test query
            $stmt = $DB->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "<p>Found " . count($tables) . " tables in the database:</p>";
            echo "<div class='code'>" . implode(", ", $tables) . "</div>";
            
            $requiredTables = ['banner', 'yachts', 'resimler', 'yacht_locations', 'translate_items'];
            $missingTables = array_diff($requiredTables, $tables);
            
            if (empty($missingTables)) {
                echo "<p class='success'>All required tables exist.</p>";
                $diagnostics['database']['required_tables'] = [
                    'value' => 'All present',
                    'status' => 'success'
                ];
            } else {
                echo "<p class='error'>Missing required tables: " . implode(", ", $missingTables) . "</p>";
                $diagnostics['database']['required_tables'] = [
                    'value' => 'Missing: ' . implode(", ", $missingTables),
                    'status' => 'error'
                ];
            }
            
            // Create VT instance and test it
            include_once(DATA . "baglanti.php");
            if (isset($VT) && method_exists($VT, 'VeriGetir')) {
                echo "<p class='success'>VT class is available and properly initialized.</p>";
                $diagnostics['database']['vt_class'] = [
                    'value' => 'Available',
                    'status' => 'success'
                ];
                
                // Test a query
                $testQuery = $VT->VeriGetir("banner", "WHERE durum=?", array(1), "ORDER BY sirano ASC", 1);
                if ($testQuery !== false) {
                    echo "<p class='success'>Test query executed successfully.</p>";
                    $diagnostics['database']['test_query'] = [
                        'value' => 'Success',
                        'status' => 'success'
                    ];
                } else {
                    echo "<p class='warning'>Test query returned no results. This might be normal if there are no active banners.</p>";
                    $diagnostics['database']['test_query'] = [
                        'value' => 'No results',
                        'status' => 'warning'
                    ];
                }
            } else {
                echo "<p class='error'>VT class is not available or improperly initialized.</p>";
                $diagnostics['database']['vt_class'] = [
                    'value' => 'Not available',
                    'status' => 'error'
                ];
            }
            
        } catch (PDOException $e) {
            echo "<p class='error'>Database connection failed: " . $e->getMessage() . "</p>";
            $diagnostics['database']['connection'] = [
                'value' => 'Failed: ' . $e->getMessage(),
                'status' => 'error'
            ];
            
            echo "<p>Please check the following:</p>";
            echo "<ul>";
            echo "<li>Database server is running</li>";
            echo "<li>Database name is correct</li>";
            echo "<li>Database username and password are correct</li>";
            echo "<li>Database user has necessary permissions</li>";
            echo "</ul>";
            
            echo "<p>Current settings:</p>";
            echo "<ul>";
            echo "<li>Host: " . ($host ?? 'Unknown') . "</li>";
            echo "<li>Database: " . ($dbname ?? 'Unknown') . "</li>";
            echo "<li>Username: " . ($username ?? 'Unknown') . "</li>";
            echo "</ul>";
            
            echo "<p>To fix this, edit the <code>data/db-credentials.php</code> file with correct credentials.</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>Function and Class Availability</h2>
        <table>
            <tr>
                <th>Function/Class</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
            <?php
            // Check critical functions
            $criticalFunctions = [
                't' => 'Translation function',
                'getMultilingualContent' => 'Multilingual content function',
                'renderLanguageSelector' => 'Language selector renderer',
                'outputLanguageData' => 'Language data output'
            ];
            
            foreach ($criticalFunctions as $function => $description) {
                $exists = function_exists($function);
                $funcStatus = $exists ? 'success' : 'error';
                $diagnostics['functions'][$function] = [
                    'value' => $exists ? 'Available' : 'Missing',
                    'status' => $funcStatus
                ];
                echo "<tr>
                        <td>{$description} ({$function}())</td>
                        <td class='{$funcStatus}'>" . ($exists ? 'Available' : 'Missing') . "</td>
                        <td>" . ($exists ? 'OK' : 'Function not defined - check implementation') . "</td>
                    </tr>";
            }
            
            // Check critical classes
            $criticalClasses = [
                'LanguageController' => 'Language controller',
                'VT' => 'Database manager'
            ];
            
            foreach ($criticalClasses as $class => $description) {
                $exists = class_exists($class);
                $classStatus = $exists ? 'success' : 'error';
                $diagnostics['functions']['class_' . $class] = [
                    'value' => $exists ? 'Available' : 'Missing',
                    'status' => $classStatus
                ];
                echo "<tr>
                        <td>{$description} ({$class} class)</td>
                        <td class='{$classStatus}'>" . ($exists ? 'Available' : 'Missing') . "</td>
                        <td>" . ($exists ? 'OK' : 'Class not defined - check implementation') . "</td>
                    </tr>";
            }
            
            // Check language controller
            if (isset($languageController) && is_object($languageController)) {
                echo "<tr>
                        <td>Language controller instance</td>
                        <td class='success'>Available</td>
                        <td>Current language: " . (method_exists($languageController, 'getCurrentLang') ? $languageController->getCurrentLang() : 'Unknown') . "</td>
                    </tr>";
                $diagnostics['functions']['language_controller_instance'] = [
                    'value' => 'Available',
                    'status' => 'success'
                ];
            } else {
                echo "<tr>
                        <td>Language controller instance</td>
                        <td class='error'>Not initialized</td>
                        <td>Language controller object not found</td>
                    </tr>";
                $diagnostics['functions']['language_controller_instance'] = [
                    'value' => 'Not initialized',
                    'status' => 'error'
                ];
            }
            ?>
        </table>
    </div>
    
    <div class="section">
        <h2>Output Buffering and Error Handling</h2>
        <?php
        // Check output buffering
        $obLevel = ob_get_level();
        $obStatus = $obLevel > 0 ? 'success' : 'warning';
        $diagnostics['misc']['output_buffering'] = [
            'value' => "Level $obLevel",
            'status' => $obStatus
        ];
        echo "<p>Output Buffering: <span class='{$obStatus}'>" . ($obLevel > 0 ? "Active (Level $obLevel)" : "Inactive") . "</span></p>";
        
        // Check error log
        $errorLogPath = ini_get('error_log');
        $errorLogExists = !empty($errorLogPath) && file_exists($errorLogPath);
        $errorLogStatus = $errorLogExists ? 'success' : 'warning';
        $diagnostics['misc']['error_log'] = [
            'value' => $errorLogExists ? $errorLogPath : 'Not found',
            'status' => $errorLogStatus
        ];
        echo "<p>Error Log: <span class='{$errorLogStatus}'>" . ($errorLogExists ? $errorLogPath : "Not found") . "</span></p>";
        
        if ($errorLogExists) {
            $errorLogSize = filesize($errorLogPath);
            $errorLogSizeFormatted = $errorLogSize < 1024 ? $errorLogSize . ' bytes' : 
                                   ($errorLogSize < 1048576 ? round($errorLogSize / 1024, 2) . ' KB' : 
                                    round($errorLogSize / 1048576, 2) . ' MB');
            
            echo "<p>Error Log Size: {$errorLogSizeFormatted}</p>";
            
            if ($errorLogSize > 0 && $errorLogSize < 1048576) { // Only show if less than 1MB
                echo "<p>Last 10 lines of error log:</p>";
                echo "<div class='code'>";
                $errorLogLines = file($errorLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $lastLines = array_slice($errorLogLines, -10);
                foreach ($lastLines as $line) {
                    echo htmlspecialchars($line) . "<br>";
                }
                echo "</div>";
            } else if ($errorLogSize >= 1048576) {
                echo "<p class='warning'>Error log is large ({$errorLogSizeFormatted}). Consider rotating or examining it.</p>";
            }
        }
        
        // Check display_errors setting
        $displayErrors = ini_get('display_errors');
        $displayErrorsStatus = $isLocalhost ? ($displayErrors ? 'success' : 'warning') : 
                              (!$displayErrors ? 'success' : 'warning');
        $diagnostics['misc']['display_errors'] = [
            'value' => $displayErrors ? 'On' : 'Off',
            'status' => $displayErrorsStatus
        ];
        echo "<p>Display Errors: <span class='{$displayErrorsStatus}'>" . ($displayErrors ? "On" : "Off") . "</span> " . 
             ($isLocalhost ? "(Recommended: On for development)" : "(Recommended: Off for production)") . "</p>";
        ?>
    </div>
    
    <div class="section">
        <h2>Summary and Recommendations</h2>
        <?php
        // Count issues
        $errors = 0;
        $warnings = 0;
        
        foreach ($diagnostics as $category => $items) {
            foreach ($items as $item) {
                if ($item['status'] === 'error') $errors++;
                if ($item['status'] === 'warning') $warnings++;
            }
        }
        
        // Display summary
        if ($errors === 0 && $warnings === 0) {
            echo "<p class='success'>No issues found! The system appears to be configured correctly.</p>";
        } else {
            echo "<p>" . ($errors > 0 ? "<span class='error'>{$errors} critical issues</span>" : "") . 
                 ($errors > 0 && $warnings > 0 ? " and " : "") . 
                 ($warnings > 0 ? "<span class='warning'>{$warnings} warnings</span>" : "") . 
                 " detected.</p>";
            
            if ($errors > 0) {
                echo "<p class='error'>Please fix the critical issues before proceeding:</p>";
                echo "<ul>";
                foreach ($diagnostics as $category => $items) {
                    foreach ($items as $key => $item) {
                        if ($item['status'] === 'error') {
                            echo "<li><strong>{$key}:</strong> {$item['value']}</li>";
                        }
                    }
                }
                echo "</ul>";
            }
            
            if ($warnings > 0) {
                echo "<p class='warning'>Warnings (not critical but should be addressed):</p>";
                echo "<ul>";
                foreach ($diagnostics as $category => $items) {
                    foreach ($items as $key => $item) {
                        if ($item['status'] === 'warning') {
                            echo "<li><strong>{$key}:</strong> {$item['value']}</li>";
                        }
                    }
                }
                echo "</ul>";
            }
        }
        
        // Execute time
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        echo "<p>Diagnostic script execution time: {$executionTime}ms</p>";
        ?>
    </div>
    
    <div class="actions">
        <a href="index.php" class="btn">Go to Homepage</a>
        <a href="minimal-html.php" class="btn">Basic HTML Test</a>
        <a href="test.php" class="btn">Basic PHP Test</a>
        <a href="minimal.php" class="btn">Minimal Diagnostic</a>
        <a href="debug-full.php" class="btn">Run Full Diagnostics Again</a>
    </div>
</body>
</html> 
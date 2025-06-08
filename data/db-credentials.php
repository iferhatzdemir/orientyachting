<?php
/**
 * Database Credentials
 * This file contains the database connection details.
 * Keep this file secure and outside web root if possible.
 */

// Database connection parameters - modify these to match your environment
$host = "localhost";      // Database host
$dbname = "orient";       // Database name
$username = "root";       // Database username (use proper credentials)
$password = "";           // Database password (use proper credentials)

// Do not modify below this line
if (!defined('DB_CREDENTIALS_LOADED')) {
    define('DB_CREDENTIALS_LOADED', true);
}
?> 
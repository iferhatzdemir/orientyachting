<?php
/**
 * Import Translations SQL
 * 
 * This script imports the translations SQL file into the database
 * to create the necessary tables for the language system.
 */

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection info
$host = "localhost";
$dbname = "eticaret";
$user = "root";
$password = "";

try {
    // Connect to database
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Importing Translation Tables</h1>";
    
    // Get the SQL from the file
    $sqlFile = file_get_contents(__DIR__ . '/data/translations.sql');
    
    if (!$sqlFile) {
        die("Error: Could not read SQL file");
    }
    
    // Split SQL file into individual queries
    $queries = explode(';', $sqlFile);
    
    // Execute each query
    foreach ($queries as $query) {
        $query = trim($query);
        
        if (empty($query)) {
            continue;
        }
        
        try {
            $db->exec($query);
            echo "<p>Successfully executed: " . substr($query, 0, 50) . "...</p>";
        } catch (PDOException $e) {
            echo "<p>Error executing query: " . $e->getMessage() . "</p>";
            echo "<pre>" . $query . "</pre>";
        }
    }
    
    echo "<h2>Import Complete!</h2>";
    echo "<p>All translation tables have been created successfully.</p>";
    
    // Insert some sample data
    $insertQueries = [
        "INSERT IGNORE INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yacht Charter', 'en', 'default')",
        "INSERT IGNORE INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yat Kiralama', 'tr', 'default')",
        "INSERT IGNORE INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yacht Charter', 'de', 'default')",
        "INSERT IGNORE INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Яхт-Чартер', 'ru', 'default')",
    ];
    
    echo "<h2>Inserting Sample Data</h2>";
    
    foreach ($insertQueries as $query) {
        try {
            $db->exec($query);
            echo "<p>Successfully inserted sample data</p>";
        } catch (PDOException $e) {
            echo "<p>Error inserting sample data: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h2>Setup Complete!</h2>";
    echo "<p>You can now use the language system.</p>";
    echo "<a href='index.php'>Return to Home Page</a>";
    
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}
?> 
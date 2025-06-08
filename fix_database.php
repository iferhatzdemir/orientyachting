<?php
/**
 * Fix Database Structure
 * 
 * This script fixes the database structure for the language system
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
    
    echo "<h1>Fixing Database Structure</h1>";
    
    // Drop existing translations table if it exists
    try {
        $db->exec("DROP TABLE IF EXISTS translations");
        echo "<p>Dropped existing translations table</p>";
    } catch (PDOException $e) {
        echo "<p>Error dropping table: " . $e->getMessage() . "</p>";
    }
    
    // Create new translations table with the correct column names
    try {
        $sql = "CREATE TABLE translations (
            id int(11) NOT NULL AUTO_INCREMENT,
            translation_key varchar(255) NOT NULL,
            translation_value text,
            lang_code varchar(10) NOT NULL,
            context varchar(50) DEFAULT 'default',
            last_updated timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (id),
            UNIQUE KEY unique_translation (translation_key, lang_code, context)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $db->exec($sql);
        echo "<p>Created translations table with correct column names</p>";
    } catch (PDOException $e) {
        echo "<p>Error creating translations table: " . $e->getMessage() . "</p>";
    }
    
    // Insert sample translations
    try {
        $insertQueries = [
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yacht Charter', 'en', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yat Kiralama', 'tr', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Yacht Charter', 'de', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.title', 'Orient Яхт-Чартер', 'ru', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.description', 'Luxury yacht charter services', 'en', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.description', 'Lüks yat kiralama hizmetleri', 'tr', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.keywords', 'yacht, charter, luxury, boat', 'en', 'default')",
            "INSERT INTO translations (translation_key, translation_value, lang_code, context) VALUES ('site.keywords', 'yat, kiralama, lüks, tekne', 'tr', 'default')"
        ];
        
        foreach ($insertQueries as $query) {
            $db->exec($query);
        }
        
        echo "<p>Inserted sample translations</p>";
    } catch (PDOException $e) {
        echo "<p>Error inserting translations: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Database Fix Complete!</h2>";
    echo "<p>You can now use the language system.</p>";
    echo "<a href='index.php'>Return to Home Page</a>";
    
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}
?> 
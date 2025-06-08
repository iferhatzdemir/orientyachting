<?php
// Setup script for yacht categories
// This file creates and populates the yacht_categories table

// Include the database connection
include_once("data/baglanti.php");

if (!isset($VT)) {
    die("Database connection not available.");
}

try {
    // Check if yacht_categories table exists
    $checkTable = $VT->SorguCalistir("SHOW TABLES LIKE 'yacht_categories'", array());
    
    if ($checkTable->rowCount() == 0) {
        echo "Creating yacht_categories table...<br>";
        
        // Table doesn't exist, read and execute the SQL file
        $sqlFile = file_get_contents('sql/yacht_categories.sql');
        
        // Split SQL statements and execute them one by one
        $statements = explode(';', $sqlFile);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            
            if (!empty($statement)) {
                $VT->SorguCalistir($statement, array());
            }
        }
        
        echo "Yacht categories table created and populated successfully!<br>";
    } else {
        echo "Yacht categories table already exists.<br>";
        
        // Check if we need to insert sample data
        $checkData = $VT->VeriGetir("yacht_categories", "", array(), "");
        
        if ($checkData === false || count($checkData) == 0) {
            echo "Adding sample yacht categories...<br>";
            
            // Read only INSERT statements from SQL file
            $sqlFile = file_get_contents('sql/yacht_categories.sql');
            $insertStatements = array();
            
            // Extract INSERT statements using regular expression
            preg_match_all('/INSERT.*?;/s', $sqlFile, $insertStatements);
            
            foreach ($insertStatements[0] as $insertStatement) {
                $VT->SorguCalistir($insertStatement, array());
            }
            
            echo "Sample yacht categories added successfully!<br>";
        } else {
            echo "Yacht categories already populated with data.<br>";
        }
    }
    
    echo "<br>Current yacht categories:<br>";
    
    // Display current yacht categories
    $categories = $VT->VeriGetir("yacht_categories", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
    
    if ($categories !== false) {
        echo "<ul>";
        foreach ($categories as $category) {
            echo "<li>" . $category["baslik"] . " (seflink: " . $category["seflink"] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "No yacht categories found.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 
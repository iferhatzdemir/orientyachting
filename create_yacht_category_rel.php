<?php
// Standalone script to create yacht_category_rel table

// Database credentials
$host = 'localhost';
$dbname = 'orient';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
    
    echo "Connected to database successfully<br>";
    
    // Check if table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'yacht_category_rel'");
    
    if ($checkTable->rowCount() == 0) {
        echo "Creating yacht_category_rel table...<br>";
        
        // Create table
        $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `yacht_category_rel` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `yacht_id` int(11) NOT NULL,
          `category_id` int(11) NOT NULL,
          PRIMARY KEY (`ID`),
          KEY `yacht_id` (`yacht_id`),
          KEY `category_id` (`category_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
        ";
        
        $conn->exec($createTableSQL);
        echo "Table created successfully<br>";
        
        // Get yacht and category IDs
        $yachts = $conn->query("SELECT ID FROM yachts ORDER BY ID")->fetchAll(PDO::FETCH_COLUMN);
        $categories = $conn->query("SELECT ID FROM yacht_categories ORDER BY ID")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($yachts) && !empty($categories)) {
            echo "Found " . count($yachts) . " yachts and " . count($categories) . " categories<br>";
            
            // Add some sample relationships
            $relationships = [];
            
            // First yacht belongs to categories 1 and 4 (if available)
            if (isset($yachts[0])) {
                if (isset($categories[0])) $relationships[] = [$yachts[0], $categories[0]];
                if (isset($categories[3])) $relationships[] = [$yachts[0], $categories[3]];
            }
            
            // Second yacht belongs to category 2
            if (isset($yachts[1]) && isset($categories[1])) {
                $relationships[] = [$yachts[1], $categories[1]];
            }
            
            // Third yacht belongs to category 3
            if (isset($yachts[2]) && isset($categories[2])) {
                $relationships[] = [$yachts[2], $categories[2]];
            }
            
            $insertSQL = "INSERT INTO yacht_category_rel (yacht_id, category_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSQL);
            
            foreach ($relationships as $rel) {
                $stmt->execute($rel);
                echo "Added relationship: Yacht ID " . $rel[0] . " ↔ Category ID " . $rel[1] . "<br>";
            }
        } else {
            echo "Warning: Could not find yachts or categories in the database.<br>";
        }
    } else {
        echo "yacht_category_rel table already exists<br>";
        
        // Show existing relationships
        $relationships = $conn->query("
            SELECT y.baslik as yacht_name, c.baslik as category_name, r.yacht_id, r.category_id
            FROM yacht_category_rel r
            INNER JOIN yachts y ON r.yacht_id = y.ID
            INNER JOIN yacht_categories c ON r.category_id = c.ID
            ORDER BY y.baslik, c.baslik
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($relationships)) {
            echo "<br>Current yacht category relationships:<br>";
            echo "<ul>";
            foreach ($relationships as $rel) {
                echo "<li><strong>" . htmlspecialchars($rel['yacht_name']) . "</strong> belongs to category <strong>" . 
                     htmlspecialchars($rel['category_name']) . "</strong></li>";
            }
            echo "</ul>";
        } else {
            echo "No yacht category relationships found.<br>";
        }
    }
    
    echo "<hr>";
    echo "Setup complete. <a href='index.php'>Go back to homepage</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 
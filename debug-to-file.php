<?php
// Open file for writing
$file = fopen('yacht-debug.txt', 'w');

// Function to write to file
function writeToFile($content) {
    global $file;
    fwrite($file, $content . "\n");
}

try {
    // Database connection parameters
    $host = 'localhost';
    $dbname = 'orient';
    $username = 'root';
    $password = '';

    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    writeToFile("Database Connection Successful");
    
    // Get all yacht types
    writeToFile("\n=== All Yacht Types ===");
    $stmt = $pdo->prepare("SELECT * FROM yacht_types WHERE durum = 1 ORDER BY sirano ASC");
    $stmt->execute();
    $allTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($allTypes) > 0) {
        foreach ($allTypes as $type) {
            writeToFile("ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - SEF: " . $type["seflink"]);
        }
    } else {
        writeToFile("No yacht types found");
    }
    
    // Get yacht types with active yachts using JOIN
    writeToFile("\n=== Yacht Types with Active Yachts (JOIN) ===");
    $stmt = $pdo->prepare("
        SELECT DISTINCT yt.* 
        FROM yacht_types yt
        INNER JOIN yachts y ON yt.ID = y.type_id
        WHERE yt.durum = 1 AND y.durum = 1
        ORDER BY yt.sirano ASC
    ");
    $stmt->execute();
    $typesWithYachts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($typesWithYachts) > 0) {
        foreach ($typesWithYachts as $type) {
            writeToFile("ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - SEF: " . $type["seflink"]);
        }
    } else {
        writeToFile("No yacht types with active yachts found");
    }
    
    // Count yachts per type
    writeToFile("\n=== Count of Active Yachts per Type ===");
    if (count($allTypes) > 0) {
        foreach ($allTypes as $type) {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM yachts 
                WHERE durum = 1 AND type_id = ?
            ");
            $stmt->execute([$type["ID"]]);
            $yachtCount = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $yachtCount["count"];
            
            writeToFile("Type ID: " . $type["ID"] . " - Name: " . $type["baslik"] . 
                 " - Active Yachts: " . $count);
        }
    }
    
    // Show all active yachts with their type information
    writeToFile("\n=== All Active Yachts with Type Information ===");
    $stmt = $pdo->prepare("
        SELECT y.*, yt.baslik as type_name
        FROM yachts y
        LEFT JOIN yacht_types yt ON y.type_id = yt.ID
        WHERE y.durum = 1
        ORDER BY y.baslik ASC
    ");
    $stmt->execute();
    $allYachts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($allYachts) > 0) {
        foreach ($allYachts as $yacht) {
            $typeInfo = empty($yacht["type_name"]) ? "No type assigned" : 
                        "Type: " . $yacht["type_name"] . " (ID: " . $yacht["type_id"] . ")";
            
            writeToFile("Yacht: " . $yacht["baslik"] . " (ID: " . $yacht["ID"] . ") - " . $typeInfo);
        }
    } else {
        writeToFile("No active yachts found");
    }
    
    // Print table structure for yacht_types
    writeToFile("\n=== yacht_types Table Structure ===");
    $stmt = $pdo->prepare("DESCRIBE yacht_types");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        writeToFile("Field: " . $col["Field"] . " | Type: " . $col["Type"] . " | Null: " . $col["Null"] . 
                    " | Key: " . $col["Key"] . " | Default: " . $col["Default"] . " | Extra: " . $col["Extra"]);
    }
    
    // Print table structure for yachts
    writeToFile("\n=== yachts Table Structure ===");
    $stmt = $pdo->prepare("DESCRIBE yachts");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        writeToFile("Field: " . $col["Field"] . " | Type: " . $col["Type"] . " | Null: " . $col["Null"] . 
                    " | Key: " . $col["Key"] . " | Default: " . $col["Default"] . " | Extra: " . $col["Extra"]);
    }
    
    echo "Debug information has been written to yacht-debug.txt";
    
} catch (PDOException $e) {
    writeToFile("Database Error: " . $e->getMessage());
    echo "Error occurred. See yacht-debug.txt for details.";
}

// Close file
fclose($file);
?> 
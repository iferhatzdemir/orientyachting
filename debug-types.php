<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'orientyachting';
$username = 'root';
$password = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Connection Successful</h2>";
    
    // Get all yacht types
    echo "<h2>All Yacht Types</h2>";
    $stmt = $pdo->prepare("SELECT * FROM yacht_types WHERE durum = 1 ORDER BY sirano ASC");
    $stmt->execute();
    $allTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($allTypes) > 0) {
        echo "<ul>";
        foreach ($allTypes as $type) {
            echo "<li>ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - SEF: " . $type["seflink"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No yacht types found</p>";
    }
    
    // Get yacht types with active yachts using JOIN
    echo "<h2>Yacht Types with Active Yachts (JOIN)</h2>";
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
        echo "<ul>";
        foreach ($typesWithYachts as $type) {
            echo "<li>ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - SEF: " . $type["seflink"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No yacht types with active yachts found</p>";
    }
    
    // Count yachts per type
    echo "<h2>Count of Active Yachts per Type</h2>";
    if (count($allTypes) > 0) {
        echo "<ul>";
        foreach ($allTypes as $type) {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM yachts 
                WHERE durum = 1 AND type_id = ?
            ");
            $stmt->execute([$type["ID"]]);
            $yachtCount = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $yachtCount["count"];
            
            echo "<li>Type ID: " . $type["ID"] . " - Name: " . $type["baslik"] . 
                 " - Active Yachts: " . $count . "</li>";
        }
        echo "</ul>";
    }
    
    // Show all active yachts with their type information
    echo "<h2>All Active Yachts with Type Information</h2>";
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
        echo "<ul>";
        foreach ($allYachts as $yacht) {
            $typeInfo = empty($yacht["type_name"]) ? "No type assigned" : 
                        "Type: " . $yacht["type_name"] . " (ID: " . $yacht["type_id"] . ")";
            
            echo "<li>Yacht: " . $yacht["baslik"] . " (ID: " . $yacht["ID"] . ") - " . $typeInfo . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No active yachts found</p>";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?> 
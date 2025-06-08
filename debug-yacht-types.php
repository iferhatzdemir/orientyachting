<?php
include_once("data/baglanti.php");

// Initialize VT class
$VT = new VT();

// Get all yacht types
echo "<h2>All Yacht Types</h2>";
$allTypes = $VT->VeriGetir("yacht_types", "", array(), "ORDER BY id ASC");
if($allTypes != false) {
    echo "Found " . count($allTypes) . " yacht types:<br>";
    echo "<pre>";
    print_r($allTypes);
    echo "</pre>";
} else {
    echo "No yacht types found.<br>";
}

// Get only active yacht types
echo "<h2>Active Yacht Types</h2>";
$activeTypes = $VT->VeriGetir("yacht_types", "WHERE durum=?", array(1), "ORDER BY id ASC");
if($activeTypes != false) {
    echo "Found " . count($activeTypes) . " active yacht types:<br>";
    echo "<pre>";
    print_r($activeTypes);
    echo "</pre>";
} else {
    echo "No active yacht types found.<br>";
}

// Get only active yacht types with active yachts
echo "<h2>Active Yacht Types with Active Yachts</h2>";
$activeTypesWithYachts = $VT->VeriGetir("yacht_types 
    INNER JOIN yachts ON yacht_types.id = yachts.type_id",
    "WHERE yacht_types.durum=? AND yachts.is_active=?",
    array(1, 1),
    "GROUP BY yacht_types.id ORDER BY yacht_types.id ASC");
if($activeTypesWithYachts != false) {
    echo "Found " . count($activeTypesWithYachts) . " active yacht types with active yachts:<br>";
    echo "<pre>";
    print_r($activeTypesWithYachts);
    echo "</pre>";
} else {
    echo "No active yacht types with active yachts found.<br>";
}

// Check column names in yacht_types table
echo "<h2>Yacht Types Table Structure</h2>";
try {
    $columns = $VT->baglan()->query("SHOW COLUMNS FROM yacht_types")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Error checking table structure: " . $e->getMessage();
}

// Get yacht types with active yachts using JOIN
echo "<h2>Yacht Types with Active Yachts (JOIN)</h2>";
$typesWithYachts = $VT->VeriGetir("yacht_types 
    INNER JOIN yachts ON yacht_types.ID = yachts.type_id",
    "WHERE yacht_types.durum=? AND yachts.durum=?",
    array(1, 1),
    "GROUP BY yacht_types.ID ORDER BY yacht_types.sirano ASC");

if($typesWithYachts != false) {
    echo "<ul>";
    foreach($typesWithYachts as $type) {
        echo "<li>ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - SEF: " . $type["seflink"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No yacht types with active yachts found</p>";
}

// Alternative approach - Count yachts per type
echo "<h2>Count of Active Yachts per Type</h2>";
if($allTypes != false) {
    echo "<ul>";
    foreach($allTypes as $type) {
        $yachtCount = $VT->VeriGetir("yachts", "WHERE durum=? AND type_id=?", array(1, $type["ID"]), "", "COUNT(ID) as count");
        $count = ($yachtCount != false) ? $yachtCount[0]["count"] : 0;
        echo "<li>Type ID: " . $type["ID"] . " - Name: " . $type["baslik"] . " - Active Yachts: " . $count . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No yacht types found</p>";
}

// Verify DB structure
echo "<h2>Database Structure</h2>";
echo "<h3>yacht_types table structure</h3>";
try {
    $queryTypesStruct = $VT->baglan->prepare("SHOW COLUMNS FROM yacht_types");
    $queryTypesStruct->execute();
    $typeColumns = $queryTypesStruct->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach($typeColumns as $col) {
        echo "<tr>";
        echo "<td>" . $col["Field"] . "</td>";
        echo "<td>" . $col["Type"] . "</td>";
        echo "<td>" . $col["Null"] . "</td>";
        echo "<td>" . $col["Key"] . "</td>";
        echo "<td>" . $col["Default"] . "</td>";
        echo "<td>" . $col["Extra"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch(PDOException $e) {
    echo "<p>Error getting yacht_types structure: " . $e->getMessage() . "</p>";
}

echo "<h3>yachts table structure</h3>";
try {
    $queryYachtsStruct = $VT->baglan->prepare("SHOW COLUMNS FROM yachts");
    $queryYachtsStruct->execute();
    $yachtColumns = $queryYachtsStruct->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach($yachtColumns as $col) {
        echo "<tr>";
        echo "<td>" . $col["Field"] . "</td>";
        echo "<td>" . $col["Type"] . "</td>";
        echo "<td>" . $col["Null"] . "</td>";
        echo "<td>" . $col["Key"] . "</td>";
        echo "<td>" . $col["Default"] . "</td>";
        echo "<td>" . $col["Extra"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch(PDOException $e) {
    echo "<p>Error getting yachts structure: " . $e->getMessage() . "</p>";
}

// Show all yachts with their type information
echo "<h2>All Active Yachts with Type Information</h2>";
$allYachts = $VT->VeriGetir("yachts", "WHERE durum=?", array(1), "ORDER BY baslik ASC");
if($allYachts != false) {
    echo "<ul>";
    foreach($allYachts as $yacht) {
        $typeInfo = "No type assigned";
        if(!empty($yacht["type_id"])) {
            $typeData = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($yacht["type_id"]), "", 1);
            if($typeData != false) {
                $typeInfo = "Type: " . $typeData[0]["baslik"] . " (ID: " . $typeData[0]["ID"] . ")";
            } else {
                $typeInfo = "Type ID " . $yacht["type_id"] . " not found";
            }
        }
        echo "<li>Yacht: " . $yacht["baslik"] . " (ID: " . $yacht["ID"] . ") - " . $typeInfo . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No active yachts found</p>";
}
?> 
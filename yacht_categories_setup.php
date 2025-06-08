<?php
// Simplified yacht categories setup
// This version uses PDO directly to create and populate the yacht_categories table

// Database connection
try {
    // Use the same DB credentials that the site uses
    include_once("data/baglanti.php"); // This should define $siteurl
    
    // Create connection
    $conn = new PDO("mysql:host=localhost;dbname=orient", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully<br>";
    
    // Check if yacht_categories table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'yacht_categories'");
    
    if ($checkTable->rowCount() == 0) {
        echo "Creating yacht_categories table...<br>";
        
        // Create table
        $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `yacht_categories` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `baslik` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
          `seflink` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
          `kategori` int(11) DEFAULT NULL,
          `metin` text COLLATE utf8_turkish_ci,
          `resim` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
          `anahtar` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
          `description` varchar(255) COLLATE utf8_turkish_ci DEFAULT NULL,
          `durum` int(5) DEFAULT NULL,
          `sirano` int(11) DEFAULT NULL,
          `tarih` date DEFAULT NULL,
          PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
        ";
        
        $conn->exec($createTableSQL);
        echo "Table created successfully<br>";
        
        // Insert sample categories
        echo "Inserting sample yacht categories...<br>";
        
        $categories = [
            [
                'baslik' => 'Motor Yachts',
                'seflink' => 'motor-yachts',
                'kategori' => 0,
                'metin' => 'Luxury motor yachts with powerful engines and modern amenities.',
                'anahtar' => 'motor yacht, power yacht, luxury motor yacht',
                'description' => 'Premium motor yachts available for charter',
                'durum' => 1,
                'sirano' => 1
            ],
            [
                'baslik' => 'Sailing Yachts',
                'seflink' => 'sailing-yachts',
                'kategori' => 0,
                'metin' => 'Experience the classic elegance of sailing with our sailing yacht collection.',
                'anahtar' => 'sailing yacht, sail boat, yacht charter',
                'description' => 'Elegant sailing yachts for the authentic experience',
                'durum' => 1,
                'sirano' => 2
            ],
            [
                'baslik' => 'Catamarans',
                'seflink' => 'catamarans',
                'kategori' => 0,
                'metin' => 'Spacious and stable multi-hull vessels perfect for groups and families.',
                'anahtar' => 'catamaran, multi-hull, catamaran charter',
                'description' => 'Stable and spacious catamarans for group travel',
                'durum' => 1,
                'sirano' => 3
            ],
            [
                'baslik' => 'Luxury Mega Yachts',
                'seflink' => 'mega-yachts',
                'kategori' => 0,
                'metin' => 'Ultra-luxury mega yachts with full crew and premium amenities.',
                'anahtar' => 'mega yacht, super yacht, luxury yacht',
                'description' => 'Ultimate luxury experience on mega yachts',
                'durum' => 1,
                'sirano' => 4
            ]
        ];
        
        $insertSQL = "INSERT INTO yacht_categories 
                     (baslik, seflink, kategori, metin, anahtar, description, durum, sirano, tarih) 
                     VALUES 
                     (:baslik, :seflink, :kategori, :metin, :anahtar, :description, :durum, :sirano, NOW())";
        
        $stmt = $conn->prepare($insertSQL);
        
        foreach ($categories as $category) {
            $stmt->bindParam(':baslik', $category['baslik']);
            $stmt->bindParam(':seflink', $category['seflink']);
            $stmt->bindParam(':kategori', $category['kategori']);
            $stmt->bindParam(':metin', $category['metin']);
            $stmt->bindParam(':anahtar', $category['anahtar']);
            $stmt->bindParam(':description', $category['description']);
            $stmt->bindParam(':durum', $category['durum']);
            $stmt->bindParam(':sirano', $category['sirano']);
            $stmt->execute();
        }
        
        echo "Sample categories inserted successfully<br>";
    } else {
        echo "Yacht categories table already exists<br>";
    }
    
    // Display existing yacht categories
    echo "<br>Current yacht categories:<br>";
    $categories = $conn->query("SELECT * FROM yacht_categories WHERE durum=1 ORDER BY sirano ASC");
    
    if ($categories->rowCount() > 0) {
        echo "<ul>";
        foreach ($categories as $category) {
            echo "<li>" . $category["baslik"] . " (seflink: " . $category["seflink"] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "No yacht categories found.";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> 
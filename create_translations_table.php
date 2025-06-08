<?php
// Include database connection
require_once('data/baglanti.php');

try {
    // Create translations table
    $sql = "CREATE TABLE IF NOT EXISTS translations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        translation_key VARCHAR(255) NOT NULL,
        lang_code VARCHAR(10) NOT NULL,
        translation_value TEXT NOT NULL,
        context VARCHAR(50) DEFAULT 'default',
        last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_translation (translation_key, lang_code, context)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sql);
    echo "Translations table created successfully.<br>";
    
    // Insert sample data
    $sampleData = [
        // English translations
        ['yacht.title', 'en', 'Luxury Yacht'],
        ['yacht.description', 'en', 'Explore the seas with our luxury yacht rental service.'],
        ['contact.title', 'en', 'Contact Us'],
        ['contact.form.name', 'en', 'Your Name'],
        ['contact.form.email', 'en', 'Email Address'],
        ['contact.form.message', 'en', 'Message'],
        ['contact.form.submit', 'en', 'Send Message'],
        
        // Turkish translations
        ['yacht.title', 'tr', 'Lüks Yat'],
        ['yacht.description', 'tr', 'Lüks yat kiralama hizmetimiz ile denizleri keşfedin.'],
        ['contact.title', 'tr', 'İletişim'],
        ['contact.form.name', 'tr', 'Adınız'],
        ['contact.form.email', 'tr', 'E-posta Adresiniz'],
        ['contact.form.message', 'tr', 'Mesajınız'],
        ['contact.form.submit', 'tr', 'Mesaj Gönder']
    ];
    
    // Prepare and execute insert statements
    $insertSql = "INSERT INTO translations (translation_key, lang_code, translation_value) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)";
    
    $stmt = $db->prepare($insertSql);
    
    foreach ($sampleData as $data) {
        $stmt->execute($data);
    }
    
    echo "Sample translation data inserted successfully.";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 
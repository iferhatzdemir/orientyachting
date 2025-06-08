// Çeviri fonksiyonu
function T($anahtar) {
    global $VT, $mevcutDil;
    
    // Veritabanından çeviriyi çek
    $ceviriler = $VT->VeriGetir("dil_ceviriler", "WHERE anahtar=?", array($anahtar), "ORDER BY ID ASC", 1);
    
    if($ceviriler != false) {
        // İlgili dilde çeviri varsa onu döndür
        if(!empty($ceviriler[0][$mevcutDil])) {
            return $ceviriler[0][$mevcutDil];
        }
        // Yoksa Türkçesini döndür
        else if(!empty($ceviriler[0]["tr"])) {
            return $ceviriler[0]["tr"];
        }
    }
    
    // Hiçbir çeviri bulunamazsa anahtarı döndür
    return $anahtar;
}

/**
 * Get content with multilingual support
 * 
 * @param string $table Base table name
 * @param int $contentId Content ID
 * @param array $fields Fields to retrieve
 * @param string $lang Language code
 * @return array Combined content from base table and language table
 */
function getMultilingualContent($table, $contentId, $fields = ['*'], $lang = null) {
    global $VT, $lang as $currentLang;
    
    if ($lang === null) {
        $lang = $currentLang;
    }
    
    // Get content from base table
    $content = $VT->VeriGetir($table, "WHERE ID = ?", [$contentId], "ORDER BY ID ASC", 1);
    
    if (!$content) {
        return null;
    }
    
    // Get translated content
    $translationTable = $table . '_dil';
    $translations = $VT->VeriGetir(
        $translationTable, 
        "WHERE {$table}_id = ? AND lang = ?", 
        [$contentId, $lang], 
        "ORDER BY ID ASC", 
        1
    );
    
    // Merge base content with translations when available
    if ($translations) {
        foreach ($translations[0] as $key => $value) {
            if (!empty($value) && $key != 'ID' && $key != "{$table}_id" && $key != 'lang') {
                $content[0][$key] = $value;
            }
        }
    }
    
    return $content[0];
} 